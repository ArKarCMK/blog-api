<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
  use HasFactory;
  protected $fillable = ['user_id', 'category_id', 'title', 'slug',  'body', 'image'];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function comments()
  {
    return $this->hasMany(Comment::class);
  }

  public function category()
  {
    return $this->belongsTo(Category::class);
  }

  public function subscribers()
  {
    return $this->belongsToMany(User::class);
  }
}
