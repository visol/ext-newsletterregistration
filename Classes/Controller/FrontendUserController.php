<?php

namespace Visol\Newsletterregistration\Controller;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
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
class FrontendUserController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * @var \Visol\Newsletterregistration\Domain\Repository\FrontendUserRepository
     * @inject
     */
    protected $frontendUserRepository;

    /**
     * persistenceManager
     *
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
     * @inject
     */
    protected $persistenceManager;

    /**
     * @param \Visol\Newsletterregistration\Domain\Model\FrontendUser $newFrontendUser
     */
    public function newAction(FrontendUser $newFrontendUser = null)
    {
        $this->view->assign('newFrontendUser', $newFrontendUser);
    }

    /**
     * @param \Visol\Newsletterregistration\Domain\Model\FrontendUser $newFrontendUser
     */
    public function createAction(FrontendUser $newFrontendUser)
    {
        /** @var \Visol\Newsletterregistration\Domain\Model\FrontendUser $existingFrontendUser */
        $existingFrontendUser = $this->frontendUserRepository->findOneByEmailAndStoragePageId($newFrontendUser->getEmail(),
            (int)$this->settings['userFolder']);
        if ($existingFrontendUser instanceof FrontendUser) {
            if ($existingFrontendUser->isDisable() === true) {
                // user is disabled, so maybe the opt-in failed - we send an opt-in-email again
                $url = $this->createOptInUri($existingFrontendUser->getUid());
                $optInUri = '<a href="' . $url .'">' . $url . '</a>';
                $emailContent = LocalizationUtility::translate('createFrontendUser.optInEmail.activate',
                    $this->controllerContext->getRequest()->getControllerExtensionName(),
                    [1 => $this->settings['newsletterTitle'], 2 => $optInUri]);
                $this->sendEmail($existingFrontendUser->getEmail(), $this->settings['newsletterTitle'], $emailContent);
            } else {
                // user exists and is active
                if (!empty($this->settings['fieldList'])) {
                    // user is editable, we send an edit profile link
                    $optInUri = $this->createOptInUri($existingFrontendUser->getUid(), 'edit');
                    $emailContent = LocalizationUtility::translate('createFrontendUser.optInEmail.edit.unsubscribeOnly',
                        $this->controllerContext->getRequest()->getControllerExtensionName(),
                        [1 => $this->settings['newsletterTitle'], 2 => $optInUri]);
                    $this->sendEmail($existingFrontendUser->getEmail(), $this->settings['newsletterTitle'],
                        $emailContent);
                } else {
                    // user is not editable, so we send an unsubscribe link
                    $optInUri = $this->createOptInUri($existingFrontendUser->getUid(), 'delete');
                    $emailContent = LocalizationUtility::translate('createFrontendUser.optInEmail.edit.unsubscribeOnly',
                        $this->controllerContext->getRequest()->getControllerExtensionName(),
                        [1 => $this->settings['newsletterTitle'], 2 => $optInUri]);
                    $this->sendEmail($existingFrontendUser->getEmail(), $this->settings['newsletterTitle'],
                        $emailContent);
                }
            }
        } else {
            $newFrontendUser->setUsername($newFrontendUser->getEmail());
            $newFrontendUser->setPassword(\Visol\Newsletterregistration\Utility\Algorithms::generateRandomString(32));
            $newFrontendUser->setActivateNewsletter(true);
            $newFrontendUser->setReceiveHtmlMail(true);
            $newFrontendUser->setDisable(true);
            $newFrontendUser->setPid((int)$this->settings['userFolder']);
            $this->frontendUserRepository->add($newFrontendUser);
            $this->persistenceManager->persistAll();
            $optInUri = $this->createOptInUri($newFrontendUser->getUid());
            $emailContent = LocalizationUtility::translate('createFrontendUser.optInEmail.activate',
                $this->controllerContext->getRequest()->getControllerExtensionName(),
                [1 => $this->settings['newsletterTitle'], 2 => $optInUri]);
            $this->sendEmail($newFrontendUser->getEmail(), $this->settings['newsletterTitle'], $emailContent);
        }

        $this->redirect('pendingOptIn');
    }

    /**
     * Display information that account was created and opt-in e-mail was sent
     */
    public function pendingOptInAction()
    {
    }

    /**
     * @param string $ruid
     * @param string $verify
     */
    public function activateAction($ruid = '', $verify = '')
    {
        $frontendUserUid = (int)base64_decode($ruid);
        if (!$frontendUserUid) {
            // frontendUserUid could not be decoded
            $this->forward('invalidLink');
        } else {
            if ($verify === GeneralUtility::stdAuthCode($frontendUserUid)) {
                $frontendUser = $this->frontendUserRepository->findOneByUidAndStoragePageId($frontendUserUid,
                    (int)$this->settings['userFolder']);
                if ($frontendUser instanceof FrontendUser) {
                    $frontendUser->setDisable(false);
                    $this->frontendUserRepository->update($frontendUser);
                    $this->persistenceManager->persistAll();
                    $successMessage = LocalizationUtility::translate('activateFrontendUser.success',
                        $this->controllerContext->getRequest()->getControllerExtensionName(),
                        [1 => $this->settings['newsletterTitle']]);
                    $this->addFlashMessage($successMessage);
                    $this->redirect('edit', null, null, $this->request->getArguments());
                } else {
                    // no frontend user found
                    $this->forward('invalidLink');
                }
            } else {
                // verification link was invalid
                $this->forward('invalidLink');
            }
        }
    }

    /**
     * Display information that the opt-in link provided was invalid
     */
    public function invalidLinkAction()
    {
    }

    /**
     * @param string $ruid
     * @param string $verify
     */
    public function editAction($ruid = '', $verify = '')
    {
        $frontendUserUid = (int)base64_decode($ruid);
        if (!$frontendUserUid) {
            // frontendUserUid could not be decoded
            $this->forward('invalidLink');
        } else {
            if ($verify === GeneralUtility::stdAuthCode($frontendUserUid)) {
                $frontendUser = $this->frontendUserRepository->findOneByUidAndStoragePageId($frontendUserUid,
                    (int)$this->settings['userFolder']);
                if ($frontendUser instanceof FrontendUser) {
                    $this->view->assign('frontendUser', $frontendUser);
                    $this->view->assign('unsubscribeUri', $this->createOptInUri($frontendUserUid, 'delete'));
                    $this->view->assign('verify', $verify);
                    if (!empty($this->settings['fieldList'])) {
                        // If fields need to be rendered, pass the names of the fields to the view
                        $this->view->assign('fieldsToRender',
                            GeneralUtility::trimExplode(',', $this->settings['fieldList']));
                    }
                } else {
                    // no frontend user found
                    $this->forward('invalidLink');
                }
            } else {
                // verification link was invalid
                $this->forward('invalidLink');
            }
        }
    }

    /**
     * @param FrontendUser $frontendUser
     * @param string $verify
     */
    public function updateAction(FrontendUser $frontendUser, $verify = '')
    {
        if ($verify === GeneralUtility::stdAuthCode($frontendUser->getUid())) {
            $this->frontendUserRepository->update($frontendUser);
            $this->persistenceManager->persistAll();
            $successMessage = LocalizationUtility::translate('updateFrontendUser.success',
                $this->controllerContext->getRequest()->getControllerExtensionName());
            $this->addFlashMessage($successMessage);
            $this->redirect('edit', null, null,
                ['verify' => $verify, 'ruid' => base64_encode($frontendUser->getUid())]);
        } else {
            // verification code invalid
            $this->forward('updateError');
        }
    }

    /**
     * Display information that updating the user was denied
     */
    public function updateErrorAction()
    {
    }

    /**
     * @param string $ruid
     * @param string $verify
     */
    public function deleteAction($ruid = '', $verify = '')
    {
        $frontendUserUid = (int)base64_decode($ruid);
        if (!$frontendUserUid) {
            // frontendUserUid could not be decoded
            $this->forward('deleteError');
        } else {
            if ($verify === GeneralUtility::stdAuthCode($frontendUserUid)) {
                $frontendUser = $this->frontendUserRepository->findByUid($frontendUserUid);
                if ($frontendUser instanceof FrontendUser) {
                    $this->frontendUserRepository->remove($frontendUser);
                    $this->persistenceManager->persistAll();
                } else {
                    // no frontend user found
                    $this->forward('deleteError');
                }
            } else {
                // verification link was invalid
                $this->forward('deleteError');
            }
        }
    }

    /**
     * Display information that deleting the user was denied
     */
    public function deleteErrorAction()
    {
    }

    /**
     * @param integer $uid
     * @param string $action
     * @return string
     */
    protected function createOptInUri($uid, $action = 'activate')
    {
        $arguments = [
            [
                'tx_newsletterregistration_newsletterregistration' => [
                    'controller' => 'FrontendUser',
                    'action' => $action,
                    'ruid' => base64_encode($uid),
                    'verify' => GeneralUtility::stdAuthCode($uid),
                ]
            ]
        ];
        return $this->uriBuilder->reset()->setUseCacheHash(false)->setCreateAbsoluteUri(true)->setArguments($arguments)->build();
    }

    /**
     * @param string $email
     * @param $subject
     * @param $content
     * @param string $replyTo
     * @param string $returnPath
     * @return bool
     */
    protected function sendEmail($email, $subject, $content, $replyTo = '', $returnPath = '')
    {
        /** @var $message \TYPO3\CMS\Core\Mail\MailMessage */
        $message = GeneralUtility::makeInstance('TYPO3\CMS\Core\Mail\MailMessage');
        $message->addTo($email);
        $message->setFrom([$this->settings['sender']['email'] => $this->settings['sender']['name']]);

        $contentBeforeWrap = '
<html>
	<head>
		<style type="text/css">
			<!--
			body {
				font-family: Arial, Helvetica, sans-serif;
				font-size: 14px;
			}
			-->
		</style>
	</head>
	<body>
		';
        $contentAfterWrap = '
	</body>
</html>';
        $signature = '
			<hr />
			<p>' . $this->settings['sender']['name'] . ' | <a href="' . $this->settings['sender']['linkUrl'] . '" target="_blank">' . $this->settings['sender']['linkText'] . '</a></p>';
        $message->setSubject($subject);
        $message->setBody($contentBeforeWrap . $content . $signature . $contentAfterWrap, 'text/html');
        $message->send();
        return $message->isSent();
    }

}
