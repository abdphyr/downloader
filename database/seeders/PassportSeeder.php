<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Laravel\Passport\Client;

class PassportSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Client::where('id', 1)->update([
            'secret' => 'Mt57LfRyUwwWIuKfSXnNzQAeWxQY0JFNerkrLymd'
        ]);
    }
}
