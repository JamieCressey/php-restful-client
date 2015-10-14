<?php
$filename = '/vendor/autoload.php';
$paths = [__DIR__.$filename, __DIR__.'/../../..'.$filename];
foreach($paths as $path){
    if (file_exists($path))
        require $path;
}

$api = new RestfulClient\ApiClient('#your_api_key', '#your_api_secret', 'http://restful.jamiecressey.com:5000');
$resp = $api->get('/books/');
var_dump($resp->ok());
var_dump($resp->errors());
var_dump($resp->message());

?>
