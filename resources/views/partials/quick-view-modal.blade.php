<div x-show="quickViewOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" @click.away="quickViewOpen = false"
    style="display: none">
    <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full mx-auto flex flex-col md:flex-row overflow-hidden"
        @click.stop>
        <div class="md:w-1/2">
            <img :src="quickViewProduct.image" alt="Product Image" class="w-full h-full object-cover" />
        </div>
        <div class="md:w-1/2 p-8 flex flex-col">
            <div class="flex justify-between items-start">
                <h2 class="text-2xl font-bold font-heading" x-text="quickViewProduct.name"></h2>
                <button @click="quickViewOpen = false" class="text-gray-500 hover:text-gray-800">
                    &times;
                </button>
            </div>
            <p class="text-2xl mt-2 text-accent" x-text="quickViewProduct.price"></p>
            <p class="mt-4 text-gray-600 text-sm">
                This is a brief, compelling description of the product. It
                highlights the key features, material, and fit to help the customer
                make a quick decision.
            </p>

            <div class="mt-6">
                <label for="size" class="block text-sm font-medium text-gray-700">Size</label>
                <select id="size" name="size"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-accent focus:border-accent sm:text-sm rounded-md">
                    <option>S</option>
                    <option selected>M</option>
                    <option>L</option>
                    <option>XL</option>
                </select>
            </div>

            <div class="mt-auto pt-6">
                <button
                    class="w-full bg-accent text-white py-3 rounded-md hover:bg-accent-dark transition-colors duration-300 font-semibold">
                    Add to Cart
                </button>
            </div>
        </div>
    </div>
</div>
