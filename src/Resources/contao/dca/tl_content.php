<?php

$GLOBALS['TL_DCA']['tl_content']['palettes']['ynfinite_form'] = '{type_legend},type,headline,ynfinite_form_id,cssID';

$GLOBALS['TL_DCA']['tl_content']['fields']['ynfinite_form_id'] = array (
    'label' => &$GLOBALS['TL_LANG']['tl_content']['ynfinite_form_id'],
    'exclude' => true,
    'inputType' => 'select',
    'options_callback'	=> array('tl_content_ynfinite', 'getForms'),
    'eval' => array(
		'mandatory'     => true,
		'includeBlankOption' => true,
		'tl_class'=>'clr'
    ),
    'sql' => "blob NOT NULL default ''"
);

class tl_content_ynfinite extends \tl_content {

	function getForms() {
        $returnArr = array();
		$objForms = $this->Database->prepare("SELECT id, title FROM tl_ynfinite_form")->execute();

	    //$objForms = YnfiniteFormModel::findAll(array("order" => "title"));
        if ($objForms->numRows){
            while($objForms->next()) {
            	$form = $objForms->row();
                $returnArr[$form['id']] = $form['title'];
            }
        }
        return $returnArr;
    }
}