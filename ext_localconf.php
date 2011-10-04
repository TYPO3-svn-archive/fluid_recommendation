<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$extConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY]);
$actionNotToCache = '';
if ($extConfiguration['ENABLECACHE'] == '0') {
	$actionNotToCache = 'showForm';
}

Tx_Extbase_Utility_Extension::configurePlugin(
	$_EXTKEY,
	'Pi1',
	array(
		'Recommendation' => 'showForm,recommend,showMailSendError',
	),
	array(
		'Recommendation' => $actionNotToCache . ',recommend',
	)
);

Tx_Extbase_Utility_Extension::configurePlugin(
	$_EXTKEY,
	'Pi2',
	array(
		'ReturnRecommendationUrl' => 'returnUrl',
	),
	array(
		'ReturnRecommendationUrl' => 'returnUrl',
	)
);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc'][$_EXTKEY]
	= 'EXT:fluid_recommendation/Classes/Hook/ClearCache.php:tx_FluidRecommendation_Hook_ClearCache->process';

?>