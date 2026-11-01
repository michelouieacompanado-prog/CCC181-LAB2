<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed the Administrator Account
        $this->call(AdminUserSeeder::class);

        // 2. Default Regular User (Student Account)
        $user = User::firstOrNew(['email' => 'michelouieacompanado@g.msuiit.edu.ph']);
        $user->name = 'Miche';
        $user->password = Hash::make('password123');
        $user->role = 'user';
        $user->save();

        // 3. Product Catalog from Lab 2
        $products = [
            [
                'name' => 'apple',
                'qty' => 50,
                'price' => 30.00,
                'description' => 'shiny and sweet fuji apples',
            ],
            [
                'name' => 'orange',
                'qty' => 100,
                'price' => 10.00,
                'description' => 'juicy mandarin oranges',
            ],
            [
                'name' => 'Durian',
                'qty' => 100,
                'price' => 150.00,
                'description' => 'smelly but delicious durian',
            ],
            [
                'name' => 'lanzones',
                'qty' => 1000,
                'price' => 50.00,
                'description' => 'ant infested sweet lanzones',
            ],
            [
                'name' => 'rambutan',
                'qty' => 10000,
                'price' => 50.00,
                'description' => 'covid looking but actually delicious rambutans',
            ],
            [
                'name' => 'mango',
                'qty' => 100,
                'price' => 180.00,
                'description' => 'expensive yet sour mangoes',
            ],
        ];

        foreach ($products as $prod) {
            Product::firstOrCreate(
                ['name' => $prod['name']],
                $prod
            );
        }
    }
}
