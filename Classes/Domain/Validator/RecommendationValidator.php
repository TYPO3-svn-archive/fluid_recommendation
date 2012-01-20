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
 * The recommendation validator
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_FluidRecommendation_Domain_Validator_RecommendationValidator extends Tx_Extbase_Validation_Validator_AbstractValidator {

	/**
	 * Settings of frontend plugin
	 *
	 * @var array
	 */
	private $settings = array();

	/**
	 * Constructor of this model validator
	 */
	public function __construct() {
		/** @var $objectManager Tx_Extbase_Object_ObjectManager */
		$objectManager = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager');

		/** @var $configurationManager Tx_Extbase_Configuration_ConfigurationManager */
		$configurationManager = $objectManager->get('Tx_Extbase_Configuration_ConfigurationManager');

		$this->settings = $configurationManager->getConfiguration(
			Tx_Extbase_Configuration_ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS
		);
	}

	/**
	 * Initial function to validate
	 *
	 * @param Tx_FluidRecommendation_Domain_Model_Recommendation $model
	 *
	 * @return boolean returns TRUE if conform to requirements, FALSE otherwise
	 */
	public function isValid($model) {
		$isValid = TRUE;
		$requiredFields = t3lib_div::trimExplode(',', $this->settings['requiredFields'], TRUE);

		foreach ($requiredFields as $requiredField) {
			$getter = 'get' . ucfirst($requiredField);
			if (method_exists($model, $getter)) {
				if (!$model->$getter()) {
					$isValid = FALSE;
					$error = t3lib_div::makeInstance('Tx_Extbase_Validation_Error', NULL, 1221560718);
					$this->addErrorForProperty($requiredField, $error);
				}
			}
		}
		return $isValid;
	}

	/**
	 * Adds the given error to $this->errors.
	 * Creates an PropertyError instance if needed.
	 *
	 * @param string $propertyName Name of the property to add error for
	 * @param Tx_Extbase_Validation_Error $error The error object
	 *
	 * @return void
	 */
	protected function addErrorForProperty($propertyName, Tx_Extbase_Validation_Error $error) {
		if (!isset($this->errors[$propertyName])) {
			$this->errors[$propertyName] = t3lib_div::makeInstance('Tx_Extbase_Validation_PropertyError', $propertyName);
		}
		$this->errors[$propertyName]->addErrors(array($error));
	}
}
?>