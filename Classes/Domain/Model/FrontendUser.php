<?php

namespace Visol\Newsletterregistration\Domain\Model;

use TYPO3\CMS\Extbase\Annotation as Extbase;
use TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

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
 */
class FrontendUser extends \TYPO3\CMS\Extbase\Domain\Model\FrontendUser
{
    /**
     * @var ObjectStorage<FrontendUserGroup>
     */
    protected $usergroup;

    /**
     * @var string
     */
    protected $gender;

    /**
     * @var bool
     */
    protected $activateNewsletter;

    /**
     * @var bool
     */
    protected $receiveHtmlMail;

    /**
     * @var bool
     */
    protected $disable;

    /**
     * @var string
     */
    #[Extbase\Validate(['validator' => 'EmailAddress'])]
    #[Extbase\Validate(['validator' => 'NotEmpty'])]
    protected $email;

    /**
     * Constructs a new Front-End User
     *
     * @param string $username
     * @param string $password
     */
    public function __construct($username = '', $password = '')
    {
        parent::__construct($username, $password);
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     */
    public function setGender($gender): void
    {
        $this->gender = $gender;
    }

    /**
     * @return bool
     */
    public function isActivateNewsletter()
    {
        return $this->activateNewsletter;
    }

    /**
     * @param bool $activateNewsletter
     */
    public function setActivateNewsletter($activateNewsletter): void
    {
        $this->activateNewsletter = $activateNewsletter;
    }

    /**
     * @return bool
     */
    public function isReceiveHtmlMail()
    {
        return $this->receiveHtmlMail;
    }

    /**
     * @param bool $receiveHtmlMail
     */
    public function setReceiveHtmlMail($receiveHtmlMail): void
    {
        $this->receiveHtmlMail = $receiveHtmlMail;
    }

    /**
     * @return bool
     */
    public function isDisable()
    {
        return $this->disable;
    }

    /**
     * @param bool $disable
     */
    public function setDisable($disable): void
    {
        $this->disable = $disable;
    }
}
