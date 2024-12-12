<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
{
    public function all()
    {
        return Category::latest()->get();
    }
    //
}
