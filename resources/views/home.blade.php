@extends('layouts.app')

@section('title', 'LOOKS - Modern Fashion Retail')

@section('content')
    <!--
                                                                                          Hero Section
                                                                                          - Full-width image with overlay for text readability
                                                                                          - Strong H1 and compelling CTA
                                                                                        -->
    <section class="relative h-[80vh] min-h-[500px] bg-cover bg-center"
        style="
          background-image: url('https://images.unsplash.com/photo-1441986300917-64674bd600d8?q=80&w=1470&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');
        ">
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="relative container mx-auto px-6 h-full flex flex-col items-center justify-center text-center text-white">
            <h1 class="text-4xl md:text-6xl font-bold font-heading uppercase tracking-wider">
                Effortless Style. Defined By You.
            </h1>
            <p class="mt-4 max-w-2xl text-lg text-gray-200">
                Curated collections of modern essentials designed for your wardrobe.
            </p>
            <a href="#"
                class="mt-8 px-10 py-4 bg-accent text-white font-semibold uppercase text-sm rounded-md hover:bg-accent-dark transition-colors duration-300">
                Shop New Arrivals
            </a>
        </div>
    </section>

    <!--
                                                                                          Category Navigation
                                                                                          - Visual separation for Men's and Women's sections
                                                                                          - High-quality images to draw users in
                                                                                        -->
    <section class="py-16 md:py-24">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <a href="#" class="group relative h-96 flex items-end justify-start p-8 rounded-lg overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1603692833615-81dd11ebd2e6?q=80&w=1473&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                        alt="Man in stylish clothing"
                        class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
                    <div class="absolute inset-0 bg-black/30"></div>
                    <div class="relative">
                        <h2 class="text-3xl font-bold text-white font-heading">
                            For Him
                        </h2>
                        <div class="mt-2 text-white font-semibold underline underline-offset-4">
                            Shop Men
                        </div>
                    </div>
                </a>
                <a href="#" class="group relative h-96 flex items-end justify-start p-8 rounded-lg overflow-hidden">
                    <img src="https://plus.unsplash.com/premium_photo-1690350731538-57344931ac02?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                        alt="Woman in stylish clothing"
                        class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
                    <div class="absolute inset-0 bg-black/30"></div>
                    <div class="relative">
                        <h2 class="text-3xl font-bold text-white font-heading">
                            For Her
                        </h2>
                        <div class="mt-2 text-white font-semibold underline underline-offset-4">
                            Shop Women
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!--
                                                                                          Featured Products
                                                                                          - Clean grid layout for product cards
                                                                                          - Interactive hover effects with "Quick View"
                                                                                        -->
    <section class="py-16 md:py-24 bg-white">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-center font-heading mb-12">
                Trending Now
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 md:gap-8">
                <!-- Product Card 1 -->
                <div class="group relative">
                    <div class="relative w-full h-72 md:h-96 rounded-lg overflow-hidden bg-gray-200">
                        <img src="https://images.unsplash.com/photo-1611312449408-fcece27cdbb7?q=80&w=369&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                            alt="Denim Jacket" class="w-full h-full object-cover" />
                        <div
                            class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                            <button
                                @click="quickViewOpen = true; quickViewProduct = {name: 'The Classic Denim Jacket', price: '$129.99', image: 'https://placehold.co/400x600/e5e7eb/333333?text=Jacket'}"
                                class="px-4 py-2 bg-white text-gray-800 text-sm font-semibold rounded-md">
                                Quick View
                            </button>
                        </div>
                    </div>
                    <div class="mt-4 text-center">
                        <h3 class="text-sm font-semibold text-gray-800">
                            The Classic Denim Jacket
                        </h3>
                        <p class="mt-1 text-md text-gray-600">$129.99</p>
                    </div>
                </div>
                <!-- Product Card 2 -->
                <div class="group relative">
                    <div class="relative w-full h-72 md:h-96 rounded-lg overflow-hidden bg-gray-200">
                        <img src="https://images.unsplash.com/photo-1740711152088-88a009e877bb?q=80&w=580&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                            alt="Linen Shirt" class="w-full h-full object-cover" />
                        <div
                            class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                            <button
                                @click="quickViewOpen = true; quickViewProduct = {name: 'Breezy Linen Shirt', price: '$79.00', image: 'https://placehold.co/400x600/d1d5db/333333?text=Shirt'}"
                                class="px-4 py-2 bg-white text-gray-800 text-sm font-semibold rounded-md">
                                Quick View
                            </button>
                        </div>
                    </div>
                    <div class="mt-4 text-center">
                        <h3 class="text-sm font-semibold text-gray-800">
                            Breezy Linen Shirt
                        </h3>
                        <p class="mt-1 text-md text-gray-600">$79.00</p>
                    </div>
                </div>
                <!-- Product Card 3 -->
                <div class="group relative">
                    <div class="relative w-full h-72 md:h-96 rounded-lg overflow-hidden bg-gray-200">
                        <img src="https://plus.unsplash.com/premium_photo-1689977493146-ed929d07d97e?q=80&w=387&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                            alt="Chino Pants" class="w-full h-full object-cover" />
                        <div
                            class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                            <button
                                @click="quickViewOpen = true; quickViewProduct = {name: 'The Everyday Chino', price: '$89.50', image: 'https://placehold.co/400x600/e5e7eb/333333?text=Pants'}"
                                class="px-4 py-2 bg-white text-gray-800 text-sm font-semibold rounded-md">
                                Quick View
                            </button>
                        </div>
                    </div>
                    <div class="mt-4 text-center">
                        <h3 class="text-sm font-semibold text-gray-800">
                            The Everyday Chino
                        </h3>
                        <p class="mt-1 text-md text-gray-600">$89.50</p>
                    </div>
                </div>
                <!-- Product Card 4 -->
                <div class="group relative">
                    <div class="relative w-full h-72 md:h-96 rounded-lg overflow-hidden bg-gray-200">
                        <img src="https://images.unsplash.com/photo-1676838179247-6e60dba67d5c?q=80&w=387&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                            alt="Leather Sneakers" class="w-full h-full object-cover" />
                        <div
                            class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                            <button
                                @click="quickViewOpen = true; quickViewProduct = {name: 'Minimalist Leather Sneaker', price: '$149.00', image: 'https://placehold.co/400x600/d1d5db/333333?text=Shoes'}"
                                class="px-4 py-2 bg-white text-gray-800 text-sm font-semibold rounded-md">
                                Quick View
                            </button>
                        </div>
                    </div>
                    <div class="mt-4 text-center">
                        <h3 class="text-sm font-semibold text-gray-800">
                            Minimalist Leather Sneaker
                        </h3>
                        <p class="mt-1 text-md text-gray-600">$149.00</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--
                                                                                          The "Looks" Difference - Trust Building
                                                                                          - Split layout for visual interest
                                                                                          - Clear icons and messaging about brand values
                                                                                        -->
    <section class="py-16 md:py-24">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row items-center gap-12">
                <div class="md:w-1/2">
                    <img src="https://images.unsplash.com/photo-1611747053746-e2ce2307ed18?q=80&w=686&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                        alt="Close up of high-quality fabric" class="rounded-lg shadow-lg w-full" />
                </div>
                <div class="md:w-1/2">
                    <h2 class="text-3xl font-bold font-heading">
                        Curated Styles, Guaranteed Authenticity
                    </h2>
                    <p class="mt-4 text-gray-600 text-justify">
                        At Looks, we believe true style is a statement of authenticity. We meticulously curate our
                        collection featuring apparel, footwear, jackets, and trousers from both renowned international
                        designers and Indonesia's most talented local artisans. Every item in our selection is a testament
                        to superior quality and is guaranteed 100% authentic.
                    </p>
                    <div class="mt-8 space-y-4">
                        <div class="flex items-center">
                            <svg class="h-6 w-6 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="ml-3 text-gray-700">Guaranteed 100% Authentic Products</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="h-6 w-6 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="ml-3 text-gray-700">Curated from the Best Global & Local Brands</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="h-6 w-6 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="ml-3 text-gray-700">Free & Easy 30-Day Returns</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--
                                                                                          Social Proof - Customer Reviews
                                                                                          - Builds credibility with user-generated content
                                                                                        -->
    <section class="py-16 md:py-24 bg-white">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-center font-heading mb-12">
                As Worn By You
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div class="p-8 border border-gray-200 rounded-lg">
                    <div class="flex justify-center text-yellow-400 mb-4">★★★★★</div>
                    <p class="text-gray-600 italic">
                        "The quality of this shirt is incredible. It feels amazing and
                        looks even better. My new favorite!"
                    </p>
                    <p class="mt-4 font-bold">- Alex R.</p>
                </div>
                <div class="p-8 border border-gray-200 rounded-lg">
                    <div class="flex justify-center text-yellow-400 mb-4">★★★★★</div>
                    <p class="text-gray-600 italic">
                        "Finally found the perfect pair of pants. They fit perfectly and
                        are so versatile. Highly recommend!"
                    </p>
                    <p class="mt-4 font-bold">- Jessica M.</p>
                </div>
                <div class="p-8 border border-gray-200 rounded-lg">
                    <div class="flex justify-center text-yellow-400 mb-4">★★★★★</div>
                    <p class="text-gray-600 italic">
                        "Fast shipping and beautiful packaging. The unboxing experience
                        was almost as good as the jacket itself!"
                    </p>
                    <p class="mt-4 font-bold">- David L.</p>
                </div>
            </div>
        </div>
    </section>

    <!--
                                                                                          Newsletter CTA
                                                                                          - High-contrast section to grab attention
                                                                                          - Clear value proposition (discount) to drive sign-ups
                                                                                        -->
    <section class="py-20 bg-gray-800 text-white">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold font-heading">
                GET 15% OFF YOUR FIRST ORDER
            </h2>
            <p class="mt-2 max-w-2xl mx-auto">
                Sign up for our newsletter for exclusive access to new drops and
                private sales.
            </p>
            <form class="mt-8 max-w-md mx-auto flex flex-col sm:flex-row gap-4">
                <input type="email" placeholder="Enter your email address"
                    class="w-full px-4 py-3 rounded-md text-gray-800 focus:outline-none focus:ring-2 focus:ring-accent"
                    required />
                <button type="submit"
                    class="w-full sm:w-auto px-8 py-3 bg-accent text-white font-semibold uppercase text-sm rounded-md hover:bg-accent-dark transition-colors duration-300">
                    Subscribe
                </button>
            </form>
        </div>
    </section>
@endsection
