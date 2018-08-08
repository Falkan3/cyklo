<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'falkan333'.'@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'verified' => 1,
            'verification_token' => NULL
        ]);

        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin'.'@admin.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'verified' => 1,
            'verification_token' => NULL
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
