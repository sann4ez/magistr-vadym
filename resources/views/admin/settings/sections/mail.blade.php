@php($group = \Domain::getId())

<div class="card card-solid">
    <div class="card-header with-border">
        <h3 class="card-title">Загальне</h3>
    </div>
    <div class="card-body">
        {!! Lte3::formOpen(['action' => route('admin.settings.save'), 'model' => null, 'method' => 'POST']) !!}

        <div class="callout callout-info">
            <h5>Основні налаштування <code>.env</code></h5>
            <table class="table table-hover table-sm">
                <tbody>
                <tr>
                    <th style="width:50%">Mailer:</th>
                    <td>{{ $mailler = config('mail.default') }}</td>
                </tr>
                @foreach(config("mail.mailers.{$mailler}", []) as $key => $val)
                <tr>
                    <th><span style="text-transform: capitalize">{{$key}}</span>:</th>
                    <td>@if(strpos($key, 'pass') !== false) ******** @else {{$val}} @endif</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="text-right">
            {!! Lte3::btnSubmit('Зберегти', null, null, ['add' => 'fixed']) !!}
        </div>

    </div>
      {!! Lte3::formClose() !!}
</div>

