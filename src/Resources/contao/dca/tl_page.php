<?php

$GLOBALS['TL_DCA']['tl_page']['palettes']['regular'] = str_replace("guests;", "guests;{ynfinite_legend},ynfinite_contentType;", $GLOBALS['TL_DCA']['tl_page']['palettes']['regular']);


$GLOBALS['TL_DCA']['tl_page']['fields']['ynfinite_contentType'] = array (
    'label'             => &$GLOBALS['TL_LANG']['tl_page']['ynfinite_contentType'],
    'inputType'         => 'select',
    'options_callback'  => array('tl_page_ynfinite', 'getContentTypes'),
    'exculde'           => true,
    'eval'              => array(
        'maxLength'     => 255,
        'includeBlankOption' => true,
        'tl_class'      => 'w100 clr',
        'submitOnChange'=> true
    ),
    'sql'               => "varchar(255) NOT NULL default ''"
);


class tl_page_ynfinite extends \tl_page {

    function getContentTypes() {
        $loadDataService = \Contao\System::getContainer()->get("ynfinite.contao-com.listener.communication");
        return $loadDataService->fetchContentTypesContent();
    }
}

?>