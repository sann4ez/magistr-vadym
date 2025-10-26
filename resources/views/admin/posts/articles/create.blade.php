@extends('admin.layouts.app')

@section('content')

    @include('admin.parts.content-header', [
        'page_title' => 'Публікації',
        'small_page_title' => 'Створити',
        'url_back' => session('admin.articles.index'),
    ])

    <section class="content">
        <div class="card">
            <div class="card-body">
            {!! Lte3::formOpen(['action' => route('admin.articles.store'), 'model' => null, 'method' => 'POST']) !!}
                 @include('admin.posts.articles.inc.form')
            {!! Lte3::formClose() !!}
            </div>
        </div>
    </section>
@endsection
