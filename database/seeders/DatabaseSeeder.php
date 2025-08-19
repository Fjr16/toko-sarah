<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('user_has_roles')->truncate();
        DB::table('roles')->truncate();
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $item = User::create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
        ]);

        DB::table('roles')->insert([
            [
                'name' => 'administrator',
                'guard_name' => 'web',
            ],
            [
                'name' => 'cashier',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manager',
                'guard_name' => 'web',
            ]
        ]);

        $admin = Role::where('name', 'administrator')->first();
        $item->roles()->attach($admin->id);
    }
}
