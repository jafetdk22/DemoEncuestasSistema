<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use DB;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = DB::connection('sqlsrv')->select('SELECT u.id as UserId ,u.name as NameUser,u.concesion,u.email, mhr.role_id,mhr.model_id, r.name as NameRole, r.id as RoleId from users u
        inner join  model_has_roles mhr on  u.id = mhr.model_id
        inner join roles r on r.id =mhr.role_id');
        $role = Role::all(); 

        $alerta=0;
        return view('usuarios.home', compact('users','role'))->with('alerta',$alerta);
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $role = Role::all(); 
        return response()->view('usuarios.create', compact('role'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $this->validate($request, [
            'name' => 'required|min:3|max:50',
            'vat_number' => 'max:13',
            'password' => 'min:8|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:8'
            ]);
        $emails =$request->get('email').$request->get('dominio');
        $existe = DB::connection('sqlsrv')->select('SELECT email FROM users WHERE email LIKE'."'".$emails."'");
        if(!$existe){
            $user = new User();
            $user->Name = $request->get('name');
            //dd($email);
            $user->Email = $emails;
            $user->password = Hash::make($request->password);
            $user->concesion = $request->get('Concesion');
            $user->ConexionDB= $request->get('ConexionDB');
            $roles= $request->get('role');
            $user->assignRole($roles);
            $user->save();

            $users = DB::connection('sqlsrv')->select('SELECT u.id as UserId ,u.name as NameUser,u.concesion,u.email, mhr.role_id,mhr.model_id, r.name as NameRole, r.id as RoleId from users u
            inner join  model_has_roles mhr on  u.id = mhr.model_id
            inner join roles r on r.id =mhr.role_id');// se quita esta partesi quieres quitar pagiacion y activas elde abajo
            //dd($users);
            $role = Role::all();
            $alerta=2;
            return view('usuarios.home', compact('users','role'))->with('alerta',$alerta);
        }else{
            $users = DB::connection('sqlsrv')->select('SELECT u.id as UserId ,u.name as NameUser,u.concesion,u.email, mhr.role_id,mhr.model_id, r.name as NameRole, r.id as RoleId from users u
            inner join  model_has_roles mhr on  u.id = mhr.model_id
            inner join roles r on r.id =mhr.role_id');// se quita esta partesi quieres quitar pagiacion y activas elde abajo
            //dd($users);
            $role = Role::all(); 
            $alerta=1;
            
            return view('usuarios.home', compact('users','role'))->with('alerta',$alerta);
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $users = User::find($id);

        //dd($users);

        return response()->view('usuarios.edit', [
            'users' => $users,
            'actualizar' => 'ok'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $users = User::find($id);

        //$user-> password = Hash::make($request -> password);
        $users->password = bcrypt('password');
        $users->save();

        return redirect('/usuarios')->with('actualizar', 'ok');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($id == 1 ){
            return redirect('/usuarios')->with('Noeliminar', 'ok');
        }else{
            $users = User::find($id);
            $users->delete();
            return redirect('/usuarios')->with('eliminar', 'ok');
        }

    }


    public function automotriz1()
    {
        $users =DB::connection('sqlsrv')->select("SELECT u.id as UserId ,u.name as NameUser,u.concesion,u.email, mhr.role_id,mhr.model_id, r.name as NameRole, r.id as RoleId from users u
        inner join  model_has_roles mhr on  u.id = mhr.model_id
        inner join roles r on r.id =mhr.role_id WHERE concesion= 'Automotriz1'");
        $alerta=0; $role = Role::all(); 
        return view('usuarios.home', compact('users','role'))->with('alerta',$alerta);
    }
    public function automotriz2(){
        $users =DB::connection('sqlsrv')->select("SELECT u.id as UserId ,u.name as NameUser,u.concesion,u.email, mhr.role_id,mhr.model_id, r.name as NameRole, r.id as RoleId from users u
        inner join  model_has_roles mhr on  u.id = mhr.model_id
        inner join roles r on r.id =mhr.role_id WHERE concesion= 'Automotriz2'");
        $alerta=0; $role = Role::all(); 
        return view('usuarios.home', compact('users','role'))->with('alerta',$alerta);
    }
    public function automotriz3(){
        $users =DB::connection('sqlsrv')->select("SELECT u.id as UserId ,u.name as NameUser,u.concesion,u.email, mhr.role_id,mhr.model_id, r.name as NameRole, r.id as RoleId from users u
        inner join  model_has_roles mhr on  u.id = mhr.model_id
        inner join roles r on r.id =mhr.role_id WHERE concesion= 'Automotriz3'");

        $alerta=0; $role = Role::all(); 
        return view('usuarios.home', compact('users','role'))->with('alerta',$alerta);
    }
    public function automotriz4(){
        $users =DB::connection('sqlsrv')->select("SELECT u.id as UserId ,u.name as NameUser,u.concesion,u.email, mhr.role_id,mhr.model_id, r.name as NameRole, r.id as RoleId from users u
        inner join  model_has_roles mhr on  u.id = mhr.model_id
        inner join roles r on r.id =mhr.role_id WHERE concesion= 'Automotriz4'");

        $alerta=0; $role = Role::all(); 
        return view('usuarios.home', compact('users','role'))->with('alerta',$alerta);
    }   
    public function automotriz5(){
        $users =DB::connection('sqlsrv')->select("SELECT u.id as UserId ,u.name as NameUser,u.concesion,u.email, mhr.role_id,mhr.model_id, r.name as NameRole, r.id as RoleId from users u
        inner join  model_has_roles mhr on  u.id = mhr.model_id
        inner join roles r on r.id =mhr.role_id WHERE concesion= 'Automotriz5'");

        $alerta=0; $role = Role::all(); 
        return view('usuarios.home', compact('users','role'))->with('alerta',$alerta);
    }
    public function automotriz6(){
        $users =DB::connection('sqlsrv')->select("SELECT u.id as UserId ,u.name as NameUser,u.concesion,u.email, mhr.role_id,mhr.model_id, r.name as NameRole, r.id as RoleId from users u
        inner join  model_has_roles mhr on  u.id = mhr.model_id
        inner join roles r on r.id =mhr.role_id WHERE concesion= 'Automotriz6'");

        $alerta=0; $role = Role::all(); 
        return view('usuarios.home', compact('users','role'))->with('alerta',$alerta);
    }

}
