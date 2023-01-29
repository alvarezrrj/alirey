<?php

namespace Database\Seeders;

use App\Models\Code;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class CodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Purge table
        Code::truncate();

        $json = Storage::disk('local')->get('country_codes.json');
        $codes = json_decode($json, true);
        foreach($codes as $code) {
            Code::create($code);
        }
    }
}
