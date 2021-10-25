<?php
namespace App\Helpers;
use DB;
use Config;
class DatabaseConnection{
	public static function getdbconnection($params){
		config(['database.connections.onthefly' => [
            'driver'   => $params['driver'],
            'host'     => $params['host'],
            'database' => $params['database'],
            'username' => $params['username'],
            'password' => $params['password']
        ]]);

        return DB::connection('onthefly');
	}
}