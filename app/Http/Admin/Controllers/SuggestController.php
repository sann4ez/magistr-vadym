<?php

namespace App\Http\Admin\Controllers;

use App\Models\User;
use App\Models\Page;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use App\Models\Post;

final class SuggestController
{
    public function posts(Request $request)
    {
        if (empty($request->q)) {
            return response()->json(['results' => []]);
        }

        $posts = Post::query()
            ->where('type', $request->type)
            ->where('name', 'like', '%' . $request->q . '%')
            ->select('id', 'name')
            ->limit(15)
            ->get();

        return response()->json([
            'results' => $posts->map(fn($b) => [
                'id' => $b->id,
                'text' => $b->name,
            ]),
        ]);
    }

    public function pages(Request $request)
    {
        if (empty($q = $request->q)) {
            return response()->json(['results' => []]);
        }
        $pages = Page::query()->where('name', 'LIKE', "%{$q}%")->select('id', 'name')
            ->limit(15)->get();

        return response()->json([
            'results' => $pages->map(fn($b) => [
                'id' => $b->id,
                'text' => $b->name,
            ]),
        ]);
    }
}
