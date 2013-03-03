<?php

class Tx_SitemgrFesettings_FieldRenderers_BooleanFieldRenderer {
	protected function postprocess($constantName, $config, &$field) {
		$field['fieldConfig']['xtype'] = 'sitemgrcombobox';
		$field['fieldConfig']['staticData'] = array(
			array(0,0),
			array(1,1),
		);
	}
}