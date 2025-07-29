<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\View\View;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //
    // public function showProducts()
    // {
    //     // Ambil data produk, kategori, dan brand dari database.
    //     // Contoh ini menggunakan data dummy yang strukturnya mirip dengan database Anda.
    //     // Di aplikasi nyata, Anda akan melakukan query seperti:
    //     // $products = Product::with(['category', 'brand', 'images'])->where('status', 'active')->get();
    //     // $categories = Category::where('is_active', true)->get();

    //     // Data dummy untuk demonstrasi
    //     $products = collect([
    //         (object)[
    //             'id' => 1,
    //             'name' => 'Nike Air Max 270',
    //             'slug' => 'nike-air-max-270',
    //             'price' => 1299000,
    //             'discount_price' => null,
    //             'rating' => 4.5,
    //             'total_reviews' => 2,
    //             'category' => (object)['name' => 'Sepatu', 'slug' => 'sepatu'],
    //             'brand' => (object)['name' => 'Nike'],
    //             'primary_image' => 'https://placehold.co/400x400/F3F4F6/7C3AED?text=Nike+AirMax',
    //         ],
    //         (object)[
    //             'id' => 2,
    //             'name' => 'Adidas Ultraboost 22',
    //             'slug' => 'adidas-ultraboost-22',
    //             'price' => 2499000,
    //             'discount_price' => 2199000,
    //             'rating' => 5.0,
    //             'total_reviews' => 1,
    //             'category' => (object)['name' => 'Sepatu', 'slug' => 'sepatu'],
    //             'brand' => (object)['name' => 'Adidas'],
    //             'primary_image' => 'https://placehold.co/400x400/F3F4F6/7C3AED?text=Adidas+Ultraboost',
    //         ],
    //         (object)[
    //             'id' => 3,
    //             'name' => 'Uniqlo Heattech Crew Neck',
    //             'slug' => 'uniqlo-heattech-crew-neck',
    //             'price' => 199000,
    //             'discount_price' => null,
    //             'rating' => 0.0,
    //             'total_reviews' => 0,
    //             'category' => (object)['name' => 'Baju', 'slug' => 'baju'],
    //             'brand' => (object)['name' => 'Uniqlo'],
    //             'primary_image' => 'https://placehold.co/400x400/F3F4F6/7C3AED?text=Uniqlo+T-Shirt',
    //         ],
    //         (object)[
    //             'id' => 4,
    //             'name' => 'Erigo Hoodie Oversize',
    //             'slug' => 'erigo-hoodie-oversize',
    //             'price' => 299000,
    //             'discount_price' => null,
    //             'rating' => 4.0,
    //             'total_reviews' => 1,
    //             'category' => (object)['name' => 'Atasan', 'slug' => 'atasan'],
    //             'brand' => (object)['name' => 'Erigo'],
    //             'primary_image' => 'https://placehold.co/400x400/F3F4F6/7C3AED?text=Erigo+Hoodie',
    //         ],
    //         (object)[
    //             'id' => 5,
    //             'name' => '3Second Jeans Slim Fit',
    //             'slug' => '3second-jeans-slim-fit',
    //             'price' => 349000,
    //             'discount_price' => 299000,
    //             'rating' => 3.0,
    //             'total_reviews' => 1,
    //             'category' => (object)['name' => 'Celana', 'slug' => 'celana'],
    //             'brand' => (object)['name' => '3Second'],
    //             'primary_image' => 'https://placehold.co/400x400/F3F4F6/7C3AED?text=3Second+Jeans',
    //         ],
    //         (object)[
    //             'id' => 6,
    //             'name' => 'Greenlight Kemeja Formal',
    //             'slug' => 'greenlight-kemeja-formal',
    //             'price' => 179000,
    //             'discount_price' => null,
    //             'rating' => 0.0,
    //             'total_reviews' => 0,
    //             'category' => (object)['name' => 'Baju', 'slug' => 'baju'],
    //             'brand' => (object)['name' => 'Greenlight'],
    //             'primary_image' => 'https://placehold.co/400x400/F3F4F6/7C3AED?text=Greenlight+Kemeja',
    //         ],
    //     ]);

    //     $categories = collect([
    //         (object)['name' => 'Atasan', 'slug' => 'atasan'],
    //         (object)['name' => 'Baju', 'slug' => 'baju'],
    //         (object)['name' => 'Celana', 'slug' => 'celana'],
    //         (object)['name' => 'Sepatu', 'slug' => 'sepatu'],
    //     ]);

    //     return view('products', compact('products', 'categories'));
    // }

    public function showProducts(): View
    {
        // Mengambil semua produk yang statusnya 'active'.
        // Menggunakan `with()` (Eager Loading) untuk mengambil relasi
        // agar tidak terjadi N+1 query problem. Ini sangat meningkatkan performa.
        $products = Product::with(['category', 'brand', 'primaryImage'])
            ->where('status', 'active')
            ->latest() // Mengurutkan berdasarkan data terbaru (created_at DESC)
            ->get()
            // Kita akan memformat data di sini agar sesuai dengan yang diharapkan oleh view.
            ->map(function ($product) {
                return (object) [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => $product->price,
                    'discount_price' => $product->discount_price,
                    'rating' => $product->rating,
                    'total_reviews' => $product->total_reviews,
                    'category' => (object) [
                        'name' => $product->category->name ?? 'N/A',
                        'slug' => $product->category->slug ?? 'n-a',
                    ],
                    'brand' => (object) [
                        'name' => $product->brand->name ?? 'N/A',
                    ],
                    // Mengambil URL gambar utama atau menyediakan placeholder jika tidak ada.
                    'primary_image' => $product->primaryImage->image_url
                        ?? 'https://placehold.co/400x400/F3F4F6/7C3AED?text=' . urlencode($product->name),
                ];
            });

        // Mengambil semua kategori yang aktif untuk ditampilkan sebagai filter.
        $categories = Category::where('is_active', true)->get(['name', 'slug']);

        // Mengirim data products dan categories ke view 'products.index'.
        // Pastikan nama file view Anda adalah `resources/views/products/index.blade.php`.
        return view('products', compact('products', 'categories'));
    }

    /**
     * Menampilkan halaman detail untuk satu produk.
     * Menggunakan Route Model Binding dengan 'slug' sebagai kunci.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\View\View
     */
    public function showDetailProduct(Product $product): View
    {
        // Pastikan Anda mengkonfigurasi Route Model Binding untuk menggunakan 'slug'.
        // Di dalam `app/Models/Product.php`, tambahkan method ini:
        // public function getRouteKeyName()
        // {
        //     return 'slug';
        // }

        // Eager load relasi yang dibutuhkan untuk halaman detail.
        $product->load(['category', 'brand', 'images', 'reviews.user']);

        // Mengirim data produk ke view 'products.show'.
        // Anda perlu membuat file view ini: `resources/views/products/show.blade.php`.
        return view('detail-product', compact('product'));
    }
}
