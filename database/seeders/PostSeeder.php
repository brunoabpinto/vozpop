<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function __construct(private int $userId) {}

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $feed = file_get_contents('https://feeds.noticiasaominuto.com/microsoft/3.0/xml/msrss_news.xml');
        $xml = simplexml_load_string($feed, 'SimpleXMLElement', LIBXML_NOCDATA);

        $items = $xml->xpath('//item');
        $items = collect($items)->shuffle()->take(10);

        foreach ($items as $item) {
            $imageUrl = null;
            $namespaces = $item->getNamespaces(true);

            if (isset($namespaces['media'])) {
                $media = $item->children($namespaces['media']);
                if (isset($media->content)) {
                    $imageUrl = (string) $media->content->attributes()->url;
                }
            }

            $post = Post::create([
                'user_id' => $this->userId,
                'user_id' => $this->userId,
                'url' => (string) $item->link,
                'title' => (string) $item->title,
                'lead' => (string) $item->description,
                'image' => $imageUrl,
            ]);

            Comment::factory(50)->create([
                'post_id' => $post->id,
            ]);
        }
    }
}
