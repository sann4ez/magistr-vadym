<?php

namespace App\Actions\Users;

use App\Models\User;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

final class  UpdateProfile
{
    use AsAction;

    public function handle(User $user, array $data)
    {
        $user->update(Arr::only($data, [
            'name', 'lastname', 'email', 'phone', 'birthday', 'avatar',
        ]));

        return $user;
    }
}
