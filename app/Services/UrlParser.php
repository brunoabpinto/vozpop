<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class UrlParser
{
    public function getUrlMeta($url)
    {
        $response = Http::get($url);
        if (! $response->successful()) {
            return null;
        }

        $html = $response->body();

        $meta = [];

        preg_match('/<title>(.*?)<\/title>/is', $html, $matches);
        $meta['title'] = $matches[1] ?? null;

        preg_match('/<link rel=["\']canonical["\'].*?href=["\'](.*?)["\']/is', $html, $matches);
        $meta['url'] = $matches[1] ?? null;

        preg_match('/<meta name=["\']description["\'].*?content=["\'](.*?)["\']/is', $html, $matches);
        if (! $matches) {
            preg_match('/<meta property=["\']og:description["\'].*?content=["\'](.*?)["\']/is', $html, $matches);
        }
        $meta['lead'] = $matches[1] ?? null;

        preg_match('/<meta property=["\']og:image["\'].*?content=["\'](.*?)["\']/is', $html, $matches);
        $meta['image'] = $matches[1] ?? null;

        return $meta;
    }
}
