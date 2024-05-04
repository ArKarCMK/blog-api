<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
// use Dotenv\Exception\ValidationException;
use App\Http\Requests\Blog\StoreBlogRequest;
use App\Http\Requests\Blog\UpdateBlogRequest;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class BlogController extends Controller
{
    public function all()
    {
        return Blog::latest()->with('comments')->get();
    }

    public function store(StoreBlogRequest $request)
    {
        try {
            $blog = $request->validated();
            Blog::create($blog);
        }
        catch(ValidationException $e) {
            return response()->json([
                'error' => $e->validator->errors()
            ], 422);
        }

    }

    public function edit (UpdateBlogRequest $request, $id)
    {
        try{
            $blog = Blog::find($id);
            if($blog){
                $data = $request->validated();
                $blog->update($data);
                return $blog;
            }
            else {
                return null;
            }
            
        }
        catch (ValidationException $e) {
            return response()->json([
                'error'=>$e->validator->errors()
            ], 422);
        }
    }

    public function delete($id)
    {
        try{
            $blog = Blog::find($id);
            if($blog){
                $blog->delete($blog);
                return response()->json([
                    'message' => 'Blog is successfully deleted'
                ]);
            }
        }
        catch(ValidationException $e){
            return response()->json([
                'error'=> $e->validator->errors()
            ]);
        }
    }

    
    
}
