<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\LikeEvent;

class PushController extends Controller
{
    
    public function test()
	{
		$message = @$_GET['message'];
		$user_id = @$_GET['user_id'];

	   event(new LikeEvent($message, $user_id)); 
	}
}
