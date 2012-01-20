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
 * This class is used to clear the whole extensions cache if called by the backend
 *
 * @author Armin Rüdiger Vieweg <info@professorweb.de>
 */
class tx_FluidRecommendation_Hook_ClearCache {
	/**
	 * The directory to store cached files to.
	 * @var string
	 */
	const CACHE_DIRECTORY = 'typo3temp/fluid_recommendation/';

	/**
	 * Clears all cached files.
	 *
	 * @return void
	 */
	public function process() {
		$path = PATH_site . self::CACHE_DIRECTORY;
		$files = t3lib_div::getFilesInDir($path);

		foreach ($files as $file) {
			unlink($path . $file);
		}
	}
}
?>