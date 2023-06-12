<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserSignInRequest;
use App\Http\Requests\StoreUserSignUpRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function index()
    {
        return view('auth.signIn');
    }

    public function signIn(StoreUserSignInRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->intended('/')
                ->withSuccess('Signed in');
        }

        return redirect("sign-in")->withSuccess('Login details are not valid');
    }

    public function signUp()
    {
        return view('auth.signUp');
    }

    public function postSignUp(StoreUserSignUpRequest $request)
    {
        $data = $request->all();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);

        auth()->login($user);

        return redirect("sign-in")->withSuccess('You have signed-in');
    }

    public function signOut() {

        Session::flush();
        Auth::logout();
        return Redirect('sign-in');
    }
}
