<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAccountSettingPasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use Exception;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ChangePasswordController extends Controller
{
    public function edit()
    {
        abort_if(Gate::denies('profile_password_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $pageConfigs = ['pageHeader' => false];

        $user = User::with(['branch_office'])->where('id', Auth::id())->first();

        return view('auth.passwords.edit',['pageConfigs' => $pageConfigs], compact('user'));
    }

    public function update(UpdateAccountSettingPasswordRequest $request)
    {
        try {
            auth()->user()->update($request->validated());
            session()->flash('success', 'Task was successful!');
        } catch(Exception $exception) {
            session()->flash('error', 'Something went wrong!');
        }

        return redirect()->route('profile.password.edit')->with('message', __('global.change_password_success'));
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = auth()->user();

        try {
            $user->update($request->validated());
            session()->flash('success', 'Task was successful!');
        } catch(Exception $exception) {
            session()->flash('error', 'Something went wrong!');
        }

        return redirect()->route('profile.password.edit')->with('message', __('global.update_profile_success'));
    }

    public function destroy()
    {
        $user = auth()->user();

        $user->update([
            'email' => time() . '_' . $user->email,
        ]);

        try {
            $user->delete();
            session()->flash('success', 'Task was successful!');
        } catch(Exception $exception) {
            session()->flash('error', 'Something went wrong!');
        }

        return redirect()->route('login')->with('message', __('global.delete_account_success'));
    }
}
