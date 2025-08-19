<?php

namespace Visol\Newsletterregistration\Domain\Repository;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\Repository;
use Visol\Newsletterregistration\Domain\Model\FrontendUser;

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
class FrontendUserRepository extends Repository
{
    public function initializeObject(): void
    {
        $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
    }

    /**
     * @param string $email
     * @param int $targetFolder
     * @return FrontendUser|null
     */
    public function findOneByEmailAndStoragePageId($email, $targetFolder)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields(true);
        $query->getQuerySettings()->setRespectStoragePage(true);
        $query->getQuerySettings()->setStoragePageIds([$targetFolder]);
        $query->matching($query->equals('email', $email));
        return $query->execute()->getFirst();
    }

    /**
     * Override default findByUid function to enable also the option to turn of
     * the enableField setting
     *
     * @param int $uid id of record
     * @param bool $respectEnableFields if set to false, hidden records are shown
     */
    public function findByUid($uid, bool $respectEnableFields = true): ?FrontendUser
    {
        $query = $this->createQuery();

        if (!$respectEnableFields) {
            $query->getQuerySettings()->setIgnoreEnableFields(true);
        }

        return $query->matching(
            $query->logicalAnd(
                $query->equals('uid', $uid),
                $query->equals('deleted', 0)
            )
        )->execute()->getFirst();
    }
}
