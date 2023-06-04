<?php

Inc::clas('resp');
Resp::header();

Arr::every($_POST, 'url') or Resp::warning('data_missing', '資料缺失');
$url = trim(Type::string($_POST['url'], ''));

// checker functions
function isPort($port){ return !($port < 1 || $port > 65535); }
function isIpv4Url($url){
    $regex = '/^(https?:\/\/)?((?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.' .
    '(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.' .
    '(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.' .
    '(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?))' .
    '(?::(\d{1,5}))?((?:\/\S*)?)$/i';
    if(!preg_match($regex, $url, $matches)) { return false; }
    $protocol = strtolower($matches[1]);
    $domain = $matches[2];
    $port = isset($matches[3]) ? $matches[3] : '';
    $uri = isset($matches[4]) ? $matches[4] : '';
    return ['ipv4', $protocol, $domain, $port, $uri];
}
function isIpv6Url($url){
    $regex = '';
    if(!preg_match($regex, $url, $matches)){ return false; }
    $protocol = strtolower($matches[1]);
    $domain = $matches[2];
    $port = isset($matches[3]) ? $matches[3] : '';
    $uri = isset($matches[4]) ? $matches[4] : '';
    return ['ipv6', $protocol, $domain, $port, $uri];
}
function isEnUrl($url){
    $regex = '/^(https?:\/\/)?((?:[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?\.)+(?:[a-zA-Z]{2,63}))(:\d+)?(\/.*)?$/i';
    if(!preg_match($regex, $url, $matches)){ return false; }
    $protocol = strtolower($matches[1]);
    $domain = strtolower($matches[2]);
    $port = isset($matches[3]) ? substr($matches[3], 1) : '';
    $uri = isset($matches[4]) ? $matches[4] : '';
    return ['english', $protocol, $domain, $port, $uri];
}

// check url format
$info = isEnUrl($url);
$info = $info !== false ? $info : isIpv4Url($url);
// $info = $info !== false ? $info : isIpv6Url($url);
if($info === false){ Resp::warning('not_url', '這並非是一個有效的網址格式 (p.s. 目前還不支援 IPv6 格式，敬請期待未來更新)'); }

// get url infos
$type = $info[0];
$protocol = $info[1];
$domain = $info[2];
$port = $info[3];
$uri = $info[4];

// check if port format is correct
if ($port !== '') {
    $port = (int)$port;
    if(!isPort($port)){ Resp::warning('port_out_range', $port, '偵測到格式錯誤的 Port，數字範圍應介於 1 ~ 65535 之間'); }
}

$protocol = empty($protocol) ? 'https://' : strtolower($protocol);
$domain = strtolower($domain);
$port = $port ? $port : '';
$uri = $uri==='' ? '' : $uri;
$url = $protocol.$domain.($port===''?'':':'.$port).$uri;

// domain not exist
if($type === 'english'){
    $isAvailableDomain = checkdnsrr($domain, 'A');
    if(!$isAvailableDomain){ Resp::warning('domain_may_not_exists', $domain, '無法被偵測到 A 紀錄的網域'); }
}

// check if domain is current site
// if(str_starts_with($url, 'http://'.Domain.Root) || str_starts_with($url, 'https://'.Domain.Root)){
if($domain === Domain && str_starts_with($uri, Root)){ Resp::warning('try_reurl_this_site', '請問您是在嘗試縮短"短網址"嗎？'); }

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
$ip = isset($_SERVER['HTTP_X_REMOTE_ADDR']) ? $_SERVER['HTTP_X_REMOTE_ADDR'] : $_SERVER["REMOTE_ADDR"];
$sql = "INSERT INTO `url` (`code`, `url`, `hash`, `ip`) VALUES(:code, :url, :hash, :ip);";
DB::query($sql)::execute([
    ':code' => $code,
    ':url' => $url,
    ':hash' => $urlHash,
    ':ip' => $ip,
]);
if(DB::error()){ Resp::error('db_insert', (DEBUG ? [$code, $url, $urlHash, $ip] : null), '資料庫在寫入時發生錯誤'); }
Resp::success('url_created', $code, '短網址成功生成');
