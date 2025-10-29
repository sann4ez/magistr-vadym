{!! Lte3::hidden('model_type') !!}
{!! Lte3::hidden('model_id') !!}

<div class="row">
   <div class="col-md-9">
       @includeFirst([
            "admin.blocks.templates.{$type['key']}",
            'admin.blocks.templates.default',
       ])
   </div>
    <div class="col-md-3">
        <div class="col-md-6" style="text-align: center">
            @php($img = '/files/blocks/' . "/{$type['key']}.png")
            @if(file_exists(public_path($img)))
                <a href="{{ $img }}" class="js-popup-image"><img src="{{ asset($img) }}" alt="img" style="width: 250px"></a>
            @endif
        </div>

        {!! Lte3::text('name', null, [
            'label' => 'Назва <small> (Для адмінпанелі)</small>',
            'default' => $type['name'],
            'required' => 1,
            'prepend' => '<i class="fas fa-cogs"></i>',
        ]) !!}

        {!! Lte3::select2('status', null, \App\Models\Block::statusesList('name', 'key')) !!}

        {!! Lte3::number('options[cache]', null, ['label' => 'Кеш, хв.', 'step' => 1, 'default' => 0]) !!}

        @isset($block)
            {!! Lte3::slug('slug') !!}
        @else
            {!! Lte3::text('slug', request('type')) !!}
        @endisset

        {!! Lte3::text('type', isset($block) ? $block->type : request('type'), ['readonly' => 1, 'label' => 'Тип']) !!}

    </div>
</div>

<div class="text-right">
    {!! Lte3::btnSubmit('Зберегти', null, null, ['add' => 'fixed']) !!}
</div>

@push('styles')
    <style>
        .f-multyblocks .f-item {
            border: 1px solid #b5b6b7;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
        }
    </style>
@endpush
