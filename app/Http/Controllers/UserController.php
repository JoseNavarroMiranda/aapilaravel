<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\JwtAuth;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function register(Request $request)
    {
        // Recoger el post
        $json = $request->input('json');
        if (empty($json)) {
            return response()->json([
                'status' => 'error',
                'codigo' => 400,
                'message' => 'No se recibieron datos',
            ], 200);
        }

        $params = json_decode($json, true); // Permite convertir a formato de array lo que llegue
        $email = $params['email'] ?? null;
        $name = $params['name'] ?? null;
        $surname = $params['surname'] ?? $name;
        $role = $params['role'] ?? 'cliente';
        $password = $params['password'] ?? null;

        if (!is_null($email) && !is_null($password) && !is_null($name)) {
            //Crear el usuario
            $user = new User();
            $user->email = $email;
            $user->name = $name;
            $user->surname = $surname;
            $user->role = $role;
            $pwd = password_hash($password, PASSWORD_DEFAULT);
            $user->password = $pwd;
            //Comprobar usuario duplicado
            $existsUser = User::where('email', $email)->exists();
            if (!$existsUser) {
                //Guardar el usuario
                $user->save();
                $data = array(
                    'status' => 'success',
                    'codigo' => 200,
                    'message' => 'Usuario Registrado correctamente'
                );
            } else {
                //No guardarlo
                $data = array(
                    'status' => 'error',
                    'codigo' => 400,
                    'message' => 'Usuario Duplicado, no puede registrarse'
                );
            }
            return response()->json($data, 200);
        } else {
            $data = array(
                'status' => 'error',
                'codigo' => 400,
                'message' => 'Usuario no Creado'
            );
            return response()->json($data, 200);
        }
    }

    public function login(Request $request){
    $jwtAuth = new JwtAuth();
    //Recibir POST
    $json = $request->input('json', null);
    $params = json_decode($json);
    $email =(!is_null($json) && isset($params->email)) ? $params->email : null;
    $password=(!is_null($json) && isset($params->password)) ? $params->password : null;
    $getToken=(!is_null($json) && isset($params->getToken)) ? $params->getToken : null;


    if(!is_null($email) && !is_null($password) && ($getToken == null || $getToken == false)){
        $signup = $jwtAuth->signup($email, $password);
    }elseif($getToken != null){
        $signup = $jwtAuth->signup($email, $password, $getToken);
    }else{
        $signup=array(
            'status'=>'error',
            'message' => 'Envía tus datos por post',
        );
    }
    return response()->json($signup, 200);
    }


}
