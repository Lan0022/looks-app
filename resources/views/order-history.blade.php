@extends('layouts.app')

@section('title', 'Riwayat Pesanan - LOOKS')

@section('content')
    <div class="bg-gray-50 font-sans">
        <div class="container mx-auto px-4 py-16 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">Riwayat Pesanan Anda</h1>
                <p class="mt-4 max-w-2xl mx-auto text-lg text-gray-500">
                    Lihat semua transaksi dan status pesanan Anda di sini.
                </p>
            </div>

            <div class="space-y-8">
                @forelse ($orders as $order)
                    <div
                        class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                        <div class="px-4 py-5 sm:px-6 bg-gray-50 border-b border-gray-200">
                            <div class="flex flex-wrap items-center justify-between gap-4">
                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-500">Nomor Pesanan</span>
                                    <p class="font-mono text-sm font-semibold text-gray-800">{{ $order->order_number }}</p>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-500">Tanggal Pesanan</span>
                                    <p class="text-sm text-gray-800">{{ $order->created_at->format('d F Y') }}</p>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-500">Total Pembayaran</span>
                                    <p class="text-sm font-bold text-gray-900">
                                        {{ 'Rp ' . number_format($order->final_amount, 0, ',', '.') }}</p>
                                </div>
                                <div class="flex items-center">
                                    <a href="{{ route('detail-order.history', $order) }}"
                                        class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                                        Lihat Detail &rarr;
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="px-4 py-5 sm:p-6">
                            <div class="flex items-center">
                                <span
                                    class="px-3 py-1 text-xs font-medium rounded-full
                                    @switch($order->status)
                                        @case('pending') bg-yellow-100 text-yellow-800 @break
                                        @case('processing') bg-blue-100 text-blue-800 @break
                                        @case('shipped') bg-cyan-100 text-cyan-800 @break
                                        @case('delivered') bg-green-100 text-green-800 @break
                                        @case('cancelled') bg-red-100 text-red-800 @break
                                        @default bg-gray-100 text-gray-800
                                    @endswitch">
                                    {{ ucfirst($order->status) }}
                                </span>
                                <p class="ml-4 text-sm text-gray-600">
                                    {{ $order->items_count }} item
                                    @if ($order->status == 'delivered')
                                        - Pesanan telah sampai.
                                    @elseif($order->status == 'shipped')
                                        - Pesanan sedang dalam perjalanan.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-16 border-2 border-dashed border-gray-300 rounded-lg">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" aria-hidden="true">
                            <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-gray-900">Anda Belum Memiliki Pesanan</h3>
                        <p class="mt-1 text-sm text-gray-500">Semua riwayat pesanan Anda akan muncul di sini.</p>
                        <div class="mt-6">
                            <a href="{{ route('products') }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">Mulai
                                Belanja</a>
                        </div>
                    </div>
                @endforelse

                <div class="mt-8">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
