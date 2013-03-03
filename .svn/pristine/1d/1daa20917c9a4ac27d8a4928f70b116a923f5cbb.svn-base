<?php

abstract class Tx_SitemgrFesettings_FieldRenderers_AbstractFieldRenderer {
	protected function postprocess($constantName, $config, &$field) {

	}
	public    function render($constantName, $config) {
		$label    = explode(':', $GLOBALS['LANG']->sL($config['label']), 2);
		$field = array(
			'xtype'       => 'sitemgrmultifield',
			'fieldLabel'  => '<b>' . $label[0] . '</b>' . $label[1],
			'checkState'  => ($config['value'] != $config['default_value']),
			'subname'     => $config['name'],
			//'name'       => 'data[' . $constant['name'] . ']',
			'width'       => 300,
			'fieldConfig' => array(
				'xtype'        => 'textfield',
				'defaultValue' => $config['default_value'],
				'value'        => $config['value'],
			),
		);
		$this->postprocess($constantName, $config, $field);
		return $field;
	}
}