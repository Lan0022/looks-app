<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Menampilkan halaman checkout.
     */
    public function showOrder(): View|RedirectResponse
    {
        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('products')
                ->with('error', 'Keranjang Anda kosong. Silakan tambahkan produk terlebih dahulu.');
        }

        // Hitung subtotal di sisi server sebagai sumber kebenaran
        $subtotal = $cartItems->sum(function ($cartItem) {
            $price = $cartItem->product->discount_price ?? $cartItem->product->price;
            return $price * $cartItem->quantity;
        });

        return view('order', compact('cartItems', 'subtotal', 'user'));
    }

    /**
     * Menampilkan daftar pesanan pengguna.
     */
    public function showOrderHistory(): View
    {
        $orders = Order::where('user_id', Auth::id())
            ->withCount('items') // Menghitung jumlah item per pesanan secara efisien
            ->latest() // Mengurutkan dari yang terbaru
            ->paginate(10); // Memberi paginasi agar tidak berat

        return view('order-history', compact('orders'));
    }

    /**
     * Menyimpan pesanan ke database.
     */
    public function storeOrder(Request $request)
    {
        // 1. Validasi data form
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:255',
            'shipping_city' => 'required|string|max:100',
            'shipping_state' => 'required|string|max:100',
            'shipping_postal_code' => 'required|string|max:20',
            'shipping_method_cost' => 'required|numeric',
            'notes' => 'nullable|string',
            'payment_method' => 'required|string',
        ]);

        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('products')->with('error', 'Keranjang Anda kosong.');
        }

        // Gunakan Database Transaction untuk memastikan semua proses berhasil atau tidak sama sekali
        try {
            DB::beginTransaction();

            // 2. Hitung total biaya
            $subtotal = $cartItems->sum(fn($item) => ($item->product->discount_price ?? $item->product->price) * $item->quantity);
            $taxAmount = $subtotal * 0.11; // Pajak 11%
            $shippingCost = $validated['shipping_method_cost'];
            $finalAmount = $subtotal + $taxAmount + $shippingCost;

            // 3. Buat entri di tabel `orders`
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => $this->generateUniqueOrderNumber(),
                'total_amount' => $subtotal,
                'shipping_cost' => $shippingCost,
                'tax_amount' => $taxAmount,
                'final_amount' => $finalAmount,
                'status' => 'pending', // Status awal
                'payment_status' => 'pending',
                'payment_method' => $validated['payment_method'],
                'shipping_address' => $validated['shipping_address'],
                'shipping_city' => $validated['shipping_city'],
                'shipping_state' => $validated['shipping_state'],
                'shipping_postal_code' => $validated['shipping_postal_code'],
                'notes' => $validated['notes'],
            ]);

            // 4. Pindahkan item dari keranjang ke `order_items`
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->discount_price ?? $item->product->price,
                ]);

                // 5. Kurangi stok produk
                $item->product->decrement('stock', $item->quantity);
            }

            // 6. Kosongkan keranjang pengguna
            Cart::where('user_id', $user->id)->delete();

            // Jika semua berhasil, commit transaksi
            DB::commit();

            // 7. Redirect ke halaman sukses
            return redirect()->route('order.history', $order)->with('success', 'Pesanan Anda telah berhasil dibuat!');
        } catch (\Exception $e) {
            // Jika ada error, rollback semua perubahan
            DB::rollBack();
            // Log error dan kembalikan dengan pesan error
            Log::error('Checkout error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memproses pesanan.');
        }
    }

    /**
     * Menghasilkan nomor pesanan unik.
     *
     * @return string
     */
    private function generateUniqueOrderNumber()
    {
        do {
            $orderNumber = 'LOOKS-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(4));
        } while (Order::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }


    /**
     * Menampilkan halaman detail dari satu pesanan.
     *
     * @param \App\Models\Order $order
     * @return \Illuminate\View\View
     */
    public function showDetailOrderHistory(Order $order): View
    {
        // Pastikan pengguna hanya bisa melihat pesanannya sendiri.
        // Ini disebut Authorization. Anda bisa membuatnya lebih canggih dengan Laravel Policy.
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Anda tidak diizinkan untuk melihat pesanan ini.');
        }

        // Eager load relasi yang dibutuhkan untuk halaman detail.
        $order->load('items.product.primaryImage');

        return view('detail-order-history', compact('order'));
    }
}
