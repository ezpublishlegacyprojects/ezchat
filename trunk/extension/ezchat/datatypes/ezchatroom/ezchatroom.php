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

class eZChatroom {

    function eZChatroom( $name )
    {
        $this->Name = $name;
        $this->Options = array();
    }

    function setName( $name )
    {
        $this->Name = $name;
    }

    function name()
    {
        return $this->Name;
    }

    function attributes()
    {
        return array( 'name',
                      'start_value',
                      'stop_value',
        			  'option_list',
        			  'chatroom',
        			  'shoutbox' );
    }

    function hasAttribute( $name )
    {
        return in_array( $name, $this->attributes() );
    }

    function attribute( $name )
    {
        switch ( $name )
        {
            case "name" :
            {
                return $this->Name;
            }break;
            case "start_value" :
            {
                return $this->StartValue;
            }break;
            case "stop_value" :
            {
                return $this->StopValue;
            }break;
            case "option_list" :
            {
                return $this->Options;
            }break;
            case "chatroom" :
            {
                return $this->displayChatroom();
            }break;
            case "shoutbox" :
            {
                return $this->displayChatroom(true);
            }break;
            default:
            {
                eZDebug::writeError( "Attribute '$name' does not exist", 'eZChatroom::attribute' );
                return null;
            }break;
        }
    }

    function addOption( $name, $value )
    {
        $this->Options[] = array( "name" => $name,
                                  "value" => $value );
    }

    function decodeXML( $xmlString )
    {
        $dom = new DOMDocument( '1.0', 'utf-8' );
        $success = $dom->loadXML( $xmlString );

        if ( $xmlString != "" )
        {
            $chatroomElement = $dom->documentElement;
            $this->setStartValue( $chatroomElement->getAttribute( 'start_value' ) );
            $this->setStopValue( $chatroomElement->getAttribute( 'stop_value' ) );
            $this->setName( $chatroomElement->getAttribute( 'name' ) );

            $option_listElement = $dom->getElementsByTagName( "option_list" )->item( 0 );
            $count = $option_listElement->getAttribute( 'count' );

            $this->Options = array();
            for ($i=0; $i < $count; $i++) {
				$optionElement = $dom->getElementsByTagName( "option" )->item( $i );
				$this->Options[] = array("name" => $optionElement->getAttribute( 'name' ), "value" => $optionElement->textContent);
            }
        }
        else
        {
            $this->StartValue = 0;
            $this->StopValue = 0;
            $this->Name = "";
            $this->Options = array();
        }
    }

    function xmlString( )
    {
        $doc = new DOMDocument( '1.0', 'utf-8' );

        $root = $doc->createElement( "ezchatroom" );
        $root->setAttribute( "start_value", $this->StartValue );
        $root->setAttribute( "stop_value", $this->StopValue );
        $root->setAttribute( "name", $this->Name );
        $doc->appendChild( $root );

        $option_list = $doc->createElement( "option_list" );
        $option_list->setAttribute( "count", count($this->Options) );
        foreach($this->Options as $key => $value) {
        	$opName = $value['name'];
        	$opValue = $value['value'];
        	$el = $doc->createElement( "option", $opValue );
        	$el->setAttribute( "name", $opName);
        	$option_list->appendChild( $el );
        }
        $root->appendChild( $option_list );

        $xml = $doc->saveXML();

        return $xml;
    }

    function setStartValue( $value )
    {
        $this->StartValue = $value;
    }

    function setStopValue( $value )
    {
        $this->StopValue = $value;
    }

	function setAllowPrivateChannels( $value )
    {
        $this->allowPrivateChannels = $value;
    }

	function setAllowUserMessageDelete( $value )
    {
        $this->allowUserMessageDelete = $value;
    }

    function setLoginURL( $value )
    {
    	$this->loginURL = $value;
    }

    function setViewParameters( $value )
    {
    	$this->view_parameters = $value;
    }

    function displayChatroom($shoutBoxMode = false) {
    	// calling chat
		require_once( "lib/ezutils/classes/ezhttptool.php" );
		require_once( "lib/ezutils/classes/ezexecution.php" );
		require_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
		require_once( "lib/ezutils/classes/ezini.php" );
		define('AJAX_CHAT_PATH', dirname($_SERVER['SCRIPT_FILENAME'].'/').'/extension/ezchat/ajaxchat/');
		define('EZ_AJAX_CHAT_PATH', dirname($_SERVER['SCRIPT_FILENAME'].'/').'/extension/ezchat/classes/');
		require(AJAX_CHAT_PATH.'lib/classes.php');

		// Init vars
		$ezchatIni =& eZINI::instance( 'ezchat.ini' );
		$currentUser = eZUser::currentUser();
		$_REQUEST['channelName'] = $this->name();

		// Overrides for room module rights
		$_REQUEST['overrideOpeningHour'] = $this->attribute('start_value');
		$_REQUEST['overrideClosingHour'] = $this->attribute('stop_value');
		$option_list = $this->attribute('option_list');
		$_REQUEST['overrideOpeningDays'] = explode("-", $option_list[3]['value']);

		// Overrides for moderate module rights
		$limitations = $currentUser->hasAccessTo('ezchat','moderate');
		if ($limitations["accessWord"] == "yes") $_REQUEST['hasModeratorRole']=true;
		$limitations = $currentUser->hasAccessTo('ezchat','administrate');
		if ($limitations["accessWord"] == "yes") $_REQUEST['hasAdministratorRole']=true;

		// additional overrides
		$_REQUEST['overridePolicies'] = array();
		$_REQUEST['overridePolicies']['allowPrivateChannels'] = $this->allowPrivateChannels;
		$_REQUEST['overridePolicies']['allowPrivateMessages'] = ($option_list[0]['value']==1);
		$_REQUEST['overridePolicies']['showChannelMessages'] = ($option_list[1]['value']==1);
		$_REQUEST['overridePolicies']['allowUserMessageDelete'] = $this->allowUserMessageDelete;
		$_REQUEST['overridePolicies']['requestMessagesPriorChannelEnter'] = ($option_list[2]['value']==1);
		$_REQUEST['overridePolicies']['deactivateGMaps'] = ($option_list[4]['value']==1);
		$_REQUEST['loginURL'] = $this->loginURL;

		// Cases of use
		  // if not anonymous and not ?logout=true, auto login
			if ($currentUser->isAnonymous()==false && (eZHTTPTool::hasGetVariable('logout')==false ) ) {
				// retrieving main config
				$langDefault = null;
				require(EZ_AJAX_CHAT_PATH.'langMap.php');
				// send post vars to chat
				$_REQUEST['userName'] = $currentUser->attribute('login');
				$_REQUEST['password'] = 'fomodepass104326547865';
				$_REQUEST['lang'] = $langDefault;
			}
		  // if logout=full, force logout from eZpublish
			if (eZHTTPTool::hasGetVariable('logout')==true && eZHTTPTool::getVariable('logout')=='full') {
				$http = eZHTTPTool::instance();
				$currentUser->logoutCurrent();
				$http->setSessionVariable( 'force_logout', 1 );
				$redirectURL = $ezchatIni->variable( 'generalDefaults', 'eZlogoutRedirect' );
			}
		  // else, display loggedOut.tpl

		$_REQUEST['view_parameters'] = $this->view_parameters;

		// Calling main chat class
		require_once(EZ_AJAX_CHAT_PATH.'EzAJAXChat.php');
		$ajaxChat = new EzAJAXChat();
		// shoutbox mode
		if ($shoutBoxMode == true) return $ajaxChat->getShoutBoxContent();
		// full screen mode
    	return $ajaxChat->getXHTMLResult();
    }


    /// Contains the Chatroom name
    public $Name;

    /// Contains the Options
    public $Options;

    /// Contains the option counter value
    public $StartValue;
    public $StopValue;

    public $allowPrivateChannels = false;
    public $allowUserMessageDelete = false;
    public $loginURL = '';
    public $view_parameters = array();
}

?>
