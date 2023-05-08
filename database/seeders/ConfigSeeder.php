<?php

namespace Database\Seeders;

use App\Models\Config;
use App\Models\User;
use App\SD\SD;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Config::create([
            'user_id' => User::whereRelation('role', 'role', SD::admin)->first()->id
        ]);
    }
}
