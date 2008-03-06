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

class EzAJAXChat extends CustomAJAXChat {

	var $_xhtmlResult;

	/*function initialize() {
		// Initialize configuration settings:
		$this->initConfig();
	}*/

	function initConfig() {
		$config = null;
		require(AJAX_CHAT_PATH.'lib/config.php');
		require(EZ_AJAX_CHAT_PATH.'config.php');
		$this->_config =& $config;
	}

	function getCustomUsers() {
		// List containing the registered chat users:
		return $this->_config['users'];
	}

	function getCustomChannels() {
		// List containing the custom channels:
		return $this->_config['channels'];
	}

	function parseCustomCommands($text, $textParts) {
		switch($textParts[0]) {

			// Map for Google Maps objects:
			case '/map':
				$this->insertParsedMessageMap($textParts);
				break;

			default:
				return false;
		} // switch
		return true;
	}

	function insertParsedMessageMap($textParts) {
		if(count($textParts) < 4) {
			$this->insertChatBotMessage(
				$this->getPrivateMessageID(),
				'/error CommandNotAllowed'.$textParts[0]
			);
		} else {
			$this->insertCustomMessage(
				$this->getUserID(),
				$this->getUserName(),
				$this->getUserRole(),
				$this->getChannel(),
				implode(' ', $textParts)
			);
		}
	}

	function sendXHTMLContent() {
		require_once(EZ_AJAX_CHAT_PATH.'EzAJAXChatTemplate.php');
		$httpHeader = new AJAXChatHTTPHeader($this->getConfig('contentEncoding'), $this->getConfig('contentType'));
		$template = new EzAJAXChatTemplate($this, $this->getTemplateFileName(), $httpHeader->getContentType());
		// Send parsed template content:
		$this->_xhtmlResult = $template->getParsedContent();
	}

	function getTemplateFileName() {
		if($this->isLoggedIn()) {
			switch($this->getView()) {
				case 'logs':
					return 'ezchat/logs.tpl';
					break;
				default:
					return 'ezchat/loggedIn.tpl';
			}
		} else {
			return 'ezchat/loggedOut.tpl';
		}
	}

	function sendXMLMessages() {
		$httpHeader = new AJAXChatHTTPHeader('UTF-8', 'text/xml');
		$httpHeader->send();
		echo $this->getXMLMessages();
		eZExecution::cleanExit(); // go out of eZ module process.
	}

	function getXHTMLResult() {
		return $this->_xhtmlResult;
	}

	function getShoutBoxContent() {
		require_once(EZ_AJAX_CHAT_PATH.'EzAJAXChatTemplate.php');
		$template = new EzAJAXChatTemplate($this, 'ezchat/shoutbox.tpl');

		// Return parsed template content:
		return $template->getParsedContent();
	}

}
?>