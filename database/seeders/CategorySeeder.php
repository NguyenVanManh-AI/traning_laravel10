<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'title' => 'Title 1',
                'description' => 'description 1',
                'search_number' => 99,
                'tag' => json_encode(['tag1', 'tag2', 'tag3']),
                'thumbnail' => null,
            ],
            [
                'title' => 'Title 2',
                'description' => 'description 2',
                'search_number' => 199,
                'tag' => json_encode(['tag1', 'tag2', 'tag3']),
                'thumbnail' => null,
            ],
        ];

        foreach ($categories as $catogory) {
            Category::create($catogory);
        }
    }
}
