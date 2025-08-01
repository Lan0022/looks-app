<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Menampilkan halaman keranjang belanja.
     *
     * @return \Illuminate\View\View
     */
    public function showCart(): View
    {
        // Pastikan pengguna sudah login untuk melihat keranjang
        if (!Auth::check()) {
            // Jika belum login, bisa diarahkan ke halaman login
            // atau ditampilkan keranjang kosong.
            // Untuk contoh ini, kita tampilkan keranjang kosong.
            return view('cart', ['cartItems' => collect()]);
        }

        // Ambil item keranjang untuk user yang sedang login.
        // Gunakan eager loading untuk mengambil data produk dan gambar utamanya.
        $cartItems = Cart::where('user_id', Auth::id())
            ->with('product.primaryImage')
            ->get()
            ->map(function ($cartItem) {
                // Jika item di keranjang tidak memiliki produk (misal produk sudah dihapus)
                if (!$cartItem->product) {
                    return null;
                }

                return (object) [
                    'id' => $cartItem->id, // ID dari tabel cart
                    'product_id' => $cartItem->product->id,
                    'name' => $cartItem->product->name,
                    'slug' => $cartItem->product->slug,
                    'price' => $cartItem->product->discount_price ?? $cartItem->product->price,
                    'quantity' => $cartItem->quantity,
                    'image_url' => $cartItem->product->primaryImage->image_url
                        ?? 'https://placehold.co/150x150/F3F4F6/7C3AED?text=No+Image',
                ];
            })->filter(); // Hapus item null jika ada produk yang tidak ditemukan

        return view('cart', compact('cartItems'));
    }

    // Anda bisa menambahkan method lain di sini nanti, seperti:
    // public function add(Request $request) { ... }
    /**
     * Menambahkan produk ke keranjang belanja.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addToCart(Request $request): RedirectResponse
    {
        // 1. Validasi input dari form
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $userId = Auth::id();

        // 2. Cek stok produk
        if ($product->stock < $validated['quantity']) {
            return back()->with('error', 'Stok produk tidak mencukupi.');
        }

        // 3. Logika untuk menambah atau memperbarui item di keranjang
        // Cari item di keranjang berdasarkan user dan produk
        $cartItem = Cart::where('user_id', $userId)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            // Jika item sudah ada, tambahkan kuantitasnya
            $newQuantity = $cartItem->quantity + $validated['quantity'];
            if ($product->stock < $newQuantity) {
                return back()->with('error', 'Total kuantitas di keranjang melebihi stok.');
            }
            $cartItem->quantity = $newQuantity;
            $cartItem->save();
        } else {
            // Jika item belum ada, buat entri baru
            Cart::create([
                'user_id' => $userId,
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
            ]);
        }

        // 4. Redirect kembali dengan pesan sukses
        return redirect()->route('cart')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    // public function update(Request $request, Cart $cart) { ... }
    public function update(Request $request, Cart $cart): JsonResponse
    {
        // Pastikan pengguna hanya bisa mengupdate keranjangnya sendiri
        if ($cart->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate(['quantity' => 'required|integer|min:1']);

        // Cek stok produk
        if ($cart->product->stock < $validated['quantity']) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak mencukupi. Sisa stok: ' . $cart->product->stock
            ], 422); // 422 Unprocessable Entity
        }

        $cart->update(['quantity' => $validated['quantity']]);

        return response()->json(['success' => true, 'message' => 'Kuantitas berhasil diperbarui.']);
    }

    // public function destroy(Cart $cart) { ... }
    /**
     * FUNGSI BARU: Menghapus item dari keranjang.
     *
     * @param \App\Models\Cart $cart
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Cart $cart): JsonResponse
    {
        // Pastikan pengguna hanya bisa menghapus item dari keranjangnya sendiri
        if ($cart->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $cart->delete();

        return response()->json(['success' => true, 'message' => 'Item berhasil dihapus dari keranjang.']);
    }
}
