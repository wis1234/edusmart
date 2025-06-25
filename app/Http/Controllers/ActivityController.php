<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with('user')->latest();

        // Filtre par utilisateur
        if (!auth()->user()->hasRole('admin')) {
            $query->where('user_id', auth()->id());
        }

        // Filtre par type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filtre par utilisateur spécifique
        if ($request->filled('user_id') && auth()->user()->hasRole('admin')) {
            $query->where('user_id', $request->user_id);
        }

        // Filtre par date
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Recherche dans la description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }

        $activities = $query->paginate(10);
        $activityTypes = Activity::distinct()->pluck('type')->toArray();
        
        // Statistiques par type
        $user = auth()->user();
        $stats = [];
        foreach (['create', 'update', 'delete', 'logout', 'login'] as $type) {
            $query = Activity::where('type', $type);
            if (!$user->hasRole('admin')) {
                if ($user->role === 'school_admin' && $user->school_id) {
                    $schoolUserIds = \App\Models\User::where('school_id', $user->school_id)->pluck('id');
                    $query->whereIn('user_id', $schoolUserIds);
                } else {
                    $query->where('user_id', $user->id);
                }
            }
            $stats[$type] = $query->count();
        }
        // Ne récupérer les utilisateurs que si l'utilisateur connecté est admin
        $users = auth()->user()->hasRole('admin') 
            ? User::orderBy('first_name')->get(['id', 'first_name', 'last_name'])
            : collect();

        return view('activities.index', compact('activities', 'activityTypes', 'users', 'stats'));
    }

    public function show($id)
    {
        $activity = Activity::with('user')->findOrFail($id);
        return view('dashboard.partials.activity_details', compact('activity'));
    }

    public function destroy($id)
    {
        try {
            $activity = Activity::findOrFail($id);
            $activity->delete();
            
            // Log the deletion
            Activity::log('delete', 'Deleted activity log entry');
            
            return response()->json(['message' => 'Activity deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete activity'], 500);
        }
    }
}
