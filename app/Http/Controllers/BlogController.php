<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
// use Dotenv\Exception\ValidationException;
use App\Http\Requests\Blog\StoreBlogRequest;
use App\Http\Requests\Blog\UpdateBlogRequest;
use Illuminate\Validation\ValidationException;

class BlogController extends Controller
{
    public function all()
    {
        return Blog::latest()->with('comments')->get();
    }

    public function add(StoreBlogRequest $request)
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

    public function storeComment(Request $request,  $id)
    {
        try{
            $blog = Blog::find($id);
            request()->validate([
                'body'=> 'required'
            ]);
            return $blog->comments()->create([
                'body' => $request->body,
                'user_id'=> $request->user_id, 
                'blog_id'=> $blog->id
            ]);
        }
        catch(ValidationException $e){
            return response()->json([
                'error'=> $e->validator->errors()
            ]);
        }
    }
    
}
