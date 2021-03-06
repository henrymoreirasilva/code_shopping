<?php

use CodeShopping\Models\Product;
use Illuminate\Database\Seeder;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = \CodeShopping\Models\Category::all();
        factory(Product::class, 30)
            ->create()
            ->each(function(Product $product) use ($categories) {
                $categoryId = $categories->random()->id;
                $product->categories()->attach($categoryId);
            }
        );
    }
}
