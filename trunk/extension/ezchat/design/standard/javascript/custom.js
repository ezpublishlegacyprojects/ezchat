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


	// Example - Overriding the replaceCustomCommands method:
ajaxChat.replaceCustomCommands = function(text, textParts) {
	switch(textParts[0]) {
		case '/map':
			return ezChat.replaceCommandMap(textParts);
		default:
			return text;
	}
};

	// Override to add custom user menu items:
ajaxChat.getCustomUserMenuItems = function(encodedUserName, userID) {
	if (userID >= 400000000) return '';
	return 	'<li><a href="javascript:ezChat.callUserPicture(ajaxChat,'
			+ userID
			+ ');">'
			+ this.lang['callUserPicture']
			+ '</a></li>';
};


var ezChat = {

	ajaxChatReference: null,
	baseURL: null,
	userPictureLoaded: new Array(),
	strMapSent: '<a href="javascript:gMapChat.addYourPOI(new GLatLng(%a, %b), \'%c\');">%c</a>',

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
            		this.ajaxChatReference.updateDOM( 'ajaxChat_um_'+userID, httpRequest.responseText, true, false);
            	}
            } else {
            	//alert("bwaaaaah !");
                //FIXME: why this should happen ? :)
            }
        }
	},

	replaceCommandMap: function(textParts) {
		return	'<span class="chatBotMessage">'
				+ this.strMapSent.replace(/%s/, textParts[1])
								.replace(/%a/, textParts[2])
								.replace(/%b/, textParts[3])
								.replace(/%c/, decodeURIComponent(textParts[4])).replace(/%c/, decodeURIComponent(textParts[4]))
				+ '</span>';
	}

};

var gMapChat = {

	map: null,
	imagesRoot: null,
	myPOIs: new Array(),
	strEdit: "",
	strRemove: "",
	strToggleVisibility: "",
	strSend: "",
	strSendDirectly: "",
	strCopyFromSearch: "",
	strEnterTitle: "",
	strChangeTitle: "",

	addMyPOI: function(center) {
		text = window.prompt(this.strEnterTitle,"New Point")
		myPOIsIndex = this.myPOIs.length;
		domNode = document.getElementById('mypois_list').insertRow(-1);
		newCell = domNode.insertCell(-1);
		newCell.innerHTML =  "<div style='width:100px;overflow:hidden;'><b>&#9733;</b>&nbsp;<a onmouseover=\"gMapChat.myPOIs["+myPOIsIndex+"].setImage(gMapChat.imagesRoot+'stars/green_marker.png');\" "
							+"onmouseout=\"gMapChat.myPOIs["+myPOIsIndex+"].setImage(gMapChat.imagesRoot+'stars/blue_marker.png');\" "
							+"href=\"javascript:gMapChat.map.panTo(gMapChat.myPOIs["+myPOIsIndex+"].getPoint());\" >"
							+text
							+"</a></div>";
		newCell = domNode.insertCell(-1);
		newCell.innerHTML =  "<a href='#' onclick=\"gMapChat.sendMyPOI("+myPOIsIndex+", this.parentNode.parentNode);\" title=\""+this.strSend+"\" >"
							+"<img src='"+this.imagesRoot+"send.gif' width='16' height='16' border='0' />"
							+"</a>";
		newCell = domNode.insertCell(-1);
		newCell.innerHTML =  "<a href='#' onclick=\"gMapChat.removeMyPOI("+myPOIsIndex+", this.parentNode.parentNode);\" title=\""+this.strRemove+"\" >"
							+"<img src='"+this.imagesRoot+"delete.gif' width='16' height='16' border='0' />"
							+"</a>";
		newCell = domNode.insertCell(-1);
		newCell.innerHTML =  "<a href='#' onclick=\"gMapChat.editMyPOI("+myPOIsIndex+", this.parentNode.parentNode);\" title=\""+this.strEdit+"\" >"
							+"<img src='"+this.imagesRoot+"edit.gif' width='16' height='16' border='0' />"
							+"</a>";
		newCell = domNode.insertCell(-1);
		newCell.innerHTML =  "<input type='checkbox' checked='checked' onclick=\"(this.checked)?gMapChat.myPOIs["+myPOIsIndex+"].show():gMapChat.myPOIs["+myPOIsIndex+"].hide();\" title=\""+this.strToggleVisibility+"\" />";

		this.addMyPOIWithText(center, text);
	},

	addPOIFromSearch: function(lat, lng, text) {
		text = window.prompt(this.strChangeTitle,text)
		myPOIsIndex = this.myPOIs.length;
		domNode = document.getElementById('mypois_list');
		domNode = document.getElementById('mypois_list').insertRow(-1);
		newCell = domNode.insertCell(-1);
		newCell.innerHTML =  "<div style='width:100px;overflow:hidden;'><b>&#9733;</b>&nbsp;<a onmouseover=\"gMapChat.myPOIs["+myPOIsIndex+"].setImage(gMapChat.imagesRoot+'stars/green_marker.png');\" "
							+"onmouseout=\"gMapChat.myPOIs["+myPOIsIndex+"].setImage(gMapChat.imagesRoot+'stars/blue_marker.png');\" "
							+"href=\"javascript:gMapChat.map.panTo(gMapChat.myPOIs["+myPOIsIndex+"].getPoint());\" >"
							+text
							+"</a></div>";
		newCell = domNode.insertCell(-1);
		newCell.innerHTML =  "<a href='#' onclick=\"gMapChat.sendMyPOI("+myPOIsIndex+", this.parentNode.parentNode);\" title=\""+this.strSend+"\" >"
							+"<img src='"+this.imagesRoot+"send.gif' width='16' height='16' border='0' />"
							+"</a>";
		newCell = domNode.insertCell(-1);
		newCell.innerHTML =  "<a href='#' onclick=\"gMapChat.removeMyPOI("+myPOIsIndex+", this.parentNode.parentNode);\" title=\""+this.strRemove+"\" >"
							+"<img src='"+this.imagesRoot+"delete.gif' width='16' height='16' border='0' />"
							+"</a>";
		newCell = domNode.insertCell(-1);
		newCell.innerHTML =  "<a href='#' onclick=\"gMapChat.editMyPOI("+myPOIsIndex+", this.parentNode.parentNode);\" title=\""+this.strEdit+"\" >"
							+"<img src='"+this.imagesRoot+"edit.gif' width='16' height='16' border='0' />"
							+"</a>";
		newCell = domNode.insertCell(-1);
		newCell.innerHTML =  "<input type='checkbox' checked='checked' onclick=\"(this.checked)?gMapChat.myPOIs["+myPOIsIndex+"].show():gMapChat.myPOIs["+myPOIsIndex+"].hide();\" title=\""+this.strToggleVisibility+"\" />";

		this.addMyPOIWithText(new GLatLng(lat,lng),text);
		this.map.setCenter(new GLatLng(lat,lng));
	},

	addMyPOIWithText: function(center, text) {
		myPOIsIndex = this.myPOIs.length;
		var options = {
			title: text,
			icon: this.buildSmallIcon("stars/blue_marker.png"),
			draggable: true,
			dragCrossMove: true
		};
		var marker = new GMarker(center, options);
		this.myPOIs[myPOIsIndex] = marker;
		GEvent.addListener(marker, 'mouseover', function() {
			this.setImage(gMapChat.imagesRoot+'/stars/green_marker.png');
		});
		GEvent.addListener(marker, 'mouseout', function() {
			this.setImage(gMapChat.imagesRoot+'/stars/blue_marker.png');
		});
		this.map.addOverlay(marker);
	},

	addYourPOI: function(center, text) {
		myPOIsIndex = this.myPOIs.length;
		domNode = document.getElementById('mypois_list');
		domNode = document.getElementById('mypois_list').insertRow(-1);
		newCell = domNode.insertCell(-1);
		newCell.innerHTML =  "<div style='width:100px;overflow:hidden;'><b>&#8226;</b>&nbsp;<a onmouseover=\"gMapChat.myPOIs["+myPOIsIndex+"].setImage(gMapChat.imagesRoot+'colors/yellow_marker.png');\" "
							+"onmouseout=\"gMapChat.myPOIs["+myPOIsIndex+"].setImage(gMapChat.imagesRoot+'colors/orange_marker.png');\" "
							+"href=\"javascript:gMapChat.map.panTo(gMapChat.myPOIs["+myPOIsIndex+"].getPoint());\" >"
							+text
							+"</a></div>";
		newCell = domNode.insertCell(-1);
		newCell.innerHTML =  "<img src='"+this.imagesRoot+"send.gif' width='16' height='16' border='0' />";
		newCell = domNode.insertCell(-1);
		newCell.innerHTML =  "<a href='#' onclick=\"gMapChat.removeMyPOI("+myPOIsIndex+", this.parentNode.parentNode);\" title=\""+this.strRemove+"\" >"
							+"<img src='"+this.imagesRoot+"delete.gif' width='16' height='16' border='0' />"
							+"</a>";
		newCell = domNode.insertCell(-1);
		newCell.innerHTML =  "<a href='#' onclick=\"gMapChat.editMyPOI("+myPOIsIndex+", this.parentNode.parentNode);\" title=\""+this.strEdit+"\" >"
							+"<img src='"+this.imagesRoot+"edit.gif' width='16' height='16' border='0' />"
							+"</a>";
		newCell = domNode.insertCell(-1);
		newCell.innerHTML =  "<input type='checkbox' checked='checked' onclick=\"(this.checked)?gMapChat.myPOIs["+myPOIsIndex+"].show():gMapChat.myPOIs["+myPOIsIndex+"].hide();\" title=\""+this.strToggleVisibility+"\" />";

		var options = {
			title: text,
			icon: this.buildSmallIcon("colors/orange_marker.png"),
			draggable: false,
			dragCrossMove: true
		};
		var marker = new GMarker(center, options);
		this.myPOIs[myPOIsIndex] = marker;
		GEvent.addListener(marker, 'mouseover', function() {
			this.setImage(gMapChat.imagesRoot+'/colors/yellow_marker.png');
		});
		GEvent.addListener(marker, 'mouseout', function() {
			this.setImage(gMapChat.imagesRoot+'/colors/orange_marker.png');
		});
		this.map.addOverlay(marker);
		this.map.setCenter(center);
	},

	buildSmallIcon: function(iconImage) {
		var iconBlue = new GIcon();
		iconBlue.image = this.imagesRoot+iconImage;
		iconBlue.shadow = this.imagesRoot+'shadow50.png';
		iconBlue.iconSize = new GSize(20, 34);
		iconBlue.shadowSize = new GSize(37, 34);
		iconBlue.iconAnchor = new GPoint(9, 34);
		iconBlue.infoWindowAnchor = new GPoint(7, 1);
		return iconBlue;
	},

	removeMyPOI: function(index, elementToRemove) {
		marker = this.myPOIs[index];
		this.map.removeOverlay(marker);
		this.myPOIs[index] = -1;
		if (elementToRemove != null) {
			domNode = document.getElementById('mypois_list');
			domNode.removeChild(elementToRemove);
		}
	},

	editMyPOI: function(index, elementToRemove) {
		text = window.prompt(this.strChangeTitle,this.myPOIs[index].getTitle());
		center = this.myPOIs[index].getPoint();
		this.removeMyPOI(index, null);
		myPOIsIndex = this.myPOIs.length;
		domNode = document.createElement("TR");
		try {
			elementToRemove.replaceNode(domNode);
			newCell = domNode.insertCell(-1);
			newCell.innerHTML =  "<div style='width:100px;overflow:hidden;'><b>&#9733;</b>&nbsp;<a onmouseover=\"gMapChat.myPOIs["+myPOIsIndex+"].setImage(gMapChat.imagesRoot+'stars/green_marker.png');\" "
								+"onmouseout=\"gMapChat.myPOIs["+myPOIsIndex+"].setImage(gMapChat.imagesRoot+'stars/blue_marker.png');\" "
								+"href=\"javascript:gMapChat.map.panTo(gMapChat.myPOIs["+myPOIsIndex+"].getPoint());\" >"
								+text
								+"</a></div>";
			newCell = domNode.insertCell(-1);
			newCell.innerHTML =  "<a href='#' onclick=\"gMapChat.sendMyPOI("+myPOIsIndex+", this.parentNode.parentNode);\" title=\""+this.strSend+"\" >"
								+"<img src='"+this.imagesRoot+"send.gif' width='16' height='16' border='0' />"
								+"</a>";
			newCell = domNode.insertCell(-1);
			newCell.innerHTML =  "<a href='#' onclick=\"gMapChat.removeMyPOI("+myPOIsIndex+", this.parentNode.parentNode);\" title=\""+this.strRemove+"\" >"
								+"<img src='"+this.imagesRoot+"delete.gif' width='16' height='16' border='0' />"
								+"</a>";
			newCell = domNode.insertCell(-1);
			newCell.innerHTML =  "<a href='#' onclick=\"gMapChat.editMyPOI("+myPOIsIndex+", this.parentNode.parentNode);\" title=\""+this.strEdit+"\" >"
								+"<img src='"+this.imagesRoot+"edit.gif' width='16' height='16' border='0' />"
								+"</a>";
			newCell = domNode.insertCell(-1);
			newCell.innerHTML =  "<input type='checkbox' checked='checked' onclick=\"(this.checked)?gMapChat.myPOIs["+myPOIsIndex+"].show():gMapChat.myPOIs["+myPOIsIndex+"].hide();\" title=\""+this.strToggleVisibility+"\" />";
		} catch (e) {
			// for Mozilla...
			elementToRemove.innerHTML = "<td><div style='width:100px;overflow:hidden;'><b>&#9733;</b>&nbsp;<a onmouseover=\"gMapChat.myPOIs["+myPOIsIndex+"].setImage(gMapChat.imagesRoot+'stars/green_marker.png');\" "
							+"onmouseout=\"gMapChat.myPOIs["+myPOIsIndex+"].setImage(gMapChat.imagesRoot+'stars/blue_marker.png');\" "
							+"href=\"javascript:gMapChat.map.panTo(gMapChat.myPOIs["+myPOIsIndex+"].getPoint());\" >"
							+text
							+"</a></div></td><td style='width:16px;'><a href='#' onclick=\"gMapChat.sendMyPOI("+myPOIsIndex+", this.parentNode.parentNode);\" title=\""+this.strSend+"\" >"
							+"<img src='"+this.imagesRoot+"send.gif' width='16' height='16' border='0' />"
							+"</a></td><td style='width:16px;'><a href='#' onclick=\"gMapChat.removeMyPOI("+myPOIsIndex+", this.parentNode.parentNode);\" >"
							+"<img src='"+this.imagesRoot+"delete.gif' width='16' height='16' border='0' />"
							+"</a></td><td style='width:16px;'><a href='#' onclick=\"gMapChat.editMyPOI("+myPOIsIndex+", this.parentNode.parentNode);\" >"
							+"<img src='"+this.imagesRoot+"edit.gif' width='16' height='16' border='0' />"
							+"</a></td><td style='width:14px;'>"
							+"<input type='checkbox' checked='checked' onclick=\"(this.checked)?gMapChat.myPOIs["+myPOIsIndex+"].show():gMapChat.myPOIs["+myPOIsIndex+"].hide();\" />"
							+"</td>";
		}
		this.addMyPOIWithText(center, text);
	},

	sendMyPOI: function(index) {
		latLng = this.myPOIs[index].getLatLng();
		text = this.myPOIs[index].getTitle()
		ajaxChat.sendMessageWrapper('/map '+latLng.lat()+' '+latLng.lng()+' '+encodeURIComponent(text));
	},

	SendPOIFromSearch: function(lat, lng, text) {
		ajaxChat.sendMessageWrapper('/map '+lat+' '+lng+' '+encodeURIComponent(text));
	},

	viewMyPOIs: function() {
		notNullPOIs = Array();
		for (i=0 ; i<this.myPOIs.length ; i++) {
			if (this.myPOIs[i] != -1 && !this.myPOIs[i].isHidden()) notNullPOIs[notNullPOIs.length] = this.myPOIs[i];
		}
		if (notNullPOIs.length >= 2) {
			bounds = new GLatLngBounds();
			for ( i=0 ; i<notNullPOIs.length ; i++ ) {
				bounds.extend(notNullPOIs[i].getPoint());
			}
			this.map.setCenter(bounds.getCenter());
			this.map.setZoom(this.map.getBoundsZoomLevel(bounds)-1);
		} else if (notNullPOIs.length == 1) {
			this.map.panTo(notNullPOIs[0].getPoint());
		} else {
			// Do nothing !
		}
	},

	viewChannelPOIs: function() {
		//TODO: ...
	},

	writeHTMLForInfoWindow: function(marker, html, result) {
		html.innerHTML="<div class=\"gs-title\">"+result.title+"</div>";
		html.innerHTML+="<div><a href=\'javascript:gMapChat.addPOIFromSearch("+marker.getLatLng().lat()+","+marker.getLatLng().lng()+",\""+result.title+"\");\'>"+gMapChat.strCopyFromSearch+"</a></div>";
		html.innerHTML+="<div><a href=\'javascript:gMapChat.SendPOIFromSearch("+marker.getLatLng().lat()+","+marker.getLatLng().lng()+",\""+result.title+"\",gMapChat.map.getZoom());\'>"+gMapChat.strSendDirectly+"</a></div>";
        html.innerHTML+="<div class=\"gs-watermark\"><a target=\"_blank\" class=\"gs-watermark\" href=\"http://code.google.com/apis/ajaxsearch/faq.html\">extract from Google</a></div>";
        return html;
	},

	openSearchFrame: function() {
		domNode = document.getElementById('channelpois');
		domNode.style.height="9em";
		domNode = document.getElementById('mypois');
		domNode.style.height="9em";
		domNode = document.getElementById('gsearch');
		domNode.style.display="block";
		domNode.style.height="8em";
	},

	closeSearchFrame: function() {
		domNode = document.getElementById('gsearch');
		domNode.style.display="none";
		domNode = document.getElementById('channelpois');
		domNode.style.height="14em";
		domNode = document.getElementById('mypois');
		domNode.style.height="14em";

	}

};
