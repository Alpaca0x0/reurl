<?php
class Router{
    static private $uri=null;       // URI of current page (not include the root path)
    static private $local=null;     // local path where is routing (not include the absolute local path)
    static private $path=null;   // the path to be processed by the router
    static private $root=null;

    # init the router, only using on the main router
    static function init(){
        self::$uri = is_null(self::$uri) ? substr($_SERVER['SCRIPT_NAME'], strlen(Root) - 1) : self::$uri;
        self::$path = ltrim(self::uri(), '/\\');
        self::$root = '';
        // self::$local = Local;
    }

    # create a new router (usually a "router" file will only call this function once)
    static function new($router){
        self::$local .= $router;
        return self::class;
    }

    # get if match then callback
    static function get($uris, $callback=null, $path=null){
        $uris = is_array($uris) ? $uris : [$uris];
        foreach($uris as $uri){
            $uri = ltrim($uri,'/');
            if (!str_starts_with(self::path(), $uri)) { continue; };
            $path = is_null($path) ? substr(self::path(), strlen($uri)) : $path;
            if(is_callable($callback)){ call_user_func($callback); }
            else if(is_string($callback)) { self::route($callback, $path, $uri); }
        }
    }

    #
    static function equal($uris, $callback=null, $path=null){
        $uris = is_array($uris) ? $uris : [$uris];
        foreach($uris as $uri){
            $uri = ltrim($uri,'/');
            if($uri !== self::path()){ continue; }
            $path = is_null($path) ? substr(self::path(), strlen($uri)) : $path;
            if(is_callable($callback)){ call_user_func($callback); }
            else if(is_string($callback)) { self::route($callback, $path, $uri); }
        }
    }

    # it will try other possibility of path when file not found.
    static function view($filename=null){
        if(headers_sent()){ die('Router Error: Headers already been sent.'); }
        $filename = is_null($filename) ? self::path() : $filename;
        $filepath = File::in(self::local())::try($filename);
        if($filepath !== false){
            $mimeType = File::getMimeType(Local.$filepath);
            header('Content-Type: '.$mimeType);
            if($mimeType === 'text/html'){ require($filepath); }
            else{ readfile($filepath); }
            die();
        }
        return false;
    }

    # route to another router
    static function route($router, $path, $root){
        self::$path = $path;
        self::$root .= $root;
        Inc::router($router);
        die();
    }

    # redirect page
    static function redirect($path, $withPost=true, $withGet=true){
        if(headers_sent()){ die('Router Error: Headers already been sent.'); }
        if($withPost) header('HTTP/1.1 307 Temporary Redirect');
        header('Location: '.Root.self::root().ltrim($path,'/').($withGet && empty($_SERVER['QUERY_STRING']) ? '' : '?'.$_SERVER['QUERY_STRING']));
        die();
    }

    # get info of router
    # e.g. /project-root/router-root/path/
    static function uri(){ return self::$uri; } // /router-root/path/
    static function local(){ return self::$local; }  // /router-local/
    static function path(){ return self::$path; }  // path/
    static function root(){ return self::$root; }  // /project-root/router-root/
}