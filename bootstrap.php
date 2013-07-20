<?php

// Turn off any output buffering
while( ob_get_level() ) ob_end_clean();

//// Initialize error handling ////

ini_set('display_errors','on');
ini_set('error_reporting', E_ALL|E_STRICT);

function coalesce( &$v, $default=null ) {
	return isset($v) ? $v : $default;
}

function dump_error_and_exit( $errno, $errstr, $errfile, $errline, $errcontext ) {
	if( !headers_sent() ) {
		header('Status: 500 Script Error');
		header('Content-Type: text/plain');
	}
	
	echo "Error code=$errno: $errstr\n";
	echo "at $errfile:$errline\n";
	echo "\n";
	echo "Backtrace:\n";
	foreach( debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS ) as $item ) {
		echo coalesce($item['file']), ':', $item['line'], "\n";
	}
	exit;
}

// I don't recommend the ErrorException approach mentioned
// on http://us2.php.net/manual/en/class.errorexception.php because
// 1) Errors (almost) always indicate a problem with your code that should be fixed, and
// 2) It can cause problems in contexts where there is no stack frame.
set_error_handler('dump_error_and_exit', E_ALL|E_STRICT);

//// Initialize autoloader ////

// Composer should set up an autoloader initialization script
// in vendor/autoload that will load both our lib/ classes
// and vendor/* classes.  If you aren't using composer for your
// project, this would be a good spot to initialize your own
// autoloader.
require 'vendor/autoload.php';

// I like to use this 'fat config object' pattern to instantiate
// singleton-ish components.  Nife doesn't care if you take this
// approach or not.
$config = new NifeDemo_Config( require 'settings.php' );
$dispatcher = $config->getComponent('Dispatcher');
$response = $dispatcher->handleRequest( $_SERVER['PATH_INFO'] );

Nife_Util::outputResponse( $response );
