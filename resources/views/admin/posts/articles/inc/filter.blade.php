@extends('admin.parts.filter-wrap2')

@section('body')
    <div class="row">
        <div class="col-md-3">
            {!! Lte3::text('q', request('q'), ['label' => 'Пошук',]) !!}
        </div>
        <div class="col-md-3">
            {!! Lte3::datetimepicker('published_at_from', request('published_at_from'), [
                  'label' => 'Публікація, від',
                  'default' => '',
              ]) !!}
        </div>
        <div class="col-md-3">
            {!! Lte3::datetimepicker('published_at_to', request('published_at_to'), [
                'label' => 'Публікація, до',
                'default' => '',
            ]) !!}
        </div>
        <div class="col-md-3">
            {!! Lte3::select2('status', request('status'),  \App\Models\Post::statusesList('name', 'key'), [
                'label' => 'Статус',
                'empty_value' => '--',
            ]) !!}
        </div>
    </div>
@stop

