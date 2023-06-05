<?php

Inc::clas('resp');
Resp::header();

Arr::every($_POST, 'url') or Resp::warning('data_missing', '資料缺失');
$url = trim(Type::string($_POST['url'], ''));

Inc::clas('url');
// check url format
$info = Url::info($url);
if($info === false){ Resp::warning('not_url', '這並非是一個有效的網址格式 (p.s. 目前還不支援 IPv6 格式，敬請期待未來更新)'); }

// get url infos
$type = $info[0];
$protocol = trim($info[1]);
$domain = trim($info[2]);
$port = trim($info[3]);
$uri = trim($info[4]);

// check if port format is correct
if ($port !== '') {
    $port = (int)$port;
    if(!Url::isPort($port)){ Resp::warning('port_out_range', $port, '偵測到格式錯誤的 Port，數字範圍應介於 1 ~ 65535 之間'); }
}

$protocol = empty($protocol) ? 'https://' : strtolower($protocol);
$domain = strtolower($domain);
$port = $port ? $port : '';
$uri = $uri==='' ? '/' : $uri;
$url = $protocol.$domain.($port===''?'':':'.$port).$uri;

// check if domain is current site
// if(str_starts_with($url, 'http://'.Domain.Root) || str_starts_with($url, 'https://'.Domain.Root)){
if($domain === Domain && str_starts_with($uri, Root)){ Resp::warning('try_reurl_this_site', '請問您是在嘗試縮短"短網址"嗎？'); }

// domain not exist
if($type === 'common'){
    $isAvailableDomain = checkdnsrr($domain, 'A');
    if(!$isAvailableDomain){ Resp::warning('domain_may_not_exists', $domain, '無法被偵測到 A 紀錄的網域'); }
}

// check if in black list
if(Url::isBlackDomain($domain)){ Resp::warning('black_domain', $domain, '恕拒絕服務，偵測到惡意網域'); }

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
$sql = "INSERT INTO `url` (`code`, `url`, `hash`, `ip`) VALUES(:code, :url, :hash, :ip);";
DB::query($sql)::execute([
    ':code' => $code,
    ':url' => $url,
    ':hash' => $urlHash,
    ':ip' => ClientIP,
]);
if(DB::error()){ Resp::error('db_insert', (DEBUG ? [$code, $url, $urlHash, ClientIP] : null), '資料庫在寫入時發生錯誤'); }
Resp::success('url_created', $code, '短網址成功生成');
