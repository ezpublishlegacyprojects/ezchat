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
require_once( "lib/ezutils/classes/ezexecution.php" );
require_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
require_once( "lib/ezutils/classes/ezini.php" );
require_once( "kernel/common/template.php" );

// Init vars
//$Module = $Params["Module"];
$ezchatIni = eZINI::instance( 'ezchat.ini' );
$tpl = templateInit();

if ($Params['UserID']) {
	$userId = $Params['UserID'];
	$classId = $ezchatIni->variable('userPicture','userClassIdentifier');
	$attributeId = $ezchatIni->variable('userPicture','pictureAttributeIdentifier');
	$imageClass = $ezchatIni->variable('userPicture','imageClass');
	$cssClass = $ezchatIni->variable('userPicture','cssClass');

	$user = eZUser::fetch($userId);
	if ($user) $userContentObject = $user->contentObject();
	if ($userContentObject) $userDataMap = $userContentObject->dataMap();
	if ($userDataMap) $pictureAttribute = $userDataMap[$attributeId];

	if ($pictureAttribute) {
		$tpl->setVariable('attribute', $pictureAttribute);
		$tpl->setVariable('image_class', $imageClass);
		$tpl->setVariable('css_class', $cssClass);
		$content = $tpl->fetch("design:content/datatype/view/ezimage.tpl");
		echo "<li style='background:none!important;list-style-type:none;'>".$content."</li>";
		eZExecution::cleanExit();
	}

	echo "";
	eZExecution::cleanExit();
}

?>