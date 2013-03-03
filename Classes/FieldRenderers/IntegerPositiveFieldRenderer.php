<?php

class Tx_SitemgrFesettings_FieldRenderers_IntegerPositiveFieldRenderer {
	protected function postprocess($constantName, $config, &$field) {
		$field['fieldConfig']['xtype'] = 'numberfield';
		$field['fieldConfig']['allowNegative'] = false;
	}
}