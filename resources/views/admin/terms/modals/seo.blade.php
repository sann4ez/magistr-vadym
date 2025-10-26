<div class="modal-header"><h4 class="modal-title">SEO {{ $term->name }}</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
    </button>
</div>
{!! Lte3::formOpen(['action' => route('admin.terms.seo.save', $term), 'method' => 'POST', 'files' => true]) !!}

<div class="modal-body">
    @include('admin.parts.seo-fields', ['seoable' => $term])
</div>

<div class="modal-footer justify-content-between">
    {!! Lte3::btnModalClose('Закрити') !!}
    {!! Lte3::btnSubmit('Зберегти') !!}
</div>

{!! Lte3::formClose() !!}
