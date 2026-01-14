<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\UrlParser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::with('user')->latest()->paginate(10);

        if ($request->wantsJson()) {
            return response()->json($posts);
        }

        return view('pages.posts.index', compact('posts'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'url' => 'required|url',
        ]);

        try {

            $article = (new UrlParser)->getUrlMeta($validatedData['url']);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['url' => 'Failed to fetch URL metadata.']);
        }

        $post = Post::firstOrCreate(
            ['url' => $validatedData['url']],
            array_merge($article, [
                'user_id' => 1,
            ]));

        return redirect()->route('posts.show', $post->public_id);
    }

    public function show(Post $post, ?int $commentId = null)
    {
        $post->load([
            'comments' => function ($query) use ($commentId) {
                if ($commentId) {
                    $query->where('parent_id', $commentId);
                }
            },
            'comments.user',
            'comments.replies.user',
        ]);

        $this->trackPostView($post);

        return view('pages.posts.show', compact('post', 'commentId'));
    }

    private function trackPostView(Post $post)
    {
        $visitedPosts = json_decode(Cookie::get('last_visited_posts', '[]'), true);
        $visitedPosts = array_filter($visitedPosts, function ($item) use ($post) {
            return $item['id'] !== $post->public_id;
        });
        $visitedPosts[] = [
            'id' => $post->public_id,
            'title' => $post->title,
            'image' => $post->image,
        ];
        $visitedPosts = array_slice($visitedPosts, -6);
        Cookie::queue('last_visited_posts', json_encode(array_values($visitedPosts)), 60 * 24 * 30);
    }
}
