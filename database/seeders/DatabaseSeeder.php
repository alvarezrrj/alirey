<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Role;
use App\Models\User;
use StaticDetails\SD;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        if(! count(User::whereRelation('role', 'role', SD::admin)->get())) {
    
            // Delete all roles
            Role::truncate();

            // Reinsert roles
            DB::table('roles')->insert([
                'role' => SD::admin,
            ]);

            DB::table('roles')->insert([
                'role' => SD::client,
            ]);

            // Insert admin
            DB::table('users')->insert([
                'role_id' => Role::where('role', SD::admin)->first()->id,
                'firstName' => 'admin',
                'lastName' => 'admin',
                'email' => 'admin@admin.com',
                'phone' => '0000000000',
                'password' => Hash::make('admin'),
            ]);
        }

        $this->call([ 
            CodeSeeder::class,
            ConfigSeeder::class,
         ]);
    }
}
