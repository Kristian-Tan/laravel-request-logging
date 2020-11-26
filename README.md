# Laravel Request Logging

## About
- log incoming HTTP request and their response
    - request method
    - request headers
    - request body
    - response status code
    - response headers
    - response body
- work on laravel 8.6
- use HTTP middleware

## Usage
- copy LogHttpRequestResponse.php to app/Http/Middleware/LogHttpRequestResponse.php
- set routes that needs logging to use ```\App\Http\Middleware\LogHttpRequestResponse::class``` middleware

## Examples
- to log laravel passport's request and response, edit file ```app/Providers/AuthServiceProvider.php```, in function boot(), change ```\ Laravel\Passport\Passport::routes(null, ['middleware' => \App\Http\Middleware\LogHttpRequestResponse::class]);```
- to log a specific route, edit file ```routes/web.php```, edit ```Route::get('/', function () { return view('welcome'); })->middleware(\App\Http\Middleware\LogHttpRequestResponse::class);```

## Reading the log
- the logs are formatted as such:
```
[YYYY-MM-DD hh:mm:ss] local.INFO: Dump request attribute {"request_uri":"schema://host/dir/file","request_method":"GET/POST/PUT/PATCH/DELETE/HEAD/etc.","request_header":"base64encodedjson","request_body":"base64encodedjson", response_code":200,"response_header":"base64encodedjson","response_body":"base64encodedjson"}
```
- example:
```
[2020-11-25 16:08:19] local.INFO: Dump request attribute {"request_uri":"http://localhost/dir1/dir2/file?a=1","request_method":"POST","request_header":"eyJj","request_body":"eyJjb2R==","response_code":200,"response_header":"eyJw==","response_body":"Intc="}
```
- to ease viewing of such log, use readlog.php in this repository: ```php readlog.php storage/logs/laravel.log```, where first argument is path to laravel's log, or if the file doesn't exist the first argument will be treated as the log that needs decoding, or if the first argument don't exist (as in calling ```php readlog.php```), it will default to read from ```storage/logs/laravel.log```
- result example:
```
TODO
```

