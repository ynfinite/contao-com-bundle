<?php

$GLOBALS['TL_DCA']['tl_ynfinite_filter'] = array(

    'config' => array(
        'dataContainer'		=> 'Table',
        'switchToEdit'		=> true,
        'ctable'            => array('tl_ynfinite_filter_fields'),
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
                'label'         => &$GLOBALS['TL_LANG']['tl_ynfinite_filter']['edit'],
                'href'          => 'act=edit',
                'icon'          => 'edit.svg'
            ),
            'editFields'             => array(
                'label'         => &$GLOBALS['TL_LANG']['tl_ynfinite_filter']['editFields'],
                'href'          => 'table=tl_ynfinite_filter_fields',
                'icon'          => 'header.svg'                
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_ynfinite_filter']['copy'],
                'href'                => 'act=paste&amp;mode=copy',
                'icon'                => 'copy.gif',
                'attributes'          => 'onclick="Backend.getScrollOffset()"',
            ),            
            'delete'			=>array(
                'label'			=> &$GLOBALS['TL_LANG']['tl_ynfinite_filter']['delete'],
                'href'			=> 'act=delete',
                'icon'			=> 'delete.svg',
                'attributes'	=> 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ),
            'show'				=>array(
                'label'			=> &$GLOBALS['TL_LANG']['tl_ynfinite_filter']['show'],
                'href'			=> 'act=show',
                'icon'			=> 'show.svg'
            )
        )
    ),
    'palettes'	=> array(
        'default'   => '{information_legend},title,contentType,useTags,sortFields,sortDirection,alias;'
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
            'label' 			=> &$GLOBALS['TL_LANG']['tl_ynfinite_filter']['title'],
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
        'contentType'             => array(
            'label'             => &$GLOBALS['TL_LANG']['tl_ynfinite_filter']['contentType'],
            'inputType'         => 'select',
            'options_callback'  => array('tl_ynfinite_filter', 'getContentTypes'),
            'load_callback'     => array(array('tl_ynfinite_filter', 'getFields')), 
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
        'useTags' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_ynfinite_filter']['useTags'],
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'checkbox',
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'sortFields'             => array(
            'label'             => &$GLOBALS['TL_LANG']['tl_ynfinite_filter']['sortFields'],
            'inputType'         => 'checkboxWizard',
            'options'           => array(),
            'exculde'           => true,
            'eval'              => array('multiple'=>true),
            'sql'               => "text NULL"
        ),
        'sortDirection'             => array(
            'label'             => &$GLOBALS['TL_LANG']['tl_ynfinite_filter']['sortDirection'],
            'inputType'         => 'select',
            'options'           => array("asc" => "Aufsteigend", "desc" => "Absteigend"),
            'exculde'           => true,
            'sql'               => "text NULL"
        ), 
        'alias'             => array(
            'label'             => &$GLOBALS['TL_LANG']['tl_ynfinite_filter']['alias'],
            'inputType'         => 'select',
            'options'           => array(),
            'exculde'           => true,
            'eval'              => array("includeBlankOption" => true),
            'sql'               => "text NULL"
        ),       
    )
);

class tl_ynfinite_filter extends Backend
{
    function getContentTypes() {
        $loadDataService = \Contao\System::getContainer()->get("ynfinite.contao-com.listener.communication");
        return $loadDataService->fetchContentTypesContent();
    }

    function getFields($strValue, DataContainer $dc) {
        $loadDataService = \Contao\System::getContainer()->get("ynfinite.contao-com.listener.communication");
        $fieldOptions = $loadDataService->getContentTypeFieldOptions($strValue);
        $fieldOptionsSort = $loadDataService->getContentTypeFieldOptions($strValue, true);

        $GLOBALS['TL_DCA']['tl_ynfinite_filter']['fields']['sortFields']['options'] = $fieldOptionsSort;
        $GLOBALS['TL_DCA']['tl_ynfinite_filter']['fields']['alias']['options'] = $fieldOptions;

        return $strValue;
    }
}
