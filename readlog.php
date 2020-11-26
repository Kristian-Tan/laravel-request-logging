<?php

$logcontent = "";

if(!empty($argv[1]) && file_exists($argv[1])) $logcontent = file_get_contents($argv[1]); // if first argument is a file name, read log from the specified file
else if(!empty($argv[1]) && is_string($argv[1])) $logcontent = $argv[1]; // if first argument is not a file name, read log from the argument
else $logcontent = file_get_contents("storage/logs/laravel.log"); // if no argument exist, read log from default path

if(!empty($argv[2]) && is_string($argv[2])) $flags = str_split($argv[2]);
else $flags = array();

$logcontent = str_replace("\r\n", "\n", $logcontent); // make sure all line ending in LF (UNIX style)
$loglines = explode("\n", $logcontent);

foreach ($loglines as $logline) 
{
    $exploded = explode(" local.INFO: Dump request attribute ", $logline);
    if(count($exploded) == 1) continue; // not a dump request log

    $logstamp = $exploded[0];
    $logrequest = $exploded[1];
    $logrequest = json_decode($logrequest, true);
    if(empty($logrequest)) continue; // error reading the log, or error when writing log

    if(is_array($logrequest) && !empty($logrequest[0]) && count($logrequest) == 1) $logrequest = $logrequest[0];

    $logrequest["request_header"] = json_decode(base64_decode($logrequest["request_header"]), true);
    $logrequest["request_body"] = json_decode(base64_decode($logrequest["request_body"]), true);
    $logrequest["response_header"] = json_decode(base64_decode($logrequest["response_header"]), true);
    $logrequest["response_body"] = json_decode(base64_decode($logrequest["response_body"]), true);
    //$logrequest["response_body"] = base64_decode($logrequest["response_body"]);

    echo "==="."\n";
    if(!in_array("t", $flags)) echo $logstamp."\n";
    echo "REQUEST => ".$logrequest["request_method"]." ".$logrequest["request_uri"]."\n";
    if(!in_array("h", $flags)) 
    {
        echo "\n";
        foreach ($logrequest["request_header"] as $key => $value) 
        {
            //var_dump($key); var_dump($value); exit;
            if(is_array($key) && !empty($key[0]) && count($key) == 1) $key = $key[0];
            if(is_array($value) && !empty($value[0]) && count($value) == 1) $value = $value[0];
            if(is_array($value)) $value = implode("; ", $value);
            if(in_array("l", $flags) && strlen($value) > 50) $value = substr($value, 0, 50) . "... (cont.)";
            echo $key . ": " . $value . "\n";
        }
    }
    if(!in_array("b", $flags)) 
    {
        echo "\n";
        foreach ($logrequest["request_body"] as $key => $value) 
        {
            if(is_array($key) && !empty($key[0]) && count($key) == 1) $key = $key[0];
            if(is_array($value) && !empty($value[0]) && count($value) == 1) $value = $value[0];
            if(in_array("l", $flags) && strlen($value) > 50) $value = substr($value, 0, 50) . "... (cont.)";
            echo $key . "=" . $value . "\n";
        }
    }
    echo "\n";
    echo "---"."\n";
    echo "RESPONSE => ".$logrequest["response_code"]."\n";
    if(!in_array("H", $flags)) 
    {
        echo "\n";
        foreach ($logrequest["response_header"] as $key => $value) 
        {
            if(is_array($key) && !empty($key[0]) && count($key) == 1) $key = $key[0];
            if(is_array($value) && !empty($value[0]) && count($value) == 1) $value = $value[0];
            if(is_array($value)) $value = implode("; ", $value);
            if(in_array("l", $flags) && strlen($value) > 50) $value = substr($value, 0, 50) . "... (cont.)";
            echo $key . ": " . $value . "\n";
        }
    }
    if(!in_array("B", $flags)) 
    {
        echo "\n";
        echo $logrequest["response_body"]."\n";
    }
    echo "\n";
    echo "\n";
    echo "\n";
}
