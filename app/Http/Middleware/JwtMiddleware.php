<?php 
namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
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
        // dd(JWTAuth::getToken());
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            // dd($e,$request);
            /*
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json(['status' => false,'msg'=>'Token is Invalid']);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return response()->json(['status' => false,'msg'=>'Token is Expired']);
            }else{
                return response()->json(['status' => false,'msg'=>'Authorization Token not found']);
            }
            */

            // Refresh token
            
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json(['status' => false,'msg'=>'Token is Invalid']);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                // If the token is expired, then it will be refreshed and added to the headers
                try
                {
                  $refreshed = JWTAuth::refresh(JWTAuth::getToken());
                  $user = JWTAuth::setToken($refreshed)->toUser();
                  $request->headers->set('Authorization','Bearer '.$refreshed);
                }catch (JWTException $e){
                    return response()->json([
                        'code'   => 103,
                        'message' => 'Token cannot be refreshed, please Login again'
                    ]);
                }
            }else{
                return response()->json(['status' => false,'msg'=>'Authorization Token not found']);
            }
            
        }
        return $next($request);
        
        
           /* $this->checkForToken($request); // Check presence of a token.
            try {
                
                if (!$this->auth->parseToken()->authenticate()) { // Check user not found. Check token has expired.
                    throw new UnauthorizedHttpException('jwt-auth', 'User not found');
                }
                
                $payload = $this->auth->manager()->getPayloadFactory()->buildClaimsCollection()->toPlainArray();
                // dd($payload['exp'] - $payload['iat']);

                return $next($request); // Token is valid. User logged. Response without any token.
            } catch (TokenExpiredException $t) { // Token expired. User not logged.
                    
                $payload = $this->auth->manager()->getPayloadFactory()->buildClaimsCollection()->toPlainArray();
                $key = 'block_refresh_token_for_user_' . $payload['sub'];
                $cachedBefore = (int) Cache::has($key);
                
                if ($cachedBefore) { // If a token alredy was refreshed and sent to the client in the last JWT_BLACKLIST_GRACE_PERIOD seconds.
                    \Auth::onceUsingId($payload['sub']); // Log the user using id.
                    return $next($request); // Token expired. Response without any token because in grace period.
                }

                try {

                    $newtoken = $this->auth->refresh(); // Get new token.
                    $gracePeriod = $this->auth->manager()->getBlacklist()->getGracePeriod();
                    $expiresAt = Carbon::now()->addSeconds($gracePeriod);
                    Cache::put($key, $newtoken, $expiresAt);
                    \Auth::onceUsingId($payload['sub']); // Log the user using id.
                    
                } catch (JWTException $e) {
                    throw new UnauthorizedHttpException('jwt-auth', $e->getMessage(), $e, $e->getCode());
                }
            }
            
            $response = $next($request); // Token refreshed and continue.
            return $this->setAuthenticationHeader($response, $newtoken); // Response with new token on header Authorization.*/
                        
    }
}