<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }
    /**
     * Display a listing of categories
     */
    public function index(Request $request)
    {
        $query = Category::withCount('products');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSorts = ['name', 'created_at', 'products_count'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $categories = $query->paginate(15)->withQueryString();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created category in storage
     */
    public function store(CategoryRequest $request)
    {
        $validated = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            try {
                $uploadResult = $this->imageService->upload(
                    $request->file('image'), 
                    'categories',
                    [
                        'resize' => ['width' => 600, 'height' => 400, 'maintain_aspect_ratio' => true],
                        'optimize' => true,
                        'quality' => 90,
                        'generate_thumbnails' => true,
                        'thumbnail_sizes' => ['thumbnail', 'small']
                    ]
                );
                $validated['image'] = $uploadResult['path'];
            } catch (\Exception $e) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Image upload failed: ' . $e->getMessage());
            }
        }

        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified category
     */
    public function show(Category $category)
    {
        $category->load('products');
        
        // Get category statistics
        $totalProducts = $category->products()->count();
        $availableProducts = $category->products()->where('is_available', true)->count();
        $totalStock = $category->products()->sum('stock');
        $lowStockProducts = $category->products()->where('stock', '<', 10)->count();

        return view('admin.categories.show', compact(
            'category', 
            'totalProducts', 
            'availableProducts', 
            'totalStock', 
            'lowStockProducts'
        ));
    }

    /**
     * Show the form for editing the specified category
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified category in storage
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $validated = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            try {
                // Delete old image if exists
                if ($category->image) {
                    $this->imageService->delete($category->image);
                }

                $uploadResult = $this->imageService->upload(
                    $request->file('image'), 
                    'categories',
                    [
                        'resize' => ['width' => 600, 'height' => 400, 'maintain_aspect_ratio' => true],
                        'optimize' => true,
                        'quality' => 90,
                        'generate_thumbnails' => true,
                        'thumbnail_sizes' => ['thumbnail', 'small']
                    ]
                );
                $validated['image'] = $uploadResult['path'];
            } catch (\Exception $e) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Image upload failed: ' . $e->getMessage());
            }
        }

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category from storage
     */
    public function destroy(Category $category)
    {
        // Check if category has products
        if ($category->products()->exists()) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Cannot delete category. It has products associated with it.');
        }

        // Delete image if exists
        if ($category->image) {
            $this->imageService->delete($category->image);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }

    /**
     * API endpoint for category dropdown (AJAX)
     */
    public function getCategories(Request $request)
    {
        $categories = Category::select('id', 'name')
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->search}%");
            })
            ->orderBy('name')
            ->get();

        return response()->json($categories);
    }

    /**
     * Remove image from category
     */
    public function removeImage(Category $category)
    {
        if ($category->image) {
            $deleted = $this->imageService->delete($category->image);
            
            if ($deleted) {
                $category->update(['image' => null]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Image removed successfully'
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'No image to remove or deletion failed'
        ]);
    }
}