<?php
	$GLOBALS['BE_MOD']['ynfinite'] = array();

	$GLOBALS['BE_MOD']['ynfinite']['ynfinite_forms'] = array(
	    'tables' => array('tl_ynfinite_form', 'tl_ynfinite_form_hooks'),
	    'icon'   => __DIR__.'../../public/assets/icon.png'
	);

	$GLOBALS['BE_MOD']['ynfinite']['ynfinite_filter'] = array(
	    'tables' => array('tl_ynfinite_filter', 'tl_ynfinite_filter_fields'),
	    'icon'   => __DIR__.'../../public/assets/icon.png'
	);

	$GLOBALS['BE_MOD']['ynfinite']['ynfinite_filter_forms'] = array(
	    'tables' => array('tl_ynfinite_filter_form', 'tl_ynfinite_filter_form_fields'),
	    'icon'   => __DIR__.'../../public/assets/icon.png'
	);

	$GLOBALS['BE_MOD']['ynfinite']['ynfinite_cache'] = array(
	    'tables' => array('tl_ynfinite_cache'),
	    'icon'   => __DIR__.'../../public/assets/icon.png'
	);

	$GLOBALS['TL_MODELS']['tl_ynfinite_cache'] = 'Ynfinite\YnfiniteCacheModel';
	$GLOBALS['TL_MODELS']['tl_ynfinite_form'] = 'Ynfinite\YnfiniteFormModel';
	$GLOBALS['TL_MODELS']['tl_ynfinite_filter_form'] = 'Ynfinite\YnfiniteFilterFormModel';
	$GLOBALS['TL_MODELS']['tl_ynfinite_filter_form_fields'] = 'Ynfinite\YnfiniteFilterFormFieldsModel';
	$GLOBALS['TL_MODELS']['tl_ynfinite_filter'] = 'Ynfinite\YnfiniteFilterModel';
	$GLOBALS['TL_MODELS']['tl_ynfinite_filter_fields'] = 'Ynfinite\YnfiniteFilterFieldsModel';

	$GLOBALS['TL_CTE']['ynfinite'] = array(
	    'ynfinite_form' => 'Ynfinite\ContentForm',
	    'ynfinite_filter_form' => 'Ynfinite\ContentFilterForm',
	    'ynfinite_content_list' => 'Ynfinite\ContentList',
	    'ynfinite_content_single' => 'Ynfinite\ContentSingle'
	);

	$GLOBALS['TL_HOOKS']['compileArticle'][] = array('Ynfinite\CheckArticle', 'checkVisibility');

	/* YNFINITE DATA */

	$GLOBALS['TL_YNFINITE_HOOKS'] = array( // Not Used Yet
		"yh_frontend_befor_send_data",
		"yh_frontend_after_send_data"
	);

	$GLOBALS['TL_YNFINITE_FILTER_OPERATIONS'] = array(
		"b" => "beinhaltet" , 
		"kg" => "kleiner gleich" , 
		"gg" => "größer gleich", 
		"z" => "zwischen", 
		"u" => "ungleich"
	);

	$GLOBALS['TL_YNFINITE_FILTER_FORM_TYPES'] = array(
		'text'        => 'Text',
		'range'    => 'Bereich',
		'select'      => 'Auswahl',
		'checkbox'    => 'Checkbox',
		'hidden'      => 'Verstecktes Feld'
	);

	$GLOBALS['TL_LANG']['FFL']['range'] = array("Bereich", "Filterfeld für eine Bereichauswahl");
?>