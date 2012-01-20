<?php

########################################################################
# Extension Manager/Repository config file for ext "fluid_recommendation".
#
# Auto generated 09-01-2011-2012 11:21
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
	'author_email' => 'info@professorweb.de',
	'author_company' => '',
	'shy' => '',
	'dependencies' => 'extbase,fluid',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'stable',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'version' => '1.1.1',
	'constraints' => array(
		'depends' => array(
			'typo3' => '4.5.0-0.0.0',
			'extbase' => '1.3.0-1.5.99',
			'fluid' => '1.3.0-1.5.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'suggests' => array(
	),
	'_md5_values_when_last_written' => 'a:26:{s:21:"ext_conf_template.txt";s:4:"4dff";s:12:"ext_icon.gif";s:4:"04c6";s:17:"ext_localconf.php";s:4:"1a51";s:14:"ext_tables.php";s:4:"777a";s:14:"ext_tables.sql";s:4:"d41d";s:47:"Classes/Controller/RecommendationController.php";s:4:"3531";s:56:"Classes/Controller/ReturnRecommendationUrlController.php";s:4:"3dd0";s:39:"Classes/Domain/Model/Recommendation.php";s:4:"602e";s:52:"Classes/Domain/Validator/RecommendationValidator.php";s:4:"2d1e";s:27:"Classes/Hook/ClearCache.php";s:4:"82fa";s:24:"Classes/Utility/Mail.php";s:4:"6540";s:28:"Classes/Utility/Settings.php";s:4:"d5e6";s:51:"Classes/ViewHelpers/RemoveWhitespacesViewHelper.php";s:4:"d971";s:43:"Classes/ViewHelpers/StripTagsViewHelper.php";s:4:"1b06";s:51:"Configuration/FlexForms/flexform_recommendation.xml";s:4:"d8dc";s:34:"Configuration/TypoScript/setup.txt";s:4:"4260";s:40:"Resources/Private/Language/locallang.xml";s:4:"1b80";s:49:"Resources/Private/Language/locallang_flexform.xml";s:4:"53f6";s:38:"Resources/Private/Layouts/default.html";s:4:"9325";s:35:"Resources/Private/Layouts/mail.html";s:4:"3562";s:42:"Resources/Private/Partials/formErrors.html";s:4:"57c9";s:64:"Resources/Private/Templates/Recommendation/MailTemplateHtml.html";s:4:"6f88";s:64:"Resources/Private/Templates/Recommendation/MailTemplateText.html";s:4:"c15c";s:56:"Resources/Private/Templates/Recommendation/ShowForm.html";s:4:"4af5";s:65:"Resources/Private/Templates/Recommendation/ShowMailSendError.html";s:4:"405b";s:14:"doc/manual.sxw";s:4:"de33";}',
);

?>