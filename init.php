<?php
/****************************************************************/
# Read Me
# - The project requires php8 version (or higher).
#
# - Do NOT output anything in this page.
#
/****************************************************************/
# Initialize
date_default_timezone_set('Asia/Taipei');
define('INIT', true);
require('config.php');

/****************************************************************/
# Init
if(!INIT){ die(MSG_MAINTAIN); }
# Debug mode will display all errors
if(DEBUG){ ini_set('display_errors',1); error_reporting(E_ALL); } else{ ini_set('display_errors',0); error_reporting(0); }
# Check libs
foreach (Libraries as $library) 
	if(!extension_loaded($library))
        if(DEV) die("Library '{$library}' not even be installed.");

/****************************************************************/
# Init
# Classes
$filenames = glob(Local.Path::clas.Path::init."*.php");
# Functions
array_push($filenames, ...glob(Local.Path::func.Path::init."*.php"));
# Loading
foreach($filenames as $filename){ require($filename); }

/****************************************************************/

