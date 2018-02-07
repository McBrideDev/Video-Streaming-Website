<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        DB::listen(function($sql, $bindings, $time) {
			// var_dump($sql);
			// var_dump($bindings);
			// var_dump($time);

			// //code to save query logs in a file
			// $logData = '';
			// $query =  $sql . ' - Time: '. $time;

			// //remove all new lines
			// $query = trim(preg_replace( "/\r\n|\n/", "", $query));
			// $newArr = array(date('Y-m-d H:i:s'), $query);
			// $logData .= implode("\t",$newArr) . "\n";

			// //write if any new data
			// if($logData != ''){
			// 	//open logs file if exists or create a new one
			// 	$logFile = fopen(storage_path('logs/query_logs/'.date('Y-m-d').'_query.log'), 'a+');
			// 	//write log to file
			// 	fwrite($logFile, $logData);
			// 	fclose($logFile);
			// }

			// $sql is an object with the properties:
			//  sql: The query
			//  bindings: the sql query variables
			//  time: The execution time for the query
			//  connectionName: The name of the connection

			// // To save the executed queries to file:
			// // Process the sql and the bindings:
			// foreach ($bindings as $i => $binding) {
			// 	if ($binding instanceof \DateTime) {
			// 		$bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
			// 	} else {
			// 		if (is_string($binding)) {
			// 			$bindings[$i] = "'$binding'";
			// 		}
			// 	}
			// }

			// // Insert bindings into query
			// $query = str_replace(array('%', '?'), array('%%', '%s'), $sql);

			// $query = vsprintf($query, $bindings);

			// // Save the query to file
			// $logFile = fopen(
			// 	storage_path('logs/query_logs' . DIRECTORY_SEPARATOR . date('Y-m-d') . '_query.log'),
			// 	'a+'
			// );
			// fwrite($logFile, date('Y-m-d H:i:s') . ': ' . $query . PHP_EOL);
			// fclose($logFile);
		});
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
