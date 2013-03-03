<?php
	if (TYPO3_MODE == 'BE') {
			//load template module if templavoila is active
		Tx_Sitemgr_Utilities_CustomerModuleUtilities::registerModule(
			'sitemgr_fesettings',
			'Tx_SitemgrFesettings_Modules_Settings_SettingsController',
			'before:sitemgr_help'
		);
	}

?>