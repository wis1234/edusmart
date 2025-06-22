<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;

class SyncUserRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roles:sync-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize the role from the users table with Spatie roles for all users.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting user roles synchronization...');

        $users = User::whereNotNull('role')->get();
        $syncedCount = 0;
        $errorCount = 0;

        foreach ($users as $user) {
            $roleName = $user->role;

            if (empty($roleName)) {
                continue;
            }

            try {
                // Ensure the role exists in the roles table
                $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);

                // Sync roles, replacing all existing roles with the one from the 'role' column
                if (!$user->hasRole($role)) {
                    $user->syncRoles([$role]);
                    $this->line("Synced role '{$roleName}' for user: {$user->email}");
                    $syncedCount++;
                }
            } catch (\Exception $e) {
                $this->error("Could not sync role for user {$user->email}. Error: " . $e->getMessage());
                $errorCount++;
            }
        }

        $this->info('Synchronization complete.');
        $this->info("{$syncedCount} users synchronized successfully.");
        if ($errorCount > 0) {
            $this->warn("{$errorCount} users could not be synchronized due to errors.");
        }

        return 0;
    }
} 