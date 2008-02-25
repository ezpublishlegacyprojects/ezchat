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

require_once( "lib/ezutils/classes/ezini.php" );
require_once( "lib/ezutils/classes/ezuri.php" );
require_once( "kernel/classes/datatypes/ezuser/ezuser.php" );

$siteIni =& eZINI::instance( 'site.ini' );
$ezchatIni =& eZINI::instance( 'ezchat.ini' );

// Default language:
if ( $ezchatIni->hasVariable( 'langDefault', 'generalDefaults' ) ) $langDefault = $ezchatIni->variable( 'langDefault', 'generalDefaults' );
$translationsAvailable = array(	'cat-ES' => 'es',	'eng-GB' => 'en',	'hin-IN' => '',	'por-MZ' => '',
								'chi-CN' => '',	'eng-US' => 'en',	'hun-HU' => '',	'por-PT' => '',
								'chi-TW' => '',	'esl-ES' => 'es',	'ita-IT' => 'it',	'rus-RU' => 'ru',
								'cro-HR' => '',	'esl-MX' => 'es',	'jpn-JP' => '',	'slk-SK' => '',
								'cze-CZ' => '',	'fin-FI' => 'fi',	'kor-KR' => '',	'swe-SE' => '',
								'dan-DK' => '',	'fre-CA' => 'fr',	'nno-NO' => 'no',	'tur-TR' => '',
								'dut-NL' => '',	'fre-FR' => 'fr',	'nor-NO' => 'no',	'ukr-UA' => 'uk',
								'eng-AU' => 'en',	'ger-DE' => 'de',	'pol-PL' => 'pl',
								'eng-CA' => 'en',	'heb-IL' => '',	'por-BR' => '');
//TODO : finir de remplir ce tableau !
$locale = $siteIni->variable( 'RegionalSettings', 'Locale' );
if ($locale && $translationsAvailable[substr($locale,0,6)]) {
	$langDefault = $translationsAvailable[substr($locale,0,6)];
}

?>