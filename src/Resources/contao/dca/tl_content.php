<?php

$GLOBALS['TL_DCA']['tl_content']['palettes']['ynfinite_form'] = '{type_legend},type,headline,ynfinite_form_id,cssID';
$GLOBALS['TL_DCA']['tl_content']['palettes']['ynfinite_filter_form'] = '{type_legend},type,headline,ynfinite_filter_form_id,ynfinite_new_window,ynfinite_template,cssID';
$GLOBALS['TL_DCA']['tl_content']['palettes']['ynfinite_content_list'] = '{type_legend},type,headline,ynfinite_filter_id,ynfinite_filter_form_id,ynfinite_perPage,ynfinite_show_form,ynfinite_jumpTo,ynfinite_template,cssID';
$GLOBALS['TL_DCA']['tl_content']['palettes']['ynfinite_content_single'] = '{type_legend},type,headline,ynfinite_contentType,ynfinite_content_id,ynfinite_template;{title_legend},ynfinite_set_page_title';

$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'ynfinite_set_page_title';
$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'ynfinite_show_form';

$GLOBALS['TL_DCA']['tl_content']['subpalettes']['ynfinite_set_page_title'] = 'ynfinite_title_field';
$GLOBALS['TL_DCA']['tl_content']['subpalettes']['ynfinite_show_form'] = 'ynfinite_form_id';

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

$GLOBALS['TL_DCA']['tl_content']['fields']['ynfinite_template'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['ynfinite_template'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'options_callback'        => array('tl_content_ynfinite', 'getYnfiniteTemplates'),
    'eval'                    => array('tl_class'=>'w50', 'includeBlankOption' => true),
    'sql'                     => "varchar(64) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['ynfinite_filter_form_id'] = array (
    'label' => &$GLOBALS['TL_LANG']['tl_content']['ynfinite_filter_form_id'],
    'exclude' => true,
    'inputType' => 'select',
    'options_callback'  => array('tl_content_ynfinite', 'getFiltersForms'),
    'eval' => array(
        'includeBlankOption' => true,
        'tl_class'=>'clr'
    ),
    'sql' => "blob NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['ynfinite_new_window'] = array (
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['ynfinite_new_window'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'sql'                     => "char(1) NOT NULL default ''"
);

/** FIELDS FOR CONTENT LIST **/

$GLOBALS['TL_DCA']['tl_content']['fields']['ynfinite_filter_id'] = array (
    'label' => &$GLOBALS['TL_LANG']['tl_content']['ynfinite_filter_id'],
    'exclude' => true,
    'inputType' => 'select',
    'options_callback'  => array('tl_content_ynfinite', 'getFilters'),
    'eval' => array(
        'mandatory' >= true,
        'includeBlankOption' => true,
        'tl_class'=>'clr'
    ),
    'sql' => "blob NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['ynfinite_perPage'] = array (
    'label' => &$GLOBALS['TL_LANG']['tl_content']['ynfinite_perPage'],
    'default' => 10,
    'exclude' => true,
    'inputType' => 'text',
    'eval' => array(
        'mandatory'     => true,
        'tl_class'=>'clr'
    ),
    'sql'   => "int(10) unsigned NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['ynfinite_show_form'] = array (
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['ynfinite_show_form'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'eval'                    => array('submitOnChange'=>true),
    'sql'                     => "char(1) NOT NULL default ''"
);

/** FIELDS FOR SINGLE CONTENT **/

$GLOBALS['TL_DCA']['tl_content']['fields']['ynfinite_content_id'] = array (
    'label'             => &$GLOBALS['TL_LANG']['tl_content']['ynfinite_content_id'],
    'inputType'         => 'text',
    'exculde'           => true,
    'eval'              => array(
        'maxLength'     => 255,
        'tl_class'      => 'w100 clr',
    ),
    'sql'               => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['ynfinite_contentType'] = array (
    'label'             => &$GLOBALS['TL_LANG']['tl_content']['ynfinite_contentType'],
    'inputType'         => 'select',
    'options_callback'  => array('tl_content_ynfinite', 'getContentTypes'),
    'load_callback'     => array(array('tl_content_ynfinite', 'getFields')), 
    'exculde'           => true,
    'eval'              => array(
        'maxLength'     => 255,
        'includeBlankOption' => true,
        'mandatory'     => true,
        'tl_class'      => 'w100 clr',
        'submitOnChange'=> true
    ),
    'sql'               => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['ynfinite_set_page_title'] = array (
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['ynfinite_set_page_title'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'eval'                    => array('submitOnChange'=>true),
    'sql'                     => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['ynfinite_title_field'] = array (
    'label'             => &$GLOBALS['TL_LANG']['tl_content']['ynfinite_title_field'],
    'inputType'         => 'select',
    'options'           => array(),
    'exculde'           => true,
    'sql'               => "text NULL"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['ynfinite_jumpTo'] = array (
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['ynfinite_jumpTo'],
    'exclude'                 => true,
    'inputType'               => 'pageTree',
    'foreignKey'              => 'tl_page.title',
    'eval'                    => array('fieldType'=>'radio'), // do not set mandatory (see #5453)
    'sql'                     => "int(10) unsigned NOT NULL default '0'",
    'relation'                => array('type'=>'hasOne', 'load'=>'lazy')
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

    function getFilters() {
        $returnArr = array();
        $objForms = $this->Database->prepare("SELECT id, title FROM tl_ynfinite_filter")->execute();

        //$objForms = YnfiniteFormModel::findAll(array("order" => "title"));
        if ($objForms->numRows){
            while($objForms->next()) {
                $form = $objForms->row();
                $returnArr[$form['id']] = $form['title'];
            }
        }
        return $returnArr;
    }

   function getFiltersForms() {
        $returnArr = array();
        $objForms = $this->Database->prepare("SELECT id, title FROM tl_ynfinite_filter_form")->execute();

        if ($objForms->numRows){
            while($objForms->next()) {
                $form = $objForms->row();
                $returnArr[$form['id']] = $form['title'];
            }
        }
        return $returnArr;
    }    

    function getContentTypes() {
        $loadDataService = \Contao\System::getContainer()->get("ynfinite.contao-com.listener.communication");
        return $loadDataService->fetchContentTypesContent();
    }

    function getFields($strValue, DataContainer $dc) {
        $loadDataService = \Contao\System::getContainer()->get("ynfinite.contao-com.listener.communication");
        $fieldOptions = $loadDataService->getContentTypeFieldOptions($strValue);

        $GLOBALS['TL_DCA']['tl_content']['fields']['ynfinite_title_field']['options'] = $fieldOptions;

        return $strValue;
    }

    public function getYnfiniteTemplates()
    {
        return $this->getTemplateGroup('ce_ynfinite_content_');
    }
}