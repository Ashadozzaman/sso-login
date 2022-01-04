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
        $database = 'np_backend';
        $table    = 'backend_users';
        $host     = '127.0.0.1';
        $username = 'root';
        $password = '';

        $params['driver']   = 'mysql';
        $params['host']     = $host;
        $params['database'] = $database;
        $params['username'] = $username;
        $params['password'] = $password;
        $connection = DatabaseConnection::getdbconnection($params);


        $user = $connection->table($table)->where('email',$request->email)->first();
        if (!$user){
            return response()->json(['error'=>'Unauthenticated'],203);
        }
        if (!Hash::check($request->password,$user->password)){
            return response()->json(['error'=>'Unauthenticated'],203);
        }
        $user_id = $user->id;
        //get user site
        $site_users = $connection->table('np_structure_site_user')->where('user_id',$user_id)->get();
        $site_access_count = count($site_users);
        $sites = [];
        foreach($site_users as $key => $site_user){
            $sites[$key] = $site_user->site_id;
        }        
        return response()->json(
            [
                'success'=>true,
                'name' => $user->first_name,
                'email' => $user->email, 
                'role'=> $user->role_id,
                'is_superuser' => $user->is_superuser,
                'site_access_count' => $site_access_count,
                'site_ids' =>json_encode($sites),
            ],200)  ;

    }
}