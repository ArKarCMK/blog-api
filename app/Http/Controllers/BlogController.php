<?php

namespace App\Http\Controllers;

use App\Http\Requests\Blog\StoreBlogRequest;
use App\Models\Blog;
use Illuminate\Http\Request;
// use Dotenv\Exception\ValidationException;
use App\Http\Requests\Blog\UpdateBlogRequest;
use App\Models\Comment;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class BlogController extends Controller
{
  public function all()
  {
    return Blog::latest()->with("comments")->paginate(6);
  }

  public function latest()
  {
    return Blog::latest()->first();
  }

  public function show($id)
  {
    $blog = Blog::with("user", "category", "comments")->findOrFail($id);

    $userProfile = $blog->user->profile_picture;
    $blog->user->profile_picture = $userProfile
      ? "data:image/jpeg;base64," . base64_encode($userProfile)
      : null;

    return $blog;
  }


  public function filterByCategory($id)
  {
    return Blog::where("category_id", $id)->get();
  }

  public function store(StoreBlogRequest $request)
  {
    try {
      $validatedBlog = $request->validated();
      $validatedBlog["user_id"] = Auth::user()->id;
      $blog = Blog::create($validatedBlog);
      return response()->json([
        "message" => "Blog is successfully created",
        "blog_id" => $blog->id,
      ]);
    } catch (ValidationException $e) {
      return response()->json(
        [
          "error" => $e->validator->errors(),
        ],
        422
      );
    }
  }

  public function edit(UpdateBlogRequest $request, $id)
  {

    try {
      $blog = Blog::find($id);
      if ($blog) {
        $data = $request->validated();
        $data["user_id"] = Auth::user()->id;
        $blog->update($data);
        return $blog;
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
      $blog = Blog::find($id);
      if ($blog) {
        $blog->delete($blog);
        return response()->json([
          "message" => "Blog is successfully deleted",
        ]);
      }
    } catch (ValidationException $e) {
      return response()->json([
        "error" => $e->validator->errors(),
      ]);
    }
  }

  public function subscription($id)
  {
    $blog = Blog::find($id);
    if (auth()->user()->subscribedBlogs->contains($blog->id)) {
      $blog->subscribers()->detach(auth()->id());
      return response()->json([
        "message" => "data is successfully deleted",
      ]);
    } else {
      $blog->subscribers()->attach(auth()->id());
      return response()->json([
        "message" => "data is successfully added",
      ]);
    }
  }

  public function blogsByUser($userId)
  {
    try {
      $blog = Blog::where("user_id", $userId)->get();

      return response()->json([
        "message" => "data is successfully fetched",
        "data" => $blog,
      ]);
    } catch (\Exception $e) {
      return response()->json([
        "message" => "Error Fetching User Blog",
        "error" => $e->getMessage(),
      ]);
    }
  }
}
