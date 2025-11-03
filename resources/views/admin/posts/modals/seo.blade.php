<div class="modal-header"><h4 class="modal-title">SEO {{ $post->name }}</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
    </button>
</div>
{!! Lte3::formOpen(['action' => route('admin.posts.seo.save', $post), 'method' => 'POST', 'files' => true]) !!}

<div class="modal-body">
    @include('admin.parts.seo-fields', ['seoable' => $post])
</div>

<div class="modal-footer justify-content-between">
    {!! Lte3::btnModalClose('Закрити') !!}
    {!! Lte3::btnSubmit('Зберегти') !!}
</div>

{!! Lte3::formClose() !!}
