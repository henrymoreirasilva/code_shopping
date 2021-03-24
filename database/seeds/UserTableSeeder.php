<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        factory(CodeShopping\Models\User::class)->create(['email' => 'user@admin.com.br']);
        factory(CodeShopping\Models\User::class, 4)->create();
    }
}
