<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2017 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Table tl_ynfinite_filter_fields
 */
$GLOBALS['TL_DCA']['tl_ynfinite_filter_fields'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'enableVersioning'            => true,
		'ptable'                      => 'tl_ynfinite_filter',
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',
				'pid' => 'index'
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 4,
			'fields'                  => array('sorting'),
			'flag'					  => 1,
			'panelLayout'             => 'filter;search,limit',
			'headerFields'            => array('title'),
			'child_record_callback'   => array('tl_ynfinite_filter_fields', 'listFormFields')
		),
       'label' => array(
            'fields'			=> array('field_type'),
            'format'			=> '%s'
        ),		
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_ynfinite_filter_fields']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.svg'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_ynfinite_filter_fields']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.svg',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_ynfinite_filter_fields']['toggle'],
				'icon'                => 'visible.svg',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback'     => array('tl_ynfinite_filter_fields', 'toggleIcon')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_ynfinite_filter_fields']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.svg'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array(),
		'default'                     => '{filter_field_legend},type_field,operation,value,value2',
	),

	// Subpalettes
	'subpalettes' => array
	(
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'pid' => array
		(
			'foreignKey'              => 'tl_ynfinite_filter.id',
			'sql'                     => "int(10) unsigned NOT NULL default '0'",
			'relation'                => array('type'=>'belongsTo', 'load'=>'eager')
		),
		'sorting' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'invisible' => array
		(
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'type_field' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_ynfinite_filter_fields']['type_field'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_ynfinite_filter_fields', 'getFields'),
			'eval'                    => array('helpwizard'=>true, 'tl_class'=>'w100'),
			'sql'                     => "varchar(64) NOT NULL default ''"
		),
		'operation' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_ynfinite_filter_fields']['operation'],
			'default'                 => 'text',
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_ynfinite_filter_fields', 'getOperations'),
			'eval'                    => array('helpwizard'=>true, 'tl_class'=>'w100'),
			'sql'                     => "varchar(64) NOT NULL default ''"
		),
		'value'  				=> array(
            'label' 			=> &$GLOBALS['TL_LANG']['tl_ynfinite_filter_fields']['value'],
            'inputType'			=> 'text',
            'exculde'			=> true,
            'sorting'			=> true,
            'flag'				=> 1,
            'search'			=> true,
            'eval'				=> array(
                'maxLength'		=> 255,
                'tl_class'		=> 'w100'
            ),
            'sql'				=> "varchar(255) NOT NULL default ''"
        ),
		'value2'  				=> array(
            'label' 			=> &$GLOBALS['TL_LANG']['tl_ynfinite_filter_fields']['value2'],
            'inputType'			=> 'text',
            'exculde'			=> true,
            'sorting'			=> true,
            'flag'				=> 1,
            'search'			=> true,
            'eval'				=> array(
                'maxLength'		=> 255,
                'tl_class'		=> 'w100'
            ),
            'sql'				=> "varchar(255) NOT NULL default ''"
        ),        
	)
);


/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class tl_ynfinite_filter_fields extends Backend
{

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}


	/**
	 * Add the type of input field
	 *
	 * @param array $arrRow
	 *
	 * @return string
	 */
	public function listFormFields($arrRow)
	{
		$return = $arrRow["type_field"]." ".$GLOBALS['TL_YNFINITE_FILTER_OPERATIONS'][$arrRow["operation"]]." ".$arrRow["value"];
		if($arrRow["value2"]) {
			$return .= " und ".$arrRow["value2"];
		}
		return $return;
	}


	/**
	 * Add a link to the option items import wizard
	 *
	 * @return string
	 */
	public function optionImportWizard()
	{
		return ' <a href="' . $this->addToUrl('key=option') . '" title="' . specialchars($GLOBALS['TL_LANG']['MSC']['ow_import'][1]) . '" onclick="Backend.getScrollOffset()">' . Image::getHtml('tablewizard.gif', $GLOBALS['TL_LANG']['MSC']['ow_import'][0]) . '</a>';
	}


	/**
	 * Return a list of form fields
	 *
	 * @return array
	 */
	public function getFields(DataContainer $dc)
	{
		if($dc->activeRecord->pid) {
			$objFilter = $this->Database->prepare("SELECT * FROM tl_ynfinite_filter WHERE id=?")
											   ->limit(1)
											   ->execute($dc->activeRecord->pid);
			if ($objFilter->numRows < 1)
			{
				throw new Contao\CoreBundle\Exception\AccessDeniedException('Can`t find any filter with ' . json_encode($dc->activeRecord->pid) . '.');
			}


			$typeId = $objFilter->contentType;

			$loadDataService = \Contao\System::getContainer()->get("ynfinite.contao-com.listener.communication");
	        $fieldOptions = $loadDataService->getContentTypeFieldOptions($typeId);

			return $fieldOptions;
		}
		else {
			return array();
		}
	}

	public function getOperations() {
		$arrOperations = $GLOBALS['TL_YNFINITE_FILTER_OPERATIONS'];
		return $arrOperations;
	}

	/**
	 * Return the "toggle visibility" button
	 *
	 * @param array  $row
	 * @param string $href
	 * @param string $label
	 * @param string $title
	 * @param string $icon
	 * @param string $attributes
	 *
	 * @return string
	 */
	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		if (strlen(Input::get('tid')))
		{
			$this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1), (@func_get_arg(12) ?: null));
			$this->redirect($this->getReferer());
		}

		$href .= '&amp;tid='.$row['id'].'&amp;state='.$row['invisible'];

		if ($row['invisible'])
		{
			$icon = 'invisible.svg';
		}

		return '<a href="'.$this->addToUrl($href).'" title="'.StringUtil::specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label, 'data-state="' . ($row['invisible'] ? 0 : 1) . '"').'</a> ';
	}


	/**
	 * Toggle the visibility of a form field
	 *
	 * @param integer       $intId
	 * @param boolean       $blnVisible
	 * @param DataContainer $dc
	 */
	public function toggleVisibility($intId, $blnVisible, DataContainer $dc=null)
	{
		// Set the ID and action
		Input::setGet('id', $intId);
		Input::setGet('act', 'toggle');

		if ($dc)
		{
			$dc->id = $intId; // see #8043
		}

		// Trigger the onload_callback
		if (is_array($GLOBALS['TL_DCA']['tl_ynfinite_filter_fields']['config']['onload_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_ynfinite_filter_fields']['config']['onload_callback'] as $callback)
			{
				if (is_array($callback))
				{
					$this->import($callback[0]);
					$this->{$callback[0]}->{$callback[1]}($dc);
				}
				elseif (is_callable($callback))
				{
					$callback($dc);
				}
			}
		}

		// Check the field access
		if (!$this->User->hasAccess('tl_ynfinite_filter_fields::invisible', 'alexf'))
		{
			throw new Contao\CoreBundle\Exception\AccessDeniedException('Not enough permissions to publish/unpublish hook with ID ' . $intId . '.');
		}

		// Set the current record
		if ($dc)
		{
			$objRow = $this->Database->prepare("SELECT * FROM tl_ynfinite_filter_fields WHERE id=?")
									 ->limit(1)
									 ->execute($intId);

			if ($objRow->numRows)
			{
				$dc->activeRecord = $objRow;
			}
		}

		$objVersions = new Versions('tl_ynfinite_filter_fields', $intId);
		$objVersions->initialize();

		// Reverse the logic (form fields have invisible=1)
		$blnVisible = !$blnVisible;

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_ynfinite_filter_fields']['fields']['invisible']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_ynfinite_filter_fields']['fields']['invisible']['save_callback'] as $callback)
			{
				if (is_array($callback))
				{
					$this->import($callback[0]);
					$blnVisible = $this->{$callback[0]}->{$callback[1]}($blnVisible, $dc);
				}
				elseif (is_callable($callback))
				{
					$blnVisible = $callback($blnVisible, $dc);
				}
			}
		}

		$time = time();

		// Update the database
		$this->Database->prepare("UPDATE tl_ynfinite_filter_fields SET tstamp=$time, invisible='" . ($blnVisible ? '1' : '') . "' WHERE id=?")
					   ->execute($intId);

		if ($dc)
		{
			$dc->activeRecord->tstamp = $time;
			$dc->activeRecord->invisible = ($blnVisible ? '1' : '');
		}

		// Trigger the onsubmit_callback
		if (is_array($GLOBALS['TL_DCA']['tl_ynfinite_filter_fields']['config']['onsubmit_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_ynfinite_filter_fields']['config']['onsubmit_callback'] as $callback)
			{
				if (is_array($callback))
				{
					$this->import($callback[0]);
					$this->{$callback[0]}->{$callback[1]}($dc);
				}
				elseif (is_callable($callback))
				{
					$callback($dc);
				}
			}
		}

		$objVersions->create();
	}
}
