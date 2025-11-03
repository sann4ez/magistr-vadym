<div class="modal-header"><h4 class="modal-title">Редагувати елемент списку</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
    </button>
</div>
{!! Lte3::formOpen(['action' => route('admin.items.update', ['item' => $itemUpdate]), 'model' => $itemUpdate, 'method' => 'PATCH']) !!}
<div class="modal-body">
    @include('admin.items.modals.form', ['itemUpdate' => $itemUpdate])
</div>

<div class="modal-footer justify-content-between">
    <button type="button" class="btn btn-default" data-dismiss="modal">Закрити</button>
    {!! Lte3::btnSubmit('Зберегти', '_destination', route('admin.settings.edit', 'shipping')) !!}
</div>
{!! Lte3::formClose() !!}
