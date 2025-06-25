<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreHostRequest;
use App\Models\User;
use App\Models\School;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use App\Services\NotificationService;

class SchoolHostController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHostRequest $request, School $school)
    {
        $data = $request->validated();
        $data['school_id'] = $request->input('school_id', $school->id);
        $data['role'] = 'school_admin';
        $data['status'] = 'active';

        // Handle profile photo upload if present
        if ($request->hasFile('profile_photo')) {
            $data['profile_photo'] = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        // Ensure the role exists before assigning it
        Role::firstOrCreate(['name' => 'school_admin', 'guard_name' => 'web']);

        $user = User::create($data);
        $user->assignRole('school_admin');
        // Notification
        $this->notificationService->sendToRole(
            'admin',
            'New School Host Created',
            'A new school host (school_admin) has been created.',
            'success',
            route('schools.show', $school)
        );
        return redirect()->back()->with('success', 'Host created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(School $school, User $host)
    {
        // Optional: You might want to add a check to ensure the host belongs to the school
        if ($host->school_id !== $school->id) {
            // Or handle this with a policy
            return abort(404); 
        }

        $host->delete();
        // Notification
        $this->notificationService->sendToRole(
            'admin',
            'School Host Deleted',
            'A school host (school_admin) has been deleted.',
            'error',
            route('schools.show', $school)
        );
        return redirect()->back()->with('success', 'Host deleted successfully.');
    }
}
