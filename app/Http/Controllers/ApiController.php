<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use DatabaseConnection;
use Hash;
class ApiController extends Controller
{
      /// login //////

    public function login(Request $request){

        //return response()->json($request->all(),203);
        if ($request->backend == 'div') {
            $database = 'np_backend';
            $table    = 'backend_users';
            $role     = 'admin';
            // if(isset($request->database)){
            //     if($request->database == 'sylhet' ){
            //         $database = '5b8641e5-db8e-4372-a165-bec7b16d28c8';
            //         $table = 'users';
            //         $role  = 'site';
            //     }
            // }
        }
        if ($request->backend == 'mofa') {
            $database = 'mofa_backend';
            $table    = 'backend_users';
            $role     = 'admin';
            if(isset($request->database)){
                if($request->database == 'sylhet' ){
                    $database = 'ff0c09cb-a4a9-4a77-bb7f-f9b015be439c';
                    $table = 'users';
                    $role  = 'site';
                }
            }
        }

        $params['driver'] = 'mysql';
        $params['host']   = '127.0.0.1';
        $params['database'] = $database;
        $params['username'] = 'root';
        $params['password'] = '';
        $connection = DatabaseConnection::getdbconnection($params);

        $user = $connection->table($table)->where('email',$request->email)->first();
        if (!$user){
            return response()->json(['error'=>'Unauthenticated'],203);
        }
        if (!Hash::check($request->password,$user->password)){
            return response()->json(['error'=>'Unauthenticated'],203);
        }
        if ($user) {
            if($role == 'admin'){
                $name = $user->first_name;
                $domain = 0;
            }else{
                $name = $user->name;
                $domain = $domain_id;
            }
            $email    = $user->email;
            $password = $user->password;
        }
        return response()->json(['success'=>true,'role'=>$role,'domain' => $domain, 'name' => $name,'email' => $email, 'password' => $password],200);

    }
}
