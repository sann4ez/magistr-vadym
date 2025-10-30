{!! Lte3::hidden('options[type]', \App\Models\Post::TYPE_MEDITATION) !!}


{!! Lte3::text('content[title]', null, ['label' => 'Заголовок']) !!}

{!! Lte3::hidden('ids', '') !!}
@isset($block)
    @php
        $ids = $block->getIds('meditations', []);
        $posts = \App\Models\Post::whereIn('id', $ids)->get();
    @endphp
    {!! Lte3::select2('ids[meditations]', $ids, $posts->pluck('name', 'id')->toArray(), [
        'label' => 'Медитації',
        'multiple' => 1,
        'selected' => $ids,
        'url_suggest' => route('admin.suggest.posts', ['type' => \App\Models\Post::TYPE_MEDITATION]),
    ]) !!}
@else
    {!! Lte3::select2('ids[meditations]', [], [], [
        'label' => 'Медитації',
        'multiple' => 1,
        'url_suggest' => route('admin.suggest.posts', ['type' => \App\Models\Post::TYPE_MEDITATION]),
    ]) !!}
@endisset

{!! Lte3::select2('options[sort]', isset($block) ? $block->getOptions('sort') : null, ['default', 'random', 'name', 'published_at'], ['label' => 'Впорядкувати за полем']) !!}
{!! Lte3::select2('options[order]', isset($block) ? $block->getOptions('order') : null, ['desc', 'asc'], ['label' => 'Впорядкувати в напрямку',]) !!}
{!! Lte3::number('options[limit]', isset($block) ? $block->getOptions('limit') : null, ['label' => 'Ліміт для виведення', 'default' => 12]) !!}
