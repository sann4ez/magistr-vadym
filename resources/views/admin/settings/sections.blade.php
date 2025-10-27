@extends('admin.layouts.app')

@section('content')

    @include('admin.parts.content-header')
    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Налаштування</h3>
                    </div>
                    <div class="card-body p-0">
                        <ul class="nav nav-pills flex-column">
                            @foreach ($sections as $sec)
                                @if(Arr::get($sec, 'perm') && !\Gate::any(Arr::get($sec, 'perm')))
                                    @continue
                                @endif
                                <li class="nav-item" >
                                    <a href="{{ Arr::get($sec, 'url') ?: url("/admin/settings/{$sec['key']}") }}" class="nav-link" @if ($sec['key'] === $section['key']) style="font-weight: bold; color: #007bff" @endif>
                                        <i class="{{ $sec['fa_icon'] }}"></i> {{ $sec['title'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
{{--
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Додатково</h3>
                    </div>
                    <div class="card-body p-0">
                        <ul class="nav nav-pills flex-column">

                            <li class="nav-item">
                                <a href="#" class="nav-link"><i class="fas fa-broom"></i> Очистити кеш</a>
                            </li>
                        </ul>
                    </div>
                </div>
                --}}
            </div>

            <div class="col-md-9">
                @include("admin.settings.sections.{$section['key']}", ['section' => $section])
            </div>
        </div>
    </section>
@endsection
