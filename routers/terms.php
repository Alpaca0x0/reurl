<?php
Router::new(Path::page.'terms/');

Router::equal('/', function () { Router::view('index'); });
Router::equal(['index', 'index/'], function () { Router::redirect('/'); });

Router::view();

http_response_code(404);