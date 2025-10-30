<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Requests\ProfileRequest;
use App\Http\Admin\WebDestinations;
use App\Models\User;
use Illuminate\Http\Request;

final class ProfileController extends Controller
{
    use WebDestinations;

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Request $request)
    {
        return view('admin.profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * @param ProfileRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileRequest $request)
    {
        /** @var User $user */
        $user = $request->user();

        $data = $request->only('name', 'lastname', 'middlename', 'birthday', 'email', 'phone', 'login');

        if ($request->password) {
            $data['passsword'] = $request->password;
        }

        $user->update($data);

        return redirect()->back()
            ->with('success', trans('alerts.store.success'));
    }

    public function options(Request $request)
    {
        $request->validate(['key' => 'required|string|max:255', 'value' => 'required']);

        session()->put($request->key, $request->value);

        return redirect()->back();
    }
}
