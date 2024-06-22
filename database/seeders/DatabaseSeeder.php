<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(LanguageSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(PassportSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(SubjectTypeSeeder::class);
    }
}
