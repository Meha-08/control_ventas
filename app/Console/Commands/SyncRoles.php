<?php

namespace App\Console\Commands;

use Database\Seeders\PermissionsSeeder;
use Database\Seeders\RolesSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncRoles extends Command
{
    protected $signature = 'roze:sync-roles';

    protected $description = 'Sync roles and permissions, and update legacy users.role column';

    public function handle(): int
    {
        $this->info('Syncing roles and permissions...');

        $this->call(RolesSeeder::class, ['--quiet' => true]);
        $this->call(PermissionsSeeder::class, ['--quiet' => true]);

        $this->info('Syncing legacy users.role column...');

        $count = 0;
        $users = DB::table('users')->whereNotNull('role')->get();
        foreach ($users as $user) {
            $role = DB::table('roles')->where('nombre', $user->role)->first();
            if ($role) {
                DB::table('role_user')->updateOrInsert(
                    ['user_id' => $user->id, 'role_id' => $role->id],
                    ['user_id' => $user->id, 'role_id' => $role->id]
                );
                $count++;
            }
        }

        $this->info("Synced {$count} users.");

        return Command::SUCCESS;
    }
}
