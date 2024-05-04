<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CommentController extends Controller
{
    public function store(Request $request,  $id)
    {
        try{
            $blog = Blog::find($id);
            return $blog;
            // request()->validate([
            //     'body'=> 'required',
            //     'user_id'=> '', 
            //     'blog_id'=> ''
            // ]);

            // return $blog->comments()->create([
            //     'body' => $request->body,
            //     'user_id'=> Auth::user()->id,
            //     'blog_id'=> $blog->id
            // ]);
        }
        catch(ValidationException $e){
            return response()->json([
                'error'=> $e->validator->errors()
            ]);
        }
    }

    public function update (Request $request, $id)
    {
        try{
            $comment = Comment::find($id);
            if($comment){
                $request->validate([
                    'body' => 'required'
                ]);
                return $comment->update([
                    'body' => $request->body
                ]);
                return $comment;
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
            $comment = Comment::find($id);
            if($comment){
                $comment->delete($comment);
                return response()->json([
                    'message' => 'Comment is successfully deleted'
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
