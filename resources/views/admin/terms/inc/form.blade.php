@php($vocabularySlug = request('vocabulary', $term->vocabulary ?? null))
<input type="hidden" name="vocabulary" value="{{ $vocabularySlug }}">
<input type="hidden" name="parent_id" value="{{ request('parent_id', $term->parent_id ?? null) }}">

<div class="row">
    <div class="col-lg-9">
        {!! Lte3::text('name', null, ['label' => 'Назва']) !!}

        {!! Lte3::textarea('body', null, ['class' => 'f-tinymce']) !!}
    </div>

    <div class="col-lg-3">

        {!! Lte3::mediaImage('image', null, [
            'label' => 'Зображення',
        ]) !!}

    </div>

</div>

<div class="text-right">
    {!! Lte3::btnReset('Вийти') !!}
    {!! Lte3::btnSubmit('Зберегти', null, null, ['add' => 'fixed']) !!}
</div>
