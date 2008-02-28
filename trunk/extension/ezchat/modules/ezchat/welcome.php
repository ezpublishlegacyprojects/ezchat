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
require_once( "kernel/common/template.php" );

// Init vars
$Module = $Params["Module"];
$ezchatIni = eZINI::instance( 'ezchat.ini' );
$Module->setTitle( $ezchatIni->variable( 'generalDefaults', 'ezchatRoomName' ) );
$Result = array();
$currentUser = eZUser::currentUser();
$tpl = templateInit();




$Result['content'] = $tpl->fetch("design:ezchat/welcome.tpl");
$Result['left_menu'] = "design:parts/ezchat/menu.tpl";
$Result['path'] = array( array( 'url' => '/ezchat/welcome',
                                'text' => ezi18n('kernel/navigationpart','eZchat','Navigation path' ) ) );



?>