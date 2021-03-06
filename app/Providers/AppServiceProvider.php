<?php

namespace CodeShopping\Providers;

use CodeShopping\Models\Product;
use CodeShopping\Models\ProductInput;
use CodeShopping\Models\ProductOutput;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        ProductInput::created(function($input) {
            $product = Product::find($input->product_id);
            $product->stock += $input->amount;
            $product->save();
        });

        ProductOutput::created(function($output) {
            $product = Product::find($output->product_id);
            $product->stock -= $output->amount;
            $product->save();
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
