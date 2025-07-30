<?php

namespace App\Http\Controllers;

use App\Models\Gift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GiftController extends Controller
{
    // Public list of gifts
    public function index()
    {
        $gifts = Gift::latest()->paginate(20);
        return view('gifts.index', compact('gifts'));
    }

    // Admin dashboard for managing gifts
    public function admin()
    {
        $gifts = Gift::latest()->paginate(20);
        return view('gifts.admin', compact('gifts'));
    }

    public function create()
    {
        return view('gifts.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
            'value' => 'required|numeric|min:0',
        ]);
        Gift::create($data);
        return redirect()->route('gifts.admin')->with('success', 'Gift created successfully.');
    }

    public function edit(Gift $gift)
    {
        return view('gifts.edit', compact('gift'));
    }

    public function update(Request $request, Gift $gift)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
            'value' => 'required|numeric|min:0',
        ]);
        $gift->update($data);
        return redirect()->route('gifts.admin')->with('success', 'Gift updated successfully.');
    }

    public function destroy(Gift $gift)
    {
        $gift->delete();
        return redirect()->route('gifts.admin')->with('success', 'Gift deleted successfully.');
    }

    public function show(Gift $gift)
    {
        return view('gifts.show', compact('gift'));
    }
} 