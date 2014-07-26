<?php

// Prevent direct access to this script
if($_GET['key'] != 'PUT_YOUR_KEY_HERE') die();

// Split URL into parts
$parts = parse_url($_GET['url']);

// Start timer
$timer = microtime(true);

// Create socket
$fp = ($parts['scheme'] == 'https') ? fsockopen('ssl://'.$parts['host'], 440, $errno, $errstr, 10) : fsockopen($parts['host'], 80, $errno, $errstr, 10);

// Get socket time
$socketTime = microtime(true) - $timer;

if (!$fp) {
    $result = array('status' => 'error');
} else {
    // Get data
    $out = "GET ".$parts['path']." HTTP/1.1\r\n";
    $out .= "Host: ".$parts['host']."\r\n";
    $out .= "Connection: Close\r\n\r\n";
    fwrite($fp, $out);

    // Look for string
    $foundString = false;
    $headers = fgets($fp, 1024);
    $code    = substr($headers, 9, 3);
    while (!feof($fp)) {
        $line = fgets($fp);
        if($foundString === false) $foundString = strpos($line, $_GET['string']);
    }

    // Set timer and close connection
    $getTime = microtime(true) - $timer;
    fclose($fp);

    $result = array('status' => 'success', 'stringFound' => ($foundString !== false), 'connectTime' => $socketTime, 'getTime' => $getTime, 'code' => $code);
}

