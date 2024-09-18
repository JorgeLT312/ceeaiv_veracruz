<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeUser;
use Illuminate\Support\Facades\DB;
use App\Mail\updatePasswordUser;



class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $areas = Area::all();
        $roles = Role::all();
        $users = User::query()
            ->when($request->search, function($query, $search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('username', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            })
            ->paginate()
            ->withQueryString();

        $filters = $request->only(['search']);

        return Inertia::render('Users', compact('users', 'filters', 'roles', 'areas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username',
            'email' => 'email|required|unique:users',
            'role_id' => 'required',
            'area_id' => 'required',
            
        ]);
        $pass = Str::random(8);
        $passHash=Hash::make($pass);
        $user=User::create($request->all()
            +['password' =>$passHash]
        );

        $rol['role_id'] = $request->role_id;
        $rol['model_type'] = "App\Models\User";
        $rol['model_id'] = $user->id;

        DB::table('model_has_roles')->insert($rol);

        try {
            Mail::to($user->email)->send(new WelcomeUser($pass,$user->username));
        }catch (\Exception $e){
            \Log::error('Error al enviar correo de bienvenida. '. $e->getMessage());
        }

        return to_route('usuarios.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username,'.$user->id,
            'email' => 'required|unique:users,email,'.$user->id,
            'role_id' => 'required',
            'area_id' => 'required',
        ]);

        switch ($request->role_id) {
            case '1':
                $rol='Admin';
                break;
            case '2':
                $rol='Invitado';
                break;
            case '3':
                $rol='Operativo';
                break;
            case '4':
                $rol='Nivel Superior';
                break;
        }

        $user->syncRoles([]);
        $user->assignRole($rol);

        $user->update($request->all());

        return to_route('usuarios.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function activate(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $user = User::findOrFail($request->id);
        $user->active = '1';
        $user->save();
    }

    public function deactivate(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $user = User::findOrFail($request->id);
        $user->active = '0';
        $user->save();
    }

    public function updatePassword(Request $request)
    {
        $id = User::where('email', $request->email)->first();
        $pass = Str::random(8);
        $passHash=Hash::make($pass);
        $user = User::find($id->id);
        if($user) {
            $user->password = $passHash;
            $user->password_admin = 1;
            $user->save();
        }
        try {
            Mail::to($user->email)->send(new updatePasswordUser($pass));
            \Log::info('Se envio correo de actualizar pass a: '.$user->email);
        }catch (\Exception $e){
            \Log::error('Error al enviar correo de actualizar pass. '. $e->getMessage());
        }
    }
}
