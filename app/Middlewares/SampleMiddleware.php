<?php

namespace App\Middlewares;
use App\Models\User AS UserModel;
use App\Models\Contact AS ContactModel;
use App\Models\Subscriber AS SubscriberModel;
use Valitron\Validator;

Class SampleMiddleware {

	function __invoke($request, $response, $next){
		$response = $next($request, $response);

		return $response;
	}

}