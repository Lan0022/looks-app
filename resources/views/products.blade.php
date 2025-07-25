@extends('layouts.app')

@section('title', 'Products - LOOKS')

@section('content')

    <div>
        <!-- No surplus words or unnecessary actions. - Marcus Aurelius -->
        @props(['products', 'categories'])

        <section class="bg-gray-50 font-sans" x-data="productBrowser({
            products: {{ Js::from($products) }},
            categories: {{ Js::from($categories) }}
        })">
            <div class="container mx-auto px-4 py-16 sm:px-6 lg:px-8">
                <!-- Header Section -->
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                        Jelajahi Produk Kami
                    </h2>
                    <p class="mt-4 max-w-2xl mx-auto text-lg text-gray-500">
                        Temukan koleksi terbaik kami yang dirancang khusus untuk gaya Anda.
                    </p>
                </div>

                <!-- Filter and Search Controls -->
                <div class="mb-10 space-y-6">
                    <!-- Search Input -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" x-model.debounce.300ms="search" placeholder="Cari produk berdasarkan nama..."
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out">
                    </div>

                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <!-- Category Filters -->
                        <div class="flex items-center flex-wrap gap-2">
                            <button @click="selectedCategory = 'all'"
                                :class="{ 'bg-indigo-600 text-white': selectedCategory === 'all', 'bg-white text-gray-700 hover:bg-gray-100': selectedCategory !== 'all' }"
                                class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-full shadow-sm transition duration-150 ease-in-out">
                                Semua
                            </button>
                            <template x-for="category in categories" :key="category.slug">
                                <button @click="selectedCategory = category.slug"
                                    :class="{
                                        'bg-indigo-600 text-white': selectedCategory === category
                                            .slug,
                                        'bg-white text-gray-700 hover:bg-gray-100': selectedCategory !== category
                                            .slug
                                    }"
                                    class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-full shadow-sm transition duration-150 ease-in-out"
                                    x-text="category.name">
                                </button>
                            </template>
                        </div>

                        <!-- Sort Dropdown -->
                        <div>
                            <select x-model="sortBy"
                                class="block w-full md:w-auto pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md transition duration-150 ease-in-out">
                                <option value="newest">Terbaru</option>
                                <option value="price_asc">Harga: Terendah</option>
                                <option value="price_desc">Harga: Tertinggi</option>
                                <option value="rating_desc">Rating Tertinggi</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Product Grid -->
                <div class="grid grid-cols-1 gap-y-10 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8">
                    <template x-if="filteredProducts.length === 0">
                        <div class="col-span-full text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" aria-hidden="true">
                                <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Produk tidak ditemukan</h3>
                            <p class="mt-1 text-sm text-gray-500">Coba ubah filter atau kata kunci pencarian Anda.</p>
                        </div>
                    </template>

                    <template x-for="product in filteredProducts" :key="product.id">
                        <a :href="`/products/${product.slug}`" class="group">
                            <div
                                class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300 ease-in-out flex flex-col h-full">
                                <!-- Product Image -->
                                <div class="relative w-full aspect-w-1 aspect-h-1 bg-gray-200 overflow-hidden">
                                    <img :src="product.primary_image" :alt="`[Gambar ${product.name}]`"
                                        class="w-full h-full object-center object-cover group-hover:opacity-75 transition-opacity duration-300">
                                    <!-- Discount Badge -->
                                    <template x-if="product.discount_price">
                                        <div
                                            class="absolute top-3 right-3 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                            SALE
                                        </div>
                                    </template>
                                </div>

                                <!-- Product Info -->
                                <div class="p-4 flex flex-col flex-grow">
                                    <p class="text-sm font-medium text-gray-500" x-text="product.category.name"></p>
                                    <h3 class="mt-1 text-lg font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors duration-300"
                                        x-text="product.name"></h3>

                                    <!-- Rating -->
                                    <div class="mt-2 flex items-center" x-show="product.rating > 0">
                                        <div class="flex items-center">
                                            <template x-for="i in 5">
                                                <svg class="h-5 w-5 flex-shrink-0"
                                                    :class="product.rating >= i ? 'text-yellow-400' : 'text-gray-300'"
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            </template>
                                        </div>
                                        <p class="ml-2 text-sm text-gray-500" x-text="`${product.total_reviews} ulasan`">
                                        </p>
                                    </div>

                                    <!-- Price -->
                                    <div class="mt-auto pt-4">
                                        <template x-if="product.discount_price">
                                            <div class="flex items-baseline gap-2">
                                                <p class="text-xl font-bold text-indigo-600"
                                                    x-text="formatCurrency(product.discount_price)"></p>
                                                <p class="text-sm text-gray-500 line-through"
                                                    x-text="formatCurrency(product.price)"></p>
                                            </div>
                                        </template>
                                        <template x-if="!product.discount_price">
                                            <p class="text-xl font-bold text-gray-900"
                                                x-text="formatCurrency(product.price)"></p>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </template>
                </div>
            </div>
        </section>
    </div>

    <script>
        // Alpine.js data and methods
        function productBrowser(data) {
            return {
                search: '',
                selectedCategory: 'all',
                sortBy: 'newest',
                products: data.products || [],
                categories: data.categories || [],

                // Computed property to filter and sort products
                get filteredProducts() {
                    let filtered = this.products;

                    // 1. Filter by category
                    if (this.selectedCategory !== 'all') {
                        filtered = filtered.filter(product => product.category.slug === this.selectedCategory);
                    }

                    // 2. Filter by search term
                    if (this.search.trim() !== '') {
                        filtered = filtered.filter(product =>
                            product.name.toLowerCase().includes(this.search.toLowerCase())
                        );
                    }

                    // 3. Sort the results
                    switch (this.sortBy) {
                        case 'price_asc':
                            filtered.sort((a, b) => (a.discount_price || a.price) - (b.discount_price || b.price));
                            break;
                        case 'price_desc':
                            filtered.sort((a, b) => (b.discount_price || b.price) - (a.discount_price || a.price));
                            break;
                        case 'rating_desc':
                            filtered.sort((a, b) => b.rating - a.rating);
                            break;
                        case 'newest':
                        default:
                            // Assuming the initial array is sorted by newest.
                            // In a real app, you would sort by created_at desc.
                            filtered.sort((a, b) => b.id - a.id);
                            break;
                    }

                    return filtered;
                },

                // Helper to format currency
                formatCurrency(amount) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(amount);
                }
            }
        }
    </script>
@endsection
