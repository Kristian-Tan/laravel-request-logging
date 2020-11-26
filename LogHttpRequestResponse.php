<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogHttpRequestResponse
{
    /*
    private $logger;
    
    public function __construct(Logger $logger) 
    {
        $this->logger = $logger;
    } // */

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        //return $next($request);

        $response = $next($request);
        //dd($request->all()); 
        //dd($response->content());
        //dd(json_encode($response->headers)); exit();
        //var_dump($response->headers->all()); exit();


        //$this->logger->info('Dump request', [
        Log::info('Dump request attribute', [
            'request_uri' => $request->getUri(),
            'request_method' => $request->getMethod(),
            'request_header' => base64_encode(json_encode($request->headers->all())),
            'request_body' => base64_encode(json_encode($request->all())),
            'response_code' => $response->getStatusCode(),
            'response_header' => base64_encode(json_encode($response->headers->all())),
            'response_body' => base64_encode(json_encode($response->content())),
        ]);

        $dumpRequestReadable = "";
        $dumpRequestReadable .= "\r\n";
        $dumpRequestReadable .= $request->getMethod()." ". $request->getUri();
        $dumpRequestReadable .= "\r\n";
        $dumpRequestReadable .= "\r\n";
        foreach ($request->headers->all() as $key => $value) 
        {
            if(is_array($value)) $value = $value[0];
            $dumpRequestReadable .= $key . ": " . $value;
            $dumpRequestReadable .= "\r\n";
        }
        $dumpRequestReadable .= "\r\n";
        $dumpRequestReadable .= $request->getContent();

        $dumpResponseReadable = "";
        $dumpResponseReadable .= "\r\n";
        $dumpResponseReadable .= $response->getProtocolVersion()." ". $response->getStatusCode();
        $dumpResponseReadable .= "\r\n";
        $dumpResponseReadable .= "\r\n";
        foreach ($response->headers->all() as $key => $value) 
        {
            if(is_array($value)) $value = $value[0];
            $dumpResponseReadable .= $key . ": " . $value;
            $dumpResponseReadable .= "\r\n";
        }
        $dumpResponseReadable .= "\r\n";
        $dumpResponseReadable .= $response->content();


        Log::info('Dump request readable', [$dumpRequestReadable]);
        Log::info('Dump response readable', [$dumpResponseReadable]);
                
        return $response;
    }
}
