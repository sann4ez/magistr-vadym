@php
    $currentLocale =  \Domain::getLocale();

    $translations = \App\Models\Translation::with('translations')->orderBy('key')->get();
@endphp
<div class="col-md-12">
    <div class="box box-solid">
        <div class="box-header with-border d-flex justify-content-sm-between">
            <h3 class="box-title">{{ "Переклади: {$translations->count()}" }}</h3>
            <div class="tools">
            @if(Domain::getOptIs('translations.operations.import') || auth()->user()->can('dev'))
                {!! Lte3::formOpen(['action' => route('admin.translations.import'), 'files' => true, 'method' => 'POST', 'class' => 'js-form-submit-file-changed', 'style' => 'display: inline-block']) !!}
                <label class="btn btn-flat btn-default mb-1">
                    <i class="fas fa-download"></i>
                    <span style="font-weight: normal"></span>
                    <input type="file" name="file" style="display: none;" accept=".csv,">
                </label>
                {!! Lte3::formClose() !!}
            @endif
            @if(Domain::getOptIs('translations.operations.export') || auth()->user()->can('dev'))
                <a href="{{ route('admin.translations.index', ['_export' => 'csv']) }}" class="btn btn-flat btn-default mb-1"><i class="fa fa-upload"></i> </a>
            @endif
            @if(Domain::getOptIs('translations.operations.create'))
                <a href="#" class="btn btn-flat btn-success mb-1" data-toggle="modal" data-target="#modalCreate"><i class="fa fa-plus"></i></a>
            @endif
            </div>
        </div>

        <div class="box-body">
            <section class="content">
                <div class="card">
                    <div class="card-body table-responsive p-0" style="height: 75vh; overflow-y: scroll">
                        <table class="table table-hover table-head-fixed">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Ключ</th>
                                <th>Переклад <code>[{{ $currentLocale }}]</code></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($translations as $translation)
                                <tr class="va-center">
                                    <td style="width: 1%">
                                        <div class="btn-actions dropdown">
                                            <button type="button" class="btn btn-sm btn-default" data-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></button>
                                            <div class="dropdown-menu" role="menu" style="top: 93%;">

                                                <a href="{{ route('admin.translations.destroy', $translation) }}"
                                                   class="dropdown-item js-click-submit" data-method="delete"
                                                   data-confirm="Видалити?">Видалити</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="width: 20%">
                                        {{ $translation->key }}
                                    </td>
                                    <td>
                                        {!! Lte3::xEditable('value', $translation->value, [
                                            'type' => 'textarea',
                                            'pk' => $translation->id,
                                            'url_save' => route('admin.translations.editable', $translation),
                                            'label' => '--',
                                            'style' => 'white-space: normal;',
                                        ]) !!}

                                        <span data-toggle="tooltip" data-html="true" title="
                                    @foreach($translation->translations as $tr)
                                        @if($currentLocale !== $tr->locale)
                                            <p><strong>{{ $tr->locale }}</strong> - {{ $tr->value }}</p>
                                        @endif
                                    @endforeach
                                "><i class="fas fa-angle-down"></i></span>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>

    </div>
</div>

@push('modals')
    <div class="modal fade" id="modalCreate">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Додати переклад</h4>
                    <button type="button" class="close"
                            data-dismiss="modal"
                            aria-label="Close"><span
                            aria-hidden="true">&times;</span>
                    </button>
                </div>
                {!! Lte3::formOpen(['action' => route('admin.translations.store'), 'method' => 'POST']) !!}
                <div class="modal-body">
                    {!! Lte3::text('key', null, ['label' => 'Ключ', 'required' => 1]) !!}
                    {!! Lte3::textarea('value', null, ['label' => "Переклад <code>[{$currentLocale}]</code>", 'rows' => 2]) !!}
                </div>
                <div class="modal-footer justify-content-between">
                    {!! Lte3::btnReset('Вийти') !!}
                    {!! Lte3::btnSubmit('Створити') !!}
                </div>
                {!! Lte3::formClose() !!}
            </div>
        </div>
    </div>
@endpush
