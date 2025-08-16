<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $seasons = Season::orderBy('id', 'asc')->get();
        return view('create', compact('seasons'));
    }

    public function store(ProductRequest $request)
    {
        $imagePathForDb = null;
        if ($request->hasFile('image')) {
            $stored = $request->file('image')->store('fruits-img', 'public');
            $imagePathForDb = 'storage/' . $stored; // asset($product->image) で表示できる形
        }

        $product = new \App\Models\Product();
        $product->name        = $request->input('name');
        $product->price       = $request->input('price');
        $product->description = $request->input('description'); // カラムが無ければ削除
        if ($imagePathForDb) $product->image = $imagePathForDb;
        $product->save();

        if ($request->filled('season_ids')) {
            $product->seasons()->sync($request->input('season_ids'));
        }

        return redirect()->route('products.search')->with('success', '商品を登録しました。');
    }
}
