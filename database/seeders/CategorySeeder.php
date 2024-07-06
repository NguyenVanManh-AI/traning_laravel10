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
                'title' => 'Title 1 ',
                'description' => 'description 1',
                'number' => 99,
            ],
            [
                'title' => 'Title 2 ',
                'description' => 'description 2',
                'number' => 199,
            ],
        ];

        foreach ($categories as $catogory) {
            Category::create($catogory);
        }
    }
}
