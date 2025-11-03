@extends('admin.layouts.app')

@section('content')
    @include('admin.parts.content-header', [
        'page_title' => 'Медитації',
        'small_page_title' => 'Редагувати',
        'url_back' => session('admin.meditations.index'),
    ])

    <section class="content">
        <div class="card">
            <div class="card-body">
                {!! Lte3::formOpen([
                    'action' => route('admin.meditations.update', $post),
                    'method' => 'PUT',
                    'model' => $post
                ]) !!}
                    @include('admin.posts.meditations.inc.form')
                {!! Lte3::formClose() !!}
            </div>
        </div>
    </section>
@endsection
