<?php
/*
Simple:Press
Desc:
$LastChangedDate: 2012-03-26 10:30:25 -0700 (Mon, 26 Mar 2012) $
$Rev: 8234 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

# ==================================================================
#
#	CORE: This file is loaded at CORE
#	SP Error Handling and reporting
#
#	If the constant SPSHOWERRORS is set to true, non E_STRICT
#	errors will also echo to the screen. E_NOTICE 'undefined'
#	errors are curtrently ignored.
#
# ==================================================================

# ------------------------------------------------------------------
# sp_construct_database_error()
#
# Version: 5.0
# DATABASE ERROR MESSAGE CONSTRUCTOR
#
# Creates database error message and sends to error log function
#
#	$sql:		the original sql statement
#	$sqlerror:	the reported mysql error text
# ------------------------------------------------------------------
function sp_construct_database_error($sql, $sqlerror) {
	$mess = '';
	$trace = debug_backtrace();
	$traceitem = $trace[2];
	$mess.= 'file: '.$traceitem['file'].'<br />';
	$mess.= 'line: '.$traceitem['line'].'<br />';
	$mess.= 'function: '.$traceitem['function'].'<br />';
	$mess.= "error: $sqlerror<br /><br />";
	$mess.= $sql;

	# write to error log
	sp_write_error('database', $mess);

	# create display message
	include_once(SPAPI.'sp-api-cache.php');
	sp_notify(1, sp_text('Invalid database query'));
}

# ------------------------------------------------------------------
# sp_construct_php_error()
#
# Version: 5.0
# PHP ERROR MESSAGE CONSTRUCTOR (at least those catchable ones)
#
# Creates php error message and sends to error log function
#
#	$errno:		Error Type
#	$errstr:	Error message text
#	$errfile:	Error File
#	$errline:	Error Line Number in file
# ------------------------------------------------------------------
function sp_construct_php_error($errno, $errstr, $errfile, $errline) {
	global $SPPATHS;

	# only interested in SP errors
	$errfile = str_replace('\\','/',$errfile); # sanitize for Win32 installs
	$pos = strpos($errfile, '/plugins/simple-press/');
	$pos1 = strpos($errfile, $SPPATHS['plugins']);
	if ($pos === false && $pos1 === false) return false;

	# For now remove the 'undefined' (index/variable) notices
	if($errno == E_NOTICE && substr($errstr, 0, 9) == 'Undefined') return false;

	$errortype = array (
		E_ERROR				 => 'Error',
		E_WARNING			 => 'Warning',
		E_PARSE				 => 'Parsing Error',
		E_NOTICE			 => 'Notice',
		E_CORE_ERROR		 => 'Core Error',
		E_CORE_WARNING		 => 'Core Warning',
		E_COMPILE_ERROR		 => 'Compile Error',
		E_COMPILE_WARNING	 => 'Compile Warning',
		E_USER_ERROR		 => 'User Error',
		E_USER_WARNING		 => 'User Warning',
		E_USER_NOTICE		 => 'User Notice',
		E_STRICT			 => 'Runtime Notice',
		E_RECOVERABLE_ERROR	 => 'Catchable Fatal Error'
	);

	if ($errno==E_NOTICE || $errno==E_RECOVERABLE_ERROR || $errno==E_WARNING) {
		$mess = '';
		$trace = debug_backtrace();
		$traceitem = $trace[1];
		unset($trace);
		if ($traceitem['function'] == 'spHandleShutdown') $traceitem['function'] ='Unavailable';

		$mess.= 'file: '.substr($errfile, $pos+8, strlen($errfile)).'<br />';
		$mess.= "line: $errline<br />";
		$mess.= 'function: '.$traceitem['function'].'<br />';
		$mess.= $errortype[$errno].' | '.$errstr;

		# write out error to our toolbox log
		sp_write_error('php', $mess);

		# wrtie error out to php error log (its still supressed from the screen)
		error_log('PHP '.$errortype[$errno].':  '.$errstr, 0);

		# if we arent showing SP errors, dont let php error handler run
		if (!defined('SPSHOWERRORS') || SPSHOWERRORS == false) return true;
	}
	return false;
}

# ------------------------------------------------------------------
# spHandleShutdown()
#
# Version: 5.0
# FATAL (CRASH) ERROR RECORDING HANDLER
#
# Creates fatal error warning and passes to main error handler
# ------------------------------------------------------------------
register_shutdown_function('spHandleShutdown');
function spHandleShutdown() {
	$error = error_get_last();
	if ($error !== NULL) sp_construct_php_error($error['type'], $error['message'], $error['file'], $error['line']);
}

# ------------------------------------------------------------------
# sp_write_error()
#
# Version: 5.0
# ERROR RECORDING HANDLER
#
# Creates entry in table sferrorlog
#
#	$errortyoe:	'database'
#	$errortext:	pre-formatted error details
# ------------------------------------------------------------------
function sp_write_error($errortype, $errortext) {
	global $SPSTATUS, $sfvars;

	if ($SPSTATUS != 'ok') return;
	if(mysql_ping() == false) return;

	# checlk error log exists as it won't until installed.
	$success = spdb_select('var', "SHOW TABLES LIKE '".SFERRORLOG."'");
	if ($success == false) return;

	$now = "'".current_time('mysql')."'";
	$sql = 'INSERT INTO '.SFERRORLOG;
	$sql.= ' (error_date, error_type, error_text) ';
	$sql.= 'VALUES (';
	$sql.= $now.", ";
	$sql.= "'".$errortype."', ";
	$sql.= "'".esc_sql($errortext)."')";
	spdb_query($sql);

	# leave just last 50 entries
	if ($sfvars['insertid'] > 51) {
		$sql = 'DELETE FROM '.SFERRORLOG.' WHERE id < '.($sfvars['insertid']-50);
		spdb_query($sql);
	}
}

# ------------------------------------------------------------------
# sp_gis_error()
#
# Version: 5.0
# Handles GetImageSize calls and produces error in failure
# ------------------------------------------------------------------
function sp_gis_error($errno, $errstr, $errfile, $errline, $errcontext) {
	global $gis_error;
	if ($errno == E_WARNING || $errno == E_NOTICE) $gis_error = sp_text('Unable to validate image details');
}


?>