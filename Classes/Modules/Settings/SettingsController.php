<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

class Tx_SitemgrFesettings_Modules_Settings_SettingsController extends Tx_Sitemgr_Modules_Abstract_AbstractController{
	protected $file = __FILE__;
	protected $access = array(
		'general' => 'customerAdmin'
	);
	/**
	 * @var \Tx_SitemgrTemplate_Domain_Repository_TemplateRepository
	 */
	protected $TemplateRepository = array();
	function __construct() {
			// general files
		$this->jsFiles = array(
			t3lib_extMgm::extRelPath('sitemgr_fesettings').'Resources/Public/JavaScripts/Modules/Template/functions.js',
			t3lib_extMgm::extRelPath('sitemgr_fesettings').'Resources/Public/Contrib/ux-sitemgrTemplate/Ext.ux.patches.js',
			t3lib_extMgm::extRelPath('sitemgr_fesettings').'Resources/Public/Contrib/ux-sitemgrTemplate/Ext.ux.sitemgrMultiField.js',
			t3lib_extMgm::extRelPath('sitemgr_fesettings').'Resources/Public/Contrib/ux-sitemgrTemplate/Ext.ux.sitemgrCombobox.js',
			t3lib_extMgm::extRelPath('sitemgr_fesettings').'Resources/Public/Contrib/ux-sitemgrTemplate/Ext.ux.sitemgrWizardfield.js',
			t3lib_extMgm::extRelPath('sitemgr_fesettings').'Resources/Public/Contrib/ux-sitemgrTemplate/Ext.ux.wizard.js',
		);
			// ryanpetrello colorfield
		#$this->jsFiles[]  = t3lib_extMgm::extRelPath('sitemgr_template').'Resources/Public/Contrib/ux-ColorField-ryanpetrelo/Ext.ux.ColorField.js';
		#$this->cssFiles[] = t3lib_extMgm::extRelPath('sitemgr_template').'Resources/Public/Contrib/ux-ColorField-ryanpetrelo/Ext.ux.ColorField.css';
			// vtswingkid colorpicker
		$this->jsFiles[]  = t3lib_extMgm::extRelPath('sitemgr_fesettings').'Resources/Public/Contrib/ux-ColorPicker-vtswingkid/sources/ColorPicker.js';
		$this->jsFiles[]  = t3lib_extMgm::extRelPath('sitemgr_fesettings').'Resources/Public/Contrib/ux-ColorPicker-vtswingkid/sources/ColorMenu.js';
		$this->jsFiles[]  = t3lib_extMgm::extRelPath('sitemgr_fesettings').'Resources/Public/Contrib/ux-ColorPicker-vtswingkid/sources/ColorPickerField.js';
		$this->cssFiles[] = t3lib_extMgm::extRelPath('sitemgr_fesettings').'Resources/Public/Contrib/ux-ColorPicker-vtswingkid/resources/css/colorpicker.css';
	}
	/**
	 * Sets template and related options through the model which was selected
	 *
	 * @return mixed
	 */
	function setOptions($args) {
		list($templateName,$uid) = explode(';',$args['args']);
		$constants     = $_POST['data'];
		$isSetConstants= $_POST['check'];
		$options       = $_POST['options'];
		$cid           = Tx_Sitemgr_Utilities_CustomerUtilities::getCustomerForPage($uid);
		$customer      = new Tx_Sitemgr_Utilities_CustomerUtilities($cid);
		$customer->enableExceptions();
		$customer->isAdministratorForCustomer();
		$pid           = $customer->getRootPage();
		/** @var $tsParserWrapper Tx_SitemgrFesettings_Helper_TsParserWrapper */
		$tsParserWrapper = t3lib_div::makeInstance('Tx_SitemgrFesettings_Helper_TsParserWrapper');
		$tsParserWrapper ->applyToPid($pid, $constants, $isSetConstants);

		//@todo add hook to apply options from skins

		return $this->getReturnForForm();
	}
	/**
	 * @param object $args
	 * @return array
	 */
	function getOptions($args) {
		$cid           = Tx_Sitemgr_Utilities_CustomerUtilities::getCustomerForPage($args->uid);
		$customer      = new Tx_Sitemgr_Utilities_CustomerUtilities($cid);
		$customer->enableExceptions();
		$customer->isAdministratorForCustomer();
		$pid           = $customer->getRootPage();

		/** @var $tsParserWrapper Tx_SitemgrFesettings_Helper_TsParserWrapper */
		$tsParserWrapper = t3lib_div::makeInstance('Tx_SitemgrFesettings_Helper_TsParserWrapper');

		//build output for fields
		$return = array(
			$this->renderFields($tsParserWrapper,$pid)
		);
			//add special options
		//@todo
		/*$fields = $tsParserWrapper->getEnvironmentOptions($pid);

		if($fields != null) {
			$return[] = array(
				array(
					'title'  => $GLOBALS['LANG']->sL('LLL:EXT:sitemgr_template/Resources/Private/Language/Modules/Template/locallang.xml:SitemgrTemplates_templateSpecialProperties'),
					'layout' => 'form',
					'labelAlign' => 'top',
					'items'  => $fields,
				),
			);
		}*/
			//build return
		return array(
			'form' =>$return
		);
	}
	private function renderFields(Tx_SitemgrFesettings_Helper_TsParserWrapper $tsParserWrapper, $pid) {
		$definition = array();
		$categories = $tsParserWrapper->getCategories($pid);
		$constants  = $tsParserWrapper->getConstants($pid);
		foreach($categories as $categorieName => $categorie) {
			asort($categorie);
			//@todo add dynamic filter
			if(is_array($categorie)) {
				$title = $GLOBALS['LANG']->sL('LLL:EXT:sitemgr_template/Resources/Private/Language/Constants/locallang.xml:cat_' . $categorieName);
				if(strlen($title) === 0) {
					$title = $categorieName;
				}
				$definition[$categorieName] = array(
					//'title'  => $GLOBALS['LANG']->sL($categorieName),
					'title'  => $title,
					'items'  => array(),
				);
				foreach($categorie as $constantName => $type) {
					$definition[$categorieName]['items'][] = $this->renderField($constantName, $constants);
				}
			}
		}
		return array_values($definition);
	}

	private function renderField($constantName, $constants) {
		$constant = $constants[$constantName];
		$label    = explode(':', $GLOBALS['LANG']->sL($constant['label']), 2);
		$field = array(
			'xtype'       => 'sitemgrmultifield',
			'fieldLabel'  => '<b>' . $label[0] . '</b>' . $label[1],
			'checkState'  => ($constant['value']!=$constant['default_value']),
			'subname'     => $constant['name'],
			//'name'       => 'data[' . $constant['name'] . ']',
			'width'       => 300,
			'fieldConfig' => array(
				'defaultValue' => $constant['default_value'],
				'value'   => $constant['value'],
					// default xtype
				'xtype'   => 'textfield',
			),
		);
		list($type, $function) = explode('[', $constant['type']);
		if(strlen($function) > 0) {
			$function = substr($function,0,-1);
		}
		switch($type) {
			case 'int':
				$field['fieldConfig']['xtype'] = 'numberfield';
			break;
			case 'int+':
				$field['fieldConfig']['xtype'] = 'numberfield';
				$field['fieldConfig']['allowNegative'] = false;
			break;
			case 'options':
				$field['fieldConfig']['xtype'] = 'sitemgrcombobox';
				$options = explode(',', substr($constant['type'],8,-1));
				foreach($options as $option) {
					$t = array_reverse(explode('=',$option));
					if(count($t) === 1) {
						$t[] = $t[0]; //add value to displayfield!!!
					}
					$field['fieldConfig']['staticData'][] = $t;
				}
			break;
			case 'color':
				#$field['fieldConfig']['xtype'] = 'colorfield';
				$field['fieldConfig']['xtype'] = 'colorpickerfield';
				$field['fieldConfig']['editMode'] = 'all';
				$field['fieldConfig']['hideHtmlCode'] = true;
				$field['fieldConfig']['opacity'] = false;
			break;
			case 'boolean':
				$field['fieldConfig']['xtype'] = 'sitemgrcombobox';
				$field['fieldConfig']['staticData'] = array(
					array(0,0),
					array(1,1),
				);
			break;
				//file selector
				//browser.php?mode=file&bparams=feld
			case 'user':
				$field['fieldConfig']['internalHandler'] = $function;
				$field['fieldConfig']['xtype'] = 'sitemgrwizardfield';
				$fieldName = 'ref';
				$formName  = 'sitemgrWizardfield_OpenWizardRef';
				switch($function) {
						// useable for files, folders and pages
					case 'EXT:templavoila_framework/class.tx_templavoilaframework_pagelink.php:&tx_templavoilaframework_pagelink->main':
							// select page wizard
						$field['fieldConfig']['wizardUri']    = \TYPO3\CMS\Backend\Utility\BackendUtility::getModuleUrl('wizard_element_browser') . '&mode=wizard&P[fieldConfig][type]=input&P[field]='. $fieldName .'&P[formName]='.$formName.'&P[itemName]='. $fieldName . '&P[params][blindLinkOptions]=url,mail,spec';
						$field['fieldConfig']['triggerClass'] = 'x-form-search-trigger';
					break;
					case '':
							// select file/folder only wizard
						$field['fieldConfig']['wizardUri']    = \TYPO3\CMS\Backend\Utility\BackendUtility::getModuleUrl('wizard_element_browser') . '&mode=wizard&P[fieldConfig][type]=input&P[field]='. $fieldName .'&P[formName]='.$formName.'&P[itemName]='. $fieldName . '&P[params][blindLinkOptions]=url,page,mail,spec';
						$field['fieldConfig']['triggerClass'] = 't3-icon t3-icon-apps t3-icon-apps-filetree t3-icon-filetree-folder-default';
					break;
					default:
						// do nothing and use default xtype
					break;
				}
			break;
			default:
				// do nothing and use default xtype
			break;
		}
		return $field;
	}
}
