<?php

class Tx_SitemgrFesettings_FieldRenderers_OptionsFieldRenderer {
	protected function postprocess($constantName, $config, &$field) {
		$field['fieldConfig']['xtype'] = 'sitemgrcombobox';
		$options = explode(',', substr($config['type'],8,-1));
		foreach($options as $option) {
			$t = array_reverse(explode('=',$option));
			if(count($t) === 1) {
				$t[] = $t[0]; //add value to displayfield!!!
			}
			$field['fieldConfig']['staticData'][] = $t;
		}
	}
}