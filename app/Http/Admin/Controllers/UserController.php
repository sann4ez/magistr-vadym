<?php

namespace App\Http\Admin\Controllers;

use App\Actions\Users\StoreUserAction;
use App\Actions\Users\UpdateUserAction;
use App\Http\Admin\Requests\UserRequest;
use App\Http\Admin\WebDestinations;
use App\Models\User;
use Illuminate\Http\Request;

final class UserController extends Controller
{
    use WebDestinations;

    public function index(Request $request)
    {
        $users = User::query()
            ->filterable()
            ->byNotDev()
            ->paginate();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(UserRequest $request)
    {
        /** @var User $user */
        $user = StoreUserAction::run($request->all() + ['source' => 'manager']);
        $user->mediaManage($request);

        return redirect()
            ->route('admin.users.edit', $user)
            ->with('success', trans('alerts.store.success'));
    }

    public function show(User $user)
    {
        return response()->json([
            'html' => view('admin.users.modals.show', \compact('user'))->render()
        ]);
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(UserRequest $request, User $user)
    {
        $user = UpdateUserAction::run($user, $request->all());

        $user->mediaManage($request);

        return redirect()
            ->route('admin.users.edit', $user)
            ->with('success', trans('alerts.store.success'));
    }

    public function destroy(Request $request, User $user)
    {
        if ($request->user()->id === $user->id) {
            abort(403);
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', trans('alerts.destroy.success'));
    }
}
