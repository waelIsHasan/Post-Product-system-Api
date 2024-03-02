<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'category_name'=>"Clothing",
        ]);
        Category::create([
            'category_name'=>"Household Furniture",
        ]);
        Category::create([
            'category_name'=>"Mobile Phone",
        ]);
        Category::create([
            'category_name'=>"Shoes",
        ]);

        Category::create([
            'category_name'=>"Games",
        ]);
    }
}
