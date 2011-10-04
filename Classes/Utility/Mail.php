<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2011 Armin Ruediger Vieweg <armin.vieweg@diemedialen.de>
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
 * This class provides some methods to build and send recommendation mails
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_FluidRecommendation_Utility_Mail {
	/**
	 * @var Tx_Extbase_Configuration_ConfigurationManagerInterface
	 */
	protected $configurationManager = NULL;

	/**
	 * @var Tx_Fluid_View_TemplateView
	 */
	protected $fluidTemplate = NULL;

	/**
	 * Set the fluid template from controller
	 *
	 * @param Tx_Fluid_View_TemplateView $fluidTemplate the fluid template
	 *
	 * @return void
	 */
	public function setFluidTemplate(Tx_Fluid_View_TemplateView $fluidTemplate) {
		$this->fluidTemplate = $fluidTemplate;
	}

	/**
	 * Set controller context from controller
	 *
	 * @param Tx_Extbase_MVC_Controller_ControllerContext $controllerContext controller context
	 *
	 * @return void
	 */
	public function setControllerContext(Tx_Extbase_MVC_Controller_ControllerContext $controllerContext) {
		$this->controllerContext = $controllerContext;
	}

	/**
	 * Creates and sends mail with data from recommendation object
	 *
	 * @param Tx_FluidRecommendation_Domain_Model_Recommendation $recommendation The recommendation object
	 *
	 * @return boolean Returns TRUE if the mail has been sent successfully, otherwise returns FALSE
	 */
	public function sendRecommendationMail(Tx_FluidRecommendation_Domain_Model_Recommendation $recommendation) {
		/** @var t3lib_mail_Message $mail */
		$mail = t3lib_div::makeInstance('t3lib_mail_Message');

		$mail->setFrom($recommendation->getSenderMail(), $recommendation->getSenderFirstName() . ' ' . $recommendation->getSenderLastName());
		$mail->setTo($recommendation->getReceiverMail(), $recommendation->getReceiverFirstName() . ' ' . $recommendation->getReceiverLastName());
		$mail->setSubject($recommendation->getSubject());

		foreach ($recommendation->getTemplates() as $type => $template) {
			switch ($type) {
				case 'html' : $mimeType = 'text/html'; break;
				case 'text' : $mimeType = 'text/plain'; break;
			}
			$mail->addPart($this->getMailMessage($recommendation, $template), $mimeType);
		}

		return (boolean) $mail->send();
	}

	/**
	 * Gets the message for a recommendation mail as fluid template
	 *
	 * @param Tx_FluidRecommendation_Domain_Model_Recommendation $recommendation The recommendation object
	 * @param string $template template file path
	 *
	 * @return string The rendered fluid template (HTML or plain text)
	 */
	protected function getMailMessage(Tx_FluidRecommendation_Domain_Model_Recommendation $recommendation, $template) {
		$this->fluidTemplate->setTemplatePathAndFilename($template);
		$this->fluidTemplate->assign('recommendation', $recommendation);
		return $this->fluidTemplate->render();
	}

}
?>