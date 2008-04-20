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

class eZChatroomFunctionCollection {

	function eZChatroomFunctionCollection() {
	}

    function fetchChatroomByName($name) {
    	$chatroomList =& $this->fetchChatroomList();
    	$name = '/'.str_replace('%','.*',$name).'/';

    	foreach ($chatroomList['result'] as $chatroom) {
    		$matches = array();
        	if (preg_match($name, $chatroom['chatroom']->name(), $matches)!=0 && $matches[0]==$chatroom['chatroom']->name())
				return array( 'result' => $chatroom );
    	}
    	return array( 'result' => false );
    }

	function fetchChatroomList($dummy = false) {
		$db = eZDB::instance();
		$chatroomClasses =& $this->fetchChatroomClasses();
		$classIds = $chatroomClasses['result'];

        // retrieves ezchatroom attributes that current user can read.
        $chatroomAttributes = array();
        // for each class that uses ezchatroom datatype
        foreach($classIds as $classId) {
        	$contentIdsQuery = "SELECT id FROM ezcontentobject WHERE contentclass_id='".$classId['contentclass_id']."'";
        	$contentIds = $db->arrayQuery( $contentIdsQuery );

        	// for each content that are instance of this class
        	foreach($contentIds as $contentId) {
        		$contentObject =& eZContentObject::fetch($contentId['id']);
        		if ($contentObject->canRead()) {
        			$nodes = $contentObject->assignedNodes();
        			foreach ($nodes as $node) {
        				if ($node->canRead() && $node->attribute("is_hidden")==0 && $node->attribute("is_invisible")==0) {
        					$dataMap =& $contentObject->dataMap();
        					$chatroomAttributes[] = array('object_id' => $contentId['id'], 'chatroom' => $dataMap[$classId['identifier']]->content());
        					continue(2);
        				}
        			}
        		}
        	}
        } // foreach

    	return array( 'result' => $chatroomAttributes );
    }

    function fetchChatroomClasses($dummy = false) {
    	$db = eZDB::instance();
		$classIdsQuery = "SELECT contentclass_id, identifier FROM ezcontentclass_attribute WHERE data_type_string='ezchatroom'";
        return array( 'result' => $db->arrayQuery( $classIdsQuery ) );
    }

}

?>
