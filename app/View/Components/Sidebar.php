<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cookie;
use Illuminate\View\Component;

class Sidebar extends Component
{
    public function render(): View|Closure|string
    {
        $recent = array_reverse(json_decode(Cookie::get('last_visited_posts', '[]'), true));

        return view('components.sidebar', compact('recent'));
    }
}
