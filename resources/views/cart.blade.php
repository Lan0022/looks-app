@extends('layouts.app')

@section('title', 'Keranjang Belanja - LOOKS')

@section('content')
    {{--
        File: resources/views/cart/index.blade.php (Disempurnakan)
        Perubahan:
        - Logika `initiateRemove(itemId)` di dalam script diperbarui.
        - Jika pengguna mengklik hapus pada item baru sementara timer undo untuk item lama masih berjalan,
          sistem akan langsung mengkonfirmasi penghapusan item lama dan memulai timer undo baru untuk item baru.
        - Ini mencegah notifikasi "Undo" bertumpuk dan membuat UI lebih responsif dan intuitif.
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

    <div class="bg-gray-50 font-sans" x-data="cartManager({
        initialItems: {{ Js::from($cartItems) }}
    })">
        <div class="container mx-auto px-4 py-16 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">Keranjang Belanja Anda</h1>
            </div>

            <!-- Tampilan jika keranjang tidak kosong -->
            <template x-if="items.length > 0">
                <div class="lg:grid lg:grid-cols-12 lg:gap-x-12 lg:items-start">
                    <!-- Daftar Item Keranjang (Kolom Kiri) -->
                    <section aria-labelledby="cart-heading" class="lg:col-span-8">
                        <h2 id="cart-heading" class="sr-only">Item di dalam keranjang belanja Anda</h2>

                        <ul role="list" class="border-t border-b border-gray-200 divide-y divide-gray-200">
                            <template x-for="item in items" :key="item.id">
                                <li class="flex py-6 sm:py-10" x-show="!item.isRemoving"
                                    x-transition:leave="transition ease-in duration-200"
                                    x-transition:leave-start="opacity-100"
                                    x-transition:leave-end="opacity-0 -translate-x-8">
                                    <div class="flex-shrink-0">
                                        <img :src="'/storage/' + item.image_url" :alt="`[Gambar ${item.name}]`"
                                            class="w-24 h-24 rounded-md object-center object-cover sm:w-32 sm:h-32">
                                    </div>

                                    <div class="ml-4 flex-1 flex flex-col justify-between sm:ml-6">
                                        <div class="relative pr-9 sm:grid sm:grid-cols-2 sm:gap-x-6 sm:pr-0">
                                            <div>
                                                <div class="flex justify-between">
                                                    <h3 class="text-sm">
                                                        <a :href="`/products/${item.slug}`"
                                                            class="font-medium text-gray-700 hover:text-gray-800"
                                                            x-text="item.name"></a>
                                                    </h3>
                                                </div>
                                                <p class="mt-1 text-sm font-medium text-gray-900"
                                                    x-text="formatCurrency(item.price)"></p>
                                            </div>

                                            <div class="mt-4 sm:mt-0 sm:pr-9">
                                                <!-- Quantity Selector -->
                                                <div class="relative inline-flex items-center rounded-md">
                                                    <button type="button"
                                                        @click="updateQuantity(item.id, item.quantity - 1)"
                                                        class="relative inline-flex items-center justify-center w-8 h-8 rounded-l-md border border-gray-300 bg-white text-gray-500 hover:bg-gray-50 transition-colors"><span
                                                            class="sr-only">Kurangi</span><svg class="h-4 w-4"
                                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                            fill="currentColor">
                                                            <path fill-rule="evenodd"
                                                                d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                                                clip-rule="evenodd" />
                                                        </svg></button>
                                                    <input type="number" x-model.number="item.quantity"
                                                        @change="updateQuantity(item.id, $event.target.value)"
                                                        min="1"
                                                        class="w-12 h-8 border-t border-b border-gray-300 text-center text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                                    <button type="button"
                                                        @click="updateQuantity(item.id, item.quantity + 1)"
                                                        class="relative -ml-px inline-flex items-center justify-center w-8 h-8 rounded-r-md border border-gray-300 bg-white text-gray-500 hover:bg-gray-50 transition-colors"><span
                                                            class="sr-only">Tambah</span><svg class="h-4 w-4"
                                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                            fill="currentColor">
                                                            <path fill-rule="evenodd"
                                                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                                                clip-rule="evenodd" />
                                                        </svg></button>
                                                </div>

                                                <div class="absolute top-0 right-0">
                                                    <button type="button" @click="initiateRemove(item.id)"
                                                        class="-m-2 p-2 inline-flex text-gray-400 hover:text-gray-500">
                                                        <span class="sr-only">Hapus</span>
                                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                                                            viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                            <path fill-rule="evenodd"
                                                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </template>
                        </ul>
                    </section>

                    <!-- Ringkasan Pesanan (Kolom Kanan) -->
                    <section aria-labelledby="summary-heading"
                        class="mt-16 bg-white rounded-lg shadow-lg px-4 py-6 sm:p-6 lg:p-8 lg:mt-0 lg:col-span-4">
                        <h2 id="summary-heading" class="text-lg font-medium text-gray-900">Ringkasan Pesanan</h2>
                        <dl class="mt-6 space-y-4">
                            <div class="flex items-center justify-between">
                                <dt class="text-sm text-gray-600">Subtotal</dt>
                                <dd class="text-sm font-medium text-gray-900" x-text="formatCurrency(subtotal)"></dd>
                            </div>
                            <div class="border-t border-gray-200 pt-4 flex items-center justify-between">
                                <dt class="flex items-center text-sm text-gray-600"><span>Biaya Pengiriman</span></dt>
                                <dd class="text-sm font-medium text-gray-900" x-text="formatCurrency(shippingCost)"></dd>
                            </div>
                            <div class="border-t border-gray-200 pt-4 flex items-center justify-between">
                                <dt class="text-sm text-gray-600">Pajak (11%)</dt>
                                <dd class="text-sm font-medium text-gray-900" x-text="formatCurrency(tax)"></dd>
                            </div>
                            <div class="border-t border-gray-200 pt-4 flex items-center justify-between">
                                <dt class="text-base font-medium text-gray-900">Total Pesanan</dt>
                                <dd class="text-base font-medium text-gray-900" x-text="formatCurrency(total)"></dd>
                            </div>
                        </dl>
                        <div class="mt-6">
                            <button type="submit"
                                class="w-full bg-indigo-600 border border-transparent rounded-md shadow-sm py-3 px-4 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 focus:ring-indigo-500">Lanjut
                                ke Checkout</button>
                        </div>
                    </section>
                </div>
            </template>

            <!-- Tampilan jika keranjang kosong -->
            <template x-if="items.length === 0 && !itemToRemove">
                <div class="text-center py-16">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">Keranjang Anda Kosong</h3>
                    <p class="mt-1 text-sm text-gray-500">Sepertinya Anda belum menambahkan produk apapun.</p>
                    <div class="mt-6"><a href="{{ route('products') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Mulai
                            Belanja</a></div>
                </div>
            </template>
        </div>

        <!-- Undo Toast/Snackbar -->
        <div x-show="showUndo" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0"
            x-transition:leave-end="translate-y-full" class="fixed bottom-0 inset-x-0 pb-2 sm:pb-5">
            <div class="max-w-xl mx-auto px-4">
                <div class="p-3 rounded-lg bg-gray-800 shadow-lg">
                    <div class="flex items-center justify-between flex-wrap">
                        <div class="w-0 flex-1 flex items-center">
                            <p class="ml-3 font-medium text-white truncate">
                                <span x-text="itemToRemove ? itemToRemove.name : ''"></span>
                                <span> telah dihapus.</span>
                            </p>
                        </div>
                        <div class="order-3 mt-2 flex-shrink-0 w-full sm:order-2 sm:mt-0 sm:w-auto">
                            <button @click="cancelRemove()"
                                class="flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-indigo-600 bg-white hover:bg-indigo-50">Urungkan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function cartManager(data) {
            return {
                items: data.initialItems.map(item => ({
                    ...item,
                    isRemoving: false
                })) || [],
                shippingCost: 20000,
                taxRate: 0.11,
                updateTimeout: null,
                showUndo: false,
                undoTimeout: null,
                itemToRemove: null,

                // PERBAIKAN LOGIKA PENGHAPUSAN
                initiateRemove(itemId) {
                    // Jika ada item lain yang sedang dalam proses undo,
                    // konfirmasi penghapusannya terlebih dahulu.
                    if (this.itemToRemove && this.itemToRemove.id !== itemId) {
                        this.forceConfirmRemove();
                    }

                    const itemIndex = this.items.findIndex(i => i.id === itemId);
                    if (itemIndex === -1) return;

                    // Jika item yang sama diklik lagi, batalkan
                    if (this.itemToRemove && this.itemToRemove.id === itemId) {
                        this.cancelRemove();
                        return;
                    }

                    this.itemToRemove = {
                        ...this.items[itemIndex]
                    }; // Clone object
                    this.items[itemIndex].isRemoving = true;
                    this.showUndo = true;

                    // Clear timeout sebelumnya jika ada
                    if (this.undoTimeout) {
                        clearTimeout(this.undoTimeout);
                    }

                    this.undoTimeout = setTimeout(() => {
                        this.confirmRemove();
                    }, 5000);
                },

                // Method terpisah untuk konfirmasi paksa tanpa delay
                forceConfirmRemove() {
                    if (!this.itemToRemove) return;

                    const itemId = this.itemToRemove.id;

                    // Clear timeout
                    if (this.undoTimeout) {
                        clearTimeout(this.undoTimeout);
                        this.undoTimeout = null;
                    }

                    // Hapus dari array
                    this.items = this.items.filter(item => item.id !== itemId);

                    // Kirim request ke server
                    this.sendRemoveRequest(itemId);

                    // Reset state immediately
                    this.resetRemoveState();
                },

                async confirmRemove() {
                    if (!this.itemToRemove) return;

                    const itemId = this.itemToRemove.id;

                    // Clear timeout first
                    if (this.undoTimeout) {
                        clearTimeout(this.undoTimeout);
                        this.undoTimeout = null;
                    }

                    // Hapus permanen dari array items di tampilan
                    this.items = this.items.filter(item => item.id !== itemId);

                    // Kirim request ke server
                    await this.sendRemoveRequest(itemId);

                    // Reset state setelah selesai
                    this.resetRemoveState();
                },

                // Method terpisah untuk mengirim request ke server
                async sendRemoveRequest(itemId) {
                    try {
                        const response = await fetch(`/cart/remove/${itemId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
                                'Accept': 'application/json',
                            },
                        });

                        if (!response.ok) {
                            throw new Error('Server error');
                        }
                    } catch (error) {
                        console.error('Gagal menghapus item dari server:', error);
                        alert('Gagal menghapus item. Halaman akan dimuat ulang untuk sinkronisasi.');
                        window.location.reload();
                    }
                },

                // Method untuk reset state removal
                resetRemoveState() {
                    this.itemToRemove = null;
                    this.showUndo = false;
                    if (this.undoTimeout) {
                        clearTimeout(this.undoTimeout);
                        this.undoTimeout = null;
                    }
                },

                cancelRemove() {
                    if (!this.itemToRemove) return;

                    const itemIndex = this.items.findIndex(i => i.id === this.itemToRemove.id);
                    if (itemIndex !== -1) {
                        this.items[itemIndex].isRemoving = false;
                    }

                    this.resetRemoveState();
                },

                updateQuantity(itemId, newQuantity) {
                    const item = this.items.find(i => i.id === itemId);
                    if (!item) return;

                    const validatedQuantity = Math.max(1, parseInt(newQuantity) || 1);
                    item.quantity = validatedQuantity;

                    clearTimeout(this.updateTimeout);
                    this.updateTimeout = setTimeout(() => {
                        this.syncQuantity(itemId, validatedQuantity);
                    }, 500);
                },

                async syncQuantity(itemId, quantity) {
                    try {
                        const response = await fetch(`/cart/update/${itemId}`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                quantity: quantity
                            })
                        });

                        const result = await response.json();
                        if (!response.ok) {
                            alert(result.message || 'Gagal memperbarui kuantitas.');
                            window.location.reload();
                        }
                    } catch (error) {
                        console.error('Error updating quantity:', error);
                        alert('Terjadi kesalahan saat memperbarui kuantitas.');
                        window.location.reload();
                    }
                },

                formatCurrency(amount) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(amount);
                },

                get subtotal() {
                    return this.items.reduce((acc, item) => acc + (item.price * item.quantity), 0);
                },

                get tax() {
                    return this.subtotal * this.taxRate;
                },

                get total() {
                    return this.subtotal + this.shippingCost + this.tax;
                }
            }
        }
    </script>
@endsection
