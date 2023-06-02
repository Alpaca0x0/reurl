<?php
Inc::clas('resp');
Resp::header();
Inc::clas('db');
DB::connect() or Resp::error('db_connet', '資料庫連接失敗');

// search
$sql = "SELECT `url` FROM `url` WHERE `code`=:code LIMIT 1;";
DB::query($sql)::execute([
    ':code' => Router::path(),
]);

// search error
if(DB::error()){ Resp::error('db_query', '資料庫在查詢時發生錯誤'); }

// not found
$row = DB::fetch();
if($row === false){ Resp::warning('not_found', '該網址並不存在'); }

if(headers_sent()){ die('Redirect Page Error: Headers already been sent.'); }
header('Location: '.$row['url']);
