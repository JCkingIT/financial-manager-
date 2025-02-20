<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class RegisterUserController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->hasAny(['ci', 'i'])) {
            Log::info('solicitar invitaciòn al administrador');
        }

        $code = $request->ci;
        $email = '';

        $guest = Invitation::where('id', $request->i)
            ->where('code', $code)
            ->first();

        if ($guest) {
            $email = $guest->email;
        }

        return view('user.account', compact('code', 'email'));
    }
    public function create(Request $request)
    {

        $valid = $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        Log::info($valid);

        $guest = Invitation::where('code', $request->code)
        ->where('email', $request->email)
        ->where('activated', false)
        ->first();

        if (!$guest || $guest->isExpired()) {
            throw ValidationException::withMessages([
                'code' => 'Invitación invalida o expirado'
            ]);
        }

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password'])
        ]);

        $user->assignRole('cliente');

        $guest->update([
            'activated' => true,
            'state' => false
        ]);

        return redirect('/admin');
    }
}
