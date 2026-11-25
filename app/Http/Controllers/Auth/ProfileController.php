<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UpdatePasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * "Meu perfil": cada conta vê os próprios dados e troca a própria senha (Breeze: profile.edit e password.update).
 * Nome, e-mail e perfil são definidos pelo administrador.
 */
class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', ['user' => $request->user()->load('roles')]);
    }

    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->forceFill(['password' => $request->validated('password')])->save();

        $request->session()->regenerate();

        activity('usuario')->causedBy($user)->performedOn($user)->event('updated')->log('trocou a própria senha');

        return redirect()->route('profile.edit')->with('status', 'Senha alterada.');
    }
}
