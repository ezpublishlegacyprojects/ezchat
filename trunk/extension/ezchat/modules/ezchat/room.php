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

// Module Init
require_once( "lib/ezutils/classes/ezhttptool.php" );
require_once( "lib/ezutils/classes/ezexecution.php" );
require_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
require_once( "lib/ezutils/classes/ezini.php" );

// Chat Init
//error_reporting(E_ALL);
define('AJAX_CHAT_PATH', dirname($_SERVER['SCRIPT_FILENAME'].'/').'/extension/ezchat/ajaxchat/');
define('EZ_AJAX_CHAT_PATH', dirname($_SERVER['SCRIPT_FILENAME'].'/').'/extension/ezchat/classes/');
require(AJAX_CHAT_PATH.'lib/classes.php');

// Init vars
$Module = $Params["Module"];
$ezchatIni = eZINI::instance( 'ezchat.ini' );
$Module->setTitle( $ezchatIni->variable( 'generalDefaults', 'ezchatRoomName' ) );
$Result = array();
$currentUser = eZUser::currentUser();
$http = eZHTTPTool::instance();
if ($Params['Channel']) $_REQUEST['channelName'] = $Params['Channel'];

// Overrides for room module rights
$limitations = $currentUser->hasAccessTo('ezchat','room');
if ($limitations["accessWord"] == "limited") {
	foreach ($limitations["policies"] as $policy) {
		if ($policy["OverrideConfig"] == NULL) $_REQUEST['overridePolicies'][] = 'Any';
		else foreach ($policy["OverrideConfig"] as $oc) $_REQUEST['overridePolicies'][] = $oc;
		if ($policy["OpeningHours"] == NULL) {
			$_REQUEST['overrideOpeningHour'] = 0;
			$_REQUEST['overrideClosingHour'] = 24;
		} else {
			$_REQUEST['overrideOpeningHour'] = 24;
			$_REQUEST['overrideClosingHour'] = 0;
			foreach ($policy["OpeningHours"] as $oc) {
				if ($oc < $_REQUEST['overrideOpeningHour']) $_REQUEST['overrideOpeningHour'] = $oc;
				if ($oc+1 > $_REQUEST['overrideClosingHour']) $_REQUEST['overrideClosingHour'] = $oc+1;
			}
		} // OpeningHours
		if ($policy["OpeningDays"] == NULL) $_REQUEST['overrideOpeningDays'][] = 'Any';
		else foreach ($policy["OpeningDays"] as $oc) $_REQUEST['overrideOpeningDays'][] = $oc;
	}
}

// Overrides for moderate module rights
$limitations = $currentUser->hasAccessTo('ezchat','moderate');
if ($limitations["accessWord"] == "yes") $_REQUEST['hasModeratorRole']=true;
$limitations = $currentUser->hasAccessTo('ezchat','administrate');
if ($limitations["accessWord"] == "yes") $_REQUEST['hasAdministratorRole']=true;

$_REQUEST['loginURL'] = '/ezchat/room';

// Cases of use
  // if not anonymous and not ?logout=true, auto login
	if ($currentUser->isAnonymous()==false && ($http->hasGetVariable('logout')==false ) ) {
		// retrieving main config
		$langDefault = null;
		require(EZ_AJAX_CHAT_PATH.'langMap.php');
		// send post vars to chat
		$_REQUEST['userName'] = $currentUser->attribute('login');
		$_REQUEST['password'] = 'fomodepass104326547865';
		$_REQUEST['lang'] = $langDefault;
	}
  // if logout=full, force logout from eZpublish
	if ($http->hasGetVariable('logout')==true && $http->getVariable('logout')=='full') {
		$currentUser->logoutCurrent();
		$http->setSessionVariable( 'force_logout', 1 );
		$redirectURL = $ezchatIni->variable( 'generalDefaults', 'eZlogoutRedirect' );
		return $Module->redirectTo( $redirectURL );
	}
  // else, display loggedOut.tpl

$_REQUEST['view_parameters'] = array();

// Calling main chat class
require_once(EZ_AJAX_CHAT_PATH.'EzAJAXChat.php');
$ajaxChat = new EzAJAXChat();
$Result['content'] = $ajaxChat->getXHTMLResult();
$Result['left_menu'] = "design:parts/ezchat/menu.tpl";
$Result['path'] = array( array( 'url' => '/ezchat/welcome',
                                'text' => ezi18n('kernel/navigationpart','eZchat','Navigation path' ) ),
						 array( 'url' => '/ezchat/room',
                                'text' => ezi18n('kernel/navigationpart','Chatroom','Navigation path' ) ) );



?>