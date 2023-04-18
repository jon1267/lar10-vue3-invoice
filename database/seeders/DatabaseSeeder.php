<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // was seeds on 1 table, uncomment each line
        //\App\Models\Counter::factory(1)->create();
        //\App\Models\Product::factory(5)->create();
        //\App\Models\Customer::factory(5)->create();
        //\App\Models\Invoice::factory(5)->create();
        //\App\Models\InvoiceItem::factory(5)->create();

        // lines below from Laravel 10 (from the box)
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
