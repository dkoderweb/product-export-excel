<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $products = Product::latest()->get();
            return DataTables::of($products)
                ->addColumn('action', function ($product) {
                    return '<button class="btn btn-primary edit-btn">Edit</button> <button class="btn btn-danger delete-btn">Delete</button>';
                })
                ->make(true);
        }

        return view('home');
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string',
            'price' => 'required|numeric',
            'discount' => 'required|numeric',
        ]);

        Product::create($request->all());

        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'product_name' => 'required|string',
            'price' => 'required|numeric',
            'discount' => 'required|numeric',
        ]);

        $product = Product::findOrFail($id);
        $product->update($request->all());

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['success' => true]);
    }
}
