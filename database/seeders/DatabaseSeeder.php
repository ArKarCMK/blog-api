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


    Category::factory()->create(['name' => 'Frontend']);
    Category::factory()->create(['name' => 'Backend']);
    Category::factory()->create(['name' => 'Database']);
    Category::factory()->create(['name' => 'Tech News']);
    Category::factory()->create(['name' => 'Others']);
    // Blog::factory(2)->create(['category_id'=>$frontend->id]);
    // Blog::factory(2)->create(['category_id'=>$backend->id]);
    // User::factory(5)->create();

  }
}
