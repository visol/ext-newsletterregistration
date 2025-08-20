<?php

namespace Visol\Newsletterregistration\Domain\Model;

use TYPO3\CMS\Extbase\Annotation as Extbase;
use TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
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
class FrontendUser extends AbstractEntity
{
    protected string $username = '';

    protected string $password = '';

    /**
     * @var ObjectStorage<FrontendUserGroup>
     */
    protected $usergroup;

    protected string $gender;

    protected bool $activateNewsletter;

    protected bool $receiveHtmlMail;

    protected bool $disable;

    #[Extbase\Validate(['validator' => 'EmailAddress'])]
    #[Extbase\Validate(['validator' => 'NotEmpty'])]
    protected string $email;

    public function __construct(
        string $username = '',
        string $password = ''
    ) {
        $this->username = $username;
        $this->password = $password;
    }

    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function setGender(string $gender): void
    {
        $this->gender = $gender;
    }

    public function isActivateNewsletter(): bool
    {
        return $this->activateNewsletter;
    }

    public function setActivateNewsletter(bool $activateNewsletter): void
    {
        $this->activateNewsletter = $activateNewsletter;
    }

    public function isReceiveHtmlMail(): bool
    {
        return $this->receiveHtmlMail;
    }

    public function setReceiveHtmlMail(bool $receiveHtmlMail): void
    {
        $this->receiveHtmlMail = $receiveHtmlMail;
    }

    public function isDisable(): bool
    {
        return $this->disable;
    }

    public function setDisable(bool $disable): void
    {
        $this->disable = $disable;
    }
}
