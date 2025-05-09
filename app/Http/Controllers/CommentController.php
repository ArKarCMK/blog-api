<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\StoreCommentRequest;
use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CommentController extends Controller
{
    public function all($id)
    {
        try {
            $comments = Comment::where("blog_id", $id)->with("user")->get();

            foreach ($comments as $comment) {
                $userProfile = $comment->user->profile_picture;

                if (
                    $userProfile &&
                    strpos($userProfile, "data:image/jpeg;base64,") !== 0
                ) {
                    $comment->user->profile_picture =
                        "data:image/jpeg;base64," . base64_encode($userProfile);
                }
            }
            return response()->json([
                "data" => $comments,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                "error" => $e->validator->errors(),
            ]);
        }
    }
    public function store(StoreCommentRequest $request)
    {
        try {
            $validatedComment = $request->validated();
            $validatedComment["user_id"] = Auth::user()->id;
            $comment = Comment::create($validatedComment);

            // return $comment;
            return response()->json([
                "message" => "Comment is successfully created",
                "data" => $comment,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                "error" => $e->validator->errors(),
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $comment = Comment::find($id);
            if ($comment) {
                $request->validate([
                    "body" => "required",
                ]);
                return $comment->update([
                    "body" => $request->body,
                ]);
                return $comment;
            } else {
                return null;
            }
        } catch (ValidationException $e) {
            return response()->json(
                [
                    "error" => $e->validator->errors(),
                ],
                422
            );
        }
    }

    public function delete($id)
    {
        try {
            $comment = Comment::find($id);
            if ($comment) {
                $comment->delete($comment);
                return response()->json([
                    "message" => "Comment is successfully deleted",
                ]);
            }
        } catch (ValidationException $e) {
            return response()->json([
                "error" => $e->validator->errors(),
            ]);
        }
    }
}
