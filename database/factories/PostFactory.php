<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $feed = file_get_contents('https://feeds.noticiasaominuto.com/microsoft/3.0/xml/msrss_news.xml');
        $xml = simplexml_load_string($feed, 'SimpleXMLElement', LIBXML_NOCDATA);

        $items = $xml->xpath('//item');
        $item = collect($items)->random();

        $imageUrl = null;
        $namespaces = $item->getNamespaces(true);
        if (isset($namespaces['media'])) {
            $media = $item->children($namespaces['media']);
            if (isset($media->content)) {
                $imageUrl = (string) $media->content->attributes()->url;
            }
        }

        return [
            'title' => (string) trim($item->title),
            'lead' => (string) trim($item->description),
            'url' => (string) trim($item->link),
            'image' => $imageUrl,
            'user_id' => $this->faker->numberBetween(1, 10),
        ];
    }
}
