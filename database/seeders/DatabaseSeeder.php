<?php

namespace Database\Seeders;

use App\Models\PushUp;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        PushUp::factory(10)->create();  
    }
}
