<?php

namespace App\Http\Admin\Controllers;

use App\Actions\Posts\ReindexPostAction;
//use App\Actions\Seo\SaveSeoAction;
//use App\Exports\PostsExport;
//use App\Http\Admin\Requests\SeoRequest;
use App\Http\Admin\WebDestinations;
//use App\Imports\PostsImport;
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
//            ->with('translations')
            ->whereType(Post::TYPE_ARTICLE)
            ->filterable()
            ->latest();

//        if ($request->has('_export')) {
//            return \Excel::download(new PostsExport($posts->get()), "export-posts-".time().".{$request->_export}");
//        }

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
//        ReindexPostAction::run($post);

        return redirect()
            ->route('admin.articles.edit', $post->id)
            ->with('success', trans('alerts.store.success'));
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
//        ReindexPostAction::run($post);

        return redirect()->route('admin.articles.edit', $article)
            ->with('success', trans('alerts.update.success'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Post $article)
    {
        $article->delete();

        return redirect()->back()
            ->with('success', trans('alerts.destroy.success'));
    }

//    public function import(Request $request)
//    {
//        \Excel::import(new PostsImport(), $request->file('file'));
//
//        return redirect()->back()
//            ->with('success', trans('alerts.update.success'));
//    }

    public function seoEdit(Request $request, Post $post)
    {
        return response()->json([
            'html' => view('admin.posts.modals.seo', \compact('post'))->render()
        ]);
    }

    /**
     * @param SeoRequest $request
     * @param Post $post
     * @param SaveSeoAction $action
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function seoSave(SeoRequest $request, Post $post)
    {
        $post->saveSeo($request->seo ?: [], $request->only('slug', 'group'));

        if ($request->ajax()) {
            return response()
                ->json(['message' => trans('alerts.update.success')]);
        }

        return redirect()->back()
            ->with('success', trans('alerts.update.success'));
    }
}
