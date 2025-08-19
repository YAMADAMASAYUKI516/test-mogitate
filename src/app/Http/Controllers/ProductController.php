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

        $image = $validated['image'];
        $timestamp = now()->format('YmdHis');
        $originalName = $image->getClientOriginalName();
        $fileName = $timestamp . '_' . $originalName;
        $image->storeAs('public/fruits-img', $fileName);

        $product = Product::create([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'image' => $fileName,
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

    public function update(ProductRequest $request, Product $product)
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $timestamp = now()->format('YmdHis');
            $originalName = $image->getClientOriginalName();
            $fileName = $timestamp . '_' . $originalName;
            $image->storeAs('public/fruits-img', $fileName);
        } else {
            $fileName = basename($request->input('saved_image'));
        }

        $product->update([
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'description' => $request->input('description'),
            'image' => $fileName,
        ]);

        $product->seasons()->sync($request->input('seasons'));

        return redirect('/products');
    }

    public function destroy(Product $product)
    {
        if ($product->image && Storage::exists('public/fruits-img/' . $product->image)) {
            Storage::delete('public/fruits-img/' . $product->image);
        }

        $product->seasons()->detach();
        $product->delete();

        return redirect('/products');
    }
}
