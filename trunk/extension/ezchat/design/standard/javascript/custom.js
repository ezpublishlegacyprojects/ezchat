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


// Overriding client side functionality:


	// Example - Overriding the replaceCustomCommands method:
ajaxChat.replaceCustomCommands = function(text, textParts) {
	switch(textParts[0]) {
		case '/map':
			return this.replaceCommandMap(textParts);
		default:
			return text;
	}
};

	// Override to add custom user menu items:
	// Return a string with list items ( <li>menuItem</li> )
	// encodedUserName contains the userName ready to be used for javascript links
	// userID is only available for the online users menu - not for the inline user menu
	// use (encodedUserName == this.encodedUserName) to check for the current user
ajaxChat.getCustomUserMenuItems = function(encodedUserName, userID) {
	return 	'<li><a href="javascript:ajaxChat.insertMessageWrapper(\'/map \');">'
			+ this.lang['userMenuMap']
			+ '</a></li>'
			+ '<li><a href="javascript:ezChat.callUserPicture(ajaxChat,'
			+ userID
			+ ');">'
			+ this.lang['callUserPicture']
			+ '</a></li>';
};


var ezChat = {

	ajaxChatReference: null,
	baseURL: null,
	userPictureLoaded: new Array(),

	callUserPicture: function(ajaxChat, userID) {
		this.ajaxChatReference = ajaxChat;
		if (this.userPictureLoaded[userID+"_"]==null) {
			var httpRequest = false;
	        if (window.XMLHttpRequest) { // Mozilla, Safari,...
	            httpRequest = new XMLHttpRequest();
	            if (httpRequest.overrideMimeType) {
	                httpRequest.overrideMimeType('text/xml');
	            }
	        }
	        else if (window.ActiveXObject) { // IE
	            try {
	                httpRequest = new ActiveXObject("Msxml2.XMLHTTP");
	            }
	            catch (e) {
	                try {
	                    httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
	                }
	                catch (e) {}
	            }
	        }
			httpRequest.onreadystatechange = function() { ezChat.showUserPicture(httpRequest, userID); };
	        httpRequest.open('GET', this.baseURL+'/ezchat/userpicture/(userid)/'+userID+'/', true);
	        httpRequest.send(null);
		}
	},

	showUserPicture: function(httpRequest, userID) {
		if (httpRequest.readyState == 4) {
            if (httpRequest.status == 200) {
				if (this.userPictureLoaded[userID+"_"]==null) {
            		this.userPictureLoaded[userID+"_"]="1";
            		this.updateDOM( 'ajaxChat_um_'+userID, httpRequest.responseText, true, false);
            	}
            } else {
            	alert("aie");
                // probleme, a traiter
            }
        }
	},

	updateDOM: function(id, str, prepend, overwrite) {
		var domNode = this.ajaxChatReference.dom[id] ? this.ajaxChatReference.dom[id] : document.getElementById(id);
		if(!domNode) {
			return;
		}
		try {
			// Test for validity before adding the string to the DOM:
			domNode.cloneNode(false).innerHTML = str;
			if(overwrite) {
				domNode.innerHTML = str;
			} else if(prepend) {
				// here we fix a ajaxchat bug...
				domNode.innerHTML = str + domNode.innerHTML;
			} else {
				domNode.innerHTML += str;
			}
		} catch(e) {
			this.ajaxChatReference.addChatBotMessageToChatList('/error DOMSyntax '+id);
		}
	}

}