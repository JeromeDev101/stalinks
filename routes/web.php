<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\DB;


Route::get('/aw', function(){

	$countries = DB::table('countries')->get();

		foreach($countries as $lang)
		{
			 $kwe = DB::table('languages')->where('name','LIKE','%'.$lang->name.'%')->get();

			if(count($kwe) > 0){
				//echo '- country'.$lang->id.''.$lang->name.' -> language'.$kwe.'<br>';
				//echo $kwe[0]->id.'<br>';
				$template = DB::table('mail_contents')->where('country_id',$lang->id)->update(['country_id'=> $kwe[0]->id]);
				
				
			}

			
			
		}

	// $check_content = DB::table('mail_contents')->get();
	// foreach($check_content as $wa)
	// {


		


	// }


	
});


Route::get('/{any}', 'HomeController@index')->where('any', '.*');


