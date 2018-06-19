<?php

$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] = str_replace('{chmod_legend:hide},', '{ynfinite_legend},ynfinite_api_token,ynfinite_cms_id;{chmod_legend:hide},', $GLOBALS['TL_DCA']['tl_settings']['palettes']['default']);

$GLOBALS['TL_DCA']['tl_settings']['fields']['ynfinite_api_token'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_settings']['ynfinite_api_token'],
    'exclude' => true,
	'inputType' => 'text',
	'eval' => array('tl_class'=>'w100'),
	'sql' => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['ynfinite_cms_id'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_settings']['ynfinite_cms_id'],
    'exclude' => true,
	'inputType' => 'text',
	'save_callback'	=> array(array('tl_settings_ynfinite', 'generateUid')),
	'eval' => array('tl_class'=>'w100'),
	'sql' => "varchar(255) NOT NULL default ''"
);

class tl_settings_ynfinite extends tl_settings {

    function generateUid($fieldValue) {
    	if(!$fieldValue) {
			return uniqid();
		}
		else {
			return $fieldValue;
		}
    }

}