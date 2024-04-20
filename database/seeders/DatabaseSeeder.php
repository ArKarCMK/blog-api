<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Blog;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {


        $frontend=Category::factory()->create(['name'=>'Frontend', 'slug'=>'frontend']);
        $backend=Category::factory()->create(['name'=>'Backend', 'slug'=>'backend']);
        Blog::factory(2)->create(['category_id'=>$frontend->id]);
        Blog::factory(2)->create(['category_id'=>$backend->id]);
        User::factory(5)->create();

    }
}

