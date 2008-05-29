<?php

// Operator autoloading

$eZTemplateOperatorArray = array();

$eZTemplateOperatorArray[] =
   array( 'script' => 'extension/ezchat/autoloads/smalloperators.php',
                                    'class' => 'SmallOperators',
                                    'operator_names' => array( 'chat_opened' ) );

?>