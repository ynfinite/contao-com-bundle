<?php
	$GLOBALS['BE_MOD']['ynfinite'] = array();

	$GLOBALS['BE_MOD']['ynfinite']['ynfinite_forms'] = array(
	    'tables' => array('tl_ynfinite_form', 'tl_ynfinite_form_field'),
	    'icon'   => __DIR__.'../../public/assets/icon.png'
	);

	$GLOBALS['BE_MOD']['ynfinite']['ynfinite_content'] = array(
	    'tables' => array('tl_ynfinite_content'),
	    'icon'   => __DIR__.'../../public/assets/icon.png'
	);

	$GLOBALS['TL_MODELS']['tl_ynfinite_form'] = 'Ynfinite\YnfiniteFormModel';

	$GLOBALS['TL_CTE']['ynfinite'] = array(
	    'ynfinite_form' => 'Ynfinite\ContentForm'
	);
?>