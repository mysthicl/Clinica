<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('role')->orderBy('name')->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::where('active', true)->get();
        return view('admin.users.create', compact('roles')); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'string|max:100',
            'email' => 'email|max:100|unique:users,email',
            'password' => 'string|min:8|confirmed',
            'id_role' => 'exists:roles,id_rol',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'id_rol' => $request->id_rol,
            'active' => true,
        ]);
        
        return redirect()->route('admin.users.index')->with('success', 'Usuario creado exitosamente.');
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
    public function edit(User $user)
    {
        $roles = Role::where('active', true)->get();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email,' . $user->email . ',email',
            'password' => 'nullable|string|min:8|confirmed,' .$user->password,
            'id_rol' => 'required|exists:roles,id_rol'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->id_rol = $request->id_rol;

        if($request->filled('password')){
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Toggle the active status of the specified resource.
     */
    public function toggleActive(User $user)
    {
        // Evitar desactivar el propio usuario
        if($user->id_user === Auth::user()->id_user){
            return back()->with('error', 'No puedes desactivar tu propio usuario.');
        }

        $user->active = !$user->active;
        $user->save();

        $mensaje = $user->active ? 'Usuario activado' : 'Usuario desactivado';
        return back()->with('success', $mensaje);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if($user->id_user === Auth::user()->id_user){
            return back()->with('error', 'No puedes eliminar tu propio usuario.');
        }

        $user->delete();
        return back()->with('success', 'Usuario eliminado exitosamente.');
    }
}
