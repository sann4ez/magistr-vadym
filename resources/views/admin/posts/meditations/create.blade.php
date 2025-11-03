@extends('admin.layouts.app')

@section('content')

    @include('admin.parts.content-header', [
        'page_title' => 'Медитації',
        'small_page_title' => 'Створити',
        'url_back' => session('admin.meditations.index'),
    ])

    <section class="content">
        <div class="card">
            <div class="card-body">
            {!! Lte3::formOpen(['action' => route('admin.meditations.store'), 'model' => null, 'method' => 'POST']) !!}
                 @include('admin.posts.meditations.inc.form')
            {!! Lte3::formClose() !!}
            </div>
        </div>
    </section>
@endsection
