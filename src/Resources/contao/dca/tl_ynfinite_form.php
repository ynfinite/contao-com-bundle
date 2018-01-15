<?php

$GLOBALS['TL_DCA']['tl_ynfinite_form'] = array(

    'config' => array(
        'dataContainer'		=> 'Table',
        'switchToEdit'		=> true,
        'ctable'            => array('tl_ynfinite_form_hooks'),
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
            'edit'              => array(
                'label'         => &$GLOBALS['TL_LANG']['tl_ynfinite_form']['edit'],
                'href'          => 'act=edit',
                'icon'          => 'edit.svg'
            ),
            'editHooks'             => array(
                'label'         => &$GLOBALS['TL_LANG']['tl_ynfinite_form']['editHooks'],
                'href'          => 'table=tl_ynfinite_form_hooks',
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
        'default'   => '{information_legend},title,leadType,formFields,introductionText,successText;{authorize_legend},authorizeForm,authorizationFields,sendAuthorizationTo;{send_legend},sendDataToYnfinite,sendDataViaEmail,targetEmail;'
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
        'introductionText' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_ynfinite_form']['introductionText'],
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'textarea',
            'eval'                    => array('mandatory'=>false, 'rte'=>'tinyMCE', 'helpwizard'=>true),
            'explanation'             => 'insertTags',
            'sql'                     => "mediumtext NULL"
        ),  
        'successText' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_ynfinite_form']['successText'],
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'textarea',
            'eval'                    => array('mandatory'=>false, 'rte'=>'tinyMCE', 'helpwizard'=>true),
            'explanation'             => 'insertTags',
            'sql'                     => "mediumtext NULL"
        ),  
        'sendDataToYnfinite' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_ynfinite_form']['sendDataToYnfinite'],
            'exclude'                 => true,
            'inputType'               => 'checkbox',
            'eval'                    => array(),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'authorizeForm' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_ynfinite_form']['authorizeForm'],
            'exclude'                 => true,
            'inputType'               => 'checkbox',
            'eval'                    => array(),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'authorizationFields'   => array(
            'label'             => &$GLOBALS['TL_LANG']['tl_ynfinite_form']['authorizationFields'],
            'inputType'         => 'checkboxWizard',
            'options'           => array(),
            'exculde'           => true,
            'eval'              => array('multiple'=>true),
            'sql'               => "text NULL"
        ),
        'sendAuthorizationTo'   => array(
            'label'             => &$GLOBALS['TL_LANG']['tl_ynfinite_form']['sendAuthorizationTo'],
            'inputType'         => 'select',
            'options'           => array(),
            'exculde'           => true,
            'eval'              => array(),
            'sql'               => "text NULL"
        ),
        'sendDataViaEmail' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_ynfinite_form']['sendDataViaEmail'],
            'exclude'                 => true,
            'inputType'               => 'checkbox',
            'eval'                    => array(),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'targetEmail'                 => array(
            'label'             => &$GLOBALS['TL_LANG']['tl_ynfinite_form']['targetEmail'],
            'inputType'         => 'text',
            'exculde'           => true,
            'sorting'           => true,
            'flag'              => 1,
            'search'            => true,
            'eval'              => array(
                'maxLength'     => 255,
                'tl_class'      => 'w100'
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
        $GLOBALS['TL_DCA']['tl_ynfinite_form']['fields']['authorizationFields']['options'] = $fieldOptions;
        $GLOBALS['TL_DCA']['tl_ynfinite_form']['fields']['sendAuthorizationTo']['options'] = $fieldOptions;

        return $strValue;
    }
}
