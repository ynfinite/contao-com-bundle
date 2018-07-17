<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2017 Leo Feyer
 *
 * @license LGPL-3.0+
 */

System::loadLanguageFile('tl_form_field');

/**
 * Table tl_ynfinite_filter_form_fields
 */
$GLOBALS['TL_DCA']['tl_ynfinite_filter_form_fields'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'enableVersioning'            => true,
		'ptable'                      => 'tl_ynfinite_filter_form',
		'onload_callback' => array
		(
			array('tl_ynfinite_filter_form_fields', 'checkPermission')
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
			'mode'                    => 4,
			'fields'                  => array('sorting'),
			'panelLayout'             => 'filter;search,limit',
			'headerFields'            => array('title', 'tstamp', 'formID', 'storeValues', 'sendViaEmail', 'recipient', 'subject'),
			'child_record_callback'   => array('tl_ynfinite_filter_form_fields', 'listFormFields')
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
				'label'               => &$GLOBALS['TL_LANG']['tl_form_field']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.svg'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_form_field']['copy'],
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.svg',
				'attributes'          => 'onclick="Backend.getScrollOffset()"'
			),
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_form_field']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.svg',
				'attributes'          => 'onclick="Backend.getScrollOffset()"'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_form_field']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.svg',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_form_field']['toggle'],
				'icon'                => 'visible.svg',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback'     => array('tl_ynfinite_filter_form_fields', 'toggleIcon')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_form_field']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.svg'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('type', 'fsType', 'multiple', 'storeFile', 'imageSubmit'),
		'default'                     => '{type_legend},type,label,contentTypeField,operation',
		'text'                        => '{type_legend},type,label,contentTypeField,operation;{fconfig_legend},placeholder;{expert_legend:hide},class,value,accesskey,tabindex;{template_legend:hide},customTpl',
		'range'                        => '{type_legend},type,label,contentTypeField,operation;{fconfig_legend},placeholder,placeholder2;{expert_legend:hide},class,value,accesskey,tabindex;{template_legend:hide},customTpl',
		'select'                      => '{type_legend},type,label,contentTypeField,operation;{fconfig_legend},multiple;{options_legend},valuesAsOptions,options;{expert_legend:hide},class,accesskey,tabindex;{template_legend:hide},customTpl',
		'checkbox'                    => '{type_legend},type,label,contentTypeField,operation;{fconfig_legend};{options_legend},options;{expert_legend:hide},class;{template_legend:hide},customTpl',
		'hidden'                      => '{type_legend},type,label,contentTypeField,operation;{fconfig_legend},value;{template_legend:hide},customTpl'
	),

	// Subpalettes
	'subpalettes' => array
	(
		'multiple'                    => 'mSize',
		'storeFile'                   => 'uploadFolder,useHomeDir,doNotOverwrite',
		'imageSubmit'                 => 'singleSRC'
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
			'foreignKey'              => 'tl_ynfinite_filter_form.id',
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
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['type'],
			'default'                 => 'text',
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_ynfinite_filter_form_fields', 'getFields'),
			'eval'                    => array('helpwizard'=>true, 'submitOnChange'=>true, 'tl_class'=>'w50'),
			'reference'               => &$GLOBALS['TL_LANG']['FFL'],
			'sql'                     => "varchar(64) NOT NULL default ''"
		),
		'contentTypeField' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_ynfinite_filter_form_fields']['contentTypeField'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_ynfinite_filter_form_fields', 'getContentTypeFields'),
			'eval'                    => array('mandatory' => true, 'tl_class'=>'w100 clr', 'submitOnChange'=> true),
			'sql'                     => "varchar(64) NOT NULL default ''"
		),
		'operation' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_ynfinite_filter_form_fields']['operation'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_ynfinite_filter_form_fields', 'getOperations'),
			'eval'                    => array('tl_class'=>'w100'),
			'sql'                     => "varchar(64) NOT NULL default ''"
		),
		'label' => array // "label" needs to come before "name" so it gets the "(copy)" suffix (see #1062)
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['label'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'name' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['name'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'fieldname', 'spaceToUnderscore'=>true, 'maxlength'=>64, 'tl_class'=>'w50 clr'),
			'sql'                     => "varchar(64) NOT NULL default ''"
		),
		'text' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['text'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('rte'=>'tinyMCE', 'helpwizard'=>true),
			'explanation'             => 'insertTags',
			'sql'                     => "text NULL"
		),
		'html' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['html'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('mandatory'=>true, 'allowHtml'=>true, 'class'=>'monospace', 'rte'=>'ace|html'),
			'sql'                     => "text NULL"
		),
		'valuesAsOptions' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_ynfinite_filter_form_fields']['valuesAsOptions'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'options' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_form_field']['options'],
			'inputType'				=> 'checkboxWizard',
            'options_callback'     => array('tl_ynfinite_filter_form_fields', 'getOptions'), 
            'exculde'           	=> true,
            'eval'             		=> array('multiple'=>true),
            'sql'               	=> "text NULL"
		),
		'mandatory' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['mandatory'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'rgxp' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['rgxp'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('digit', 'alpha', 'alnum', 'extnd', 'date', 'time', 'datim', 'phone', 'email', 'url'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_form_field'],
			'eval'                    => array('helpwizard'=>true, 'includeBlankOption'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'placeholder' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['placeholder'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'placeholder2' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['placeholder'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),		
		'minlength' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['minlength'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'natural', 'tl_class'=>'w50'),
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'maxlength' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['maxlength'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'natural', 'tl_class'=>'w50'),
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'size' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['size'],
			'default'                 => array(4, 40),
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'multiple'=>true, 'size'=>2, 'rgxp'=>'natural', 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'multiple' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['multiple'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true, 'tl_class'=>'clr'),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'mSize' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['mSize'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'natural'),
			'sql'                     => "smallint(5) unsigned NOT NULL default '0'"
		),
		'extensions' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['extensions'],
			'exclude'                 => true,
			'default'                 => 'jpg,jpeg,gif,png,pdf,doc,docx,xls,xlsx,ppt,pptx',
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'extnd', 'maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'storeFile' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['storeFile'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'uploadFolder' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['uploadFolder'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('fieldType'=>'radio', 'tl_class'=>'clr'),
			'sql'                     => "binary(16) NULL"
		),
		'useHomeDir' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['useHomeDir'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50'),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'doNotOverwrite' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['doNotOverwrite'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50'),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'fsType' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['fsType'],
			'default'                 => 'fsStart',
			'exclude'                 => true,
			'inputType'               => 'radio',
			'options'                 => array('fsStart', 'fsStop'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_form_field'],
			'eval'                    => array('helpwizard'=>true, 'submitOnChange'=>true),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'class' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['class'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'value' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['value'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'accesskey' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['accesskey'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'alnum', 'maxlength'=>1, 'tl_class'=>'w50'),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'tabindex' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['tabindex'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'natural', 'tl_class'=>'w50'),
			'sql'                     => "smallint(5) unsigned NOT NULL default '0'"
		),
		'fSize' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['fSize'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'natural', 'tl_class'=>'w50'),
			'sql'                     => "smallint(5) unsigned NOT NULL default '0'"
		),
		'customTpl' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['customTpl'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_ynfinite_filter_form_fields', 'getFormFieldTemplates'),
			'eval'                    => array('includeBlankOption'=>true, 'chosen'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(64) NOT NULL default ''"
		),
		'slabel' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['slabel'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50 clr'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'imageSubmit' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['imageSubmit'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'singleSRC' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['singleSRC'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('fieldType'=>'radio', 'filesOnly'=>true, 'mandatory'=>true, 'tl_class'=>'clr'),
			'sql'                     => "binary(16) NULL"
		)
	)
);


/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class tl_ynfinite_filter_form_fields extends Backend
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
	 * Check permissions to edit table tl_form_field
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
					$objField = $this->Database->prepare("SELECT pid FROM tl_ynfinite_filter_form_fields WHERE id=?")
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
				$objField = $this->Database->prepare("SELECT pid FROM tl_ynfinite_filter_form_fields WHERE id=?")
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

				$objForm = $this->Database->prepare("SELECT id FROM tl_ynfinite_filter_form_fields WHERE pid=?")
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
		$arrRow['required'] = $arrRow['mandatory'];
		$key = $arrRow['invisible'] ? 'unpublished' : 'published';

		$typeName = $GLOBALS['TL_LANG']['FFL'][$arrRow['type']][0];
		if(!$typeName) $typeNamer = $arrRow['type'];

		$strType = '
<div class="cte_type ' . $key . '">' . $typeName . ($arrRow['name'] ? ' (' . $arrRow['name'] . ')' : '') . '</div>
<div class="limit_height' . (!Config::get('doNotCollapse') ? ' h32' : '') . '">';

		$strClass = $GLOBALS['TL_FFL'][$arrRow['type']];

		if (!class_exists($strClass))
		{
			return $strType . $typeName . ' '. $arrRow['label'] .'</div>' . "\n";
		}

		/** @var Widget $objWidget */
		$objWidget = new $strClass($arrRow);

		$strWidget = $objWidget->parse();
		$strWidget = preg_replace('/ name="[^"]+"/i', '', $strWidget);
		$strWidget = str_replace(array(' type="submit"', ' autofocus', ' required'), array(' type="button"', '', ''), $strWidget);

		if ($objWidget instanceof FormHidden)
		{
			return $strType . "\n" . $objWidget->value . "\n</div>\n";
		}

		return $strType . StringUtil::insertTagToSrc($strWidget) . '
</div>' . "\n";
	}


	/**
	 * Return a list of form fields
	 *
	 * @return array
	 */
	public function getFields()
	{
		$arrFields = $GLOBALS['TL_YNFINITE_FILTER_FORM_TYPES'];
		// Add the translation
		foreach (array_keys($arrFields) as $key)
		{
			$arrFields[$key] = $GLOBALS['TL_LANG']['FFL'][$key][0];
		}

		return $arrFields;
	}


	/**
	 * Return all form field templates as array
	 *
	 * @return array
	 */
	public function getFormFieldTemplates()
	{
		return $this->getTemplateGroup('form_');
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
		if (is_array($GLOBALS['TL_DCA']['tl_form_field']['config']['onload_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_form_field']['config']['onload_callback'] as $callback)
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
		if (!$this->User->hasAccess('tl_ynfinite_filter_form_fields::invisible', 'alexf'))
		{
			throw new Contao\CoreBundle\Exception\AccessDeniedException('Not enough permissions to publish/unpublish form field ID ' . $intId . '.');
		}

		// Set the current record
		if ($dc)
		{
			$objRow = $this->Database->prepare("SELECT * FROM tl_ynfinite_filter_form_fields WHERE id=?")
									 ->limit(1)
									 ->execute($intId);

			if ($objRow->numRows)
			{
				$dc->activeRecord = $objRow;
			}
		}

		$objVersions = new Versions('tl_ynfinite_filter_form_fields', $intId);
		$objVersions->initialize();

		// Reverse the logic (form fields have invisible=1)
		$blnVisible = !$blnVisible;

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_form_field']['fields']['invisible']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_form_field']['fields']['invisible']['save_callback'] as $callback)
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
		$this->Database->prepare("UPDATE tl_ynfinite_filter_form_fields SET tstamp=$time, invisible='" . ($blnVisible ? '1' : '') . "' WHERE id=?")
					   ->execute($intId);

		if ($dc)
		{
			$dc->activeRecord->tstamp = $time;
			$dc->activeRecord->invisible = ($blnVisible ? '1' : '');
		}

		// Trigger the onsubmit_callback
		if (is_array($GLOBALS['TL_DCA']['tl_form_field']['config']['onsubmit_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_form_field']['config']['onsubmit_callback'] as $callback)
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

	public function getContentTypeFields(DataContainer $dc)
	{
		if($dc->activeRecord->pid) {
			$objFilter = $this->Database->prepare("SELECT * FROM tl_ynfinite_filter_form WHERE id=?")
											   ->limit(1)
											   ->execute($dc->activeRecord->pid);
			if ($objFilter->numRows < 1)
			{
				throw new Contao\CoreBundle\Exception\AccessDeniedException('Can`t find any filter with ' . json_encode($dc->activeRecord->pid) . '.');
			}


			$typeId = $objFilter->contentType;

			$loadDataService = \Contao\System::getContainer()->get("ynfinite.contao-com.listener.communication");
	        $fieldOptions = $loadDataService->getContentTypeFieldOptions($typeId, true);

			return $fieldOptions;
		}
		else {
			return array();
		}
	}

	public function getOptions(DataContainer $dc) {
		$objFilter = $this->Database->prepare("SELECT * FROM tl_ynfinite_filter_form WHERE id=?")
										   ->limit(1)
										   ->execute($dc->activeRecord->pid);
		if ($objFilter->numRows < 1)
		{
			throw new Contao\CoreBundle\Exception\AccessDeniedException('Can`t find any filter with ' . json_encode($dc->activeRecord->pid) . '.');
		}


		$typeId = $objFilter->contentType;

		$loadDataService = \Contao\System::getContainer()->get("ynfinite.contao-com.listener.communication");
        
        $items = $loadDataService->getContentTypeFieldItems($typeId, $dc->activeRecord->contentTypeField);
        var_dump($items);

        return $items;
	}

	public function getOperations() {
		$arrOperations = $GLOBALS['TL_YNFINITE_FILTER_OPERATIONS'];
		var_dump($arrOperations);
		return $arrOperations;
	}

}
