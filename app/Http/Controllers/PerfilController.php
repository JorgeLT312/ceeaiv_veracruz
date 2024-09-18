<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\PerfilRequest;
use Auth;

class PerfilController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render("Perfil", [
            "user" => Auth::user(),
        ]);
    }

    public function update(PerfilRequest $request)
    {
        /*$request->validate([
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);*/

        if(Auth::user()){
            $id = Auth::id();
        }else{
            $id = User::where('username', $request->username)->select('id')->get();
            foreach($id as $i){
                $id = $i->id;
            }
        }

        $new_password = $request->password;
        $repeat_password = $request->password_confirmation;

        $user = User::find($id);
        if($user) {
            $user->password = Hash::make($new_password);
            $user->password_admin = 0;
            $user->save();
        }

        if($request->wantsJson()) {
            return response()->json([
                "message" => 'Se actualizo la contraseña',
                "success" => true
            ], 200);
        }

        return redirect(route('perfil'));
    }

    public function updatePassword(Request $request)
    {
        $id = User::where('username', $request->username)->select('id')->get();
        foreach($id as $i){
            $id = $i->id;
        }
        $new_password=$request->new_password;
        $repeat_password=$request->repeat_password;

        if(strlen($new_password) < 8){
            return response()->json([
                "message" => 'Debe contener 8 o más caracteres',
                "success" => false
            ], 200);
        }

        if ($new_password==$repeat_password) {
            $user = User::find($id);
            if($user) {
                $user->password = Hash::make($request->new_password);
                $user->save();
            }
            return response()->json([
                "message" => 'Se actualizo la contraseña',
                "success" => true
            ], 200);
            return redirect('login');
        }
        else{
            return response()->json([
                "message" => 'Contraseñas no coinciden',
                "success" => false
            ], 200);
        }
    }

    public function resetPassword(Request $request)
    {
        $id = User::where('username', $request->username)->select('id')->get();
        foreach($id as $i){
            $id = $i->id;
        }
        $new_password = $request->password;
        $repeat_password = $request->repeat_password;

        if(strlen($new_password) < 8){
            return Inertia::render('Auth/ResetPassword', [
                "message" => 'La contraseña debe contener 8 o más caracteres',
                "success" => true
            ]);
        }

        if ($new_password==$repeat_password) {
            $user = User::find($id);
            if($user) {
                $user->password = Hash::make($request->password);
                $user->save();
            }
            return Inertia::render('Auth/ResetPassword', [
                "message" => 'Se actualizo la contraseña',
                "success" => true
            ]);
        }
        else{
            return Inertia::render('Auth/ResetPassword', [
                "message" => 'Las contraseñas no coinciden',
                "success" => true
            ]);
        }
    }
}
