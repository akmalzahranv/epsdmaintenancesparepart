<?php

namespace Database\Seeders;

use App\Models\Category;
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
            ['category_name' => 'Temuan TPM'],
            ['category_name' => 'Temuan Red Tag'],
            ['category_name' => 'Order Sheet'],
            ['category_name' => 'Temporary Problem'],
            ['category_name' => 'Temuan Accuracy Machine'],
            ['category_name' => 'Improvement Machine'],
            ['category_name' => 'Temuan Patrol Check'],
            ['category_name' => 'Temuan 4S'],
            ['category_name' => 'Temuan Predictive'],
            ['category_name' => 'Cost Reduction'],
            ['category_name' => 'Problem']
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
