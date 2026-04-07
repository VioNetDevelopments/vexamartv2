<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Makanan', 'Minuman', 'Snack', 'Kebutuhan Pokok', 'Elektronik'];
        foreach ($categories as $cat) {
            Category::create([
                'name' => $cat,
                'slug' => Str::slug($cat),
                'is_active' => true
            ]);
        }
    }
}