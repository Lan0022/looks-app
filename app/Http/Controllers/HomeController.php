<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    /**
     * Display the home page with featured products and testimonials.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // // Ambil produk trending/featured
        // $featuredProducts = Product::where('is_featured', true)
        //     ->orWhere('is_trending', true)
        //     ->take(4)
        //     ->get();

        // // Ambil testimonial (jika ada model Review)
        // $testimonials = [
        //     [
        //         'name' => 'Alex R.',
        //         'rating' => 5,
        //         'comment' => 'The quality of this shirt is incredible. It feels amazing and looks even better. My new favorite!'
        //     ],
        //     [
        //         'name' => 'Jessica M.',
        //         'rating' => 5,
        //         'comment' => 'Finally found the perfect pair of pants. They fit perfectly and are so versatile. Highly recommend!'
        //     ],
        //     [
        //         'name' => 'David L.',
        //         'rating' => 5,
        //         'comment' => 'Fast shipping and beautiful packaging. The unboxing experience was almost as good as the jacket itself!'
        //     ]
        // ];

        // return view('home', compact('featuredProducts', 'testimonials'));
        return view('home');
    }
}
