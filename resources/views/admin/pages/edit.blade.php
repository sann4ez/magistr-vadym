@extends('admin.layouts.app')

@section('content')
    @include('admin.parts.content-header', [
        'page_title' => 'Сторінки',
        'small_page_title' => 'Редагувати',
        'url_back' => session('admin.pages.index'),
    ])

    <section class="content">
        <div class="card">
{{--            <div class="card-header">--}}
{{--                <h3 class="card-title">Редагувати</h3>--}}
{{--            </div>--}}
            <div class="card-body">

                <ul class="nav nav-tabs js-activeable-url" data-tag="a" data-class="active" style="margin-bottom: 15px">
                    <li class="nav-item">
                        <a class="nav-link"
                            href="{{ route('admin.pages.edit', $page) }}">Дані</a>
                    </li>
{{--                    @if(\Domain::getOpt('pages.blocks.on'))--}}
                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ route('admin.pages.edit', [$page, '_tab' => 'blocks']) }}"
                           data-pat="blocks">Блоки</a>
                    </li>
{{--                    @endif--}}
                </ul>
            @if (\Request::fullUrlIs(route('admin.pages.edit', [$page, '_tab' => 'blocks'])))
                @include('admin.pages.inc.blocks', ['entity' => $page])
            @else
                {!! Lte3::formOpen(['action' => route('admin.pages.update', $page), null , 'method' => 'PUT' ,'model' => $page]) !!}
                @include('admin.pages.inc.form')
                {!! Lte3::formClose() !!}
            @endif
            </div>
        </div>
    </section>
@endsection
