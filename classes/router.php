<?php
class Router{
    static private $uri=null;       // URI of current page (not include the root path of project)
    static private $chain=[];     // list of all routers
    static private $local=null;   // the local address corresponding to the current router
    static private $path=null;   // the path to be processed by the router

    # init the router, only using on the main router
    static function init(){
        self::$uri = is_null(self::$uri) ? substr($_SERVER['SCRIPT_NAME'], strlen(Root) - 1) : self::$uri;
        self::$path = ltrim(self::uri(), '/\\');
        self::$local = Local;
    }

    # create a new router (usually a "router" file will only call this function once)
    static function new(){
        array_push(self::$chain, ...func_get_args());
        self::$local = Local.self::chain();
        // self::$args = explode('/', rtrim(substr(self::$uri, strlen(self::$root)), '/') );
        // self::$path = rtrim(substr(self::$uri, strlen(self::$preRoot)), '/');
        return self::class;
    }
    #

    # get if match then callback
    static function get($uris, $callback=null, $path=null){
        $uris = is_array($uris) ? $uris : [$uris];
        foreach($uris as $uri){
            $uri = '/'.ltrim($uri,'/');
            if (!str_starts_with(self::$uri, $uri)) { continue; };
            self::$path = is_null($path) ? substr(self::path(), strlen($uri)-1) : $path;
            if(is_callable($callback)){ call_user_func($callback); }
            else if (is_string($callback)) { self::route($callback); }
        }
    }
    #
    static function equal($uris, $callback=null, $path=null){
        $uris = is_array($uris) ? $uris : [$uris];
        foreach($uris as $uri){
            $uri = '/'.ltrim($uri,'/');
            if($uri !== self::$uri){ continue; }
            self::$path = is_null($path) ? substr(self::path(), strlen($uri)) : $path;
            if(is_callable($callback)){ call_user_func($callback); }
            else if (is_string($callback)) { self::route($callback); }
        }
    }
    # it will try other possibility of path when file not found.
    static function view($filename=null){
        if(headers_sent()){ die('Router Error: Headers already been sent.'); }
        $filename = is_null($filename) ? self::path() : $filename;
        $filepath = File::in(self::chain())::try($filename);
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
    static function route($router){ Inc::router($router); die(); }
    # redirect page
    static function redirect($uri, $withPost=true, $withGet=true){
        if(headers_sent()){ die('Router Error: Headers already been sent.'); }
        if($withPost) header('HTTP/1.1 307 Temporary Redirect');
        header('Location: '.Root.ltrim($uri,'/').($withGet && empty($_SERVER['QUERY_STRING']) ? '' : '?'.$_SERVER['QUERY_STRING']));
        die();
    }

    # get info of router
    # e.g. /project-root/router-root/path/
    static function uri(){ return self::$uri; } // /router-root/path/
    static function chain($array=false){ return $array ? self::$chain : implode('', self::$chain); }  // /router-chain/
    static function path(){ return self::$path; }  // path/
    static function local(){ return self::$local; }  // /router-local/
}