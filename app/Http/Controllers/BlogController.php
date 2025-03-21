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
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class BlogController extends Controller
{
  public function base64Encode($data)
  {
    $blogImage = $data->image;
    $data->image = "data:image/jpeg;base64," . base64_encode($blogImage);
    return $data;
  }
  public function all(Request $request)
  {

    $query = Blog::query();
    if ($request->filled('category')) {
      $query->where('category_id', $request->input('category'));
    }
    if ($request->filled('search')) {
      $search = $request->input('search');
      $query->where('title', 'like', '%' . $search . '%')
        ->orWhere('body', 'like', '%' . $search . '%');
    }

    $blogs = $query->latest()->paginate(6);

    foreach ($blogs as $blog) {
      if ($blog->image) {

        $blog = $this->base64Encode($blog);
      }
    }

    return $blogs;
  }

  public function latest()
  {
    $blog = Blog::latest()->first();
    if ($blog->image) {

      $blog = $this->base64Encode($blog);
    }
    return $blog;
  }

  public function show($id)
  {
    $blog = Blog::with("user", "category", "comments", "subscribers:id,name")->findOrFail($id);

    $userProfile = $blog->user->profile_picture;
    $blog->user->profile_picture = $userProfile
      ? "data:image/jpeg;base64," . base64_encode($userProfile)
      : null;
    if ($blog->image) {
      $blog = $this->base64Encode($blog);
    }


    return $blog;
  }

  public function store(StoreBlogRequest $request)
  {
    try {
      $validatedBlog = $request->validated();
      $validatedBlog["user_id"] = Auth::user()->id;

      if ($request->hasFile("image")) {
        $file = $request->file("image");

        $binaryData = file_get_contents($file);
        if (!$binaryData) {
          throw new \Exception(
            "Failed to read binary data from image"
          );
        }
        $validatedBlog["image"] = $binaryData;
      }
      $blog = Blog::create($validatedBlog);
      return response()->json([
        "message" => "Blog is successfully created",
        "blog_id" => $blog->id,
      ]);
    } catch (ValidationException $e) {
      $validatedBlog = $request->validated();
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

        Log::info($data);

        if ($request->hasFile("image")) {
          $file = $request->file("image");

          $binaryData = file_get_contents($file);
          if (!$binaryData) {
            throw new \Exception(
              "Failed to read binary data from image"
            );
          }
          $data["image"] = $binaryData;
        }
        $blog->update($data);
        return response()->json([
          "message" => "Blog is successfully edited"
        ]);
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
      $blogs = Blog::where("user_id", $userId)->get();

      foreach ($blogs as $blog) {
        if ($blog->image) {
          $blog = $this->base64Encode($blog);
        }
      }

      return response()->json([
        "message" => "data is successfully fetched",
        "data" => $blogs,
      ]);
    } catch (\Exception $e) {
      return response()->json([
        "message" => "Error Fetching User Blog",
        "error" => $e->getMessage(),
      ]);
    }
  }
}
