@extends('admin.layouts.app')

@section('content')
    @include('admin.parts.content-header', [
        'page_title' => 'Публікації',
        'small_page_title' => 'Редагувати',
        'url_back' => session('admin.articles.index'),
    ])

    <section class="content">
        <div class="card">
            <div class="card-body">
                {!! Lte3::formOpen(['action' => route('admin.articles.update', $post), null, 'method' => 'PUT', 'model' => $post]) !!}
                    @include('admin.posts.articles.inc.form')
                {!! Lte3::formClose() !!}
            </div>
        </div>
    </section>
@endsection
