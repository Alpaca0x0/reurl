<?php

Inc::clas('resp');
Resp::header();

Arr::every($_POST, 'url') or Resp::warning('data_missing', '資料缺失');
$url = trim(Type::string($_POST['url'], ''));

if(!filter_var($url, FILTER_VALIDATE_URL)){ Resp::warning('not_url', '這並非網址'); }

if(str_starts_with($url, 'http://'.Domain.Root) || str_starts_with($url, 'https://'.Domain.Root)){
    Resp::warning('do_not_reurl_this_site', '請不要嘗試縮短短網址，不然短網址就會變成短網址中的短網址!@#$%^&*()_+...');
}

Inc::clas('db');
DB::connect() or Resp::error('db_connet', '資料庫連接失敗');

$tryTimes = 16;
$length = 6;
$charTable = [
    'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k',      'm', 'n',      'p',      'r',      't', 'u', 'v', 'w', 'x', 'y', 'z',
    'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H',      'J', 'K', 'L', 'M', 'N',      'P', 'Q', 'R',      'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
    '9', '8', '7', '6', '5', '4', '3', '2',
];

function getRandomString($len, $table){
    $str = '';
    if($len < 1 || count($table) < 1) return $str;
    for($i=$len; $i>0; $i--){
        $str .= $table[random_int(0, count($table)-1)];
    }
    return $str;
}

// check if url exist already
$urlHash = hash('sha256', $url);
$sql = "SELECT `code` from `url` WHERE `hash`=:urlHash LIMIT 1;";
DB::query($sql)::execute([
    ':urlHash' => $urlHash,
]);
if(DB::error()){ Resp::error('db_query', '資料庫在查詢時發生錯誤'); }
$row = DB::fetch();
if($row !== false){ Resp::success('url_created', $row['code'], '短網址成功生成');; }

// url not exist, create one
for($i=$tryTimes; $i > 0; $i--) {
    $code = getRandomString($length, $charTable);
    // check if code exist
    $sql = "SELECT `id` from `url` WHERE `code`=:code LIMIT 1;";
    DB::query($sql)::execute([
        ':code' => $code,
    ]);
    if(DB::fetch() === false){ break; }
}
if($i < 1){ Resp::warning('code_not_unique', '生成短網址編碼時發生多次重覆，請稍後再嘗試一次'); }

// write into db
$ip = $_SERVER["REMOTE_ADDR"];
$sql = "INSERT INTO `url` (`code`, `url`, `hash`, `ip`) VALUES(:code, :url, :hash, :ip);";
DB::query($sql)::execute([
    ':code' => $code,
    ':url' => $url,
    ':hash' => $urlHash,
    ':ip' => $ip,
]);
if(DB::error()){ Resp::error('db_insert', [$code, $url, $urlHash, $ip], '資料庫在寫入時發生錯誤'); }

Resp::success('url_created', $code, '短網址成功生成');