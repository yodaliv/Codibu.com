<?php

use Database\Seeders\PaymentPlatformSeeder;
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
        $this->call([
            PaymentPlatformSeeder::class
        ]);
    }
}
