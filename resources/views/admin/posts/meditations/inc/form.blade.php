@php
    $categories = \App\Models\Term::byVocabulary(\App\Models\Term::VOCABULARY_MEDITATION_CATEGORIES)/*->with('translations')*/->get();
//    $sounds = \App\Models\Post::query()->whereType(App\Models\Post::TYPE_SOUND)->get();
@endphp

<div class="row">
    <div class="col-md-9">
        {!! Lte3::text('name', null, ['label' => 'Назва', 'required' => true]) !!}

        {!! Lte3::textarea('body', null, ['class' => 'f-tinymce']) !!}
    </div>

    <div class="col-md-3">
        {!! Lte3::select2('category_id', null, $categories->pluck('name', 'id')->toArray(), [
            'label' => 'Категорія',
        ]) !!}

{{--        {!! Lte3::select2('terms[post_categories]', isset($post) ? $post->categories->pluck('id')->toArray() : [], $categories->pluck('name', 'id')->toArray(), [--}}
{{--            'label' => 'Категорії ∞',--}}
{{--            'empty_value' => '--',--}}
{{--            'multiple' => true,--}}
{{--        ]) !!}--}}

        {!! Lte3::select2('status', null, \App\Models\Post::statusesList('name', 'key'), [
           'label' => 'Статус',
        ]) !!}

        {!! Lte3::datetimepicker('published_at', null, [
             'label' => 'Дата публікації',
             'format' => 'Y-m-d H:i',
             'required' => true,
         ]) !!}

        {!! Lte3::mediaImage('image', null, ['label' => 'Зображення']) !!}

        {!! Lte3::mediaFile('video', null, ['label' => 'Відео', 'accept' => 'video/*']) !!}
    </div>
</div>

<div class="text-right">
    {!! Lte3::btnReset('Вийти') !!}
    {!! Lte3::btnSubmit('Зберегти', null, null, ['add' => 'fixed']) !!}
</div>
