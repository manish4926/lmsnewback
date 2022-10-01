<?php

namespace App\Http\Middleware;

use Closure;

class RestrictIpMiddleware
{
    protected $ips = [
        '43.255.154.95',    //server
        '103.51.116.138',
        '14.140.107.211',
        '64.233.173.137',
        '115.97.156.55',   //Charu
        '127.0.0.1'
    ];
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /*$allowed_ip = "1.2.3.4"; 
        var_dump(request()->ip);

        if($allowed_ip != request()->ip )
        {
            if(in_array(request()->ip(), $ipsDeny))
            {
                \Log::warning("Unauthorized access, IP address was => ".request()->ip);
                 return response()->json(['Unauthorized!'],400);
            }
        }*/
        foreach ($request->getClientIps() as $ip) {
            if ($this->isValidIp($ip)) {
                return $next($request);
            } else {
                return response()->json('Unauthorized Access!. You are not allowed to access this page. Kindly contact the administrator to access this page.',400);   
            }
        }
        
    }

    protected function isValidIp($ip)
    {
        return in_array($ip, $this->ips);
    }
}
