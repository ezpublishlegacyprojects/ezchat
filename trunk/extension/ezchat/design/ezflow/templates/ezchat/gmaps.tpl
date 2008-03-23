{set-block scope=root variable=cache_ttl}0{/set-block}

	<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key={ezini( 'gmapsSettings', 'googleKey', 'ezchat.ini' )}"
      type="text/javascript"></script>
    <script type="text/javascript">
    //<![CDATA[

    function initGoogleMap(defaultStartingLocationLat, defaultStartingLocationLng, defaultStartingLocationZoom) {ldelim}
      if (GBrowserIsCompatible()) {ldelim}
		gMapChat.imagesRoot = "{'/extension/ezchat/design/standard/images/gmaps/'|ezroot(no)}";
		gMapChat.strEdit = "{'Edit this marker'|i18n('design/standard/ezchat')}";
		gMapChat.strRemove = "{'Remove this marker'|i18n('design/standard/ezchat')}";
		gMapChat.strToggleVisibility = "{'Toggle marker visibility'|i18n('design/standard/ezchat')}";
		gMapChat.strSend = "{'Send this marker trough chatroom'|i18n('design/standard/ezchat')}";
		gMapChat.strSendDirectly = "{'Send directly trough chatroom'|i18n('design/standard/ezchat')}";
		gMapChat.strCopyFromSearch = "{'Copy to my markers'|i18n('design/standard/ezchat')}";
		gMapChat.strEnterTitle = "{'Enter marker title:'|i18n('design/standard/ezchat')}";
		gMapChat.strChangeTitle = "{'Change marker title:'|i18n('design/standard/ezchat')}";
      	var gBarOptions = {ldelim}
			resultList : document.getElementById('gresults'),
			onSearchCompleteCallback : gMapChat.openSearchFrame,
			onIdleCallback : gMapChat.closeSearchFrame,
			onGenerateMarkerHtmlCallback : gMapChat.writeHTMLForInfoWindow
		{rdelim};
        gMapChat.map = new GMap2(document.getElementById("map"), {ldelim}googleBarOptions: gBarOptions{rdelim});
        gMapChat.map.setCenter(new GLatLng(defaultStartingLocationLat,defaultStartingLocationLng),defaultStartingLocationZoom);
		gMapChat.map.addControl(new GLargeMapControl());
		gMapChat.map.enableGoogleBar();
		gMapChat.map.checkResize();
      {rdelim}
      if (window.addEventListener) {ldelim}
		  window.addEventListener("unload", GUnload, false);
	  {rdelim} else if (document.addEventListener) {ldelim}
		  document.addEventListener("unload", GUnload, false);
	  {rdelim} else if (window.attachEvent) {ldelim}
		  window.attachEvent("onunload", GUnload);
	  {rdelim}
    {rdelim}

    function showMenu(force) {ldelim}
    	/*opener = document.getElementById('gmaps-controls-opener');*/
    	controls = document.getElementById('gmaps-controls');
    	if (force == true) controls.style.display = 'block';
    	else controls.style.display = (controls.style.display == 'none')? 'block':'none';
    {rdelim}

    //]]>
    </script>

    <div id="gmaps-main">
    	<div id="map"></div>
    	<div id="gmaps-controls-container">
    		<div id="gmaps-controls-subcontainer"><div class="controls-subcontainer">

    			<div id="gmaps-controls-opener" onclick="showMenu(false);">
	    			<img src={'li-bullet.gif'|ezimage} />
	    			<img src={'li-bullet.gif'|ezimage} />
	    			<img src={'li-bullet.gif'|ezimage} />
	    			<img src={'li-bullet.gif'|ezimage} />
	    			<img src={'li-bullet.gif'|ezimage} />
	    			<img src={'li-bullet.gif'|ezimage} />
	    			<img src={'li-bullet.gif'|ezimage} />
	    			<img src={'li-bullet.gif'|ezimage} />
	    			<img src={'li-bullet.gif'|ezimage} />
	    		</div>
		    	<div id="gmaps-controls">
					<div class="header-subline gmapheader">
						<a class="gmapheader-button" href="#" title="{'Show/hide all markers'|i18n('design/standard/ezchat')}"><img src={'gmaps/toggle.gif'|ezimage} width="16" height="16" border="0" /></a>
						<a class="gmapheader-button" href="javascript:gMapChat.viewChannelPOIs();" title="{'View all markers on map'|i18n('design/standard/ezchat')}"><img src={'gmaps/window_fullscreen.png'|ezimage} width="16" height="16" border="0" /></a>
						{'Chatroom markers'|i18n('design/standard/ezchat')}
					</div>
					<div id="channelpois" class="gmaplist">
						<table><tbody id="channelpois_list">
							<tr><td>01. <a href="#">Coming soon...</a></td>
							<td style='width:14px;'><input type='checkbox' checked='checked' onclick="" /></td></tr>
						</tbody></table>
					</div>

					<div class="header-subline gmapheader">
						<a class="gmapheader-button" href="#" title="{'Show/hide all markers'|i18n('design/standard/ezchat')}"><img src={'gmaps/toggle.gif'|ezimage} width="16" height="16" border="0" /></a>
						<a class="gmapheader-button" href="javascript:gMapChat.viewMyPOIs();" title="{'View all markers on map'|i18n('design/standard/ezchat')}"><img src={'gmaps/window_fullscreen.png'|ezimage} width="16" height="16" border="0" /></a>
						<a class="gmapheader-button" href="javascript:gMapChat.addMyPOI(gMapChat.map.getCenter());" title="{'Add a marker'|i18n('design/standard/ezchat')}"><img src={'gmaps/add.gif'|ezimage} width="16" height="16" border="0" /></a>
						{'My markers'|i18n('design/standard/ezchat')}
					</div>
					<div id="mypois" class="gmaplist">
						<table><tbody id="mypois_list">
							<tr><td></td><td style='width:16px;'></td><td style='width:16px;'></td>
							<td style='width:16px;'></td><td style='width:14px;'></td></tr>
						</tbody></table>
					</div>

					<div id="gsearch" style="display:none;">
						<div class="header-mainline gmapheader">
							<a class="gmapheader-button" href="javascript:gMapChat.closeSearchFrame();" title="{'Close search results'|i18n('design/standard/ezchat')}"><img src={'gmaps/delete.gif'|ezimage} width="16" height="16" border="0" /></a>
							{'Search results'|i18n('design/standard/ezchat')}
						</div>
						<div class="gmaplist">
							<div id="gresults"></div>
						</div>
					</div>
				</div>

	    	</div></div>
		</div>
		<div class="break"></div>
	</div>
