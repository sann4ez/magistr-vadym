<?php

namespace App\Http\Admin\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
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
}
