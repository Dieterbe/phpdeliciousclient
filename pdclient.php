#!/usr/bin/php
<?php

/**
 * This is the main executable for PhpDeliciousClient
 *
 * PHP versions 4 and 5
 *
 * @copyright		Copyright (c) 2007, Dieter Plaetinck
 * @link			http://dieter.plaetinck.be/php_delicious_client
 * @version			v0.5
 * @license			GPL v2. See the LICENSE file
 */

startup();
require_once('php-delicious/php-delicious.inc.php');
require_once('enhanced-php-delicious.inc.php');
require_once('pdlib.inc.php');
require_once('pdsettings.inc.php');

$pd = null;
$username = false;
$password = false;

if($argc > 1 && in_array($argv[1], array('--help', '-h', '--info', '-i', '--commands', '-c', '-?'))){
	helpMode();
}

if($argc > 2 && in_array($argv[1], array('--user', '-u'))){
	$username = $argv[2];
}

if($argc > 3 && in_array($argv[3], array('--password', '-p'))){
	if($argc > 4) $password = $argv[4];
	else setAccount(true);
}

echo " ---- ".APP_STRING." ---- \n";

while(true)
{
    if(!$pd){
    	debug('Don\'t forget to setup your account!',2);
    	// won't work with invalid account but we need some sort of pd object to prevent breakage
    	$pd = new EnhancedPhpDelicious($username, $password);
    }
    echo ">> ";

    switch(strtolower(trim(fgets(STDIN,256))))
   {
            case 'a':	setAccount();
            			break;
            case 't':	viewTag();
            			break;
            case 'ts':	viewTags();
            			break;
            case 'p':	viewPost();
						break;
			case 'ps':	viewPosts();
						break;
			case 'lu':	lastUpdate();
						break;
			case 'h':	showHelp();
						break;
			case 'q':
            case 'x':	shutdown();
            default:	debug('Unrecognized command',1); break;
        }
        if($pd && $pd->LastErrorNo()){
        	debug("last error: ".$errors[$pd->LastErrorNo()],4);
        }
}

function setAccount($onlypass = false){
	global $username;
	global $password;
	global $pd;

	if(!$onlypass){
	$usr = input('Username',false,$username,false);
	if($usr) $username = $usr;
	}
	//TODO: don't show password on screen!
	if($password)	$pass = input('Password',false,'type enter to keep previous one',false);
	else 			$pass = input('Password',false,null,false);
	if($pass) $password = $pass;

	if(!$pd) $pd = new EnhancedPhpDelicious($username, $password);
	else{
		$pd->sUsername = $username;
		$pd->sPassword = $password;
	}

	debug("Username: $username ",1);
	debug("Password: $password ",5);
}

function viewTag(){
	//FIXME: what would happen if non-existing tag is entered? associated posts? ..?
	global $pd;
	$tag = input('Name of tag');
	if($tag){
		$tag = $pd->getTag($tag, true);
		showTag($tag, true, true, true);
	}
	else echo "No tag given. \n";
}

function viewTags(){
	global $pd;
	$ll = input('Lowerlimit');
	$ul = input('Upperlimit');
	$hl = input('Hard limit');
	echo ("[S]how list or loop through list to [e]dit each tag?");
	$do = input();
	echo ("Show associated posts? [y/n]: ");
	$posts = input(null,true,false,true);
	$tags = $pd->GetAllTags($posts);

	if($do=='s'){
		showTags($tags, true, $posts, false, $ll, $ul, $hl, true);
	}
	if($do=='e'){
		showTags($tags, true, $posts, true, $ll, $ul, $hl, true);
	}
}

function viewPost(){
	global $pd;
	//FIXME: multiple tags works?
	//FIXME: url filtering doesn't work
	$tag = input('Tag');
	//$url = input('Part of the url');
	$url = null;
	$posts = $pd->GetAllPosts($tag,'',$url);
	if(isset($posts[0])){
		echo "Showing first hit from ".sizeof($posts)." hits\n";
		showPosts($posts[0]);
	}
	else echo "No post found\n";
}

function viewPosts(){
	global $pd;
	$posts = $pd->GetAllPosts();
	showPosts($posts);
}

function lastUpdate(){
	global $pd;
	$date = $pd->GetLastUpdate(); // TODO: wrong timezone or something: date is 2hrs to early
	echo "Last update of you del.icio.us posts/tags/bundles/... : $date \n";
}

function helpMode(){
	echo "This is ".APP_STRING."\n";
	showHelp();
	shutdown();
}

function showHelp(){
	echo " === Commands === \n"; 	//TODO: add/edit posts (edit url, name, desc, add/remove/rename tags,...)
	echo "[a]   Account setup\n";
	echo "[t]   View Tag\n";
	echo "[ts]  View Tags\n";
	echo "[p]   View Post\n";
	echo "[ps]  View Posts\n";
	echo "[lu]  Date of last update\n";
	echo "[h]   Help\n";
	echo "[x/q] Exit\n";
	echo "For more information see http://dieter.plaetinck.be/php-delicious-client\n";
}

function startup(){
	set_time_limit(0);
	ini_set('track_errors', TRUE);
	ini_set('html_errors', FALSE);
	ini_set('magic_quotes_runtime', FALSE);

	if (version_compare(phpversion(), '4.3.0', '<') || php_sapi_name() == 'cgi') {

	   @ob_end_flush();
	   ob_implicit_flush(TRUE);

	   define('STDIN', fopen('php://stdin', 'r'));
	   define('STDOUT', fopen('php://stdout', 'w'));
	   define('STDERR', fopen('php://stderr', 'w'));

	   register_shutdown_function(
	       create_function('',
	       'fclose(STDIN); fclose(STDOUT); fclose(STDERR); return true;')
	       );
	}
}

function shutdown(){
	fclose(STDIN);
	exit();
}
?>