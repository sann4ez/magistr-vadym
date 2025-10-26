<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\WebDestinations;
use App\Models\Term;
use App\Http\Admin\Requests\TermRequest;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

final class TermController extends Controller
{
    use WebDestinations;


    /**
     * @param Request $request
     * @return Factory|View
     */
    public function index(Request $request)
    {
        if (empty($request->vocabulary)) {
            $vocabularies = Term::vocabulariesList('*', 'slug');

            return view('admin.terms.vocabularies', compact('vocabularies'));
        }

        if (!in_array($request->vocabulary, Term::vocabulariesList('slug'))) {
            abort(404);
        }

        $vocabulary = Term::vocabulariesList('*', 'slug')[$request->vocabulary];

        $terms = Term::byVocabulary($request->vocabulary)
            /*->with('translations')*/->get();

//        if ($request->has('_export')) {
//            $type = $request->vocabulary ?? 'terms';
//            return Excel::download(new TermsExport($terms), "export-$type-".time().".{$request->_export}");
//        }

        $tree = $terms->toTree();

        return view('admin.terms.index', compact('vocabulary', 'terms', 'tree'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create(Request $request)
    {
        if (!in_array($request->vocabulary, Term::vocabulariesList('slug'))) {
            abort(404);
        }

        $vocabulary = Term::vocabulariesList('*', 'slug')[$request->vocabulary];

        return view('admin.terms.create', compact('vocabulary'));
    }

    /**
     * @param TermRequest $request
     * @return RedirectResponse
     */
    public function store(TermRequest $request)
    {
        $term = Term::create($request->getData());
        $term->mediaManage($request);
//        $term->saveSeo($request->seo ?: [], $request->only('slug', 'group'));

        if ($request->has('posts')) {
            $term->postsByTerms()->sync($request->get('posts'));
        }

        if ($request->has('attributes')) {
            $term->attrs()->sync($request->get('attributes'));
        }

        return redirect()
            ->route('admin.terms.edit', [$term, 'vocabulary' => $term->vocabulary])
            ->with('success', trans('alerts.store.success'));
    }

    /**
     * @param Request $request
     * @param Term $term
     * @return Application|Factory|View
     */
    public function edit(Request $request, Term $term)
    {
        if (!in_array($request->vocabulary, Term::vocabulariesList('slug'))) {
            abort(404);
        }

        $vocabulary = Term::vocabulariesList('*', 'slug')[$request->vocabulary];

        return view('admin.terms.edit', compact('term', 'vocabulary'));
    }

    /**
     * @param TermRequest $request
     * @param Term $term
     * @return RedirectResponse
     */
    public function update(TermRequest $request, Term $term)
    {
        $term->update($request->getData());
        $term->mediaManage($request);
//        $term->saveSeo($request->seo ?: [], $request->only('slug', 'group'));

        if ($request->has('posts')) {
            $term->postsByTerms()->sync($request->get('posts'));
        }

        if ($request->has('attributes')) {
            $term->attrs()->sync($request->get('attributes'));
        }

        return redirect()
            ->route('admin.terms.edit', [$term, 'vocabulary' => $term->vocabulary])
            ->with('success', trans('alerts.update.success'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Term $term)
    {
        if ($term->children->count()) {
            return redirect()
                ->to($this->destinationUrl(route('admin.terms.index', ['vocabulary' => $term->vocabulary])))
                ->with('error', trans('alerts.destroy.error_children'));
        }

        $term->delete();

        return redirect()
            ->to($this->destinationUrl(route('admin.terms.index', ['vocabulary' => $term->vocabulary])))
            ->with('success', trans('alerts.destroy.success'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function order(Request $request)
    {
        $this->validate($request, [
            'data' => 'required|array'
        ]);

        $entities = build_linear_array_sort($request->data);

        foreach ($entities as $item) {
            optional(Term::find($item['id']))->update($item);
        }

        return response()
            ->json(['message' => trans('alerts.update.success')], Response::HTTP_ACCEPTED);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function autocomplete(Request $request)
    {
        /** @var Collection $tags */
        $terms = Term::withTrans()->when($q = $request->q, function ($query) use ($q) {
            $normalized = Str::slug($q);
            $query->where('slug', 'LIKE', "%$normalized%");
        })->when($v = $request->vocabulary, function ($query) use ($v) {
            $query->where('vocabulary', $v);
        })->limit(15)->get();

        if ($request->get('format') === 'name') {
            return response()->json([
                'results' => $terms->map(function ($tag) {
                    return [
                        'id' => $tag->name,
                        'text' => $tag->name,
                    ];
                })
            ]);
        }

        return response()->json([
            'results' => $terms->map(function ($tag) {
                return [
                    'id' => $tag->id,
                    'text' => $tag->name,
                ];
            })
        ]);
    }

    public function seoEdit(Request $request, Term $term)
    {
        return response()->json([
            'html' => view('admin.terms.modals.seo', \compact('term'))->render()
        ]);
    }

//    /**
//     * @param SeoRequest $request
//     * @param Term $term
//     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
//     */
//    public function seoSave(SeoRequest $request, Term $term)
//    {
//        $term->saveSeo($request->seo ?: [], $request->only('slug', 'group'));
//
//        if ($request->ajax()) {
//            return response()
//                ->json(['message' => trans('alerts.update.success')]);
//        }
//
//        return redirect()->back()
//            ->with('success', trans('alerts.update.success'));
//    }
}
