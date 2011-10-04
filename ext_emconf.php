<?php

########################################################################
# Extension Manager/Repository config file for ext "fluid_recommendation".
#
# Auto generated 18-04-2011 13:24
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Fluid Recommendation',
	'description' => 'Let your visitors recommend your website by mail. Based on Fluid Templating Engine.',
	'category' => 'plugin',
	'author' => 'Armin Ruediger Vieweg',
	'author_email' => 'armin.vieweg@diemedialen.de',
	'author_company' => 'Die Medialen GmbH',
	'shy' => '',
	'dependencies' => 'extbase,fluid',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'version' => '0.3.0',
	'constraints' => array(
		'depends' => array(
			'typo3' => '4.5.0-0.0.0',
			'extbase' => '1.3.0-0.0.0',
			'fluid' => '1.3.0-0.0.0',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'suggests' => array(
	),
	'_md5_values_when_last_written' => 'a:17:{s:21:"ext_conf_template.txt";s:4:"59e5";s:12:"ext_icon.gif";s:4:"c590";s:17:"ext_localconf.php";s:4:"2d70";s:14:"ext_tables.php";s:4:"8705";s:14:"ext_tables.sql";s:4:"d41d";s:47:"Classes/Controller/RecommendationController.php";s:4:"00f2";s:51:"Classes/ViewHelpers/RemoveWhitespacesViewHelper.php";s:4:"d971";s:43:"Classes/ViewHelpers/StripTagsViewHelper.php";s:4:"1b06";s:51:"Configuration/FlexForms/flexform_recommendation.xml";s:4:"f703";s:34:"Configuration/TypoScript/setup.txt";s:4:"05d7";s:40:"Resources/Private/Language/locallang.xml";s:4:"a32e";s:49:"Resources/Private/Language/locallang_flexform.xml";s:4:"3d37";s:38:"Resources/Private/Layouts/default.html";s:4:"9325";s:42:"Resources/Private/Partials/formErrors.html";s:4:"f5bc";s:53:"Resources/Private/Templates/Recommendation/Index.html";s:4:"f2b8";s:35:"Resources/Public/Icons/relation.gif";s:4:"e615";s:59:"Resources/Public/Icons/tx_extteaser_domain_model_teaser.gif";s:4:"905a";}',
);

?>