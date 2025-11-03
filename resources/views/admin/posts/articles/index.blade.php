@extends('admin.layouts.app')

@section('btn-content-header')
{{--    @can('post.create')--}}
    <a href="{{ route('admin.articles.create') }}" class="btn btn-flat btn-success mb-1"><i class="fa fa-plus"></i></a>
{{--    @endcan--}}
@endsection

@section('content')

    @include('admin.parts.content-header', [
        'page_title' => "Статті: {$posts->total()}",
        'btn_search' => true,
        'btn_filter' => true,
    ])

    <section class="content">

        @include('admin.posts.articles.inc.filter')

        @if($posts->total())
        <div class="card">
            <div class="card-body table-responsive p-0">
                <table class="table table-hover ">
                    <thead>
                        <tr>
                            <th style="width: 65px"></th>
                            <th>Назва</th>
                            <th>Cлаґ</th>
                            <th class="text-center">{!! \Sort::getSortLink('published_at', 'Опубліковано') !!}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($posts as $post)
                            <tr id="{{ $loop->index }}" class="va-center">
                                <td>
                                    <div class="btn-actions dropdown">
                                        <button type="button" class="btn btn-sm btn-default" data-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></button>
                                        <div class="dropdown-menu" role="menu" style="top: 93%;">
{{--                                            @can('post.update')--}}
                                            <a href="{{ route('admin.articles.edit', $post) }}" class="dropdown-item">Редагувати</a>
{{--                                            @endcan--}}
{{--                                            @can('post.seo')--}}
{{--                                                <a href="{{ route('admin.posts.seo.edit', $post) }}" class="dropdown-item js-modal-fill-html" data-target="#modal-xl" data-fn-inits="initTinyMce,initJsVerificationSlugField">SEO</a>--}}
{{--                                            @endcan--}}
                                            <div class="dropdown-divider"></div>
{{--                                            @can('post.delete')--}}
                                            <a href="{{ route('admin.articles.destroy', $post) }}" class="dropdown-item js-click-submit" data-method="delete" data-confirm="Видалити?">Видалити</a>
{{--                                            @endcan--}}
                                        </div>
                                    </div>
                                </td>
                                <td>
{{--                                    @can('post.update')--}}
                                        <a class="hover-edit" href="{{ route('admin.articles.edit', $post) }}">{{ $post->name }}</a>
{{--                                    @else--}}
{{--                                        {{ $post->name }}--}}
{{--                                    @endcan--}}
                                    <br><small>Створено {{ $post->getDatetime('created_at') }}</small>
                                </td>
                                <td>{{ $post->slug }}</td>
                                <td class="text-center">
                                    @if ($post->isAllowed())
                                        {{ $post->getDatetime('published_at') }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-footer clearfix">
                {!! Lte3::pagination($posts ?? null) !!}
            </div>
        </div>
        @else
            @include('admin.parts.empty-rows')
        @endif

    </section>
@endsection
