<?php

namespace App\Http\Admin\Controllers;

use App\Actions\Posts\ReindexPostAction;
//use App\Exports\PostsExport;
use App\Http\Admin\WebDestinations;
use App\Models\Post;
use App\Http\Admin\Requests\PostRequest;
use Illuminate\Http\Request;
//use FFMpeg\FFProbe;

final class MeditationController extends Controller
{
    use WebDestinations;

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $posts = Post::query()
            ->with(/*'translations', */'category')
            ->whereType(Post::TYPE_MEDITATION)
            ->filterable()
            ->latest();

//        if ($request->has('_export')) {
//            return \Excel::download(new PostsExport($posts->get()), "export-meditations-".time().".{$request->_export}");
//        }

        return view('admin.posts.meditations.index', ['posts' => $posts->paginate()]);
    }


    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('admin.posts.meditations.create');
    }

    /**
     * @param PostRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PostRequest $request)
    {
        $data = $request->getData() + ['type' => Post::TYPE_MEDITATION];

        if ($fields = $request->get('fields', [])) {
            $data['fields'] = Post::prepareFieldsForSave($fields, $request->get('notarrays'));
        }

        /** @var Post $post */
        $post = Post::create($data);

        $post->syncTerms($request->get('terms', []), [$post->category_id]);

        // Аудіофайл
//        $file = $request->file('audio');
//        if (!is_null($file)) {
//            $ffprobe = FFProbe::create();
//            $duration = $ffprobe
//                ->format($file->getRealPath())
//                ->get('duration');
//
//            $post->mediaManage($request);
//            $post->setAttribute('duration', intval($duration))->saveQuietly();
//
//        } else {
            $post->mediaManage($request);
//        }

//        ReindexPostAction::run($post);

        return redirect()->route('admin.meditations.edit', $post)
            ->with('success', trans('alerts.store.success'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Request $request, Post $meditation)
    {
        return view('admin.posts.meditations.edit', [
            'post' => $meditation,
        ]);
    }

    /**
     * @param PostRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PostRequest $request, Post $meditation)
    {
        $data = $request->getData();

        $meditation->update($data);

        $meditation->syncTerms($request->get('terms', []), [$meditation->category_id]);

        // Аудіофайл
        $file = $request->file('audio');
        if (!is_null($file)) {
            $ffprobe = FFProbe::create();
            $duration = $ffprobe
                ->format($file->getRealPath())
                ->get('duration');

            $meditation->mediaManage($request);
            $meditation->setAttribute('duration', intval($duration))->saveQuietly();

        } else {
            $meditation->mediaManage($request);
        }


        ReindexPostAction::run($meditation);

        return redirect()->route('admin.meditations.edit', $meditation)
            ->with('success', trans('alerts.update.success'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Post $meditation)
    {
        $meditation->delete();

        return redirect()->back()
            ->with('success', trans('alerts.destroy.success'));
    }

    // TODO: Потрібно доробити
//    public function import(Request $request)
//    {
//        \Excel::import(new PostsImport(), $request->file('file'));
//
//        return redirect()->back()
//            ->with('success', trans('alerts.update.success'));
//    }
}
