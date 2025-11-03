@php($group = \Domain::getId())

<div class="card card-solid">
    <div class="card-header with-border">
        <h3 class="card-title">Загальне</h3>
    </div>
    <div class="card-body">
        {!! Lte3::formOpen(['action' => route('admin.settings.save'), 'model' => null, 'method' => 'POST']) !!}

        <div class="callout callout-warning">
            <h5>Налаштувань немає!</h5>
            <p>Доступних для встановлення налаштувань немає. Перейдіть на ін. вкладки розділу</p>
        </div>
{{--

        <div class="text-right">
            {!! Lte3::btnSubmit('Зберегти', null, null, ['add' => 'fixed']) !!}
        </div>
--}}

    </div>
      {!! Lte3::formClose() !!}
</div>

