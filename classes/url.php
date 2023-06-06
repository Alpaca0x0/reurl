<?php
class Url{
    static function isPort($port){ return !($port < 1 || $port > 65535); }
    
    static function isPrivateIpv4($ip) {
        $privateIPs = array(
            '10.0.0.0|10.255.255.255',      // Class A私人IP
            '172.16.0.0|172.31.255.255',    // Class B私人IP
            '192.168.0.0|192.168.255.255',  // Class C私人IP
            '169.254.0.0|169.254.255.255',  // 非公網IP
            '127.0.0.0|127.255.255.255',    // Loopback IP
            '0.0.0.0|0.255.255.255'         // 保留IP
        );
        $ip = ip2long($ip);
        if ($ip !== false) {
            foreach ($privateIPs as $range) {
                list($start, $end) = explode('|', $range);
                if ($ip >= ip2long($start) && $ip <= ip2long($end)) { return true; }
            }
        }
        return false;
    }

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
        $domain = trim($domain, './ ');
        $partsDir = Path::config.'black-domains/parts/';
        $partFile = explode('.', $domain);
        $partFile = trim(end($partFile), './ ').'.txt';
        // 
        if(File::in('/')::existDir($partsDir) && File::in($partsDir)::exist($partFile)){
            $fs = fopen($partsDir.$partFile, 'r');
            if($fs){
                while(($line = fgets($fs)) !== false) {
                    $line = trim($line, " \n\t./");
                    if($domain === $line || str_ends_with($domain, '.'.$line)) {
                        return $line;
                        break;
                    }
                }
                fclose($fs);
                return false;
            }else{ return null; }
        }
    }
}