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

$Module = array( 'name' => 'eZChat',
                 'variable_params' => true );

$ViewList = array();
$ViewList['room'] = array(	'script' => 'room.php',
							'functions' => array( 'room' ),
							'params' => array(),
							'default_navigation_part' => 'ezchatnavigationpart',
							'unordered_params' => array( 'channel' => 'Channel' )
						);
$ViewList['welcome'] = array(	'script' => 'welcome.php',
								'functions' => array( 'administrate' ),
								'params' => array(),
								'default_navigation_part' => 'ezchatnavigationpart'
						);
$ViewList['userpicture'] = array(	'script' => 'userpicture.php',
									'functions' => array( 'room' ),
									'params' => array(),
									'default_navigation_part' => 'ezchatnavigationpart',
									'unordered_params' => array( 'userid' => 'UserID' )
						);
$FunctionList = array();
$FunctionList['room'] = array(	'OverrideConfig' => array( 'name' => 'OverrideConfig', 'single_select' => false,
    														'values'=> array(
															        array( 'Name' => 'allowPrivateChannels',
															               'value' => 'allowPrivateChannels'),
															        array( 'Name' => 'allowPrivateMessages',
															               'value' => 'allowPrivateMessages' ),
															        array( 'Name' => 'showChannelMessages',
															               'value' => 'showChannelMessages' ),
															        array( 'Name' => 'allowUserMessageDelete',
															               'value' => 'allowUserMessageDelete' ),
															        array( 'Name' => 'requestMessagesPriorChannelEnter',
															               'value' => 'requestMessagesPriorChannelEnter' ) )
															),
								'OpeningHours' => array( 'name' => 'OpeningHours', 'single_select' => false,
    														'values'=> array(
															        array( 'Name' => '00 h',
															               'value' => '0'),
															        array( 'Name' => '01 h',
															               'value' => '1' ),
															        array( 'Name' => '02 h',
															               'value' => '2'),
															        array( 'Name' => '03 h',
															               'value' => '3' ),
															        array( 'Name' => '04 h',
															               'value' => '4'),
															        array( 'Name' => '05 h',
															               'value' => '5' ),
															        array( 'Name' => '06 h',
															               'value' => '6'),
															        array( 'Name' => '07 h',
															               'value' => '7' ),
															        array( 'Name' => '08 h',
															               'value' => '8'),
															        array( 'Name' => '09 h',
															               'value' => '9' ),
															        array( 'Name' => '10 h',
															               'value' => '10'),
															        array( 'Name' => '11 h',
															               'value' => '11' ),
															        array( 'Name' => '12 h',
															               'value' => '12'),
															        array( 'Name' => '13 h',
															               'value' => '13' ),
															        array( 'Name' => '14 h',
															               'value' => '14'),
															        array( 'Name' => '15 h',
															               'value' => '15' ),
															        array( 'Name' => '16 h',
															               'value' => '16'),
															        array( 'Name' => '17 h',
															               'value' => '17' ),
															        array( 'Name' => '18 h',
															               'value' => '18'),
															        array( 'Name' => '19 h',
															               'value' => '19' ),
															        array( 'Name' => '20 h',
															               'value' => '20'),
															        array( 'Name' => '21 h',
															               'value' => '21' ),
															        array( 'Name' => '22 h',
															               'value' => '22'),
															        array( 'Name' => '23 h',
															               'value' => '23' ) )
															),
								'OpeningDays' => array( 'name' => 'OpeningDays', 'single_select' => false,
    														'values'=> array(
															        array( 'Name' => 'Sunday',
															               'value' => '0'),
															        array( 'Name' => 'Monday',
															               'value' => '1' ),
															        array( 'Name' => 'Marday',
															               'value' => '2' ),
															        array( 'Name' => 'Mercreday',
															               'value' => '3' ),
															        array( 'Name' => 'Jeuday',
															               'value' => '4' ),
															        array( 'Name' => 'Vendreday',
															               'value' => '5' ),
															        array( 'Name' => 'Sameday',
															               'value' => '6' ) )
															)
);

$FunctionList['moderate'] = array();
$FunctionList['administrate'] = array();


?>
