@extends('layouts.app')

@section('title', 'Detail Produk - ' . $product->name)

@section('content')
    {{--
        File: resources/views/detail-product.blade.php (Diperbaiki)
        Perubahan:
        - Input kuantitas sekarang tidak lagi `readonly`.
        - Menggunakan `type="number"` untuk input, namun panah bawaan browser disembunyikan dengan CSS
          untuk menjaga desain minimalis.
        - Menambahkan event listener `@change="validateQuantity()"` pada input.
        - Fungsi `validateQuantity()` di Alpine.js akan memastikan bahwa jika pengguna
          memasukkan angka di bawah 1 atau input yang tidak valid, nilainya akan otomatis direset ke 1.
        - Ini memberikan fleksibilitas input manual kepada pengguna sambil menjaga integritas data.
    --}}

    {{-- CSS untuk menyembunyikan panah pada input number --}}
    <style>
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
            /* Firefox */
        }
    </style>

    <div class="bg-gray-50 font-sans">
        <div class="container mx-auto px-4 py-12 sm:px-6 lg:px-8">
            <div x-data="productDetail({
                images: {{ json_encode($product->images->pluck('image_url')) }},
                primaryImage: '{{ $product->primaryImage->image_url ?? ($product->images->first()->image_url ?? 'https://placehold.co/600x600/F3F4F6/7C3AED?text=No+Image') }}'
            })">

                <!-- Main Product Section -> Grid -->
                <div class="lg:grid lg:grid-cols-2 lg:gap-x-12 lg:items-start">
                    <!-- Image gallery -->
                    <div class="mb-8 lg:mb-0">
                        <!-- Main Image -->
                        <div class="w-full aspect-square rounded-lg bg-gray-200 overflow-hidden shadow-lg">
                            <img :src="'/storage/' + selectedImage" alt="{{ $product->name }}"
                                class="w-full h-full object-center object-cover transition-opacity duration-300">
                        </div>
                        <!-- Thumbnail Images -->
                        <div class="mt-4 grid grid-cols-4 sm:grid-cols-5 gap-4">
                            <template x-for="image in images" :key="image">
                                <div @click="selectedImage = image"
                                    :class="{ 'ring-2 ring-offset-2 ring-indigo-500': selectedImage === image }"
                                    class="cursor-pointer rounded-md bg-gray-100 aspect-square overflow-hidden hover:opacity-75 transition">
                                    <img :src="'/storage/' + image" alt="[Gambar Thumbnail]"
                                        class="w-full h-full object-center object-cover">
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Product info -->
                    <div class="mt-10 px-4 sm:px-0 sm:mt-16 lg:mt-0">
                        <!-- Breadcrumbs -->
                        <nav aria-label="Breadcrumb">
                            <ol role="list" class="flex items-center space-x-2 text-sm">
                                <li><a href="/" class="text-gray-500 hover:text-gray-600">Home</a></li>
                                <li><svg class="h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z"></path>
                                    </svg></li>
                                <li><a href="{{ route('products') }}" class="text-gray-500 hover:text-gray-600">Produk</a>
                                </li>
                                <li><svg class="h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z"></path>
                                    </svg></li>
                                <li>
                                    <p class="font-medium text-gray-700">{{ $product->name }}</p>
                                </li>
                            </ol>
                        </nav>

                        <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 mt-6">{{ $product->name }}</h1>

                        <div class="mt-3">
                            <h2 class="sr-only">Informasi Produk</h2>
                            @if ($product->discount_price)
                                <div class="flex items-baseline gap-2">
                                    <p class="text-3xl text-indigo-600 font-bold">
                                        {{ 'Rp ' . number_format($product->discount_price, 0, ',', '.') }}</p>
                                    <p class="text-xl text-gray-500 line-through">
                                        {{ 'Rp ' . number_format($product->price, 0, ',', '.') }}</p>
                                </div>
                            @else
                                <p class="text-3xl text-gray-900">{{ 'Rp ' . number_format($product->price, 0, ',', '.') }}
                                </p>
                            @endif
                        </div>

                        <!-- Reviews -->
                        <div class="mt-3">
                            <h3 class="sr-only">Ulasan</h3>
                            <div class="flex items-center h-5">
                                @if ($product->total_reviews > 0)
                                    <div class="flex items-center">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg class="h-5 w-5 flex-shrink-0 {{ $product->rating >= $i ? 'text-yellow-400' : 'text-gray-300' }}"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @endfor
                                    </div>
                                    <a href="#reviews-section"
                                        class="ml-3 text-sm font-medium text-indigo-600 hover:text-indigo-500">{{ $product->total_reviews }}
                                        ulasan</a>
                                @else
                                    <p class="text-sm text-gray-400 italic">Belum ada ulasan</p>
                                @endif
                            </div>
                        </div>

                        <div class="mt-6">
                            <h3 class="sr-only">Deskripsi</h3>
                            <div class="text-base text-gray-700 space-y-6">
                                <p>{{ $product->description }}</p>
                            </div>
                        </div>

                        {{-- Form untuk Add to Cart --}}
                        <form class="mt-6" action="{{-- route('cart.add') --}}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                            <!-- Quantity Selector -->
                            <!-- PERUBAHAN DIMULAI DI SINI -->
                            <div class="mt-8">
                                <label for="quantity" class="block text-sm font-medium text-gray-900 mb-2">Kuantitas</label>
                                <div class="relative inline-flex items-center rounded-md shadow-sm">
                                    <!-- Tombol Kurang (-) -->
                                    <button type="button" @click="quantity = Math.max(1, quantity - 1)"
                                        class="relative inline-flex items-center justify-center w-10 h-10 px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-150">
                                        <span class="sr-only">Kurangi kuantitas</span>
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                            fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <!-- Input Kuantitas -->
                                    <input type="number" name="quantity" x-model.number="quantity"
                                        @change="validateQuantity()"
                                        class="w-16 h-10 border-t border-b border-gray-300 bg-white text-gray-900 text-center font-medium focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                                        min="1">
                                    <!-- Tombol Tambah (+) -->
                                    <button type="button" @click="quantity++"
                                        class="relative -ml-px inline-flex items-center justify-center w-10 h-10 px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-150">
                                        <span class="sr-only">Tambah kuantitas</span>
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                            fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <!-- AKHIR PERUBAHAN -->

                            <div class="mt-10 flex">
                                <button type="submit"
                                    class="w-full bg-indigo-600 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Tambah ke Keranjang
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Reviews Section -->
                <div id="reviews-section" class="mt-16 pt-10 border-t border-gray-200">
                    <h2 class="text-2xl font-extrabold tracking-tight text-gray-900">Ulasan Pelanggan</h2>
                    <div class="mt-6 space-y-10">
                        @forelse ($product->reviews as $review)
                            <div class="flex flex-col sm:flex-row">
                                <div class="mt-6 sm:mt-0 sm:ml-4 flex-1">
                                    <div class="flex items-center">
                                        <p class="text-sm font-medium text-gray-900">{{ $review->user->name ?? 'User' }}
                                        </p>
                                        <div class="ml-4 flex items-center">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <svg class="h-4 w-4 flex-shrink-0 {{ $review->rating >= $i ? 'text-yellow-400' : 'text-gray-300' }}"
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            @endfor
                                        </div>
                                    </div>
                                    <div class="mt-2 text-base text-gray-600 space-y-4">
                                        <p>{{ $review->review }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">Belum ada ulasan untuk produk ini.</p>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function productDetail(data) {
            return {
                images: data.images ? data.images.map(img => img.replace(/\\/g, '/')) : [],
                selectedImage: data.primaryImage ? data.primaryImage.replace(/\\/g, '/') : '',
                quantity: 1,
                // PERUBAHAN DI SINI
                validateQuantity() {
                    // Jika kuantitas bukan angka atau kurang dari 1, reset ke 1.
                    // `parseInt` digunakan untuk memastikan kita membandingkan angka.
                    if (!this.quantity || parseInt(this.quantity) < 1) {
                        this.quantity = 1;
                    }
                    // Anda juga bisa menambahkan validasi untuk stok maksimal di sini jika ada
                    // if (parseInt(this.quantity) > maxStock) {
                    //     this.quantity = maxStock;
                    // }
                }
            }
        }
    </script>
@endsection
