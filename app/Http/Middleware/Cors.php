<?php

namespace App\Http\Middleware;

use Closure;

class Cors 
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
       // dd($request->getMethod());
       //working method for webapp
//        $response = $next($request);
      
//        $response->header('Access-Control-Allow-Origin', '*');
//        $response->header('Access-Control-Allow-Methods', 'HEAD, GET, POST, PUT, DELETE');           
//        $response->header('Access-Control-Allow-Headers', $request->header('Access-Control-Request-Headers'));       
//        return $response;
//end process
        // $response = $next($request);
      
        //     $response->header('Access-Control-Allow-Origin', '*');
        //     $response->header('Access-Control-Allow-Methods', 'HEAD, GET, POST, PUT, DELETE');           
        //     $response->header('Access-Control-Allow-Headers', $request->header('Access-Control-Request-Headers'));       
        // return $response;

        $headers = [
            'Access-Control-Allow-Origin'      => '*',
            'Access-Control-Allow-Methods'     => 'POST, GET, OPTIONS',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Max-Age'           => '86400',
            'Access-Control-Allow-Headers'     => $request->header('Access-Control-Request-Headers')
        ];
        if ($request->isMethod('OPTIONS')) {
            return response()->json('{"method":"OPTIONS"}', 200, $headers);
        }
        $response = $next($request);
        foreach($headers as $key => $value) {
            $response->headers->set($key, $value);
        }
 
        return $response;
        
        // if ($request->getMethod() === "OPTIONS") {
        //     return response()->json('ok',200,$headers);
        // }
        // $response = $next($request);
        // foreach($headers as $key => $value){
        //     $response->header($key,$value);
        // }
        // return $response;
            // return response($request->getMethod(), 200) 
            // ->header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE')
            // ->header('Access-Control-Allow-Headers', 'Content-Type')
            // ->header('Access-Control-Allow-Origin', '*'); 
        
           
            // $response->headers->set('Access-Control-Allow-Origin', ['https://vshwan-webapp-staging.web.app']);
            // $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
            // $response->headers->set('Access-Control-Allow-Headers','Origin, Content-Type, Accept, Authorization, X-Request-With, X-Auth-Token');
            // $response->headers->set('Access-Control-Allow-Credentials','true');                       
    }
}
