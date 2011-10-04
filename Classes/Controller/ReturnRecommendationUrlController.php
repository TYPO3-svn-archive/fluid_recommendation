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
 * Controller for the recommendation url
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_FluidRecommendation_Controller_ReturnRecommendationUrlController extends Tx_Extbase_MVC_Controller_ActionController {

	/**
	 * Returns link to formerly recommended page
	 *
	 * @return void
	 */
	public function returnUrlAction() {
		$lastRecommendationUrl = $GLOBALS['TSFE']->fe_user->getKey('ses', 'tx_fluid_recommendation_lastRecommendedPage');
		if (!empty($lastRecommendationUrl)) {
			return '<a href="' . $lastRecommendationUrl . '" class="tx_fluid_recommendation_lastRecommendedUrl">' . Tx_Extbase_Utility_Localization::translate('returnToUrlLinkText', 'fluid_recommendation') . '</a>';
		}
		return '';
	}
}
?>