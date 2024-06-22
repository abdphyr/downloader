<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = json_decode(file_get_contents(database_path('json/category.json')), true);
        foreach ($data as $item) {
            $this->create($item);
        }
    }

    public function create($item, $parent = null)
    {
        if (isset($item['children'])) {
            $this->store(null, $item['translations']);
            foreach ($item['children'] as $child) {
                $this->create($child, $item);
            }
        } else {
            $parentId = ($parent && isset($parent['id'])) ? $parent['id'] : null; 
            $this->store($parentId, $item['translations']);
        }
    }

    protected function store($parentId, $translations)
    {
        $category = Category::create(['parent_id' => $parentId]);
        foreach ($translations as $translation) {
            $category->translation()->create($translation);
        }
    }
}
