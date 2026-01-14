<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;

    protected $appends = ['is_liked', 'votes'];

    protected $fillable = ['url', 'user_id', 'public_id', 'title', 'lead', 'image'];

    public function getRouteKeyName()
    {
        return 'public_id';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->with('replies');
    }

    public function likes()
    {
        return $this->hasMany(PostLike::class);
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

    protected static function booted()
    {
        static::creating(function ($post) {
            $post->public_id = Str::substr(str_shuffle(Str::ulid()), -10);
        });
    }
}
