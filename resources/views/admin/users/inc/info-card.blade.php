<div class="card">
    <div class="card-body">
        @isset($user)
            <div class="text-center">
                <a href="{{ $img = $user->getAvatar() }}" class="js-popup-image">
                    <img src="{{ $img }}" style="width: 100px" class="profile-user-img img-responsive img-circle">
                </a>
            </div>
            <br>

            <div class="text-center">
                <strong>{{ $user->fullname }}</strong>
            </div>
            <br>
            <table class="table">
                <tr>
                    <th style="width: 50%">Дата реєстрації:</th>
                    <td>{{ $user->getDatetime('created_at') }}</td>
                </tr>
                <tr>
                    <th>Активність:</th>
                    <td>
                        {{ $user->getDatetime('activity_at') ?: '-' }}
                        @if ($user->isOnline())
                            <small class="text-success" data-toggle="tooltip" title="Зараз OnLine"><i class="fas fa-circle"></i></small>
                        @endif
                    </td>
                </tr>
            </table>
        @else
            <div class="text-center">
                <a href="/vendor/lte3/img/no-avatar.png" class="js-popup-image">
                    <img src="/vendor/lte3/img/no-avatar.png" style="width: 100px" class="profile-user-img img-responsive img-circle">
                </a>
            </div>
            <br>

            <div class="text-center">
                <strong>Створення нового <br> користувача</strong>
            </div>
        @endisset
    </div>
</div>

@if(isset($user) && ($visit = $user->visit))

    <div class="card collapsed-card">
        <div class="card-header" data-card-widget="collapse">
            <h3 class="card-title"><i class="fas fa-map-marker-alt"></i> Візит</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                            class="fas fa-plus"></i>
                </button>
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table">
                <tr>
                    <th style="width:50%">Action:</th>
                    <td>{{ $visit->action }}</td>
                </tr>
                <tr>
                    <th style="width:50%">IP:</th>
                    <td>{{ $visit->ip }}</td>
                </tr>
                @if($visit->referer_host)
                    <tr>
                        <th>Referer:</th>
                        <td>{{ $visit->referer_host }}</td>
                    </tr>
                @endif
                <tr>
                    <th>Device:</th>
                    <td>{{ $visit->device_type }} {{ $visit->device_family }} {{ $visit->device_model }}</td>
                </tr>
                <tr>
                    <th>OS:</th>
                    <td>{{ $visit->platform }}</td>
                </tr>
                <tr>
                    <th>Browser:</th>
                    <td>{{ $visit->browser }}</td>
                </tr>
                <tr>
                    <th>Location:</th>
                    <td>{{ $visit->country_code }} @if($visit->city)
                            , {{ $visit->city }}
                        @endif @if($visit->lat)
                            <small>({{ $visit->lat }}, {{ $visit->lng }})</small>
                        @endif</td>
                </tr>
                <tr>
                    <th>Locale:</th>
                    <td>{{ $visit->locale_code }}</td>
                </tr>
            </table>
        </div>
    </div>
@endif