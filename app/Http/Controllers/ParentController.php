<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ParentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'parent');
    }

    /**
     * Display a listing of the parents.
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);
        $parents = User::role('parent')->get();
        return view('parents.index', compact('parents'));
    }

    /**
     * Show the form for creating a new parent.
     */
    public function create()
    {
        $this->authorize('create', User::class);
        return view('parents.create');
    }

    /**
     * Store a newly created parent in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $parent = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        $parent->assignRole('parent');

        return redirect()->route('parents.index')->with('success', 'Parent created successfully.');
    }

    /**
     * Display the specified parent.
     */
    public function show(User $parent)
    {
        $this->authorize('view', $parent);
        return view('parents.show', compact('parent'));
    }

    /**
     * Show the form for editing the specified parent.
     */
    public function edit(User $parent)
    {
        $this->authorize('update', $parent);
        return view('parents.edit', compact('parent'));
    }

    /**
     * Update the specified parent in storage.
     */
    public function update(Request $request, User $parent)
    {
        $this->authorize('update', $parent);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $parent->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $parent->name = $validated['name'];
        $parent->email = $validated['email'];
        if (!empty($validated['password'])) {
            $parent->password = bcrypt($validated['password']);
        }
        $parent->save();

        return redirect()->route('parents.index')->with('success', 'Parent updated successfully.');
    }

    /**
     * Remove the specified parent from storage.
     */
    public function destroy(User $parent)
    {
        $this->authorize('delete', $parent);

        $parent->delete();
        return redirect()->route('parents.index')->with('success', 'Parent deleted successfully.');
    }
}
