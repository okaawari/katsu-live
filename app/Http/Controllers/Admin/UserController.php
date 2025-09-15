<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\PaymentHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::with(['roles', 'permissions'])
            ->withCount(['anime', 'episodes', 'comments', 'ratings']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by subscription status
        if ($request->filled('subscription_status')) {
            switch ($request->subscription_status) {
                case 'active':
                    $query->where('subscription_expires_at', '>', now());
                    break;
                case 'expired':
                    $query->where('subscription_expires_at', '<=', now());
                    break;
                case 'free':
                    $query->whereNull('subscription_expires_at');
                    break;
            }
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'subscription_type' => 'nullable|in:free,premium,vip',
            'subscription_expires_at' => 'nullable|date|after:now',
            'subscription_duration_days' => 'nullable|integer|min:1|max:365',
            'subscription_duration_months' => 'nullable|integer|min:1|max:12',

            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
            'status' => 'nullable|in:active,inactive,suspended,banned',
        ]);

        // Calculate subscription expiration date if duration is provided
        $subscriptionExpiresAt = $validated['subscription_expires_at'];
        $subscriptionDate = now();

        // Only calculate new expiration date if days or months are provided
        if ($validated['subscription_duration_days'] ?? false) {
            $subscriptionExpiresAt = now()->addDays((int) $validated['subscription_duration_days']);
        } elseif ($validated['subscription_duration_months'] ?? false) {
            $subscriptionExpiresAt = now()->addMonths((int) $validated['subscription_duration_months']);
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'subscription_type' => $validated['subscription_type'] ?? 'free',
            'subscription_date' => $subscriptionDate,
            'subscription_expires_at' => $subscriptionExpiresAt,
            'status' => $validated['status'] ?? 'active',
        ]);

        if (isset($validated['roles'])) {
            $user->syncRoles($validated['roles']);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load([
            'roles', 
            'permissions', 
            'anime', 
            'episodes', 
            'comments', 
            'ratings',
            'videoProgress',
            'episodeLists',
            'userBadges.badge',
            'sessions',
            'paymentHistories'
        ]);

        $roles = Role::all();
        
        return view('admin.users.show', compact('user', 'roles'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'subscription_type' => 'nullable|in:free,premium,vip',
            'subscription_expires_at' => 'nullable|date',
            'subscription_duration_days' => 'nullable|integer|min:1|max:365',
            'subscription_duration_months' => 'nullable|integer|min:1|max:12',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
            'status' => 'nullable|in:active,inactive,suspended,banned',
            'status_reason' => 'nullable|string|max:500',
            'bio' => 'nullable|string|max:1000',
            'location' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'birth_date' => 'nullable|date',
        ]);

        // Calculate subscription expiration date if duration is provided
        $subscriptionExpiresAt = $validated['subscription_expires_at'];
        $subscriptionDate = $user->subscription_date ?? now();

        // Only calculate new expiration date if days or months are provided
        $durationAdded = false;
        if ($validated['subscription_duration_days'] ?? false) {
            // If subscription_expires_at is not null, add days to it, otherwise add to now
            $baseDate = $user->subscription_expires_at ? $user->subscription_expires_at : now();
            $subscriptionExpiresAt = $baseDate->addDays((int) $validated['subscription_duration_days']);
            $durationAdded = true;
        } elseif ($validated['subscription_duration_months'] ?? false) {
            // If subscription_expires_at is not null, add months to it, otherwise add to now
            $baseDate = $user->subscription_expires_at ? $user->subscription_expires_at : now();
            $subscriptionExpiresAt = $baseDate->addMonths((int) $validated['subscription_duration_months']);
            $durationAdded = true;
        }

        // Update subscription_date if it's null and we're setting an expiration date
        if (!$user->subscription_date && $subscriptionExpiresAt) {
            $validated['subscription_date'] = now();
        }

        // Only update subscription_expires_at if we calculated a new value or if it was explicitly provided
        if ($subscriptionExpiresAt) {
            $validated['subscription_expires_at'] = $subscriptionExpiresAt;
        }

        $user->update($validated);

        // Create payment history record if duration was added
        if ($durationAdded) {
            $durationValue = null;
            if ($validated['subscription_duration_days'] ?? false) {
                $durationValue = $validated['subscription_duration_days'] . ' days';
            } elseif ($validated['subscription_duration_months'] ?? false) {
                $durationValue = $validated['subscription_duration_months'] . ' months';
            }

            PaymentHistory::create([
                'user_id' => $user->id,
                'type' => 1,
                'refId' => Str::uuid(),
                'transaction_date' => now(),
                'amount' => 0.00, // Admin extension, no payment amount
                'code' => 'ADMIN_EXTENSION',
                'duration' => $durationValue,
            ]);
        }

        if (isset($validated['roles'])) {
            $user->syncRoles($validated['roles']);
        }

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Prevent admin from deleting themselves
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Update user subscription dates.
     */
    public function updateSubscription(Request $request, User $user)
    {
        $validated = $request->validate([
            'subscription_type' => 'required|in:free,premium,vip',
            'subscription_expires_at' => 'nullable|date',
            'subscription_duration_days' => 'nullable|integer|min:1|max:365',
            'subscription_duration_months' => 'nullable|integer|min:1|max:12',
        ]);

        // Calculate subscription expiration date if duration is provided
        $subscriptionExpiresAt = $validated['subscription_expires_at'];
        $subscriptionDate = $user->subscription_date ?? now();

        // Only calculate new expiration date if days or months are provided
        $durationAdded = false;
        if ($validated['subscription_duration_days'] ?? false) {
            // If subscription_expires_at is not null, add days to it, otherwise add to now
            $baseDate = $user->subscription_expires_at ? $user->subscription_expires_at : now();
            $subscriptionExpiresAt = $baseDate->addDays((int) $validated['subscription_duration_days']);
            $durationAdded = true;
        } elseif ($validated['subscription_duration_months'] ?? false) {
            // If subscription_expires_at is not null, add months to it, otherwise add to now
            $baseDate = $user->subscription_expires_at ? $user->subscription_expires_at : now();
            $subscriptionExpiresAt = $baseDate->addMonths((int) $validated['subscription_duration_months']);
            $durationAdded = true;
        }

        // Update subscription_date if it's null and we're setting an expiration date
        if (!$user->subscription_date && $subscriptionExpiresAt) {
            $validated['subscription_date'] = now();
        }

        // Only update subscription_expires_at if we calculated a new value or if it was explicitly provided
        if ($subscriptionExpiresAt) {
            $validated['subscription_expires_at'] = $subscriptionExpiresAt;
        }

        $user->update($validated);

        // Create payment history record if duration was added
        if ($durationAdded) {
            $durationValue = null;
            if ($validated['subscription_duration_days'] ?? false) {
                $durationValue = $validated['subscription_duration_days'] . ' days';
            } elseif ($validated['subscription_duration_months'] ?? false) {
                $durationValue = $validated['subscription_duration_months'] . ' months';
            }

            PaymentHistory::create([
                'user_id' => $user->id,
                'type' => 1,
                'refId' => Str::uuid(),
                'transaction_date' => now(),
                'amount' => 0.00, // Admin extension, no payment amount
                'code' => 'ADMIN_EXTENSION',
                'duration' => $durationValue,
            ]);
        }

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'Subscription updated successfully.');
    }

    /**
     * Update user roles.
     */
    public function updateRoles(Request $request, User $user)
    {
        $validated = $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $user->syncRoles($validated['roles']);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User roles updated successfully.');
    }

    /**
     * Update user status.
     */
    public function updateStatus(Request $request, User $user)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,inactive,suspended,banned',
            'status_reason' => 'nullable|string|max:500',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User status updated successfully.');
    }
}
