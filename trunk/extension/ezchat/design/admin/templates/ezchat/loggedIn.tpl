{* DO NOT EDIT THIS FILE! Use an override template instead. *}
{*
 SOFTWARE NAME: eZchat
 SOFTWARE RELEASE: 0.1.x
 COPYRIGHT NOTICE: Copyright (C) 2008 Xavier GOULEY & Maxime THOMAS
 SOFTWARE LICENSE: GNU General Public License v2.0
 NOTICE: >
   This program is free software; you can redistribute it and/or
   modify it under the terms of version 2.0  of the GNU General
   Public License as published by the Free Software Foundation.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of version 2.0 of the GNU General
   Public License along with this program; if not, write to the Free
   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
   MA 02110-1301, USA.
*}

{set-block scope=root variable=cache_ttl}0{/set-block}


<script src="{ezroot(no)}extension/ezchat/ajaxchat/js/chat.js" type="text/javascript" charset="UTF-8"></script>
<script src="{'javascript/custom.js'|ezdesign(no)}" type="text/javascript" charset="UTF-8"></script>
<script src="{ezroot(no)}extension/ezchat/ajaxchat/js/lang/[LANG_CODE/].js" type="text/javascript" charset="UTF-8"></script>
<script src="{ezroot(no)}extension/ezchat/ajaxchat/js/config.js" type="text/javascript" charset="UTF-8"></script>
<script src="{ezroot(no)}extension/ezchat/ajaxchat/js/FABridge.js" type="text/javascript" charset="UTF-8"></script>
<script type="text/javascript">
	// <![CDATA[
		function toggleContainer(containerID, hideContainerIDs) {ldelim}
			if(hideContainerIDs) {ldelim}
				for(var i=0; i<hideContainerIDs.length; i++) {ldelim}
					ajaxChat.showHide(hideContainerIDs[i], 'none');
				{rdelim}
			{rdelim}
			ajaxChat.showHide(containerID);
			if(typeof arguments.callee.styleProperty == 'undefined') {ldelim}
				if(typeof isIElt7 != 'undefined') {ldelim}
					arguments.callee.styleProperty = 'marginRight';
				{rdelim} else {ldelim}
					arguments.callee.styleProperty = 'right';
				{rdelim}
			{rdelim}
			var containerWidth = document.getElementById(containerID).offsetWidth;
			if(containerWidth) {ldelim}
				document.getElementById('chatList').style[arguments.callee.styleProperty] = (containerWidth+28)+'px';
			{rdelim} else {ldelim}
				document.getElementById('chatList').style[arguments.callee.styleProperty] = '20px';
			{rdelim}
		{rdelim}

		function initialize() {ldelim}
			if(ajaxChat.getSetting('onlineList') === false) {ldelim}
				toggleContainer('onlineListContainer');
			{rdelim}
			ajaxChat.updateButton('audio', 'audioButton');
			ajaxChat.updateButton('autoScroll', 'autoScrollButton');
			document.getElementById('bbCodeSetting').checked = ajaxChat.getSetting('bbCode');
			document.getElementById('hyperLinksSetting').checked = ajaxChat.getSetting('hyperLinks');
			document.getElementById('lineBreaksSetting').checked = ajaxChat.getSetting('lineBreaks');
			document.getElementById('emoticonsSetting').checked = ajaxChat.getSetting('emoticons');
			document.getElementById('autoFocusSetting').checked = ajaxChat.getSetting('autoFocus');
			document.getElementById('maxMessagesSetting').value = ajaxChat.getSetting('maxMessages');
			document.getElementById('wordWrapSetting').checked = ajaxChat.getSetting('wordWrap');
			document.getElementById('maxWordLengthSetting').value = ajaxChat.getSetting('maxWordLength');
			document.getElementById('dateFormatSetting').value = ajaxChat.getSetting('dateFormat');
			document.getElementById('persistFontColorSetting').checked = ajaxChat.getSetting('persistFontColor');
			for(var i=0; i<document.getElementById('audioVolumeSetting').options.length; i++) {ldelim}
				if(document.getElementById('audioVolumeSetting').options[i].value == ajaxChat.getSetting('audioVolume')) {ldelim}
					document.getElementById('audioVolumeSetting').options[i].selected = true;
					break;
				{rdelim}
			{rdelim}
			ajaxChat.fillSoundSelection('soundReceiveSetting', ajaxChat.getSetting('soundReceive'));
			ajaxChat.fillSoundSelection('soundSendSetting', ajaxChat.getSetting('soundSend'));
			ajaxChat.fillSoundSelection('soundEnterSetting', ajaxChat.getSetting('soundEnter'));
			ajaxChat.fillSoundSelection('soundLeaveSetting', ajaxChat.getSetting('soundLeave'));
			ajaxChat.fillSoundSelection('soundChatBotSetting', ajaxChat.getSetting('soundChatBot'));
			ajaxChat.fillSoundSelection('soundErrorSetting', ajaxChat.getSetting('soundError'));
			document.getElementById('blinkSetting').checked = ajaxChat.getSetting('blink');
			document.getElementById('blinkIntervalSetting').value = ajaxChat.getSetting('blinkInterval');
			document.getElementById('blinkIntervalNumberSetting').value = ajaxChat.getSetting('blinkIntervalNumber');
		{rdelim}

		function finalize() {ldelim}
			ajaxChat.setSetting('onlineList', (document.getElementById('onlineListContainer').offsetWidth > 0));
		{rdelim}

		ajaxChatConfig.loginChannelID = parseInt('[LOGIN_CHANNEL_ID/]');
		ajaxChatConfig.sessionName = '[SESSION_NAME/]';
		ajaxChatConfig.cookieExpiration = parseInt('[COOKIE_EXPIRATION/]');
		ajaxChatConfig.cookiePath = '[COOKIE_PATH/]';
		ajaxChatConfig.cookieDomain = '[COOKIE_DOMAIN/]';
		ajaxChatConfig.cookieSecure = '[COOKIE_SECURE/]';
		ajaxChatConfig.chatBotName = decodeURIComponent('[CHAT_BOT_NAME/]');
		ajaxChatConfig.chatBotID = '[CHAT_BOT_ID/]';
		ajaxChatConfig.allowUserMessageDelete = parseInt('[ALLOW_USER_MESSAGE_DELETE/]');
		ajaxChatConfig.inactiveTimeout = parseInt('[INACTIVE_TIMEOUT/]');
		ajaxChatConfig.privateChannelDiff = parseInt('[PRIVATE_CHANNEL_DIFF/]');
		ajaxChatConfig.privateMessageDiff = parseInt('[PRIVATE_MESSAGE_DIFF/]');
		ajaxChatConfig.showChannelMessages = parseInt('[SHOW_CHANNEL_MESSAGES/]');
		ajaxChatConfig.messageTextMaxLength = parseInt('[MESSAGE_TEXT_MAX_LENGTH/]');
		ajaxChatConfig.socketServerEnabled = parseInt('[SOCKET_SERVER_ENABLED/]');
		ajaxChatConfig.socketServerHost = decodeURIComponent('[SOCKET_SERVER_HOST/]');
		ajaxChatConfig.socketServerPort = parseInt('[SOCKET_SERVER_PORT/]');
		ajaxChatConfig.socketServerChatID = parseInt('[SOCKET_SERVER_CHAT_ID/]');

		ajaxChatConfig.ajaxURL = "{$loginURL|ezurl(no)}?ajax=true";
		ajaxChat.ajaxURL = "{$loginURL|ezurl(no)}?ajax=true";
 		ajaxChatConfig.baseURL = "{ezroot(no)}extension/ezchat/ajaxchat/";

		ajaxChat.init(ajaxChatConfig, ajaxChatLang, true, true, true, initialize, finalize);

		ajaxChat.lang['callUserPicture'] = "{'Show picture'|i18n('design/standard/ezchat')}";
		ezChat.baseURL = "{'/'|ezurl(no)}";
	// ]]>
</script>

<div class="context-block">
<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
	<select id="channelSelection" onchange="ajaxChat.switchChannel(this.options[this.selectedIndex].value);">[CHANNEL_OPTIONS/]</select>
	<h1 class="context-title">[LANG]title[/LANG]</h1>
	<div class="header-mainline"></div>
</div></div></div></div></div></div>
<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-content">

	{* START RIGHT COLUMN *}
	<div id="chatrightcolumn"><div class="rightcolumn">
		<div class="box-ml"><div class="box-mr">
			<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
				<h4>
					<a class="showhide" href="#" onclick="toggleContainer('tools1Container', '');toggleContainer('tools2Container', '');" title="{'deploy/shrink tools'|i18n('design/admin/ezchat')}"><span class="bracket">[</span>+/-<span class="bracket">]</span></a>
					{'Chatroom window'|i18n('design/admin/ezchat')}
				</h4>
				<div class="break"></div>
			</div></div></div></div></div></div>
			<div class="box-content">
				<ul class="bulletlist">
					<li id="audioButton"><div>    <a href="#" onclick="ajaxChat.toggleSetting('audio', 'audioButton');" title="[LANG]toggleAudio[/LANG]">[LANG]toggleAudio[/LANG]</a></div></li>
					<li id="autoScrollButton"><div>    <a href="#" onclick="ajaxChat.toggleSetting('autoScroll', 'autoScrollButton');" title="[LANG]toggleAutoScroll[/LANG]">[LANG]toggleAutoScroll[/LANG]</a></div></li>
					<li id="helpButton" class="off"><div>    <a href="#" onclick="ajaxChat.toggleSetting('help', 'helpButton');toggleContainer('helpContainer', '');" title="[LANG]toggleHelp[/LANG]">[LANG]toggleHelp[/LANG]</a></div></li>
				</ul>
				<div class="block">
					<input type="button" class="button smallbutton" value="[LANG]bbCodeLabelQuote[/LANG]" title="[LANG]bbCodeTitleQuote[/LANG]" onclick="ajaxChat.insertBBCode('quote');"/>
					<input type="button" class="button smallbutton" value="[LANG]bbCodeLabelCode[/LANG]" title="[LANG]bbCodeTitleCode[/LANG]" onclick="ajaxChat.insertBBCode('code');"/>
					<input type="button" class="button smallbutton" value="[LANG]bbCodeLabelURL[/LANG]" title="[LANG]bbCodeTitleURL[/LANG]" onclick="ajaxChat.insertBBCode('url');"/>
				</div>
				<div id="tools2Container" class="block">
					<input type="button" class="button smallbutton" value="[LANG]bbCodeLabelBold[/LANG]" title="[LANG]bbCodeTitleBold[/LANG]" onclick="ajaxChat.insertBBCode('b');" style="font-weight:bold;"/>
					<input type="button" class="button smallbutton" value="[LANG]bbCodeLabelItalic[/LANG]" title="[LANG]bbCodeTitleItalic[/LANG]" onclick="ajaxChat.insertBBCode('i');" style="font-style:italic;"/>
					<input type="button" class="button smallbutton" value="[LANG]bbCodeLabelUnderline[/LANG]" title="[LANG]bbCodeTitleUnderline[/LANG]" onclick="ajaxChat.insertBBCode('u');" style="text-decoration:underline!important;"/>
				</div>
				<div id="tools1Container" class="block iconblock">
					<div id="emoticonsContainer" dir="ltr"></div>
					<div id="colorCodesContainer" dir="ltr"></div>
				</div>
			</div>
		</div></div>
		<div class="box-ml"><div class="box-mr">
			<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr">
				<h4>
					<a class="showhide" href="#" onclick="toggleContainer('onlineListContainer', '');" title="{'deploy/shrink online users'|i18n('design/admin/ezchat')}"><span class="bracket">[</span>+/-<span class="bracket">]</span></a>
					[LANG]onlineUsers[/LANG]
				</h4>
				<div class="break"></div>
			</div></div></div></div>
			<div class="box-content">
				<div id="onlineListContainer" class="block">
					<div id="onlineList"></div>
				</div>
			</div>
		</div></div>
		<div class="box-ml"><div class="box-mr">
			<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr">
				<h4>
					<a class="showhide" href="#" onclick="toggleContainer('settingsContainer', '');" title="{'deploy/shrink settings'|i18n('design/admin/ezchat')}"><span class="bracket">[</span>+/-<span class="bracket">]</span></a>
					[LANG]settings[/LANG]
				</h4>
				<div class="break"></div>
			</div></div></div></div>
			<div class="box-content">

			  	<div id="settingsContainer" class="block" style="display:none;">
		 			<div id="settingsList">
						<table>
							<tr class="rowOdd">
								<td><label for="bbCodeSetting">[LANG]settingsBBCode[/LANG]</label></td>
								<td class="setting"><input type="checkbox" id="bbCodeSetting" onclick="ajaxChat.setSetting('bbCode', this.checked);"/></td>
							</tr>
							<tr class="rowEven">
								<td><label for="hyperLinksSetting">[LANG]settingsHyperLinks[/LANG]</label></td>
								<td class="setting"><input type="checkbox" id="hyperLinksSetting" onclick="ajaxChat.setSetting('hyperLinks', this.checked);"/></td>
							</tr>
							<tr class="rowOdd">
								<td><label for="lineBreaksSetting">[LANG]settingsLineBreaks[/LANG]</label></td>
								<td class="setting"><input type="checkbox" id="lineBreaksSetting" onclick="ajaxChat.setSetting('lineBreaks', this.checked);"/></td>
							</tr>
							<tr class="rowEven">
								<td><label for="emoticonsSetting">[LANG]settingsEmoticons[/LANG]</label></td>
								<td class="setting"><input type="checkbox" id="emoticonsSetting" onclick="ajaxChat.setSetting('emoticons', this.checked);"/></td>
							</tr>
							<tr class="rowOdd">
								<td><label for="autoFocusSetting">[LANG]settingsAutoFocus[/LANG]</label></td>
								<td class="setting"><input type="checkbox" id="autoFocusSetting" onclick="ajaxChat.setSetting('autoFocus', this.checked);"/></td>
							</tr>
							<tr class="rowEven">
								<td><label for="maxMessagesSetting">[LANG]settingsMaxMessages[/LANG]</label></td>
								<td class="setting"><input type="text" class="text" id="maxMessagesSetting" onchange="ajaxChat.setSetting('maxMessages', parseInt(this.value));"/></td>
							</tr>
							<tr class="rowOdd">
								<td><label for="wordWrapSetting">[LANG]settingsWordWrap[/LANG]</label></td>
								<td class="setting"><input type="checkbox" id="wordWrapSetting" onclick="ajaxChat.setSetting('wordWrap', this.checked);"/></td>
							</tr>
							<tr class="rowEven">
								<td><label for="maxWordLengthSetting">[LANG]settingsMaxWordLength[/LANG]</label></td>
								<td class="setting"><input type="text" class="text" id="maxWordLengthSetting" onchange="ajaxChat.setSetting('maxWordLength', parseInt(this.value));"/></td>
							</tr>
							<tr class="rowOdd">
								<td><label for="dateFormatSetting">[LANG]settingsDateFormat[/LANG]</label></td>
								<td class="setting"><input type="text" class="text" id="dateFormatSetting" onchange="ajaxChat.setSetting('dateFormat', this.value);"/></td>
							</tr>
							<tr class="rowEven">
								<td><label for="persistFontColorSetting">[LANG]settingsPersistFontColor[/LANG]</label></td>
								<td class="setting"><input type="checkbox" id="persistFontColorSetting" onclick="ajaxChat.setPersistFontColor(this.checked);"/></td>
							</tr>
							<tr class="rowOdd">
								<td><label for="audioVolumeSetting">[LANG]settingsAudioVolume[/LANG]</label></td>
								<td class="setting">
									<select class="left" id="audioVolumeSetting" onchange="ajaxChat.setAudioVolume(this.options[this.selectedIndex].value);">
										<option value="1.0">100 %</option>
										<option value="0.9">90 %</option>
										<option value="0.8">80 %</option>
										<option value="0.7">70 %</option>
										<option value="0.6">60 %</option>
										<option value="0.5">50 %</option>
										<option value="0.4">40 %</option>
										<option value="0.3">30 %</option>
										<option value="0.2">20 %</option>
										<option value="0.1">10 %</option>
									</select>
								</td>
							</tr>
							<tr class="rowEven">
								<td><label for="soundReceiveSetting">[LANG]settingsSoundReceive[/LANG]</label></td>
								<td class="setting">
									<select id="soundReceiveSetting" onchange="ajaxChat.setSetting('soundReceive', this.options[this.selectedIndex].value);"><option value="">-</option></select><input type="button" class="button playback" value="&gt;" onclick="ajaxChat.playSound(this.previousSibling.options[this.previousSibling.selectedIndex].value);"/>
								</td>
							</tr>
							<tr class="rowOdd">
								<td><label for="soundSendSetting">[LANG]settingsSoundSend[/LANG]</label></td>
								<td class="setting">
									<select id="soundSendSetting" onchange="ajaxChat.setSetting('soundSend', this.options[this.selectedIndex].value);"><option value="">-</option></select><input type="button" class="button playback" value="&gt;" onclick="ajaxChat.playSound(this.previousSibling.options[this.previousSibling.selectedIndex].value);"/>
								</td>
							</tr>
							<tr class="rowEven">
								<td><label for="soundEnterSetting">[LANG]settingsSoundEnter[/LANG]</label></td>
								<td class="setting">
									<select id="soundEnterSetting" onchange="ajaxChat.setSetting('soundEnter', this.options[this.selectedIndex].value);"><option value="">-</option></select><input type="button" class="button playback" value="&gt;" onclick="ajaxChat.playSound(this.previousSibling.options[this.previousSibling.selectedIndex].value);"/>
								</td>
							</tr>
							<tr class="rowOdd">
								<td><label for="soundLeaveSetting">[LANG]settingsSoundLeave[/LANG]</label></td>
								<td class="setting">
									<select id="soundLeaveSetting" onchange="ajaxChat.setSetting('soundLeave', this.options[this.selectedIndex].value);"><option value="">-</option></select><input type="button" class="button playback" value="&gt;" onclick="ajaxChat.playSound(this.previousSibling.options[this.previousSibling.selectedIndex].value);"/>
								</td>
							</tr>
							<tr class="rowEven">
								<td><label for="soundChatBotSetting">[LANG]settingsSoundChatBot[/LANG]</label></td>
								<td class="setting">
									<select id="soundChatBotSetting" onchange="ajaxChat.setSetting('soundChatBot', this.options[this.selectedIndex].value);"><option value="">-</option></select><input type="button" class="button playback" value="&gt;" onclick="ajaxChat.playSound(this.previousSibling.options[this.previousSibling.selectedIndex].value);"/>
								</td>
							</tr>
							<tr class="rowOdd">
								<td><label for="soundErrorSetting">[LANG]settingsSoundError[/LANG]</label></td>
								<td class="setting">
									<select id="soundErrorSetting" onchange="ajaxChat.setSetting('soundError', this.options[this.selectedIndex].value);"><option value="">-</option></select><input type="button" class="button playback" value="&gt;" onclick="ajaxChat.playSound(this.previousSibling.options[this.previousSibling.selectedIndex].value);"/>
								</td>
							</tr>
							<tr class="rowEven">
								<td><label for="blinkSetting">[LANG]settingsBlink[/LANG]</label></td>
								<td class="setting"><input type="checkbox" id="blinkSetting" onclick="ajaxChat.setSetting('blink', this.checked);"/></td>
							</tr>
							<tr class="rowOdd">
								<td><label for="blinkIntervalSetting">[LANG]settingsBlinkInterval[/LANG]</label></td>
								<td class="setting"><input type="text" class="text" id="blinkIntervalSetting" onchange="ajaxChat.setSetting('blinkInterval', parseInt(this.value));"/></td>
							</tr>
							<tr class="rowEven">
								<td><label for="blinkIntervalNumberSetting">[LANG]settingsBlinkIntervalNumber[/LANG]</label></td>
								<td class="setting"><input type="text" class="text" id="blinkIntervalNumberSetting" onchange="ajaxChat.setSetting('blinkIntervalNumber', parseInt(this.value));"/></td>
							</tr>
						</table>
					</div>
			  	</div>

			</div>
		</div></div>

		<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content"></div></div></div></div></div></div>
	</div></div>
	{* END RIGHT COLUMN *}

	{* START LEFT COLUMN *}
	<div class="chatleftcolumn"><div class="leftcolumn">
		<div class="box-ml"><div class="box-mr">
			<div class="header-subline chatheader">
				<a class="showhide" href="#" onclick="document.getElementById('chatList').style['height']='26em';" title="{'deploy chatroom window'|i18n('design/admin/ezchat')}"><span class="bracket">[</span>+<span class="bracket">]</span></a>
				<a class="showhide" href="#" onclick="document.getElementById('chatList').style['height']='9em';" title="{'shrink chatroom window'|i18n('design/admin/ezchat')}"><span class="bracket">[</span>-<span class="bracket">]</span></a>
			</div>
			<div class="box-content chatBlock">
				<div id="chatList"></div>
		  	</div>
			<div class="header-mainline"></div>
		</div></div>
		<div class="controlbar">
		<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
			<div class="block" style="padding:0.1em;">
				<div id="submitButtonContainer" class="buttonblock" style="float:right;">
					<span id="messageLengthCounter">0/[MESSAGE_TEXT_MAX_LENGTH/]</span><br/>
					<input class="button" type="button" id="submitButton" value="[LANG]messageSubmit[/LANG]" onclick="ajaxChat.sendMessage();"/>
				</div>
				<div id="inputFieldContainer">
					<textarea id="inputField" rows="2" cols="70" wrap="soft" title="[LANG]inputLineBreak[/LANG]" onkeypress="ajaxChat.handleInputFieldKeyPress(event);" onkeyup="ajaxChat.handleInputFieldKeyUp(event);"></textarea>
				</div>
				<div class="break"></div>
				<div id="helpContainer" style="display:none;">
		 			<div id="helpList">
						<table>
							<tr class="rowOdd">
								<td class="desc" style="width:50%">[LANG]helpItemDescJoin[/LANG]</td>
								<td class="code" style="width:50%">[LANG]helpItemCodeJoin[/LANG]</td>
							</tr>
							<tr class="rowEven">
								<td class="desc">[LANG]helpItemDescJoinCreate[/LANG]</td>
								<td class="code">[LANG]helpItemCodeJoinCreate[/LANG]</td>

							</tr>
							<tr class="rowOdd">
								<td class="desc">[LANG]helpItemDescInvite[/LANG]</td>
								<td class="code">[LANG]helpItemCodeInvite[/LANG]</td>
							</tr>
							<tr class="rowEven">
								<td class="desc">[LANG]helpItemDescUninvite[/LANG]</td>
								<td class="code">[LANG]helpItemCodeUninvite[/LANG]</td>
							</tr>

							<tr class="rowOdd">
								<td class="desc">[LANG]helpItemDescLogout[/LANG]</td>
								<td class="code">[LANG]helpItemCodeLogout[/LANG]</td>
							</tr>
							<tr class="rowEven">
								<td class="desc">[LANG]helpItemDescPrivateMessage[/LANG]</td>
								<td class="code">[LANG]helpItemCodePrivateMessage[/LANG]</td>
							</tr>
							<tr class="rowOdd">

								<td class="desc">[LANG]helpItemDescQueryOpen[/LANG]</td>
								<td class="code">[LANG]helpItemCodeQueryOpen[/LANG]</td>
							</tr>
							<tr class="rowEven">
								<td class="desc">[LANG]helpItemDescQueryClose[/LANG]</td>
								<td class="code">[LANG]helpItemCodeQueryClose[/LANG]</td>
							</tr>
							<tr class="rowOdd">
								<td class="desc">[LANG]helpItemDescAction[/LANG]</td>
								<td class="code">[LANG]helpItemCodeAction[/LANG]</td>
							</tr>
							<tr class="rowEven">
								<td class="desc">[LANG]helpItemDescDescribe[/LANG]</td>
								<td class="code">[LANG]helpItemCodeDescribe[/LANG]</td>
							</tr>
							<tr class="rowOdd">
								<td class="desc">[LANG]helpItemDescIgnore[/LANG]</td>
								<td class="code">[LANG]helpItemCodeIgnore[/LANG]</td>

							</tr>
							<tr class="rowEven">
								<td class="desc">[LANG]helpItemDescIgnoreList[/LANG]</td>
								<td class="code">[LANG]helpItemCodeIgnoreList[/LANG]</td>
							</tr>
							<tr class="rowOdd">
								<td class="desc">[LANG]helpItemDescWhereis[/LANG]</td>
								<td class="code">[LANG]helpItemCodeWhereis[/LANG]</td>
							</tr>

							<tr class="rowEven">
								<td class="desc">[LANG]helpItemDescKick[/LANG]</td>
								<td class="code">[LANG]helpItemCodeKick[/LANG]</td>
							</tr>
							<tr class="rowOdd">
								<td class="desc">[LANG]helpItemDescUnban[/LANG]</td>
								<td class="code">[LANG]helpItemCodeUnban[/LANG]</td>
							</tr>
							<tr class="rowEven">

								<td class="desc">[LANG]helpItemDescBans[/LANG]</td>
								<td class="code">[LANG]helpItemCodeBans[/LANG]</td>
							</tr>
							<tr class="rowOdd">
								<td class="desc">[LANG]helpItemDescWhois[/LANG]</td>
								<td class="code">[LANG]helpItemCodeWhois[/LANG]</td>
							</tr>
							<tr class="rowEven">
								<td class="desc">[LANG]helpItemDescWho[/LANG]</td>

								<td class="code">[LANG]helpItemCodeWho[/LANG]</td>
							</tr>
							<tr class="rowOdd">
								<td class="desc">[LANG]helpItemDescList[/LANG]</td>
								<td class="code">[LANG]helpItemCodeList[/LANG]</td>
							</tr>
							<tr class="rowEven">
								<td class="desc">[LANG]helpItemDescRoll[/LANG]</td>
								<td class="code">[LANG]helpItemCodeRoll[/LANG]</td>

							</tr>
							<tr class="rowOdd">
								<td class="desc">[LANG]helpItemDescNick[/LANG]</td>
								<td class="code">[LANG]helpItemCodeNick[/LANG]</td>
							</tr>
						</table>
					</div>
			  	</div>
			</div>
		</div></div></div></div></div></div>
		</div>


		A small try for GoogleMaps interface:
		{set-block scope=root variable=cache_ttl}0{/set-block}
		{include uri="design:ezchat/gmaps.tpl"}


	</div></div>
	{* END LEFT COLUMN *}
	<div class="break"></div>

</div></div></div>

<div class="controlbar">
<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
    <!--
		Please retain the full copyright notice below including the link to blueimp.net.
		This not only gives respect to the amount of time given freely by the developer
		but also helps build interest, traffic and use of AJAX Chat.

		Thanks,
		Sebastian Tschan
	//-->
	<div class="chatCopyright"><a href="https://blueimp.net/ajax/">AJAX Chat</a> &copy; <a href="https://blueimp.net">blueimp.net</a></div>
	<div id="flashInterfaceContainer"></div>
</div>
</div></div></div></div></div></div>
</div>

</div></div>

<div class="break"></div>