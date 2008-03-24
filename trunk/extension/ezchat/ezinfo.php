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

class ezchatInfo
{
    static function info()
    {
        return array( 'Name' => "eZchat",
                      'Version' => "0.1",
                      'Copyright' => "Copyright (C) 2008 Xavier GOULEY & Maxime THOMAS",
                      'License' => "GNU General Public License v2.0",
        			  'For more information' => 'http://www.wascou.org/',
        			  'Includes the following third-party software' => array( 'Name' => 'Ajax Chat',
                                                                              'Version' => '0.8.1.2',
        																	  'Copyright' => 'Copyright (C) 2008 blueimp.net',
                                                                              'License' => 'GNU AFFERO GENERAL PUBLIC LICENSE v3 - 19 November 2007',
                                                                              'For more information' => 'http://blueimp.net/' )
                    );
    }
}
?>
