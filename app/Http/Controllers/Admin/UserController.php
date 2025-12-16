<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class UserController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }
    /**
     * Display a listing of users with filter by role
     */
    public function index(Request $request)
    {
        // Only admin can access user management
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $query = User::withCount(['transactions', 'expenses']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSorts = ['name', 'email', 'role', 'created_at', 'transactions_count'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $users = $query->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        // Only admin can create users
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage
     */
    public function store(UserRequest $request)
    {
        $validated = $request->validated();

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            try {
                $uploadResult = $this->imageService->upload(
                    $request->file('avatar'), 
                    'users',
                    [
                        'resize' => ['width' => 300, 'height' => 300, 'maintain_aspect_ratio' => false],
                        'optimize' => true,
                        'quality' => 90,
                        'generate_thumbnails' => true,
                        'thumbnail_sizes' => ['thumbnail']
                    ]
                );
                $validated['avatar'] = $uploadResult['path'];
            } catch (\Exception $e) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Avatar upload failed: ' . $e->getMessage());
            }
        }

        $validated['password'] = Hash::make($validated['password']);
        $validated['email_verified_at'] = now(); // Auto-verify admin created users

        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        // Load user statistics
        $totalTransactions = $user->transactions()->count();
        $totalRevenue = $user->transactions()->sum('total');
        $totalExpenses = $user->expenses()->sum('amount');
        $recentTransactions = $user->transactions()
            ->with('customer')
            ->latest()
            ->limit(5)
            ->get();
        $recentExpenses = $user->expenses()
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.users.show', compact(
            'user',
            'totalTransactions',
            'totalRevenue',
            'totalExpenses',
            'recentTransactions',
            'recentExpenses'
        ));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        // Only admin can edit users, or users can edit themselves (limited)
        if (!auth()->user()->isAdmin() && auth()->id() !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage
     */
    public function update(UserRequest $request, User $user)
    {
        $validated = $request->validated();

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            try {
                // Delete old avatar if exists
                if ($user->avatar) {
                    $this->imageService->delete($user->avatar);
                }

                $uploadResult = $this->imageService->upload(
                    $request->file('avatar'), 
                    'users',
                    [
                        'resize' => ['width' => 300, 'height' => 300, 'maintain_aspect_ratio' => false],
                        'optimize' => true,
                        'quality' => 90,
                        'generate_thumbnails' => true,
                        'thumbnail_sizes' => ['thumbnail']
                    ]
                );
                $validated['avatar'] = $uploadResult['path'];
            } catch (\Exception $e) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Avatar upload failed: ' . $e->getMessage());
            }
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage
     */
    public function destroy(User $user)
    {
        // Only admin can delete users
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        // Prevent deleting self
        if (auth()->id() === $user->id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        // Check if user has transactions or expenses
        if ($user->transactions()->exists() || $user->expenses()->exists()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Cannot delete user. User has transaction or expense history.');
        }

        // Delete avatar if exists
        if ($user->avatar) {
            $this->imageService->delete($user->avatar);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Reset user password
     */
    public function resetPassword(Request $request, User $user)
    {
        // Only admin can reset passwords
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed'
        ]);

        $user->update([
            'password' => Hash::make($validated['password'])
        ]);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'Password reset successfully.');
    }

    /**
     * Change user role
     */
    public function changeRole(Request $request, User $user)
    {
        // Only admin can change roles
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'role' => 'required|in:admin,manager,cashier'
        ]);

        // Prevent changing own role to non-admin
        if (auth()->id() === $user->id && $validated['role'] !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'You cannot change your own role from admin.'
            ]);
        }

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Role updated successfully',
            'new_role' => $user->role
        ]);
    }

    /**
     * Remove avatar from user
     */
    public function removeAvatar(User $user)
    {
        // Only admin can remove avatars, or users can remove their own
        if (!auth()->user()->isAdmin() && auth()->id() !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        if ($user->avatar) {
            $deleted = $this->imageService->delete($user->avatar);
            
            if ($deleted) {
                $user->update(['avatar' => null]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Avatar removed successfully'
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'No avatar to remove or deletion failed'
        ]);
    }

    /**
     * Get user statistics for dashboard
     */
    public function getStats(User $user)
    {
        $stats = [
            'total_transactions' => $user->transactions()->count(),
            'total_revenue' => $user->transactions()->sum('total'),
            'total_expenses' => $user->expenses()->sum('amount'),
            'transactions_today' => $user->transactions()->whereDate('created_at', today())->count(),
            'revenue_today' => $user->transactions()->whereDate('created_at', today())->sum('total'),
            'expenses_today' => $user->expenses()->whereDate('created_at', today())->sum('amount'),
        ];

        return response()->json($stats);
    }

    /**
     * Export users to CSV
     */
    public function export(Request $request)
    {
        // Only admin can export users
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $users = User::withCount(['transactions', 'expenses'])->get();

        $filename = 'users_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID', 'Name', 'Email', 'Phone', 'Role', 
                'Total Transactions', 'Total Expenses', 'Created Date'
            ]);

            // CSV data
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->phone,
                    $user->role,
                    $user->transactions_count,
                    $user->expenses_count,
                    $user->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}