<?php

include_once('extension/nmvcard/classes/class.vCard.inc.php');
include_once( "lib/ezutils/classes/ezini.php" );

// initiate object
$vCard 	= (object) new vCard('var/cache','');
$ini 	= eZINI::instance( 'vcard.ini' );	

// fetch node
$node = eZContentObjectTreeNode::fetch( $Params['NodeID'] );

// get node class identifier
$classIdentifier = $node->attribute('class_identifier');

// get node data map
$dataMap = $node->dataMap();

// INI block
$iniBlock = 'Class_' . $classIdentifier;

// fetch INI fields
$iniFields = $ini->variable( $iniBlock, 'Fields' );

// for each INI field
foreach($iniFields as $value)
{
	$array 		= explode(";", $value);
	$methodName = 'set' . $array[0];
	$attribute	= $array[1];
	
	// if the attribute contains a hard-coded text
	if(strstr($attribute, '"'))
	{
		$data = trim($attribute, '"');
	}
	else
	{
		$data = $dataMap[$attribute]->content();
	}
	
	$vCard->$methodName($data);
}

$vCard->outputFile('vcf');

$Result['pagelayout'] = false;

?>
