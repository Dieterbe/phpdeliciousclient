<?php

/**
 * This file defines some settings for PhpDeliciousClient
 *
 * PHP versions 4 and 5
 *
 * @copyright		Copyright (c) 2007, Dieter Plaetinck
 * @link			http://dieter.plaetinck.be/php_delicious_client
 * @version			v0.5
 * @license			GPL v2. See the LICENSE file
 */

define('APP_VERSION', 0.5);
define('APP_STRING','PhpDeliciousClient v'.APP_VERSION);
ini_set('user_agent', APP_STRING.' (http://dieter.plaetinck.be/php_delicious_client)');

/*
 * debugging/logging levels:
 * 6 = also show internal data from del.icio.us (print_r's of tags and posts)
 * 5 = log everything, and i mean everything (including passwords)
 * 4 = like 5 but no passwords
 * 2 = give some additional infos like the received format when got bad format
 * 1 = only log important stuff (default for production maybe sometime..)
 * 0 = no debugging information at all
 */

$debug = 1;

$errors = array(	1 =>'Error: Connection failed or incorrect account',
					//TODO: all incorrect login errors should be error 2, not 1.
					2 =>'Error: Login failed: incorrect account',
					3 =>'Error: Throttled (Back off for a while now!)',
					4 =>'Error: Couldn\'t parse XML',
					5 =>'Error: Unknown error'
				);
?>