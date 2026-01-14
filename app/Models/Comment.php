<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'post_id', 'parent_id', 'content'];

    protected $appends = ['is_liked', 'votes'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->with('replies');
    }

    public function likes()
    {
        return $this->hasMany(CommentLike::class);
    }

    public function getVotesAttribute()
    {
        $upvotes = $this->likes()->where('liked', 1)->count();
        $downvotes = $this->likes()->where('liked', 0)->count();

        return $upvotes - $downvotes;
    }

    public function getIsLikedAttribute()
    {
        $userId = 1;

        return $this->likes()->where('user_id', $userId)->first()?->liked;
    }
}
