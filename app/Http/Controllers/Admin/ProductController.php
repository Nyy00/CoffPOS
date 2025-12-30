<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Category;
use App\Services\SimpleImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    protected $imageService;

    public function __construct(SimpleImageService $imageService)
    {
        $this->imageService = $imageService;
    }
    /**
     * Display a listing of products with search and filter
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Debug logging
        \Log::info('Products search request', [
            'search' => $request->search,
            'category_id' => $request->category_id,
            'is_available' => $request->is_available,
            'all_params' => $request->all(),
            'url' => $request->fullUrl(),
            'method' => $request->method()
        ]);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            \Log::info('Applying search filter', ['search_term' => $search]);
            $query->where(function ($q) use ($search) {
                // Use ILIKE for PostgreSQL case-insensitive search
                if (config('database.default') === 'pgsql') {
                    $q->where('name', 'ILIKE', "%{$search}%")
                      ->orWhere('description', 'ILIKE', "%{$search}%");
                } else {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                }
            });
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by availability
        if ($request->filled('is_available')) {
            $isAvailable = $request->is_available;
            // Handle both string '1'/'0' and boolean true/false
            if ($isAvailable === '1' || $isAvailable === 1 || $isAvailable === true) {
                $query->where('is_available', true);
            } elseif ($isAvailable === '0' || $isAvailable === 0 || $isAvailable === false) {
                $query->where('is_available', false);
            }
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

        // Debug: Log the final query
        \Log::info('Final query SQL', ['sql' => $query->toSql(), 'bindings' => $query->getBindings()]);

        $products = $query->paginate(15)->withQueryString();
        $categories = Category::all()->pluck('name', 'id');

        // Debug: Log results count and sample data
        \Log::info('Products search results', [
            'count' => $products->count(), 
            'total' => $products->total(),
            'first_product' => $products->count() > 0 ? $products->first()->name : 'none',
            'search_applied' => $request->filled('search'),
            'search_term' => $request->search
        ]);

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new product
     */
    public function create()
    {
        $categories = Category::all()->pluck('name', 'id');
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
                    'products'
                );
                $validated['image'] = $uploadResult['path'];
            } catch (\Exception $e) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Image upload failed: ' . $e->getMessage());
            }
        }

        $validated['is_available'] = $request->has('is_available');
        
        // Set default cost if not provided (70% of price)
        if (!isset($validated['cost']) || $validated['cost'] === null) {
            $validated['cost'] = $validated['price'] * 0.7;
        }

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
        $categories = Category::all()->pluck('name', 'id');
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
                    'products'
                );
                $validated['image'] = $uploadResult['path'];
            } catch (\Exception $e) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Image upload failed: ' . $e->getMessage());
            }
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product from storage
     */
    public function destroy(Product $product)
    {
        try {
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
                
        } catch (\Exception $e) {
            \Log::error('Product deletion failed: ' . $e->getMessage());
            return redirect()->route('admin.products.index')
                ->with('error', 'Failed to delete product: ' . $e->getMessage());
        }
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
                // Use ILIKE for PostgreSQL case-insensitive search
                if (config('database.default') === 'pgsql') {
                    $q->where('name', 'ILIKE', "%{$search}%")
                      ->orWhere('description', 'ILIKE', "%{$search}%")
                      ->orWhereHas('category', function ($q) use ($search) {
                          $q->where('name', 'ILIKE', "%{$search}%");
                      });
                } else {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhereHas('category', function ($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      });
                }
            });
        }

        // Filter by availability (for admin search)
        if ($request->filled('available_only') && $request->available_only) {
            $query->where('is_available', true)->where('stock', '>', 0);
        }

        // Pagination for search results
        $perPage = $request->get('per_page', 10);
        $products = $query->orderBy('name')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $products->items(),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'has_more' => $products->hasMorePages()
            ]
        ]);
    }

    /**
     * API endpoint for advanced product filtering
     */
    public function filter(Request $request)
    {
        // If not AJAX request, redirect to normal index with parameters
        if (!$request->ajax() && !$request->wantsJson()) {
            return redirect()->route('admin.products.index', $request->all());
        }

        $query = Product::with('category');

        // Category filter
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Availability filter
        if ($request->filled('is_available')) {
            $isAvailable = $request->is_available;
            // Handle both string '1'/'0' and boolean true/false
            if ($isAvailable === '1' || $isAvailable === 1 || $isAvailable === true) {
                $query->where('is_available', true);
            } elseif ($isAvailable === '0' || $isAvailable === 0 || $isAvailable === false) {
                $query->where('is_available', false);
            }
        }

        // Stock level filter
        if ($request->filled('stock_min')) {
            $query->where('stock', '>=', $request->stock_min);
        }
        if ($request->filled('stock_max')) {
            $query->where('stock', '<=', $request->stock_max);
        }

        // Price range filter
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        // Search query
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                // Use ILIKE for PostgreSQL case-insensitive search
                if (config('database.default') === 'pgsql') {
                    $q->where('name', 'ILIKE', "%{$search}%")
                      ->orWhere('description', 'ILIKE', "%{$search}%");
                } else {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                }
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');
        $allowedSorts = ['name', 'price', 'stock', 'created_at'];
        
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $perPage = $request->get('per_page', 15);
        $products = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $products->items(),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem()
            ],
            'filters_applied' => $request->only([
                'category_id', 'is_available', 'stock_min', 'stock_max', 
                'price_min', 'price_max', 'search', 'sort_by', 'sort_order'
            ])
        ]);
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