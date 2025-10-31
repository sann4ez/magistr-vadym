<div class="modal-header"><h4 class="modal-title">Створити елемент списку</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
    </button>
</div>

{!! Lte3::formOpen(['action' => route('admin.items.store'), 'method' => 'POST']) !!}
<div class="modal-body">
    @include('admin.items.modals.form', ['type' => $type])
</div>

<div class="modal-footer justify-content-end">
    {!! Lte3::btnModalClose('Закрити') !!}
    {!! Lte3::btnSubmit('Зберегти') !!}
</div>
{!! Lte3::formClose() !!}
