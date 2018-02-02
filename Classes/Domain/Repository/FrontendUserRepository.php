<?php

namespace Visol\Newsletterregistration\Domain\Repository;

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
 * A Frontend User repository
 *
 * @api
 */
class FrontendUserRepository extends \TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository
{

    /**
     * @param string $email
     * @param integer $targetFolder
     * @return \Visol\Newsletterregistration\Domain\Model\FrontendUser|NULL
     */
    public function findOneByEmailAndStoragePageId($email, $targetFolder)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields(true);
        $query->getQuerySettings()->setStoragePageIds([$targetFolder]);
        $query->matching($query->equals('email', $email));
        return $query->execute()->getFirst();
    }

    /**
     * @param string $uid
     * @param integer $targetFolder
     * @return \Visol\Newsletterregistration\Domain\Model\FrontendUser|NULL
     */
    public function findOneByUidAndStoragePageId($uid, $targetFolder)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields(true);
        $query->getQuerySettings()->setStoragePageIds([$targetFolder]);
        $query->matching($query->equals('uid', $uid));
        return $query->execute()->getFirst();
    }

}
