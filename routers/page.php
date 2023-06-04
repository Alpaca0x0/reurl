<?php
Router::new(Path::page);

Router::equal('/', function () { Router::redirect('/index/'); });
Router::equal('/index/', function () { Router::view('index'); });
if(DEBUG) Router::equal('/lab/', function () { Router::view('lab'); });
Router::equal('/terms/', function () { Router::view('terms'); });
Router::view('redirect');

http_response_code(404);