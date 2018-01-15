<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2017 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Table tl_ynfinite_form_hooks
 */
$GLOBALS['TL_DCA']['tl_ynfinite_form_hooks'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'enableVersioning'            => true,
		'ptable'                      => 'tl_ynfinite_form',
		'onload_callback' => array
		(
			array('tl_ynfinite_form_hooks', 'checkPermission')
		),
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
			'mode'                    => 2,
			'fields'                  => array('type'),
			'flag'					  => 1,
			'panelLayout'             => 'filter;search,limit',
			'headerFields'            => array('title'),
			'child_record_callback'   => array('tl_ynfinite_form_hooks', 'listFormFields')
		),
       'label' => array(
            'fields'			=> array('type'),
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
				'label'               => &$GLOBALS['TL_LANG']['tl_ynfinite_form_hooks']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.svg'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_ynfinite_form_hooks']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.svg',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_ynfinite_form_hooks']['toggle'],
				'icon'                => 'visible.svg',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback'     => array('tl_ynfinite_form_hooks', 'toggleIcon')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_ynfinite_form_hooks']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.svg'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array(),
		'default'                     => '{type_legend},type,code',
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
			'foreignKey'              => 'tl_ynfinite_form.id',
			'sql'                     => "int(10) unsigned NOT NULL default '0'",
			'relation'                => array('type'=>'belongsTo', 'load'=>'lazy')
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
		'type' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_ynfinite_form_hooks']['type'],
			'default'                 => 'text',
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_ynfinite_form_hooks', 'getHooks'),
			'eval'                    => array('helpwizard'=>true, 'tl_class'=>'w50'),
			'reference'               => &$GLOBALS['TL_LANG']['FFL'],
			'sql'                     => "varchar(64) NOT NULL default ''"
		),
		'code' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_ynfinite_form_hooks']['code'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('style'=>'height:120px', 'preserveTags'=>true, 'rte'=>'ace|html', 'tl_class'=>'clr'),
			'sql'                     => "text NULL"
		),
	)
);


/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class tl_ynfinite_form_hooks extends Backend
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
	 * Check permissions to edit table tl_ynfinite_form_hooks
	 *
	 * @throws Contao\CoreBundle\Exception\AccessDeniedException
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin)
		{
			return;
		}

		// Set root IDs
		if (!is_array($this->User->forms) || empty($this->User->forms))
		{
			$root = array(0);
		}
		else
		{
			$root = $this->User->forms;
		}

		$id = strlen(Input::get('id')) ? Input::get('id') : CURRENT_ID;

		// Check current action
		switch (Input::get('act'))
		{
			case 'paste':
				// Allow
				break;

			case 'create':
			case 'select':
				if (!strlen(Input::get('id')) || !in_array(Input::get('id'), $root))
				{
					throw new Contao\CoreBundle\Exception\AccessDeniedException('Not enough permissions to access form ID ' . Input::get('id') . '.');
				}
				break;

			case 'cut':
			case 'copy':
				$pid = Input::get('pid');

				// Get form ID
				if (Input::get('mode') == 1)
				{
					$objField = $this->Database->prepare("SELECT pid FROM tl_ynfinite_form_hooks WHERE id=?")
											   ->limit(1)
											   ->execute(Input::get('pid'));

					if ($objField->numRows < 1)
					{
						throw new Contao\CoreBundle\Exception\AccessDeniedException('Invalid form field ID ' . Input::get('pid') . '.');
					}

					$pid = $objField->pid;
				}

				if (!in_array($pid, $root))
				{
					throw new Contao\CoreBundle\Exception\AccessDeniedException('Not enough permissions to ' . Input::get('act') . ' form field ID ' . $id . ' to form ID ' . $pid . '.');
				}
				// NO BREAK STATEMENT HERE

			case 'edit':
			case 'show':
			case 'delete':
			case 'toggle':
				$objField = $this->Database->prepare("SELECT pid FROM tl_ynfinite_form_hooks WHERE id=?")
										   ->limit(1)
										   ->execute($id);

				if ($objField->numRows < 1)
				{
					throw new Contao\CoreBundle\Exception\AccessDeniedException('Invalid form field ID ' . $id . '.');
				}

				if (!in_array($objField->pid, $root))
				{
					throw new Contao\CoreBundle\Exception\AccessDeniedException('Not enough permissions to ' . Input::get('act') . ' form field ID ' . $id . ' of form ID ' . $objField->pid . '.');
				}
				break;

			case 'editAll':
			case 'deleteAll':
			case 'overrideAll':
			case 'cutAll':
			case 'copyAll':
				if (!in_array($id, $root))
				{
					throw new Contao\CoreBundle\Exception\AccessDeniedException('Not enough permissions to access form ID ' . $id . '.');
				}

				$objForm = $this->Database->prepare("SELECT id FROM tl_ynfinite_form_hooks WHERE pid=?")
										  ->execute($id);

				if ($objForm->numRows < 1)
				{
					throw new Contao\CoreBundle\Exception\AccessDeniedException('Invalid form ID ' . $id . '.');
				}

				/** @var Symfony\Component\HttpFoundation\Session\SessionInterface $objSession */
				$objSession = System::getContainer()->get('session');

				$session = $objSession->all();
				$session['CURRENT']['IDS'] = array_intersect($session['CURRENT']['IDS'], $objForm->fetchEach('id'));
				$objSession->replace($session);
				break;

			default:
				if (strlen(Input::get('act')))
				{
					throw new Contao\CoreBundle\Exception\AccessDeniedException('Invalid command "' . Input::get('act') . '".');
				}
				elseif (!in_array($id, $root))
				{
					throw new Contao\CoreBundle\Exception\AccessDeniedException('Not enough permissions to access form ID ' . $id . '.');
				}
				break;
		}
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
		return $arrRow["type"];
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
	public function getHooks()
	{
		$arrHooks = $GLOBALS['TL_YNFINITE_HOOKS'];

		return $arrHooks;
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
		if (is_array($GLOBALS['TL_DCA']['tl_ynfinite_form_hooks']['config']['onload_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_ynfinite_form_hooks']['config']['onload_callback'] as $callback)
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
		if (!$this->User->hasAccess('tl_ynfinite_form_hooks::invisible', 'alexf'))
		{
			throw new Contao\CoreBundle\Exception\AccessDeniedException('Not enough permissions to publish/unpublish hook with ID ' . $intId . '.');
		}

		// Set the current record
		if ($dc)
		{
			$objRow = $this->Database->prepare("SELECT * FROM tl_ynfinite_form_hooks WHERE id=?")
									 ->limit(1)
									 ->execute($intId);

			if ($objRow->numRows)
			{
				$dc->activeRecord = $objRow;
			}
		}

		$objVersions = new Versions('tl_ynfinite_form_hooks', $intId);
		$objVersions->initialize();

		// Reverse the logic (form fields have invisible=1)
		$blnVisible = !$blnVisible;

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_ynfinite_form_hooks']['fields']['invisible']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_ynfinite_form_hooks']['fields']['invisible']['save_callback'] as $callback)
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
		$this->Database->prepare("UPDATE tl_ynfinite_form_hooks SET tstamp=$time, invisible='" . ($blnVisible ? '1' : '') . "' WHERE id=?")
					   ->execute($intId);

		if ($dc)
		{
			$dc->activeRecord->tstamp = $time;
			$dc->activeRecord->invisible = ($blnVisible ? '1' : '');
		}

		// Trigger the onsubmit_callback
		if (is_array($GLOBALS['TL_DCA']['tl_ynfinite_form_hooks']['config']['onsubmit_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_ynfinite_form_hooks']['config']['onsubmit_callback'] as $callback)
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
