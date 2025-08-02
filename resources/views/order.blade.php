@extends('layouts.app')

@section('title', 'Checkout - LOOKS')

@section('content')
    {{--
        File: resources/views/checkout/index.blade.php (DIPERBAIKI)
        Perbaikan:
        1. Fixed CSS conflict: Removed conflicting 'block' and 'flex' classes
        2. Added Alpine.js handling untuk payment method selection
        3. Improved visual feedback untuk selected payment method
        4. Enhanced accessibility dengan proper ARIA labels
    --}}
    <div class="bg-gray-50 font-sans" x-data="checkoutManager({
        subtotal: {{ $subtotal }},
        user: {{ Js::from($user) }}
    })">
        <div class="container mx-auto px-4 py-16 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">Checkout</h1>
            </div>

            <form action="{{ route('order.store') }}" method="POST">
                @csrf
                <div class="lg:grid lg:grid-cols-2 lg:gap-x-12">
                    <!-- Kolom Kiri: Form Pengiriman & Pembayaran -->
                    <div class="flex flex-col">
                        <!-- Contact Information -->
                        <div>
                            <h2 class="text-lg font-medium text-gray-900">Informasi Kontak</h2>
                            <div class="mt-4">
                                <label for="email" class="block text-sm font-medium text-gray-700">Alamat
                                    Email</label>
                                <div class="mt-1">
                                    <input type="email" id="email" name="email"
                                        value="{{ old('email', $user->email) }}" autocomplete="email"
                                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @error('email')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Information -->
                        <div class="mt-10">
                            <h2 class="text-lg font-medium text-gray-900">Alamat Pengiriman</h2>
                            <div class="mt-4 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Nama
                                        Lengkap</label>
                                    <input type="text" name="name" id="name"
                                        value="{{ old('name', $user->name) }}"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                                    @error('name')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700">Nomor
                                        Telepon</label>
                                    <input type="text" name="phone" id="phone"
                                        value="{{ old('phone', $user->phone) }}"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                                    @error('phone')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="sm:col-span-2">
                                    <label for="shipping_address"
                                        class="block text-sm font-medium text-gray-700">Alamat</label>
                                    <input type="text" name="shipping_address" id="shipping_address"
                                        value="{{ old('shipping_address', $user->address) }}"
                                        placeholder="Nama jalan, nomor rumah, dll."
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                                    @error('shipping_address')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="shipping_city" class="block text-sm font-medium text-gray-700">Kota /
                                        Kabupaten</label>
                                    <input type="text" name="shipping_city" id="shipping_city"
                                        value="{{ old('shipping_city') }}"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                                    @error('shipping_city')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="shipping_state"
                                        class="block text-sm font-medium text-gray-700">Provinsi</label>
                                    <input type="text" name="shipping_state" id="shipping_state"
                                        value="{{ old('shipping_state') }}"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                                    @error('shipping_state')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="shipping_postal_code" class="block text-sm font-medium text-gray-700">Kode
                                        Pos</label>
                                    <input type="text" name="shipping_postal_code" id="shipping_postal_code"
                                        value="{{ old('shipping_postal_code') }}"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                                    @error('shipping_postal_code')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="sm:col-span-2">
                                    <label for="notes" class="block text-sm font-medium text-gray-700">Catatan
                                        (Opsional)</label>
                                    <textarea name="notes" id="notes" rows="3"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Method -->
                        <div class="mt-10 border-t border-gray-200 pt-10">
                            <h2 class="text-lg font-medium text-gray-900">Metode Pengiriman</h2>
                            <fieldset class="mt-4">
                                <legend class="sr-only">Pilih metode pengiriman</legend>
                                <div class="space-y-4">
                                    <template x-for="method in shippingMethods" :key="method.id">
                                        <label
                                            :class="{
                                                'ring-2 ring-indigo-500 border-indigo-500 bg-indigo-50': selectedShipping
                                                    .id === method.id,
                                                'border-gray-300': selectedShipping.id !==
                                                    method.id
                                            }"
                                            class="relative border rounded-lg p-4 flex cursor-pointer transition-all hover:border-indigo-300">
                                            <input type="radio" name="shipping_method_cost" :value="method.cost"
                                                @click="selectedShipping = method" class="sr-only">
                                            <div class="flex-1 flex">
                                                <div class="flex flex-col">
                                                    <span class="text-sm font-medium text-gray-900"
                                                        x-text="method.name"></span>
                                                    <span class="mt-1 flex items-center text-sm text-gray-500"
                                                        x-text="method.eta"></span>
                                                    <span class="mt-6 text-sm font-medium text-gray-900"
                                                        x-text="formatCurrency(method.cost)"></span>
                                                </div>
                                            </div>
                                            <svg class="h-5 w-5 text-indigo-600"
                                                x-show="selectedShipping.id === method.id" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </label>
                                    </template>
                                </div>
                                @error('shipping_method_cost')
                                    <p class="mt-2 text-sm text-red-600">Silakan pilih metode pengiriman.</p>
                                @enderror
                            </fieldset>
                        </div>

                        <!-- Payment Method (DIPERBAIKI) -->
                        <div class="mt-10 border-t border-gray-200 pt-10">
                            <h2 class="text-lg font-medium text-gray-900">Metode Pembayaran</h2>
                            <fieldset class="mt-4">
                                <legend class="sr-only">Pilih metode pembayaran</legend>
                                <div class="space-y-4">
                                    <template x-for="method in paymentMethods" :key="method.value">
                                        <label
                                            :class="{
                                                'ring-2 ring-indigo-500 border-indigo-500 bg-indigo-50': selectedPayment ===
                                                    method.value,
                                                'border-gray-300': selectedPayment !== method.value
                                            }"
                                            class="relative border rounded-lg p-4 flex cursor-pointer transition-all hover:border-indigo-300">
                                            <input type="radio" name="payment_method" :value="method.value"
                                                @click="selectedPayment = method.value" class="sr-only">
                                            <div class="flex-1 flex">
                                                <div class="flex flex-col">
                                                    <span class="text-sm font-medium text-gray-900"
                                                        x-text="method.name"></span>
                                                    <span class="mt-1 text-sm text-gray-500"
                                                        x-text="method.description"></span>
                                                </div>
                                            </div>
                                            <!-- Check icon untuk payment method yang dipilih -->
                                            <svg class="h-5 w-5 text-indigo-600" x-show="selectedPayment === method.value"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </label>
                                    </template>
                                </div>
                                @error('payment_method')
                                    <p class="mt-2 text-sm text-red-600">Silakan pilih metode pembayaran.</p>
                                @enderror
                            </fieldset>
                        </div>

                    </div>

                    <!-- Kolom Kanan: Ringkasan Pesanan -->
                    <div class="mt-10 lg:mt-0">
                        <div class="bg-white rounded-lg shadow-lg p-6 sticky top-24">
                            <h2 class="text-lg font-medium text-gray-900">Ringkasan Pesanan</h2>
                            <ul role="list" class="divide-y divide-gray-200 my-6">
                                @foreach ($cartItems as $item)
                                    <li class="flex py-4">
                                        <div
                                            class="flex-shrink-0 w-20 h-20 border border-gray-200 rounded-md overflow-hidden">
                                            <img src="{{ '/storage/' . ($item->product->primaryImage->image_url ?? '') }}"
                                                alt="[Gambar {{ $item->product->name }}]"
                                                class="w-full h-full object-center object-cover">
                                        </div>
                                        <div class="ml-4 flex-1 flex flex-col">
                                            <div>
                                                <h3 class="text-sm text-gray-700">{{ $item->product->name }}</h3>
                                                <p class="mt-1 text-sm text-gray-500">Qty: {{ $item->quantity }}</p>
                                            </div>
                                            <div class="flex-1 flex items-end justify-between text-sm">
                                                <p class="text-gray-900 font-medium">
                                                    {{ 'Rp ' . number_format(($item->product->discount_price ?? $item->product->price) * $item->quantity, 0, ',', '.') }}
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>

                            <dl class="space-y-4 border-t border-gray-200 pt-6">
                                <div class="flex items-center justify-between">
                                    <dt class="text-sm text-gray-600">Subtotal</dt>
                                    <dd class="text-sm font-medium text-gray-900" x-text="formatCurrency(subtotal)">
                                    </dd>
                                </div>
                                <div class="flex items-center justify-between">
                                    <dt class="text-sm text-gray-600">Pengiriman</dt>
                                    <dd class="text-sm font-medium text-gray-900" x-text="formatCurrency(shippingCost)">
                                    </dd>
                                </div>
                                <div class="flex items-center justify-between">
                                    <dt class="text-sm text-gray-600">Pajak (11%)</dt>
                                    <dd class="text-sm font-medium text-gray-900" x-text="formatCurrency(tax)"></dd>
                                </div>
                                <div class="flex items-center justify-between border-t border-gray-200 pt-4">
                                    <dt class="text-base font-medium text-gray-900">Total</dt>
                                    <dd class="text-base font-medium text-gray-900" x-text="formatCurrency(total)">
                                    </dd>
                                </div>
                            </dl>

                            <div class="mt-6">
                                <button type="submit"
                                    class="w-full bg-indigo-600 border border-transparent rounded-md shadow-sm py-3 px-4 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
                                    :disabled="!selectedPayment || !selectedShipping.id">
                                    <span x-show="selectedPayment && selectedShipping.id">Bayar Sekarang</span>
                                    <span x-show="!selectedPayment || !selectedShipping.id">Pilih Metode Pembayaran &
                                        Pengiriman</span>
                                </button>
                            </div>

                            <!-- Debug info (hapus di production) -->
                            <div class="mt-4 text-xs text-gray-500" x-show="true">
                                <p>Payment: <span x-text="selectedPayment || 'Not selected'"></span></p>
                                <p>Shipping: <span x-text="selectedShipping.name || 'Not selected'"></span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function checkoutManager(data) {
            return {
                subtotal: data.subtotal || 0,
                user: data.user || {},
                shippingMethods: [{
                        id: 1,
                        name: 'Reguler',
                        eta: '3-5 hari kerja',
                        cost: 20000
                    },
                    {
                        id: 2,
                        name: 'Express',
                        eta: '1-2 hari kerja',
                        cost: 45000
                    },
                    {
                        id: 3,
                        name: 'Same Day',
                        eta: 'Hari yang sama',
                        cost: 75000
                    },
                ],
                // DITAMBAHKAN: Payment methods array
                paymentMethods: [{
                        value: 'bank_transfer',
                        name: 'Bank Transfer',
                        description: 'Transfer melalui ATM, Mobile Banking, atau Internet Banking'
                    },
                    {
                        value: 'cod',
                        name: 'Cash on Delivery (COD)',
                        description: 'Bayar tunai saat barang sampai'
                    }
                ],
                selectedShipping: {},
                selectedPayment: '', // DITAMBAHKAN: Track selected payment method
                taxRate: 0.11,

                get shippingCost() {
                    return this.selectedShipping.cost || 0;
                },
                get tax() {
                    return (this.subtotal + this.shippingCost) * this.taxRate;
                },
                get total() {
                    return this.subtotal + this.shippingCost + this.tax;
                },
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
