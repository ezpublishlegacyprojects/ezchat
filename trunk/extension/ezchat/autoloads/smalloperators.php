<?php

include_once("kernel/classes/ezcontentobjecttreenode.php");
include_once("kernel/classes/ezcontentobject.php");
include_once("lib/ezutils/classes/ezhttptool.php");

class SmallOperators {

	function SmallOperators() {
		$this->Operators = array(
			'chat_opened',

		);
		$this->NamedOperators = array(

		);
	}

	function modify($tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters) {
		switch ( $operatorName ) {
			case 'chat_opened':
            	$operatorValue = $this->chat_opened($operatorValue);
            	break;
			default :
				$tpl->warning( $operatorName, "Unknown operator '$operatorName'" );
            	break;
		}
	}

    function chat_opened(&$value) {
		$time = time();
		// Check the opening hours:
		if(($value->attribute('start_value') > date('G', $time)) || ($value->attribute('stop_value') <= date('G', $time)))
			return false;
		// Check the opening weekdays:
		$optionList = $value->attribute('option_list');
		if(!in_array(date('w', $time), explode('-',$optionList[3]["value"]) ))
			return false;
		return true;
    }




	function operatorList()	{
		return $this->Operators;
	}

	function namedParameterPerOperator() {
		return true;
	}

	function namedParameterList() {
		return $this->NamedOperators;
	}
}
?>