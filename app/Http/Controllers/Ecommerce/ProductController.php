<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\NotificationService;

class ProductController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        $products = Product::paginate(10);
        return inertia('Ecommerce/Products/Index', compact('products'));
    }

    public function show(Product $product)
    {
        // Décoder les images JSON pour les passer en tableau à la vue
        $product->images = json_decode($product->image ?? '[]', true);
        return inertia('Ecommerce/Products/Show', compact('product'));
    }

    public function create()
    {
        return inertia('Ecommerce/Products/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'nullable|string|max:255',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imagePaths = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $imagePaths[] = $path;
            }
        }

        Product::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'category' => $validated['category'] ?? null,
            'image' => json_encode($imagePaths),
        ]);

        // Notification
        $this->notificationService->sendToRole(
            'admin',
            'New Product Created',
            'A new product has been created in the system.',
            'success'
        );
        return redirect()->route('ecommerce.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $product->images = json_decode($product->image ?? '[]', true);
        return inertia('Ecommerce/Products/Edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'nullable|string|max:255',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imagePaths = json_decode($product->image ?? '[]', true);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $imagePaths[] = $path;
            }
        }

        $product->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'category' => $validated['category'] ?? null,
            'image' => json_encode($imagePaths),
        ]);

        // Notification
        $this->notificationService->sendToRole(
            'admin',
            'Product Updated',
            'A product has been updated in the system.',
            'warning'
        );
        return redirect()->route('ecommerce.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        // Supprimer les images du stockage avant suppression du produit
        $images = json_decode($product->image ?? '[]', true);
        foreach ($images as $img) {
            Storage::disk('public')->delete($img);
        }

        $product->delete();

        // Notification
        $this->notificationService->sendToRole(
            'admin',
            'Product Deleted',
            'A product has been deleted from the system.',
            'error'
        );
        return redirect()->route('ecommerce.index')->with('success', 'Product deleted successfully.');
    }
}
