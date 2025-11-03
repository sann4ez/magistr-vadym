@extends('admin.layouts.app')

@section('btn-content-header')
{{--    @can('user.create')--}}
        <a href="{{ route('admin.users.create') }}" class="btn btn-flat btn-success mb-1"><i class="fa fa-plus"></i></a>
{{--    @endcan--}}
@endsection

@section('content')

    @include('admin.parts.content-header', [
        'page_title' => "Користувачі: {$users->total()}",
        'btn_search' => true,
        'btn_filter' => true,
    ])

    <section class="content">

        @include('admin.users.inc.filter')

        <div class="card">
            <div class="card-body table-responsive p-0">
                <table class="table table-hover ">
                    <thead>
                        <tr>
                            <th style="width: 65px;"></th>
                            <th style="width: 80px"></th>
                            <th style="width: 260px">{!! \Sort::getSortLink('fullname', 'ПІБ') !!}</th>
                            <th style="width: 20%">Контакти</th>
                            <th class="text-center">Роль</th>
                            <th class="text-center">{!! \Sort::getSortLink('status', 'Статус') !!}</th>
                            <th class="text-center">{!! \Sort::getSortLink('created_at', 'Створено') !!}</th>
                            <th class="text-center">{!! \Sort::getSortLink('activity_at', 'Активність') !!}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr id="{{ $loop->index }}" class="va-center">
                                <td>
                                    <div class="btn-actions dropdown">
                                        <button type="button" class="btn btn-sm btn-default" data-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></button>
                                        <div class="dropdown-menu" role="menu" style="top: 93%;">
{{--                                            @can('user.update')--}}
                                                <a href="{{ route('admin.users.edit', $user) }}" class="dropdown-item">Редагувати</a>
{{--                                            @endcan--}}

                                            <div class="dropdown-divider"></div>

{{--                                            @can('user.delete')--}}
                                                <a href="{{ route('admin.users.destroy', $user) }}"
                                                   class="dropdown-item js-click-submit" data-method="delete"
                                                   data-confirm="Видалити?">Видалити</a>
{{--                                            @endcan--}}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ $img = $user->getAvatar() }}" class="js-popup-image">
                                        <img src="{{ $img }}" style="height: 50px; min-width: 50px;" class="img-thumbnail">
                                    </a>
                                </td>
                                <td>
                                    @if ($val = $user->fullname)
{{--                                        @can('user.update')--}}
                                            <a href="{{ route('admin.users.edit', $user) }}"
                                               class="hover-edit" title="{{ $user->id }}">{{ $user->fullname }}</a>
                                        @else
                                            {{ $val }}
{{--                                        @endcan--}}
                                    @endif
                                </td>
                                <td>
                                    @if ($user->email)
                                        <i class="fa fa-envelope"></i> {{ $user->email }}
                                            {!! $user->hasVerifiedEmail() ? '<i class="fa fa-check"></i>' : '' !!}<br>
                                    @endif
                                    @if ($user->phone)
                                        <i class="fa fa-phone"></i> <a
                                                href="tel:{{ $user->phone }}">{{ $user->phone }}</a><br>
                                    @endif
                                </td>
                                <td class="text-center">{{ $user->roles->implode('name', ', ') }}</td>
                                <td class="text-center">{{ $user->getStatus() }}</td>
                                <td class="text-center">{{ $user->getDatetime('created_at') }}</td>
                                <td class="text-center">
                                    {{ $user->getDatetime('activity_at') }}
                                    @if ($user->isOnline())
                                        <small class="text-success" data-toggle="tooltip" title="Зараз OnLine">
                                            <i class="fas fa-circle"></i>
                                        </small>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-footer clearfix">
                {!! Lte3::pagination($users ?? null) !!}
            </div>
        </div>

    </section>
@endsection
