<?php

################################################################
# Basic settings                 ##
# 基本設置                       ##
##################################

# Name of project
# 專案名稱
define('NAME', '(๑•̀ㅂ•́)و✧ URL 縮起乃');

# Debug mode: show errors
# 除錯模式：顯示錯誤資訊
define('DEBUG', true);

# Development mode: show info
# 開發者模式：顯示更多資訊
define('DEV', true);

# Full development mode: (show more info)
# 進階開發者模式：顯示更多資訊
define('FULL_DEV', false);

# URL root path of the project, e.g. /router/
# 網站 URL 根目錄，例如：/router/
define('Root', '/');



################################################################
# Optional settings             ##
# 其他設置                      ##
#################################

# Message displayed during maintenance
# 維護時顯示的資訊
define('MSG_MAINTAIN', 'The website is under maintenance. Please come back later.<br>網站正在維修中，請稍後再回來看看吧。');



################################################################
# Advanced settings              ##
# 進階設置                       ##
##################################

# Libraries of php which using on this project
# 在此專案中所使用的 PHP 函式庫
define('Libraries', [
	#'pdo_mysql', // for database
    'gd', // 用於繪製圖像驗證碼
    'pdo', // 資料庫連接使用 pdo
]);

# Local paths
# 本地路徑
class Path{ const 
    init = 'init/',             // For auto load file (It is recommended NOT to change)
    clas = 'classes/',          // Classes
    func = 'functions/',        // Functions
    page = 'pages/',            // Pages
    config = 'configs/',        // Config
    component = 'components/',  // Components
    router = 'routers/',        // Routers
    asset = 'assets/',          // Assets, e.g. css, js, img ...
    lib = 'libraries/'          // Libraries, e.g. PHPMailer
# URI paths
# URI 路徑
;const
    js = 'js/',         // Javascript
    css = 'css/',       // CSS
    img = 'img/',       // Images
    auth = 'auth/',     // The path where the pages used for verification is stored
    plugin = 'plugin/',  // Plugins, e.g. Animation.js
    api = 'api/'  // API
;}



################################################################
# Advanced settings (Usually no manual changes are required)  ##
# 進階設置 (通常不需要手動變更)                                 ##
###############################################################

# Local root path of project
# 本地端根目錄
define('Local', __DIR__.DIRECTORY_SEPARATOR);

# Domain
# 網域
define('Domain', isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ''));

# Protocol
# 協定
define('Protocol', isset($_SERVER['HTTP_X_FORWARDED_PROTO']) ? $_SERVER['HTTP_X_FORWARDED_PROTO'] : (!empty($_SERVER['HTTPS']) ? 'https' : 'http'));

# Client IP
define('ClientIP', $_SERVER["REMOTE_ADDR"]);

