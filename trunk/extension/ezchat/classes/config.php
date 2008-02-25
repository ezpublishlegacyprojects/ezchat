<?php
//
// SOFTWARE NAME: eZchat
// SOFTWARE RELEASE: 0.1.x
// COPYRIGHT NOTICE: Copyright (C) 2008 Xavier GOULEY & Maxime THOMAS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
//

require_once( "lib/ezutils/classes/ezini.php" );
require_once( "lib/ezutils/classes/ezuri.php" );
require_once( "kernel/classes/datatypes/ezuser/ezuser.php" );

$siteIni =& eZINI::instance( 'site.ini' );
$ezchatIni =& eZINI::instance( 'ezchat.ini' );

// computing channels
$config['channels'] = array();
$chatFetcher = new eZChatroomFunctionCollection();
$iniChannels = $ezchatIni->variableArray( 'generalDefaults', 'channels' );
foreach($iniChannels as $channel) $config['channels'][] = $channel[0];
$ezchannels =& $chatFetcher->fetchChatroomList();
foreach($ezchannels['result'] as $ezchannel)
	$config['channels'][$ezchannel['object_id']] = $ezchannel['chatroom']->name();

// computing users
$currentUser = eZUser::currentUser();
$config['users'] = array();
$config['users'][0] = array();
$config['users'][0]['userRole'] = AJAX_CHAT_GUEST;
$config['users'][0]['userName'] = null;
$config['users'][0]['password'] = null;
$config['users'][0]['channels'] = array();
foreach ($config['channels'] as $key => $channel) $config['users'][0]['channels'][] = $key;
if (!$currentUser->isAnonymous()) {
	$userID = $currentUser->currentUserID();
	$config['users'][$userID] = array();

	$config['users'][$userID]['userName'] = $currentUser->attribute('login');
	$config['users'][$userID]['password'] = 'fomodepass104326547865';

	$config['users'][$userID]['channels'] = array();
	foreach ($config['channels'] as $key => $channel) {
		$config['users'][$userID]['channels'][] = $key;
	}

	if ($_REQUEST['hasAdministratorRole']) $config['users'][$userID]['userRole'] = AJAX_CHAT_ADMIN;
	elseif ($_REQUEST['hasModeratorRole']) $config['users'][$userID]['userRole'] = AJAX_CHAT_MODERATOR;
	else $config['users'][$userID]['userRole'] = AJAX_CHAT_USER;
}


// Database connection values:
$config['dbConnection'] = array();
// Database hostname:
if ( $ezchatIni->hasVariable( 'dbConnection', 'host' ) ) $config['dbConnection']['host'] = $ezchatIni->variable( 'dbConnection', 'host' );
else $config['dbConnection']['host'] = $siteIni->variable( 'DatabaseSettings', 'Server' );
// Database username:
if ( $ezchatIni->hasVariable( 'dbConnection', 'user' ) ) $config['dbConnection']['user'] = $ezchatIni->variable( 'dbConnection', 'user' );
else $config['dbConnection']['user'] = $siteIni->variable( 'DatabaseSettings', 'User' );
// Database password:
if ( $ezchatIni->hasVariable( 'dbConnection', 'pass' ) ) $config['dbConnection']['pass'] = $ezchatIni->variable( 'dbConnection', 'pass' );
else $config['dbConnection']['pass'] = $siteIni->variable( 'DatabaseSettings', 'Password' );
// Database name:
if ( $ezchatIni->hasVariable( 'dbConnection', 'name' ) ) $config['dbConnection']['name'] = $ezchatIni->variable( 'dbConnection', 'name' );
else $config['dbConnection']['name'] = $siteIni->variable( 'DatabaseSettings', 'Database' );
// Database type:
if ( $ezchatIni->hasVariable( 'dbConnection', 'type' ) ) $config['dbConnection']['type'] = $ezchatIni->variable( 'dbConnection', 'type' );
else $config['dbConnection']['type'] = null;
// Database link:
$config['dbConnection']['link'] = null;


// Default language:
$langDefault = null;
require(EZ_AJAX_CHAT_PATH.'langMap.php');
$config['langDefault'] =& $langDefault;

$config['styleDefault'] = 'beige';
$config['sessionName'] = 'ez_ajax_chat';
$config['sessionKeyPrefix'] = 'eZajaxChat';
$config['forceAutoLogin'] = false;
$config['allowGuestUserName'] = true;
$config['allowNickChange'] = false;
$config['allowGuestLogins'] = true;
$config['allowGuestWrite'] = true;

// Default Channel
$config['defaultChannelID'] = 0;
$config['defaultChannelName'] = 'Public';
if ( $ezchatIni->hasVariable( 'generalDefaults', 'channelDefault' ) ) {
	$config['defaultChannelID'] = $ezchatIni->variable( 'generalDefaults', 'channelDefault' );
	$channels = $ezchatIni->variableArray( 'generalDefaults', 'channels' );
	$config['defaultChannelName'] = $channels[$config['defaultChannelID']];
}

// Enable/Disable private Channels:
$config['allowPrivateChannels'] = true;
if (	$_REQUEST['overridePolicies'] &&
		!in_array('Any', $_REQUEST['overridePolicies']) &&
		!in_array('allowPrivateChannels', $_REQUEST['overridePolicies'])
	) $config['allowPrivateChannels'] = false;

// Enable/Disable private Messages:
$config['allowPrivateMessages'] = true;
if (	$_REQUEST['overridePolicies'] &&
		!in_array('Any', $_REQUEST['overridePolicies']) &&
		!in_array('allowPrivateMessages', $_REQUEST['overridePolicies'])
	) $config['allowPrivateMessages'] = false;

// Defines if login/logout and channel enter/leave are displayed:
$config['showChannelMessages'] = true;
if (	$_REQUEST['overridePolicies'] &&
		!in_array('Any', $_REQUEST['overridePolicies']) &&
		!in_array('showChannelMessages', $_REQUEST['overridePolicies'])
	) $config['showChannelMessages'] = false;

// Defines the hour of the day the chat is opened (0 - closingHour):
$config['openingHour'] = 0;
if ( $_REQUEST['overrideOpeningHour'] ) $config['openingHour'] = $_REQUEST['overrideOpeningHour'];
// Defines the hour of the day the chat is closed (openingHour - 24):
$config['closingHour'] = 24;
if ( $_REQUEST['overrideClosingHour'] ) $config['closingHour'] = $_REQUEST['overrideClosingHour'];
// Defines the weekdays the chat is opened (0=Sunday to 6=Saturday):
$config['openingWeekDays'] = array(0,1,2,3,4,5,6);
if ( $_REQUEST['overrideOpeningDays'] ) $config['openingWeekDays'] = $_REQUEST['overrideOpeningDays'];
if ( $config['openingWeekDays'][0] == "Any" ) $config['openingWeekDays'] = array(0,1,2,3,4,5,6);

// Allow/Disallow registered users to delete their own messages:
$config['allowUserMessageDelete'] = true;
if (	$_REQUEST['overridePolicies'] &&
		!in_array('Any', $_REQUEST['overridePolicies']) &&
		!in_array('allowUserMessageDelete', $_REQUEST['overridePolicies'])
	) $config['allowUserMessageDelete'] = false;

// The userName used for ChatBot messages
if ( $ezchatIni->hasVariable( 'generalDefaults', 'chatBotName' ) ) $config['chatBotName'] = $ezchatIni->variable( 'generalDefaults', 'chatBotName' );
else $config['chatBotName'] = 'ChatBot';

// Minutes until a user is declared inactive (last status update) - the minimum is 2 minutes:
if ( $ezchatIni->hasVariable( 'generalDefaults', 'inactiveTimeout' ) ) $config['inactiveTimeout'] = $ezchatIni->variable( 'generalDefaults', 'inactiveTimeout' );
else $config['inactiveTimeout'] = 2;
// Interval in minutes to check for inactive users:
if ( $ezchatIni->hasVariable( 'generalDefaults', 'inactiveCheckInterval' ) ) $config['inactiveCheckInterval'] = $ezchatIni->variable( 'generalDefaults', 'inactiveCheckInterval' );
else $config['inactiveCheckInterval'] = 3;

// Defines if messages are shown which have been sent before the user entered the channel:
$config['requestMessagesPriorChannelEnter'] = true;
if (	$_REQUEST['overridePolicies'] &&
		!in_array('Any', $_REQUEST['overridePolicies']) &&
		!in_array('requestMessagesPriorChannelEnter', $_REQUEST['overridePolicies'])
	) $config['requestMessagesPriorChannelEnter'] = false;
//TODO: Si le channel est privé, alors false automatique serait mieux, non ?

// Max time difference in hours for messages to display on each request:
if ( $ezchatIni->hasVariable( 'generalDefaults', 'requestMessagesTimeDiff' ) ) $config['requestMessagesTimeDiff'] = $ezchatIni->variable( 'generalDefaults', 'requestMessagesTimeDiff' );
else $config['requestMessagesTimeDiff'] = 24;
// Max number of messages to display on each request:
if ( $ezchatIni->hasVariable( 'generalDefaults', 'requestMessagesLimit' ) ) $config['requestMessagesLimit'] = $ezchatIni->variable( 'generalDefaults', 'requestMessagesLimit' );
else $config['requestMessagesLimit'] = 10;

// Max users in chat (does not affect moderators or admins):
if ( $ezchatIni->hasVariable( 'generalDefaults', 'maxUsersLoggedIn' ) ) $config['maxUsersLoggedIn'] = $ezchatIni->variable( 'generalDefaults', 'maxUsersLoggedIn' );
else $config['maxUsersLoggedIn'] = 100;
// Max userName length:
if ( $ezchatIni->hasVariable( 'generalDefaults', 'userNameMaxLength' ) ) $config['userNameMaxLength'] = $ezchatIni->variable( 'generalDefaults', 'userNameMaxLength' );
else $config['userNameMaxLength'] = 16; //TODO: verifier : si le login eZ est plus long, quel resultat ?
// Max messageText length:
if ( $ezchatIni->hasVariable( 'generalDefaults', 'messageTextMaxLength' ) ) $config['messageTextMaxLength'] = $ezchatIni->variable( 'generalDefaults', 'messageTextMaxLength' );
else $config['messageTextMaxLength'] = 1040;
// Defines the max number of messages a user may send per minute:
if ( $ezchatIni->hasVariable( 'generalDefaults', 'maxMessageRate' ) ) $config['maxMessageRate'] = $ezchatIni->variable( 'generalDefaults', 'maxMessageRate' );
else $config['maxMessageRate'] = 20;

// Defines the default time in minutes a user gets banned if kicked from a moderator without ban minutes parameter:
if ( $ezchatIni->hasVariable( 'generalDefaults', 'defaultBanTime' ) ) $config['defaultBanTime'] = $ezchatIni->variable( 'generalDefaults', 'defaultBanTime' );
else $config['defaultBanTime'] = 30;

// Argument that is given to the handleLogout JavaScript method:
if (sizeOf($_REQUEST['loginURL']) > 0 ) $uri = $_REQUEST['loginURL'].'?logout=true';
elseif ( $ezchatIni->hasVariable( 'generalDefaults', 'logoutData' ) ) $uri = $ezchatIni->variable( 'generalDefaults', 'logoutData' );
else $uri = '/ezchat/room?logout=true';
ezURI::transformURI($uri);
$config['logoutData'] = $uri;

// Defines the earliest year used for the logs selection:
if ( $ezchatIni->hasVariable( 'logsDefaults', 'logsFirstYear' ) ) $config['logsFirstYear'] = $ezchatIni->variable( 'logsDefaults', 'logsFirstYear' );
else $config['logsFirstYear'] = 2008;

// Defines if old messages are purged from the database:
if ( $ezchatIni->hasVariable( 'logsDefaults', 'logsFirstYear' ) ) $config['logsPurgeLogs'] = ($ezchatIni->variable( 'logsDefaults', 'logsFirstYear' )=="enabled");
else $config['logsPurgeLogs'] = false;
// Max time difference in days for old messages before they are purged from the database:
if ( $ezchatIni->hasVariable( 'logsDefaults', 'logsPurgeTimeDiff' ) ) $config['logsPurgeTimeDiff'] = $ezchatIni->variable( 'logsDefaults', 'logsPurgeTimeDiff' );
else $config['logsPurgeTimeDiff'] = 32;

// Defines if registered users (including moderators) have access to the logs (admins are always granted access):
if ( $ezchatIni->hasVariable( 'logsDefaults', 'logsUserAccess' ) ) $config['logsUserAccess'] = ($ezchatIni->variable( 'logsDefaults', 'logsUserAccess' )=="enabled");
else $config['logsUserAccess'] = false;

// Defines if the socket server is enabled:
if ( $ezchatIni->hasVariable( 'serverConnection', 'socketServerEnabled' ) ) $config['socketServerEnabled'] = ($ezchatIni->variable( 'serverConnection', 'socketServerEnabled' )=="enabled");
else $config['socketServerEnabled'] = false;
// Defines the port of the socket server:
if ( $ezchatIni->hasVariable( 'serverConnection', 'socketServerPort' ) ) $config['socketServerPort'] = $ezchatIni->variable( 'serverConnection', 'socketServerPort' );
else $config['socketServerPort'] = 1935;
// This ID can be used to distinguish between different chat installations using the same socket server:
$config['socketServerChatID'] = 0;
?>