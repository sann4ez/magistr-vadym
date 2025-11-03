<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\WebDestinations;
use App\Models\Post;
use App\Http\Admin\Requests\PostRequest;
use Illuminate\Http\Request;

final class ArticleController extends Controller
{
    use WebDestinations;

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $posts = Post::query()
            ->whereType(Post::TYPE_ARTICLE)
            ->filterable()
            ->latest();

        return view('admin.posts.articles.index', ['posts' => $posts->paginate()]);
    }


    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('admin.posts.articles.create');
    }

    /**
     * @param PostRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PostRequest $request)
    {
        $data = $request->getData() + ['type' => Post::TYPE_ARTICLE];

        if ($fields = $request->get('fields', [])) {
            $data['fields'] = Post::prepareFieldsForSave($fields, $request->get('notarrays'));
        }

        /** @var Post $post */
        $post = Post::create($data);

        $post->syncTerms($request->get('terms', []), [$post->category_id]);
        $post->mediaManage($request);

        return redirect()
            ->route('admin.articles.edit', $post->id)
            ->with('success', 'Дані успішно збережено!');
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Request $request, Post $article)
    {
        return view('admin.posts.articles.edit', [
            'post' => $article,
        ]);
    }

    /**
     * @param PostRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PostRequest $request, Post $article)
    {
        $data = $request->getData();

        $article->update($data);

        $article->syncTerms($request->get('terms', []), [$article->category_id]);
        $article->mediaManage($request);

        return redirect()->route('admin.articles.edit', $article)
            ->with('success', 'Дані успішно оновлено!');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Post $article)
    {
        $article->delete();

        return redirect()->back()
            ->with('success', 'Дані успішно видалено!');
    }
}
