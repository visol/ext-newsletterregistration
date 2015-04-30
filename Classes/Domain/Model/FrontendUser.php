<?php
namespace Visol\Newsletterregistration\Domain\Model;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */
/**
 * A Frontend User
 *
 * @api
 */
class FrontendUser extends \TYPO3\CMS\Extbase\Domain\Model\FrontendUser {

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup>
	 */
	protected $usergroup = NULL;

	/**
	 * @var string
	 */
	protected $gender;

	/**
	 * @var boolean
	 */
	protected  $activateNewsletter;

	/**
	 * @var boolean
	 */
	protected  $receiveHtmlMail;

	/**
	 * @var boolean
	 */
	protected $disable;

	/**
	 * @validate EmailAddress
	 * @validate NotEmpty
	 * @var string
	 */
	protected $email;

	/**
	 * Constructs a new Front-End User
	 *
	 * @api
	 * @param string $username
	 * @param string $password
	 */
	public function __construct($username = '', $password = '') {
		parent::__construct($username, $password);
	}

	/**
	 * @return string
	 */
	public function getGender() {
		return $this->gender;
	}

	/**
	 * @param string $gender
	 */
	public function setGender($gender) {
		$this->gender = $gender;
	}

	/**
	 * @return boolean
	 */
	public function isActivateNewsletter() {
		return $this->activateNewsletter;
	}

	/**
	 * @param boolean $activateNewsletter
	 */
	public function setActivateNewsletter($activateNewsletter) {
		$this->activateNewsletter = $activateNewsletter;
	}

	/**
	 * @return boolean
	 */
	public function isReceiveHtmlMail() {
		return $this->receiveHtmlMail;
	}

	/**
	 * @param boolean $receiveHtmlMail
	 */
	public function setReceiveHtmlMail($receiveHtmlMail) {
		$this->receiveHtmlMail = $receiveHtmlMail;
	}

	/**
	 * @return boolean
	 */
	public function isDisable() {
		return $this->disable;
	}

	/**
	 * @param boolean $disable
	 */
	public function setDisable($disable) {
		$this->disable = $disable;
	}

}
