<?php


$GLOBALS['TL_DCA']['tl_article']['palettes']['__selector__'][] = 'hideTroughYnfinite';
$GLOBALS['TL_DCA']['tl_article']['palettes']['default'] = str_replace("cssID;", "cssID;{ynfinite_legend},hideTroughYnfinite;", $GLOBALS['TL_DCA']['tl_article']['palettes']['default']);
$GLOBALS['TL_DCA']['tl_article']['subpalettes']['hideTroughYnfinite'] = 'ynfinite_contentType,ynfinite_content_id,ynfinite_publish_field';



$GLOBALS['TL_DCA']['tl_article']['fields']['hideTroughYnfinite'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_article']['hideTroughYnfinite'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('submitOnChange'=>true),
	'sql'                     => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_article']['fields']['ynfinite_contentType'] = array (
    'label'             => &$GLOBALS['TL_LANG']['tl_article']['ynfinite_contentType'],
    'inputType'         => 'select',
    'options_callback'  => array('tl_article_ynfinite', 'getContentTypes'),
    'load_callback'     => array(array('tl_article_ynfinite', 'getFields')), 
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

$GLOBALS['TL_DCA']['tl_article']['fields']['ynfinite_content_id'] = array (
    'label'             => &$GLOBALS['TL_LANG']['tl_article']['ynfinite_content_id'],
    'inputType'         => 'text',
    'exculde'           => true,
    'eval'              => array(
        'maxLength'     => 255,
        'tl_class'      => 'w100 clr',
    ),
    'sql'               => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_article']['fields']['ynfinite_publish_field'] = array (
    'label'             => &$GLOBALS['TL_LANG']['tl_article']['ynfinite_publish_field'],
    'inputType'         => 'select',
    'options'           => array(),
    'exculde'           => true,
    'sql'               => "text NULL"
);


class tl_article_ynfinite extends \tl_article {

    function getContentTypes() {
        $loadDataService = \Contao\System::getContainer()->get("ynfinite.contao-com.listener.communication");
        return $loadDataService->fetchContentTypesContent();
    }

    function getFields($strValue, DataContainer $dc) {
        $loadDataService = \Contao\System::getContainer()->get("ynfinite.contao-com.listener.communication");
        $fieldOptions = $loadDataService->getContentTypeFieldOptions($strValue);

        $GLOBALS['TL_DCA']['tl_article']['fields']['ynfinite_publish_field']['options'] = $fieldOptions;

        return $strValue;
    }
}