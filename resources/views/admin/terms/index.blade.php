@extends('admin.layouts.app')

@section('btn-content-header')
{{--    @if(Domain::getOptIs('terms.operations.import') || auth()->user()->can('dev'))--}}
{{--        {!! Lte3::formOpen(['action' => route('admin.terms.import', ['vocabulary' => $vocabulary['slug']]), 'files' => true, 'method' => 'POST', 'class' => 'js-form-submit-file-changed', 'style' => 'display: inline-block']) !!}--}}
{{--        <label class="btn btn-flat btn-default mb-1" data-toggle="tooltip" title="Імпорт">--}}
{{--            <i class="fas fa-download"></i>--}}
{{--            <span style="font-weight: normal"></span>--}}
{{--            <input type="file" name="file" style="display: none;" accept=".csv,.xlsx">--}}
{{--        </label>--}}
{{--        {!! Lte3::formClose() !!}--}}
{{--    @endif--}}

{{--    @if(Domain::getOptIs('terms.operations.export') || auth()->user()->can('dev'))--}}
{{--        <a href="{{ \Illuminate\Support\Facades\Request::fullUrlWithQuery(['_export' => 'csv']) }}" class="btn btn-flat btn-default mb-1" data-toggle="tooltip" title="Експорт"><i class="fa fa-upload"></i> </a>--}}
{{--    @endif--}}

{{--    @if(\Illuminate\Support\Arr::get($vocabulary, 'permissions.create'))--}}
    <a href="{{ route('admin.terms.create', ['vocabulary' => $vocabulary['slug']]) }}" class="btn btn-flat btn-success mb-1"><i class="fa fa-plus"></i></a>
{{--    @endif--}}
@endsection

@section('content')
    @include('admin.parts.content-header', [
        'page_title' => $vocabulary['name'] . ': ' . $terms->count(),
    ])

    <section class="content">

        @if($terms->count())
            {!! Lte3::nestedset($terms, [
                   'has_nested' => $vocabulary['has_hierarchy'],
                   'routes' => [
                       'edit' => 'admin.terms.edit',
                       'create' => 'admin.terms.create',
                       'delete' => 'admin.terms.destroy',
                       'order' => 'admin.terms.order',
                       'params' => ['vocabulary' => $vocabulary['slug']],
                       'item' => 'vendor.lte3.components.nestedset.item',
                       'has_seo' => \false,
//                       'has_seo' => \Domain::getOpt('seo.seofields', null, 'seo.manage'),
                   ],
           ]) !!}
        @else
            @include('admin.parts.empty-rows')
        @endif

    </section>
@endsection
