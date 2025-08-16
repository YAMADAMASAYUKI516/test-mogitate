<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Season;
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('keyword')) {
            $keyword = preg_replace('/^\s+|\s+$/u', '', $request->input('keyword'));
            if ($keyword !== '') {
                $query->where('name', 'like', '%' . $keyword . '%');
            }
        }

        $sortMap = [
            'price_asc'  => ['price', 'asc'],
            'price_desc' => ['price', 'desc'],
        ];
        $sortKey = $request->input('sort');

        if (isset($sortMap[$sortKey])) {
            [$col, $dir] = $sortMap[$sortKey];
            $query->orderBy($col, $dir);
        } else {
            $query->orderBy('id', 'asc');
        }

        $products = $query->paginate(6)->appends($request->query());

        return view('index', compact('products'));
    }

    public function create()
    {
        $seasons = Season::all();
        return view('create', compact('seasons'));
    }

    public function store(ProductRequest $request)
    {
        $validated = $request->validated();

        $imagePath = $validated['image']->store('public/fruits-img');
        $imageName = basename($imagePath);

        $product = Product::create([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'image' => $imageName,
            'description' => $validated['description'],
        ]);

        $product->seasons()->attach($validated['seasons']);

        return redirect('/products');
    }

    public function edit(Product $product)
    {
        $seasons = Season::all();
        return view('edit', compact('product', 'seasons'));
    }
}
