<?php
class Url{
    static function isPort($port){ return !($port < 1 || $port > 65535); }
    
    static function isIpv4Url($url){
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
        
        if (!filter_var($domain, FILTER_VALIDATE_IP)){ return false; }

        return ['ipv4', $protocol, $domain, $port, $uri];
    }

    static function isIpv6Url($url){
        $regex = '';
        if(!preg_match($regex, $url, $matches)){ return false; }
        $protocol = strtolower($matches[1]);
        $domain = $matches[2];
        $port = isset($matches[3]) ? $matches[3] : '';
        $uri = isset($matches[4]) ? $matches[4] : '';
        return ['ipv6', $protocol, $domain, $port, $uri];
    }

    static function isCommonUrl($url){
        $regex = '/^(https?:\/\/)?((?:[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?\.)+(?:[a-zA-Z]{2,63}))(:\d+)?(\/.*)?$/i';
        if(!preg_match($regex, $url, $matches)){ return false; }
        $protocol = strtolower($matches[1]);
        $domain = strtolower($matches[2]);
        $port = isset($matches[3]) ? substr($matches[3], 1) : '';
        $uri = isset($matches[4]) ? $matches[4] : '';
        return ['common', $protocol, $domain, $port, $uri];
    }

    static function info($url){
        $info = self::isCommonUrl($url);
        $info = $info !== false ? $info : self::isIpv4Url($url);
        // $info = $info !== false ? $info : self::isIpv6Url($url);
        return $info;
    }

    static function isBlackDomain($domain){
        $partsDir = Path::config.'black-domains/parts/';
        $partFile = substr($domain, 0, 1).'.txt';
        // 
        if(File::in('/')::existDir($partsDir) && File::in($partsDir)::exist($partFile)){
            $fs = fopen($partsDir.$partFile, 'r');
            if($fs){
                while(($line = fgets($fs)) !== false) {
                    if(trim($line) === $domain) {
                        return true;
                        break;
                    }
                }
                fclose($fs);
                return false;
            }else{ return null; }
        }
    }
}