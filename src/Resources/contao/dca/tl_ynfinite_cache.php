<?php

$GLOBALS['TL_DCA']['tl_ynfinite_cache'] = array(

    'config' => array(
        'dataContainer'		=> 'Table',
        'switchToEdit'		=> true,
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
            'fields'        => array('url'),
            'flag'          => 1,
            'panelLayout'   => 'filter;sort,search,limit'
        ),
        'label' => array(
            'fields'			=> array('methode', 'url'),
            'format'			=> '%s: %s'
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
            'delete'			=>array(
                'label'			=> &$GLOBALS['TL_LANG']['tl_ynfinite_cache']['delete'],
                'href'			=> 'act=delete',
                'icon'			=> 'delete.svg',
                'attributes'	=> 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ),
            'show'				=>array(
                'label'			=> &$GLOBALS['TL_LANG']['tl_ynfinite_cache']['show'],
                'href'			=> 'act=show',
                'icon'			=> 'show.svg'
            )
        )
    ),
    'palettes'	=> array(
        'default'   => '{information_legend},url,methode,data,result,created;'
    ),
    'fields' 	=> array(
        'id'					=> array(
            'sql'				=> "int(10) unsigned NOT NULL auto_increment"
        ),
        'tstamp'				=> array(
            'sql' 				=> "int(10) unsigned NOT NULL default '0'"
        ),
        'url'  				=> array(
            'label' 			=> &$GLOBALS['TL_LANG']['tl_ynfinite_cache']['url'],
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
            'sql'               => "TEXT NULL"
        ),
        'methode'               => array(
            'label'             => &$GLOBALS['TL_LANG']['tl_ynfinite_cache']['methode'],
            'inputType'         => 'text',
            'exculde'           => true,
            'sorting'           => true,
            'flag'              => 1,
            'search'            => true,
            'eval'              => array(
                'mandatory'     => true,
                'maxLength'     => 255,
                'tl_class'      => 'w100'
            ),
            'sql'               => "varchar(255) NOT NULL default ''"
        ),        
        'data'               => array(
            'label'             => &$GLOBALS['TL_LANG']['tl_ynfinite_cache']['data'],
            'inputType'         => 'textarea',
            'exculde'           => true,
            'sorting'           => true,
            'flag'              => 1,
            'search'            => true,
            'eval'              => array(
                'mandatory'     => true,
                'maxLength'     => 255,
                'tl_class'      => 'w100'
            ),
            'sql'               => "LONGTEXT NULL"
        ),
        'result'               => array(
            'label'             => &$GLOBALS['TL_LANG']['tl_ynfinite_cache']['result'],
            'inputType'         => 'textarea',
            'exculde'           => true,
            'sorting'           => true,
            'flag'              => 1,
            'search'            => true,
            'eval'              => array(
                'mandatory'     => true,
                'maxLength'     => 255,
                'tl_class'      => 'w100'
            ),
            'sql'               => "LONGTEXT NULL"
        ),        
        'created'                => array(
            'sql'               => "int(10) unsigned NOT NULL default '0'"
        ),
    )
);
