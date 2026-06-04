<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Category::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $data = [
            [
                'name' => ['en' => 'elctronics', 'ar' => 'الكترونيات'],
                'status' => 1,
                'slug' => "elctronics",
                'parent_id' => null,
            ],
            [
                'name' => ['en' => 'cloths', 'ar' => 'ملابس'],
                'status' => 1,
                'slug' => "cloths",
                'parent_id' => null,
            ],
            [
                'name' => ['en' => 'books', 'ar' => 'كتب'],
                'status' => 1,
                'slug' => 'books',
                'parent_id' => null,
            ],
            [
                'name' => ['en' => 'home', 'ar' => 'منزل'],
                'status' => 1,
                'slug' => 'home',
                'parent_id' => null,
            ],
        ];

        foreach ($data as $cat) {
            Category::create($cat);
        }
    }
}