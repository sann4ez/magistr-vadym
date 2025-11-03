@extends('admin.layouts.app')

@section('btn-content-header')
    <a href="{{ route('admin.pages.create') }}" class="btn btn-flat btn-success mb-1"><i class="fa fa-plus"></i></a>
@endsection


@section('content')
    @include('admin.parts.content-header', [
        'page_title' => "Cторінки: {$pages->total()}",
    ])

    <section class="content">

        @if($pages->total())
        <div class="card">
            <div class="card-body table-responsive p-0">
                <table class="table table-hover ">
                    <thead>
                        <tr>
                            <th style="width: 65px"></th>
                            <th>Назва</th>
                            <th>Cлаґ</th>
                            <th class="text-center" >Опубліковано</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pages as $page)
                            <tr id="{{ $loop->index }}" class="va-center">
                                <td>
                                    <div class="btn-actions dropdown">
                                        <button type="button" class="btn btn-sm btn-default" data-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></button>
                                        <div class="dropdown-menu" role="menu" style="top: 93%;">

                                            {{--<a href="{{ $page->getUrlClient('_preview') }}" class="dropdown-item" target="_blank">Переглянути</a>--}}
{{--                                            @can('page.update')--}}
                                                <a href="{{ route('admin.pages.edit', $page) }}" class="dropdown-item">Редагувати</a>
{{--                                            @endcan--}}
{{--                                            @if(\Domain::getOpt('seo.seofields', null, 'seo.manage'))--}}
{{--                                                <a href="{{ route('admin.pages.seo.edit', $page) }}" class="dropdown-item js-modal-fill-html" data-target="#modal-xl" data-fn-inits="initTinyMce,initJsVerificationSlugField">SEO</a>--}}
{{--                                            @endcan--}}
                                            <div class="dropdown-divider"></div>
{{--                                            @can('page.delete')--}}
                                                <a href="{{ route('admin.pages.destroy', $page) }}"
                                                   class="dropdown-item js-click-submit" data-method="delete"
                                                   data-confirm="Видалити?">Видалити</a>
{{--                                            @endcan--}}
                                        </div>
                                    </div>
                                </td>
                                <td>
{{--                                    @can('page.update')--}}
                                        <a class="hover-edit" href="{{ route('admin.pages.edit', $page) }}">{{ $page->name }}</a>
{{--                                    @else--}}
{{--                                        {{ $page->name }}--}}
{{--                                    @endcan--}}
                                    <br><small>Оновлено {{ $page->getDatetime('updated_at') }}</small>
                                </td>
                                <td>
                                    {{ $page->slug }}
                                </td>
                                <td class="text-center" >
                                    @if ($page->isAllowed())
                                    <i class="far fa-check-circle"></i>@else<i class="far fa-circle"></i>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-footer clearfix">
                {!! Lte3::pagination($pages ?? null) !!}
            </div>
        </div>
        @else
            @include('admin.parts.empty-rows')
        @endif

    </section>
@endsection
