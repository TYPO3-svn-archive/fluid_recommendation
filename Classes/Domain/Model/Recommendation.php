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
 * The recommendation model
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_FluidRecommendation_Domain_Model_Recommendation extends Tx_Extbase_DomainObject_AbstractEntity {
	/**
	 * Path of default mail template without type. Filename will be MailTemplateHtml for example.
	 *
	 * @var string
	 */
	const DEFAULT_TEMPLATE = 'Resources/Private/Templates/Recommendation/MailTemplate';

	/**
	 * Template file extension.
	 *
	 * @var string
	 */
	const DEFAULT_TEMPLATE_EXTENSION = '.html';

	/**
	 * receiver first name
	 *
	 * @var string
	 */
	protected $receiverFirstName;

	/**
	 * receiver last name
	 *
	 * @var string
	 */
	protected $receiverLastName;

	/**
	 * receiver mail address
	 *
	 * @var string
	 *
	 * @validate NotEmpty
	 * @validate EmailAddress
	 */
	protected $receiverMail;

	/**
	 * sender first name
	 *
	 * @var string
	 */
	protected $senderFirstName;

	/**
	 * sender last name
	 *
	 * @var string
	 */
	protected $senderLastName;

	/**
	 * sender mail address
	 *
	 * @var string
	 *
	 * @validate NotEmpty
	 * @validate EmailAddress
	 */
	protected $senderMail;

	/**
	 * mail's subject
	 *
	 * @var string
	 */
	protected $subject;

	/**
	 * mail's message
	 *
	 * @var string
	 */
	protected $message;

	/**
	 * url of page to recommend
	 *
	 * @var string
	 *
	 * @validate NotEmpty
	 */
	protected $url;

	/**
	 * templates of mail body (key=type, value=template)
	 *
	 * @var array
	 */
	protected $templates = array();


	/**
	 * @return string
	 */
	public function getReceiverFirstName() {
		return $this->receiverFirstName;
	}

	/**
	 * @param string $receiverFirstName
	 * @return void
	 */
	public function setReceiverFirstName($receiverFirstName) {
		$this->receiverFirstName = $receiverFirstName;
	}

	/**
	 * @return string
	 */
	public function getReceiverLastName() {
		return $this->receiverLastName;
	}

	/**
	 * @param string $receiverLastName
	 * @return void
	 */
	public function setReceiverLastName($receiverLastName) {
		$this->receiverLastName = $receiverLastName;
	}

	/**
	 * @return string
	 */
	public function getReceiverMail() {
		return $this->receiverMail;
	}

	/**
	 * @param string $receiverMail
	 * @return void
	 */
	public function setReceiverMail($receiverMail) {
		$this->receiverMail = $receiverMail;
	}

	/**
	 * @return string
	 */
	public function getSenderFirstName() {
		return $this->senderFirstName;
	}

	/**
	 * @param string $senderFirstName
	 * @return void
	 */
	public function setSenderFirstName($senderFirstName) {
		$this->senderFirstName = $senderFirstName;
	}

	/**
	 * @return string
	 */
	public function getSenderLastName() {
		return $this->senderLastName;
	}

	/**
	 * @param string $senderLastName
	 * @return void
	 */
	public function setSenderLastName($senderLastName) {
		$this->senderLastName = $senderLastName;
	}

	/**
	 * @return string
	 */
	public function getSenderMail() {
		return $this->senderMail;
	}

	/**
	 * @param string $senderMail
	 * @return void
	 */
	public function setSenderMail($senderMail) {
		$this->senderMail = $senderMail;
	}

	/**
	 * @return string
	 */
	public function getMessage() {
		return $this->message;
	}

	/**
	 * @param string $comment
	 * @return void
	 */
	public function setMessage($message) {
		$this->message = $message;
	}

	/**
	 * @return string
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 * @param string $url
	 * @return void
	 */
	public function setUrl($url) {
		$this->url = $url;
	}

	/**
	 * @return string
	 */
	public function getSubject() {
		return $this->subject;
	}

	/**
	 * Set subject. Replaces recommendation attributes if exists.
	 * Subject example: "Subject contains the {receiverFirstName}!"
	 *
	 * @param string $subject the mail subject. May contain attributes of this model.
	 *
	 * @return void
	 */
	public function setSubject($subject) {
		$this->subject = $subject;
		if (preg_match_all('/\{.*?\}/', $this->subject, $matches)) {
			foreach ($matches[0] as $match) {
				$getter = 'get' . ucfirst(substr($match, 1, -1));
				if (method_exists($this, $getter)) {
					$this->subject = str_replace($match, $this->$getter(), $this->subject);
				}
			}
		}
	}

	/**
	 * Get templates with type as key
	 *
	 * @return array Templates, with type as key
	 */
	public function getTemplates() {
		return $this->templates;
	}

	/**
	 * Adds template by type
	 *
	 * @param string $type type of template
	 * @param string $template Path of template. If NULL or empty string, default template will be taken
	 *
	 * @return void
	 */
	public function addTemplate($type, $template = NULL) {
		if (!empty($template) && file_exists(PATH_site . $template)) {
			$this->templates[$type] = $template;
		} else {
			$defaultTemplate = self::DEFAULT_TEMPLATE . ucfirst($type) . self::DEFAULT_TEMPLATE_EXTENSION;
			$this->templates[$type] = t3lib_extMgm::extPath('fluid_recommendation') . $defaultTemplate;
		}
	}
}
?>