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

require_once( "kernel/classes/ezdatatype.php" );
require_once( "extension/ezchat/datatypes/ezchatroom/ezchatroom.php" );
require_once( "extension/ezchat/modules/ezchat/functioncollection.php" );


define( "EZ_DATATYPESTRING_CHATROOM", "ezchatroom" );

class ezchatroomtype extends eZDataType
{
    /*!
      Constructor
    */
    function ezchatroomtype() {
        $this->eZDataType( EZ_DATATYPESTRING_CHATROOM, ezi18n( 'ezchat/datatypes/chatroom', "Chatroom", 'Datatype name' )/*,
        					array( 'serialize_supported' => true )*/ );
    }

    /*!
    Validates all variables given on content class level
     \return EZ_INPUT_VALIDATOR_STATE_ACCEPTED or EZ_INPUT_VALIDATOR_STATE_INVALID if
             the values are accepted or not
    */
    function validateClassAttributeHTTPInput( &$http, $base, &$classAttribute ) {
        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
    }

    /*!
     Fetches all variables inputed on content class level
     \return true if fetching of class attributes are successfull, false if not
    */
    function fetchClassAttributeHTTPInput( &$http, $base, &$classAttribute ) {
        //$attributeContent = $this->classAttributeContent( $classAttribute );
        $classAttributeID = $classAttribute->attribute( 'id' );

        // allowPrivateChannels to data_int1
    	if ( $http->hasPostVariable( $base . '_ezchatroom_allowprivatechannels_value_' . $classAttributeID . '_exists' ) ) {
    		if ( $http->hasPostVariable( $base . "_ezchatroom_allowprivatechannels_value_" . $classAttributeID ) ) {
    			$allowPrivateChannels = $http->postVariable( $base . "_ezchatroom_allowprivatechannels_value_" . $classAttributeID );
                if ( isset( $allowPrivateChannels ) ) $allowPrivateChannels = 1;
                $classAttribute->setAttribute( "data_int1", $allowPrivateChannels );
    		} else $classAttribute->setAttribute( "data_int1", 0 );
    	}

        // allowUserMessageDelete to data_int2
    	if ( $http->hasPostVariable( $base . '_ezchatroom_allowusermessagedelete_value_' . $classAttributeID . '_exists' ) ) {
    		if ( $http->hasPostVariable( $base . "_ezchatroom_allowusermessagedelete_value_" . $classAttributeID ) ) {
    			$allowUserMessageDelete = $http->postVariable( $base . "_ezchatroom_allowusermessagedelete_value_" . $classAttributeID );
                if ( isset( $allowUserMessageDelete ) ) $allowUserMessageDelete = 1;
                $classAttribute->setAttribute( "data_int2", $allowUserMessageDelete );
    		} else $classAttribute->setAttribute( "data_int2", 0 );
    	}

    	return true;
    }

    /*!
     Validates input on content object level
     \return EZ_INPUT_VALIDATOR_STATE_ACCEPTED or EZ_INPUT_VALIDATOR_STATE_INVALID if
             the values are accepted or not
    */
    function validateObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute ) {
    	if ( $http->hasPostVariable( $base . '_ezchatroom_data_text_' . $contentObjectAttribute->attribute( 'id' ) ) ) {
            $channelName = $http->postVariable( $base . '_ezchatroom_data_text_' . $contentObjectAttribute->attribute( 'id' ) );
            if ( $channelName == "" and $contentObjectAttribute->validateIsRequired()) {
            	return EZ_INPUT_VALIDATOR_STATE_INVALID;
            } else {
            	// check if channel name is not too long
            	require_once( "lib/ezutils/classes/ezini.php" );
            	$ezchatIni =& eZINI::instance( 'ezchat.ini' );
            	$maxLen = $ezchatIni->variableArray( 'chatroomDatatype', 'channelNameMaxLength' );
        		$textCodec = eZTextCodec::instance( false );
		        if ( $textCodec->strlen( $channelName ) > $maxLen and $maxLen > 0 ) {
            		$contentObjectAttribute->setValidationError( ezi18n( 'ezchat/datatypes/chatroom',
                                                                 'The channel name is too long. The maximum number of characters allowed is %1.' ),
                                                         		 $maxLen );
            		return EZ_INPUT_VALIDATOR_STATE_INVALID;
        		}
        		// check if no special chars in channel name
        		$regexp = $ezchatIni->variable( 'chatroomDatatype', 'channelNameAllowedCharsRegexp' );
        		$matches = array();
        		if (preg_match("/".$regexp."/", $channelName, &$matches)==0 || $matches[0]!=$channelName) {
        			$contentObjectAttribute->setValidationError( ezi18n( 'ezchat/datatypes/chatroom',
                                                                 'The channel name contains forbidden characters or spaces.' ) );

            		return EZ_INPUT_VALIDATOR_STATE_INVALID;
        		}

        		return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
            }
    	} // channelName

        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
    }

    /*!
     Fetches all variables from the object
     \return true if fetching of class attributes are successfull, false if not
    */
    function fetchObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        if ( ! $http->hasPostVariable( $base . '_ezchatroom_data_text_' . $contentObjectAttribute->attribute( 'id' ) ) ) return false;
        $data = $http->postVariable( $base . '_ezchatroom_data_text_' . $contentObjectAttribute->attribute( 'id' ) );
        $XMLParams = new eZChatroom( $data );

    	if ( $http->hasPostVariable( $base . "_ezchatroom_allowprivatemessages_boolean_" . $contentObjectAttribute->attribute( "id" ) )) {
            $data = $http->postVariable( $base . "_ezchatroom_allowprivatemessages_boolean_" . $contentObjectAttribute->attribute( "id" ) );
            if ( isset( $data ) && $data !== '0' && $data !== 'false' )
                $XMLParams->addOption("allowprivatemessages",1);
            else
                $XMLParams->addOption("allowprivatemessages",0);
        } else $XMLParams->addOption("allowprivatemessages",0);

		if ( $http->hasPostVariable( $base . "_ezchatroom_showchannelmessages_boolean_" . $contentObjectAttribute->attribute( "id" ) )) {
            $data = $http->postVariable( $base . "_ezchatroom_showchannelmessages_boolean_" . $contentObjectAttribute->attribute( "id" ) );
            if ( isset( $data ) && $data !== '0' && $data !== 'false' )
                $XMLParams->addOption("showchannelmessages",1);
            else
                $XMLParams->addOption("showchannelmessages",0);
        } else $XMLParams->addOption("showchannelmessages",0);

        if ( $http->hasPostVariable( $base . "_ezchatroom_requestmessagespriorchannelenter_boolean_" . $contentObjectAttribute->attribute( "id" ) )) {
            $data = $http->postVariable( $base . "_ezchatroom_requestmessagespriorchannelenter_boolean_" . $contentObjectAttribute->attribute( "id" ) );
            if ( isset( $data ) && $data !== '0' && $data !== 'false' )
                $XMLParams->addOption("requestmessagespriorchannelenter",1);
            else
                $XMLParams->addOption("requestmessagespriorchannelenter",0);
        } else $XMLParams->addOption("requestmessagespriorchannelenter",0);

		if ( $http->hasPostVariable( $base . '_ezchatroom_hours_array_' . $contentObjectAttribute->attribute( 'id' ) ) )
        {
            $selectHours = $http->postVariable( $base . '_ezchatroom_hours_array_' . $contentObjectAttribute->attribute( 'id' ) );
            if (is_array($selectHours) && count($selectHours)>0) {
            	$XMLParams->setStartValue($selectHours[0]);
            	$XMLParams->setStopValue($selectHours[count($selectHours)-1]+1);
            } else {
	        	$XMLParams->setStartValue(0);
	            $XMLParams->setStopValue(0);
	        }
        } else {
        	$XMLParams->setStartValue(0);
            $XMLParams->setStopValue(0);
        }

    	if ( $http->hasPostVariable( $base . '_ezchatroom_days_array_' . $contentObjectAttribute->attribute( 'id' ) ) )
        {
            $selectDays = $http->postVariable( $base . '_ezchatroom_days_array_' . $contentObjectAttribute->attribute( 'id' ) );
            $idString = ( is_array( $selectDays ) ? implode( '-', $selectDays ) : "" );
            $XMLParams->addOption("days", $idString);
        } else $XMLParams->addOption("days", array());

        if ( $http->hasPostVariable( $base . "_ezchatroom_deactivateGMaps_boolean_" . $contentObjectAttribute->attribute( "id" ) )) {
            $data = $http->postVariable( $base . "_ezchatroom_deactivateGMaps_boolean_" . $contentObjectAttribute->attribute( "id" ) );
            if ( isset( $data ) && $data !== '0' && $data !== 'false' )
                $XMLParams->addOption("deactivateGMaps",1);
            else
                $XMLParams->addOption("deactivateGMaps",0);
        } else $XMLParams->addOption("deactivateGMaps",0);

        $contentObjectAttribute->setAttribute( 'data_text', $XMLParams->xmlString() );
		$contentObjectAttribute->setContent( $XMLParams );

        return true;
    }

    function storeObjectAttribute( $contentObjectAttribute )
    {
        $XMLParams = $contentObjectAttribute->content();
        $contentObjectAttribute->setAttribute( "data_text", $XMLParams->xmlString() );
    }

    /*!
     Returns the content.
    */
    function objectAttributeContent( $contentObjectAttribute )
    {
        $XMLParams = new eZChatroom( "" );
        $XMLParams->decodeXML( $contentObjectAttribute->attribute( "data_text" ) );
        $contentClassAttribute = $contentObjectAttribute->contentClassAttribute();
        $XMLParams->setAllowPrivateChannels($contentClassAttribute->attribute( "data_int1" )==1);
        $XMLParams->setAllowUserMessageDelete($contentClassAttribute->attribute( "data_int2" )==1);
        $object = $contentObjectAttribute->object();
        $XMLParams->setLoginURL("/".$object->mainNode()->urlAlias());
        return $XMLParams;
    }

    /*!
     \reimp
    */
    function isInformationCollector()
    {
        return false; //TODO: on pourrait s'en servir pour collecter des donnees, genre une adresse de flux video personnel ?
    }

	/*!
      \reimp
    */
    function diff( $old, $new, $options = false )
    {
        //include_once( 'lib/ezdiff/classes/ezdiff.php' );
        $diff = new eZDiff();
        $diff->setDiffEngineType( $diff->engineType( 'text' ) );
        $diff->initDiffEngine();
        $diffObject = $diff->diff( $old->content(), $new->content() );
        return $diffObject;
    }




    // Le reste en dessous ne devrait plus bouger

    function metaData( $contentObjectAttribute )
    {
        $XMLParams = new eZChatroom( "" );
        $XMLParams->decodeXML( $contentObjectAttribute->attribute( "data_text" ) );
        return $XMLParams->attribute('name');
    }

    function title( $contentObjectAttribute, $name = null )
    {
        $XMLParams = new eZChatroom( "" );
        $XMLParams->decodeXML( $contentObjectAttribute->attribute( "data_text" ) );
        return $XMLParams->attribute('name');
    }

    function isIndexable()
    {
        return true;
    }

    function sortKey( $contentObjectAttribute )
    {
        $trans = eZCharTransform::instance();
        return $trans->transformByGroup( $this->title($contentObjectAttribute), 'lowercase' );
    }

    function sortKeyType()
    {
        return 'string';
    }

}

eZDataType::register( EZ_DATATYPESTRING_CHATROOM, "ezchatroomtype" );


?>