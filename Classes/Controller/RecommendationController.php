<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011-2012 Armin Ruediger Vieweg <info@professorweb.de>
*
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * Controller for the Recommendation object
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_FluidRecommendation_Controller_RecommendationController extends Tx_Extbase_MVC_Controller_ActionController {
	/**
	 * Name of setting which is used for templates
	 *
	 * @var string
	 */
	const TEMPLATE_SETTINGS_NAME = 'mailTemplateRootFile';

	/**
	 * Possible types of mail
	 *
	 * @var array
	 */
	protected $templateTypes = array('html', 'text');


	/**
	 * @var array
	 */
	protected $settings = array();

	/**
	 * @var Tx_FluidRecommendation_Utility_Settings
	 */
	protected $settingsUtility = NULL;

	/**
	 * @var Tx_FluidRecommendation_Utility_Mail
	 */
	protected $mailUtility = NULL;

	/**
	 * Injects the settings utility class
	 *
	 * @param Tx_FluidRecommendation_Utility_Settings $utility the utility to inject
	 *
	 * @return void
	 */
	public function injectSettingsUtility(Tx_FluidRecommendation_Utility_Settings $utility) {
		$this->settingsUtility = $utility;
	}

	/**
	 * Injects the mail utility class
	 *
	 * @param Tx_FluidRecommendation_Utility_Mail $utility the utility to inject
	 *
	 * @return void
	 */
	public function injectMailUtility(Tx_FluidRecommendation_Utility_Mail $utility) {
		$this->mailUtility = $utility;
	}

	/**
	 * Initialize Action will performed before each action will be executed
	 *
	 * @return void
	 */
	public function  initializeAction() {
		$this->settings = $this->settingsUtility->renderConfigurationArray($this->settings);
	}

	/**
	 * Show form action
	 *
	 * @param Tx_FluidRecommendation_Domain_Model_Recommendation $recommendation New recommendation
	 *
	 * @dontvalidate $recommendation
	 *
	 * @return void
	 */
	public function showFormAction(Tx_FluidRecommendation_Domain_Model_Recommendation $recommendation = NULL) {
		// Sets template as file if configured
		$this->performTemplatePathAndFilename($this->settings['formTemplateRootFile']);

		if ($recommendation === NULL) {
			$url = t3lib_div::getIndpEnv('TYPO3_SITE_URL');
			if ($this->request->hasArgument('url') && $this->checkUrlWithAllowedDomains($this->request->getArgument('url'))) {
				$url = $this->request->getArgument('url');
			}
			$this->view->assign('url', $url);
		} else {
			$this->view->assign('url', $recommendation->getUrl());
		}

		if ($recommendation !== NULL) {
			$this->view->assign('recommendation', $recommendation);
		}
	}

	/**
	 * Makes the recommendation
	 *
	 * @param Tx_FluidRecommendation_Domain_Model_Recommendation $recommendation the recommendation model to use
	 *
	 * @return void
	 *
	 * @dontverifyrequesthash
	 */
	public function recommendAction(Tx_FluidRecommendation_Domain_Model_Recommendation $recommendation = NULL) {
		if (!$this->checkUrlWithAllowedDomains($recommendation->getUrl())) {
			$error = Tx_Extbase_Utility_Localization::translate('error.urlManipulation', 'fluid_recommendation');
			$this->redirect('showMailSendError', NULL, NULL, array('url' => $recommendation->getUrl(), 'error' => $error));
			return;
		}

		$this->mailUtility->setFluidTemplate($this->makeFluidTemplateObject());

		// Charge recommendation model with some related settings
		$recommendation->setSubject($this->settings['mailSubject']);

		// Set mail templates
		foreach ($this->templateTypes as $templateType) {
			if (strstr($this->settings['mailType'], $templateType)) {
				$recommendation->addTemplate(
					$templateType,
					$this->settings[self::TEMPLATE_SETTINGS_NAME . ucfirst($templateType)]
				);
			}
		}

			// Spam check
		if ($this->isMailAddressGettingSpammed($recommendation->getReceiverMail()) === TRUE) {
			$error = Tx_Extbase_Utility_Localization::translate('error.spam', 'fluid_recommendation');
			$this->redirect('showMailSendError', NULL, NULL, array('url' => $recommendation->getUrl(), 'error' => $error));
			return;
		}

			// Honeypot check
		if ($this->isBot($recommendation->getPassword(), $recommendation->getUsername()) === FALSE) {
				// Send mail
			$mailStatus = $this->mailUtility->sendRecommendationMail($recommendation);
			if ($mailStatus === FALSE) {
				$this->flashMessageContainer->flush();
				$this->flashMessageContainer->add(Tx_Extbase_Utility_Localization::translate('error.unknown', 'fluid_recommendation'));
				$this->redirect('showMailSendError', NULL, NULL, array('url' => $recommendation->getUrl()));
				return;
			}
		}

			// Save recommended page to session
		$GLOBALS['TSFE']->fe_user->setKey('ses', 'tx_fluid_recommendation_lastRecommendedPage', $recommendation->getUrl());

		if ($this->settings['actionOnSuccess'] === 'forward') {
			$this->redirectToURI($this->buildUriByUid($this->settings['formSuccessPage']));
		} elseif ($this->settings['actionOnSuccess'] === 'return') {
			if (parse_url($recommendation->getUrl(), PHP_URL_QUERY) === NULL) {
				$getParameter = '?';
			} else {
				$getParameter = '&';
			}
			$getParameter .= 'tx_fluid_recommendation[success]=1';

			$this->redirectToURI(
				$recommendation->getUrl() . $getParameter
			);
		}
		return;
	}

	/**
	 * Checks the given mail address to prevent spam.
	 *
	 * @param string $mailAddress mail address to check
	 *
	 * @return boolean Returns FALSE is it is no spam, returns TRUE if it is spam
	 */
	protected function isMailAddressGettingSpammed($mailAddress) {
		$encodedMailAddress = base64_encode($mailAddress);
		$checkFile = PATH_site . 'typo3temp/fluid_recommendation/' . $encodedMailAddress;
		if (!file_exists($checkFile)) {
			t3lib_div::writeFileToTypo3tempDir($checkFile, time());
			return FALSE;
		} else {
			$checkTimestamp = file_get_contents($checkFile);
			if ($checkTimestamp < time() - $this->settings['spamPreventDuration']) {
				t3lib_div::writeFileToTypo3tempDir($checkFile, time());
				return FALSE;
			}
		}
		return TRUE;
	}

	/**
	 * Checks the two values to detect bots
	 * The first value gets updated by JavaScript so bots with turned off JavaScript (almost all) will fail this test.
	 * The second value is hidden with CSS and has to be empty. Stupid bots will fill this field.
	 *
	 * @param string $hiddenInput mail address to check
	 * @param string $cssHiddenInput mail address to check
	 * @return boolean Returns FALSE is it is no spam, returns TRUE if it is spam
	 */
	protected function isBot($hiddenInput, $cssHiddenInput) {
		if ($this->settings['honeypot'] !== '1') {
			return FALSE;
		}
		return ($hiddenInput !== '0' || $cssHiddenInput !== '');
	}

	/**
	 * Mail send error action
	 *
	 * @param string $url url to recommend
	 * @param string $error error message to show
	 *
	 * @return void
	 */
	public function showMailSendErrorAction($url = '', $error = '') {
		$this->view->assign('url', $url);
		$this->view->assign('error', $error);
	}

	/**
	 * Sets the fluid template to file if file is selected in flexform
	 * configuration and file exists
	 *
	 * @param string $templateFile file path to template (gets prepend constant PATH_site)
	 *
	 * @return boolean Returns TRUE if templateType is file and exists,
	 *         otherwise returns FALSE
	 */
	protected function performTemplatePathAndFilename($templateFile) {
		if (!empty($templateFile) && file_exists(PATH_site . $templateFile)) {
			$this->view->setTemplatePathAndFilename($templateFile);
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Returns a built URI by pageUid
	 *
	 * @param integer $uid The uid to use for building link
	 *
	 * @return string The link
	 */
	protected function buildUriByUid($uid) {
		$uri = $this->uriBuilder->setTargetPageUid($uid)->build();
		$uri = $this->addBaseUriIfNecessary($uri);
		return $uri;
	}

	/**
	 * Checks that a given URL is valid, that means that the host of URL is the same as
	 * the current domain or is contained in settings 'allowedUrls'.
	 *
	 * @param string $url URL to check
	 *
	 * @return boolean Returns TRUE if the URL is valid, otherwise returns FALSE
	 */
	public function checkUrlWithAllowedDomains($url) {
		$allowedUrls = $this->settings['allowedUrls'];
		if (empty($allowedUrls)) {
			$allowedUrls = t3lib_div::getHostname();
		}
		$allowedUrls = t3lib_div::trimExplode(',', $allowedUrls, TRUE);
		$url = parse_url($url);

		foreach ($allowedUrls as $allowedUrl) {
			if ($url['host'] === trim($allowedUrl)) {
				return TRUE;
			}
		}
		return FALSE;
	}

	/**
	 * Makes and returns a fluid template object
	 *
	 * @return Tx_Fluid_View_TemplateView|Tx_Fluid_View_StandaloneView the fluid template object depending on
	 *         TYPO3 version
	 */
	protected function makeFluidTemplateObject() {
		$layoutDirectory = t3lib_div::getFileAbsFileName('EXT:fluid_recommendation/Resources/Private/Layouts/');
		$partialDirectory = t3lib_div::getFileAbsFileName('EXT:fluid_recommendation/Resources/Private/Partials/');

		if (t3lib_utility_VersionNumber::convertVersionNumberToInteger(TYPO3_version) >= 4006000) {
			// If TYPO3 4.6.0 or greater
			/** @var $fluidTemplate Tx_Fluid_View_StandaloneView */
			$fluidTemplate = t3lib_div::makeInstance('Tx_Fluid_View_StandaloneView');

			$fluidTemplate->setLayoutRootPath($layoutDirectory);
			$fluidTemplate->setPartialRootPath($partialDirectory);
		} else {
			/** @var Tx_Fluid_View_TemplateView $fluidTemplate  */
			$fluidTemplate = t3lib_div::makeInstance('Tx_Fluid_View_TemplateView');

			// Set controller context
			$controllerContext = $this->buildControllerContext();
			$controllerContext->setRequest($this->request);
			$fluidTemplate->setControllerContext($controllerContext);
		}
		$fluidTemplate->setLayoutRootPath($layoutDirectory);
		$fluidTemplate->setPartialRootPath($partialDirectory);

		return $fluidTemplate;
	}

}
?>