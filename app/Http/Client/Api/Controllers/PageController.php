<?php

namespace App\Http\Client\Api\Controllers;

use App\Http\Client\Api\Resources\PageResource;
use App\Http\Client\Api\Resources\SeoResource;
use App\Http\Client\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

final class PageController extends Controller
{
    /**
     * @api {get} /api/pages/{slug} 01. Дані сторінки
     * @apiVersion 1.0.0
     * @apiName PageShow
     * @apiGroup Pages
     *
     * @apiDescription Слаги: `home, faq, terms, policy`,...
     *
     */
    public function show(Request $request, string $slug)
    {
        /** @var Page $page */
        $page = Page::where('slug', $slug)->firstOrFail();

        return PageResource::make($page)
            ->additional([
                'blocks' => $page->getResourceBlocks(),
            ]);
    }
}
