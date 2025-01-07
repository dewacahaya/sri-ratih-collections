<?php

namespace App\Http\Controllers;

use App\Models\Busana;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Report;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{

    // LANDING PAGE
    public function showHomePage()
    {
        $busanas = Busana::all();
        return view('home', compact('busanas'));
    }
    // END LANDING PAGE

    // RECOMMENDATION PAGE
    public function showRecommendationPage()
    {
        $recommendations = Busana::take(10)->get();

        $special1 = Busana::find(1);
        $special2 = Busana::find(2);
        $specialMain = Busana::find(3);
        return view('customers.homes.rekomen', compact('recommendations', 'special1', 'special2', 'specialMain'));
    }
    // END RECOMMENDATION PAGE


    // REGISTER / LOGIN FUNCTION
    public function register(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            "name" => "required|string|max:255",
            "email" => "required|email|unique:customers,email",
            "password" => "required|string|confirmed",
            "no_telp" => "required|numeric|min:12|max:999999999999999",
            "alamat" => "required|string|max:255",
        ]);

        // Encrypt password sebelum disimpan
        $validatedData['password'] = Hash::make($request->password);

        try {
            // Membuat customer baru
            Customer::create($validatedData);
        } catch (\Exception $e) {
            Log::error('Failed to create customer: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Failed to register. Please try again.']);
        }

        // Mengarahkan ke halaman login atau halaman lain setelah berhasil registrasi
        return redirect()->route("customer.home")->with('registered', 'Registrasi berhasil! Silakan login.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Attempt to log the user in
        if (Auth::guard('customers')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('')->with('success', 'Login Berhasil');
        }

        return back()->withErrors([
            'email' => 'Email atau Password salah.',
        ])->with('failed', 'Email atau Password salah');
    }


    public function logout(Request $request)
    {
        Auth::guard('customers')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logout Berhasil');
    }
    // END REGISTER / LOGIN FUNCTION

    // BUSANAS PAGE
    public function showAllBusanas(Request $request)
    {
        $search = $request->input('search');

        // Mengambil data busana dengan pencarian dan stok lebih dari 1
        $busanas = Busana::when($search, function ($query, $search) {
            return $query->where('nama_busana', 'like', "%{$search}%")
                ->orWhere('harga', 'like', "%{$search}%")
                ->orWhere('deskripsi', 'like', "%{$search}%");
        })->where('stok', '>', 0)->paginate(10);

        return view('customers.homes.busanas', compact('busanas', 'search'));
    }

    public function showDetail($id)
    {
        $recommendations = Busana::take(3)->where('stok', '>', 0)->get();

        $busana = Busana::findOrFail($id);
        return view('customers.homes.busanaDetail', compact('busana', 'recommendations'));
    }
    // END BUSANAS PAGE


    // CART LOGIC
    public function showCart()
    {
        $cart = session()->get('cart', []);

        foreach ($cart as $key => $item) {
            $busana = Busana::find($key);
            if ($busana) {
                $cart[$key]['max_stock'] = $busana->stok;
            } else {
                unset($cart[$key]); // Hapus item jika data Busana tidak ditemukan
            }
        }


        $grandTotal = array_reduce($cart, function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);

        // Tetapkan opsi pengiriman ke sesi jika belum ada
        if (!session()->has('shipping_option')) {
            session()->put('shipping_option', 'Standar');
        }

        $shippingOption = session()->get('shipping_option');

        return view('customers.transaction.cart', compact('cart', 'grandTotal', 'shippingOption'));
    }

    public function updateCart(Request $request)
    {
        $busanaId = $request->input('busana_id');
        $quantity = $request->input('quantity', 1);
        $shippingOption = $request->input('shipping_option', 'standard');

        if (!in_array($shippingOption, ['Standar', 'Cepat', 'Ambil Di Tempat'])) {
            return response()->json(['status' => 'error', 'message' => 'Opsi pengiriman tidak valid.']);
        }

        $cart = session()->get('cart', []);
        if (isset($cart[$busanaId])) {
            $cart[$busanaId]['quantity'] = $quantity;
            session()->put('cart', $cart);
        }

        session()->put('shipping_option', $shippingOption);

        return response()->json(['status' => 'success', 'message' => 'Keranjang berhasil diperbarui.']);
    }

    public function addToCart(Request $request)
    {
        $busanaId = $request->input('busana_id');
        $busana = Busana::find($busanaId);

        if (!$busana) {
            return redirect()->route('customer.busanas')->with('error', 'Busana tidak ditemukan.');
        }

        // Cek apakah stok habis
        if ($busana->stok <= 0) {
            return redirect()->back()->with('error', 'Stok habis. Tidak dapat menambahkan ke keranjang.');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$busanaId])) {
            if ($cart[$busanaId]['quantity'] + 1 > $busana->stok) {
                return redirect()->back()->with('error', 'Stok tidak mencukupi.');
            }
            $cart[$busanaId]['quantity']++;
        } else {
            $cart[$busanaId] = [
                "id" => $busanaId, // Add this line
                "name" => $busana->nama_busana,
                "quantity" => 1,
                "price" => $busana->harga,
                "image" => $busana->gambar
            ];
        }

        session()->put('cart', $cart);
        return redirect()->route('customer.cart')->with('success', 'Busana berhasil ditambahkan ke keranjang.');
    }

    public function removeFromCart(Request $request)
    {
        $busanaId = $request->input('busana_id');

        $cart = session()->get('cart', []);

        if (isset($cart[$busanaId])) {
            unset($cart[$busanaId]);
            session()->put('cart', $cart);
            return redirect()->route('customer.cart')->with('success', 'Busana berhasil dihapus dari keranjang.');
        }

        return redirect()->route('customer.cart')->with('failed', 'Busana tidak ditemukan di keranjang.');
    }
    // END CART LOGIC

    // CHECKOUT LOGIC
    public function showCheckout(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('customer.cart')->with('error', 'Keranjang Anda kosong.');
        }

        // Validasi data cart dengan database
        foreach ($cart as $key => $item) {
            $busana = Busana::find($key);
            if (!$busana) {
                unset($cart[$key]); // Hapus jika tidak ditemukan di database
            }
        }

        // Simpan kembali cart yang telah divalidasi
        session()->put('cart', $cart);

        if (empty($cart)) {
            return redirect()->route('customer.cart')->with('error', 'Keranjang Anda kosong.');
        }

        $shippingOption = session()->get('shipping_option', 'Standar');
        $shippingFee = $shippingOption === 'Cepat' ? 10000 : 0;

        $grandTotal = array_reduce($cart, function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);

        $customer = auth('customers')->user();

        return view('customers.transaction.checkout', compact('cart', 'grandTotal', 'shippingOption', 'shippingFee', 'customer'));
    }

    public function processCheckout(Request $request)
    {
        try {
            // Pastikan user login
            if (!auth('customers')->check()) {
                return redirect()->route('customer.login')->with('error', 'Anda harus login untuk melakukan checkout.');
            }

            DB::beginTransaction();

            // Validasi request
            $request->validate([
                'name' => 'required',
                'phone' => 'required',
                'address' => 'required',
                'shipping_method' => 'required|in:Standar,Cepat,Ambil Di Tempat',
                'payment_method' => 'required|in:COD,Bank'
            ]);

            // Ambil data customer yang sedang login
            $customer = auth('customers')->user();

            // Siapkan data untuk update profil customer
            $updateData = [
                'name' => $request->name,
                'no_telp' => $request->phone,
                'alamat' => $request->address,
            ];

            // Update data customer
            DB::table('customers')->where('id', $customer->id)->update($updateData);


            // Ambil data keranjang
            $cart = session()->get('cart', []);

            if (empty($cart)) {
                throw new \Exception('Keranjang belanja kosong.');
            }

            // Hitung total belanja
            $grandTotal = array_reduce($cart, function ($carry, $item) {
                return $carry + ($item['price'] * $item['quantity']);
            }, 0);

            // Tambahkan biaya pengiriman jika diperlukan
            $shippingFee = $request->shipping_method === 'Cepat' ? 10000 : 0;
            $grandTotal += $shippingFee;

            // Buat order baru
            $order = Order::create([
                'user_id' => auth('customers')->id(),
                'tanggal_pesan' => now(),
                'total_belanja' => $grandTotal,
                'pengiriman' => $request->shipping_method,
                'pembayaran' => $request->payment_method,
                'status_pesanan' => 'Pending',
            ]);

            // Tambahkan detail pesanan
            foreach ($cart as $busanaId => $item) {
                $orderDetail = $order->orderDetails()->create([
                    'busana_id' => $busanaId,
                    'jumlah' => $item['quantity'],
                    'harga' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);

                // Update stok
                $busana = Busana::find($busanaId);

                // Validasi stok sebelum mengurangi
                if ($busana->stok < $item['quantity']) {
                    throw new \Exception("Stok busana '{$busana->nama_busana}' tidak mencukupi.");
                }

                $busana->stok -= $item['quantity'];
                $busana->save();

                // Simpan data ke tabel reports
                Report::updateOrCreate(
                    [
                        'busana_id' => $busanaId,
                        'bulan' => now()->format('m'),
                        'tahun' => now()->format('Y'),
                    ],
                    [
                        'total_pesanan' => DB::raw('total_pesanan + ' . $item['quantity']),
                        'total_penjualan' => DB::raw('total_penjualan + ' . $item['price'] * $item['quantity']),
                    ]
                );
            }

            DB::commit();

            // Hapus keranjang dari sesi
            session()->forget('cart');

            return redirect()->route('customer.complete', ['orderId' => $order->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }
    // END CHECKOUT LOGIC


    // ORDERS PAGE
    public function showOrders()
    {
        // Ambil data orders dengan relasi order_details
        $user_id = auth('customers')->id(); // Dapatkan ID pelanggan yang sedang login
        $orders = Order::with('orderDetails.busana') // Pastikan relasi 'orderDetails' dan 'product' telah diatur di model
            ->where('user_id', $user_id)
            ->orderBy('created_at', 'asc')
            ->get();
        $no = 1;

        return view('customers.transaction.myorder', compact('orders', 'no'));
    }
    // END ORDERS PAGE

    // COMPLETED ORDERS
    public function showCompletePage($orderId)
    {
        // Pastikan user login
        if (!auth('customers')->check()) {
            return redirect()->route('customer.login')->with('error', 'Anda harus login untuk melihat pesanan.');
        }

        // Ambil data pesanan
        $order = Order::with('orderDetails.busana')->where('id', $orderId)
            ->where('user_id', auth('customers')->id())
            ->firstOrFail();

        return view('customers.transaction.complete', compact('order'));
    }
    // END COMPLETED ORDERS
}
