<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::create([
            'firstname' => 'Abdumannon',
            'lastname' => 'Norboyev',
            'username' => 'abdumannon',
            'password' => 'abdumannon'
        ]);
        User::factory(5)->create();
    }
}
