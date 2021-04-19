<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    public function getPassword($token)
    {
        return view('auth.password.reset', ['token' => $token]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
        ]);

        $updatePassword = User::where(['email' => $request->email])->first();

        if(!$updatePassword)
            return back()->withInput()->with('error', 'Invalid Email!');

            $user = User::where('email', $request->email)->update(['password' => Hash::make($request->password)]);
            DB::table('password_resets')->where(['token'=> $request->token])->delete();
            //$user->save();

          

          return redirect('/login')->with('message', 'Your password has been changed!');
    }
}
