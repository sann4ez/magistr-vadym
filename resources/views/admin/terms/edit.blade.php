@extends('admin.layouts.app')

@section('content')
    @include('admin.parts.content-header', [
        'page_title' => $vocabulary['name'],
        'small_page_title' => 'Редагувати',
        'url_back' => route('admin.terms.index', ['vocabulary' => $vocabulary['slug']])
    ])

    <section class="content">
        <div class="card">
            <div class="card-body ">
                {!! Lte3::formOpen(['action' => route('admin.terms.update', $term), 'model' => $term, 'method' => 'PATCH']) !!}
                {!! Lte3::hidden('_destination', Request::fullUrl()) !!}
                @include('admin.terms.inc.form', ['post' => $term])
                {!! Lte3::formClose() !!}
            </div>
        </div>
    </section>
@stop
