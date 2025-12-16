<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Category;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }
    /**
     * Display a listing of products with search and filter
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by availability
        if ($request->filled('is_available')) {
            $query->where('is_available', $request->is_available);
        }

        // Filter by stock level
        if ($request->filled('stock_filter')) {
            switch ($request->stock_filter) {
                case 'low':
                    $query->where('stock', '<', 10);
                    break;
                case 'out':
                    $query->where('stock', '=', 0);
                    break;
                case 'available':
                    $query->where('stock', '>', 0);
                    break;
            }
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSorts = ['name', 'price', 'stock', 'created_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $products = $query->paginate(15)->withQueryString();
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new product
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage
     */
    public function store(ProductRequest $request)
    {
        $validated = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            try {
                $uploadResult = $this->imageService->upload(
                    $request->file('image'), 
                    'products',
                    [
                        'resize' => ['width' => 800, 'height' => 800, 'maintain_aspect_ratio' => true],
                        'optimize' => true,
                        'quality' => 85,
                        'generate_thumbnails' => true,
                        'thumbnail_sizes' => ['thumbnail', 'small', 'medium']
                    ]
                );
                $validated['image'] = $uploadResult['path'];
            } catch (\Exception $e) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Image upload failed: ' . $e->getMessage());
            }
        }

        $validated['is_available'] = $request->has('is_available');

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified product with transaction history
     */
    public function show(Product $product)
    {
        $product->load('category');
        
        // Get transaction history for this product
        $transactionItems = $product->transactionItems()
            ->with(['transaction.customer', 'transaction.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Calculate statistics
        $totalSold = $product->transactionItems()->sum('quantity');
        $totalRevenue = $product->transactionItems()
            ->selectRaw('SUM(quantity * price) as total')
            ->value('total') ?? 0;

        return view('admin.products.show', compact('product', 'transactionItems', 'totalSold', 'totalRevenue'));
    }

    /**
     * Show the form for editing the specified product
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage
     */
    public function update(ProductRequest $request, Product $product)
    {
        $validated = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            try {
                // Delete old image if exists
                if ($product->image) {
                    $this->imageService->delete($product->image);
                }

                $uploadResult = $this->imageService->upload(
                    $request->file('image'), 
                    'products',
                    [
                        'resize' => ['width' => 800, 'height' => 800, 'maintain_aspect_ratio' => true],
                        'optimize' => true,
                        'quality' => 85,
                        'generate_thumbnails' => true,
                        'thumbnail_sizes' => ['thumbnail', 'small', 'medium']
                    ]
                );
                $validated['image'] = $uploadResult['path'];
            } catch (\Exception $e) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Image upload failed: ' . $e->getMessage());
            }
        }

        $validated['is_available'] = $request->has('is_available');

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product from storage
     */
    public function destroy(Product $product)
    {
        // Check if product has transaction items
        if ($product->transactionItems()->exists()) {
            return redirect()->route('admin.products.index')
                ->with('error', 'Cannot delete product. It has transaction history.');
        }

        // Delete image if exists
        if ($product->image) {
            $this->imageService->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    /**
     * API endpoint for live search (AJAX)
     */
    public function search(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $products = $query->where('is_available', true)
            ->where('stock', '>', 0)
            ->limit(10)
            ->get();

        return response()->json($products);
    }

    /**
     * Update stock for a product
     */
    public function updateStock(Request $request, Product $product)
    {
        $validated = $request->validate([
            'stock' => 'required|integer|min:0',
            'action' => 'required|in:add,subtract,set'
        ]);

        switch ($validated['action']) {
            case 'add':
                $product->increment('stock', $validated['stock']);
                break;
            case 'subtract':
                $product->decrement('stock', $validated['stock']);
                break;
            case 'set':
                $product->update(['stock' => $validated['stock']]);
                break;
        }

        return response()->json([
            'success' => true,
            'message' => 'Stock updated successfully',
            'new_stock' => $product->fresh()->stock
        ]);
    }
}