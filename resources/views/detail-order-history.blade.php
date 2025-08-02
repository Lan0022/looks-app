@extends('layouts.app')

@section('title', 'Detail Pesanan ' . $order->order_number)

@section('content')
    <div class="bg-gray-50 font-sans">
        <div class="container mx-auto px-4 py-16 sm:px-6 lg:px-8">
            <!-- Header -->
            <div>
                <nav class="flex items-center text-sm">
                    <a href="{{ route('order.history') }}" class="font-medium text-gray-500 hover:text-gray-700">&larr;
                        Kembali
                        ke Riwayat Pesanan</a>
                </nav>
                <div class="mt-2 md:flex md:items-center md:justify-between">
                    <div class="flex-1 min-w-0">
                        <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">Detail Pesanan</h1>
                        <p class="mt-1 text-sm text-gray-500">Nomor Pesanan <span
                                class="font-mono">{{ $order->order_number }}</span></p>
                    </div>
                    @if ($order->status == 'pending' && $order->payment_status == 'pending')
                        <div class="mt-4 flex-shrink-0 flex md:mt-0 md:ml-4">
                            <a href="#"
                                class="w-full bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700">Selesaikan
                                Pembayaran</a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 lg:gap-8">
                <!-- Kolom Kiri: Daftar Item -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow">
                        <h2 class="sr-only">Produk yang dibeli</h2>
                        <ul role="list" class="divide-y divide-gray-200">
                            @foreach ($order->items as $item)
                                <li class="p-4 sm:p-6">
                                    <div class="flex items-start">
                                        <div
                                            class="flex-shrink-0 w-20 h-20 bg-gray-200 rounded-lg overflow-hidden sm:w-24 sm:h-24">
                                            <img src="{{ '/storage/' . ($item->product->primaryImage->image_url ?? '') }}"
                                                alt="[Gambar {{ $item->product->name }}]"
                                                class="w-full h-full object-center object-cover">
                                        </div>
                                        <div class="ml-6 flex-1 text-sm">
                                            <div class="font-medium text-gray-900">
                                                <h5>{{ $item->product->name }}</h5>
                                                <p class="mt-1">{{ 'Rp ' . number_format($item->price, 0, ',', '.') }}</p>
                                            </div>
                                            <p class="mt-1 text-gray-500">Qty: {{ $item->quantity }}</p>
                                        </div>
                                    </div>
                                    @if ($order->status == 'delivered')
                                        <div class="mt-4 flex justify-end">
                                            {{-- Cek jika produk ini sudah direview atau belum --}}
                                            <a href="{{ route('products.show.detail', $item->product->slug) }}#reviews-section"
                                                class="text-sm font-medium text-indigo-600 hover:text-indigo-500">Beri
                                                Ulasan</a>
                                        </div>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Kolom Kanan: Ringkasan & Alamat -->
                <div class="mt-8 lg:mt-0">
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-medium text-gray-900">Ringkasan Biaya</h2>
                        <dl class="mt-6 space-y-4">
                            <div class="flex items-center justify-between">
                                <dt class="text-sm text-gray-600">Subtotal</dt>
                                <dd class="text-sm font-medium text-gray-900">
                                    {{ 'Rp ' . number_format($order->total_amount, 0, ',', '.') }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-sm text-gray-600">Pengiriman</dt>
                                <dd class="text-sm font-medium text-gray-900">
                                    {{ 'Rp ' . number_format($order->shipping_cost, 0, ',', '.') }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-sm text-gray-600">Pajak</dt>
                                <dd class="text-sm font-medium text-gray-900">
                                    {{ 'Rp ' . number_format($order->tax_amount, 0, ',', '.') }}</dd>
                            </div>
                            <div class="flex items-center justify-between border-t border-gray-200 pt-4">
                                <dt class="text-base font-medium text-gray-900">Total</dt>
                                <dd class="text-base font-medium text-gray-900">
                                    {{ 'Rp ' . number_format($order->final_amount, 0, ',', '.') }}</dd>
                            </div>
                        </dl>
                    </div>
                    <div class="mt-8 bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-medium text-gray-900">Alamat Pengiriman</h2>
                        <address class="mt-4 text-sm text-gray-600 not-italic">
                            <strong class="block">{{ $order->user->name }}</strong>
                            {{ $order->shipping_address }}<br>
                            {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_postal_code }}
                        </address>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
