<?php

$GLOBALS['TL_DCA']['tl_ynfinite_form'] = array(

    'config' => array(
        'dataContainer'		=> 'Table',
        'switchToEdit'		=> true,
        'ctable'            => array('tl_ynfinite_form_field'),
        'enableVersioning' 	=> true,
        'sql'				=> array (
            'keys'			=> array(
                'id'		=> 'primary'
            )
        )
    ),
    'list' => array(
        'sorting'           => array(
            'mode'          => 1,
            'fields'        => array('title'),
            'flag'          => 1,
            'panelLayout'   => 'filter;sort,search,limit'
        ),
        'label' => array(
            'fields'			=> array('title'),
            'format'			=> '%s'
        ),
        'global_operations'		=> array(
            'all' 				=> array(
                'label'			=> &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'			=> 'act=select',
                'class'			=> 'header_edit_all',
                'attributes'	=> 'onclick="Backend.getScrollOffset()" acceskey="e"'
            )
        ),
        'operations' => array(
            'edit'				=> array(
                'label'			=> &$GLOBALS['TL_LANG']['tl_ynfinite_form']['edit'],
                'href'          => 'table=tl_ynfinite_form_field',
                'icon'			=> 'edit.svg'
            ),
            'editForm'              => array(
                'label'         => &$GLOBALS['TL_LANG']['tl_ynfinite_form']['editForm'],
                'href'          => 'act=edit',
                'icon'          => 'header.svg'
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_ynfinite_form']['copy'],
                'href'                => 'act=paste&amp;mode=copy',
                'icon'                => 'copy.gif',
                'attributes'          => 'onclick="Backend.getScrollOffset()"',
            ),            
            'delete'			=>array(
                'label'			=> &$GLOBALS['TL_LANG']['tl_ynfinite_form']['delete'],
                'href'			=> 'act=delete',
                'icon'			=> 'delete.svg',
                'attributes'	=> 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ),
            'show'				=>array(
                'label'			=> &$GLOBALS['TL_LANG']['tl_ynfinite_form']['show'],
                'href'			=> 'act=show',
                'icon'			=> 'show.svg'
            )
        )
    ),
    'palettes'	=> array(
        'default'   => '{information_legend},title,leadType,formFields,showAdditionalFields;'
    ),
    'fields' 	=> array(
        'id'					=> array(
            'sql'				=> "int(10) unsigned NOT NULL auto_increment"
        ),
        'sorting' => array
        (
            'sql'               => "int(10) unsigned NOT NULL default '0'"
        ),
        'tstamp'				=> array(
            'sql' 				=> "int(10) unsigned NOT NULL default '0'"
        ),
        'title'  				=> array(
            'label' 			=> &$GLOBALS['TL_LANG']['tl_ynfinite_form']['title'],
            'inputType'			=> 'text',
            'exculde'			=> true,
            'sorting'			=> true,
            'flag'				=> 1,
            'search'			=> true,
            'eval'				=> array(
                'mandatory'		=> true,
                'maxLength'		=> 255,
                'tl_class'		=> 'w100'
            ),
            'sql'				=> "varchar(255) NOT NULL default ''"
        ),
        'leadType'             => array(
            'label'             => &$GLOBALS['TL_LANG']['tl_ynfinite_form']['leadType'],
            'inputType'         => 'select',
            'options_callback'  => array('tl_ynfinite_form', 'getLeadTypes'),
            'load_callback'     => array(array('tl_ynfinite_form', 'getContentType')), 
            'exculde'           => true,
            'eval'              => array(
                'maxLength'     => 255,
                'includeBlankOption' => true,
                'mandatory'     => true,
                'tl_class'      => 'w100',
                'submitOnChange'=> true
            ),
            'sql'               => "varchar(255) NOT NULL default ''"
        ),
        'formFields'             => array(
            'label'             => &$GLOBALS['TL_LANG']['tl_ynfinite_form']['formFields'],
            'inputType'         => 'checkboxWizard',
            'options'           => array(),
            'exculde'           => true,
            'eval'              => array('multiple'=>true, 'helpwizard'=>true),
            'sql'               => "text NULL"
        ),
        'showAdditionalFields'             => array(
            'label'             => &$GLOBALS['TL_LANG']['tl_ynfinite_form']['showAdditionalFields'],
            'inputType'         => 'select',
            'options'           => array("Davor", "Dahinter"),
            'exculde'           => true,
            'eval'              => array(
                'maxLength'     => 255,
                'includeBlankOption' => true,
                'tl_class'      => 'w100',
            ),
            'sql'               => "varchar(255) NOT NULL default ''"
        ),        
    )
);

class tl_ynfinite_form extends Backend
{

    function getLeadTypes() {
        
        $loadDataService = \Contao\System::getContainer()->get("ynfinite.contao-com.listener.communication");
        return $loadDataService->fetchContentTypesLeads();

    }

    function getContentType($strValue, DataContainer $dc) {
        $loadDataService = \Contao\System::getContainer()->get("ynfinite.contao-com.listener.communication");
        $fieldOptions = $loadDataService->getContentTypeFieldOptions($strValue);

        $GLOBALS['TL_DCA']['tl_ynfinite_form']['fields']['formFields']['options'] = $fieldOptions;

        return $strValue;
    }
}
