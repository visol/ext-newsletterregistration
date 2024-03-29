<?php

namespace Visol\Newsletterregistration\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Crypto\PasswordHashing\PasswordHashFactory;
use TYPO3\CMS\Core\Crypto\Random;
use TYPO3\CMS\Extbase\Http\ForwardResponse;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Visol\Newsletterregistration\Domain\Repository\FrontendUserRepository;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
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
class FrontendUserController extends ActionController
{

    /**
     * @var FrontendUserRepository
     */
    protected $frontendUserRepository;

    /**
     * persistenceManager
     *
     * @var PersistenceManager
     */
    protected $persistenceManager;

    /**
     * @param FrontendUser|null $newFrontendUser
     * @return ResponseInterface
     */
    public function newAction(FrontendUser $newFrontendUser = null): ResponseInterface
    {
        $this->view->assign('newFrontendUser', $newFrontendUser);
        return $this->htmlResponse();
    }

    /**
     * @param FrontendUser $newFrontendUser
     */
    public function createAction(FrontendUser $newFrontendUser): void
    {
        /** @var FrontendUser $existingFrontendUser */
        $existingFrontendUser = $this->frontendUserRepository->findOneByEmailAndStoragePageId($newFrontendUser->getEmail(),
            (int)$this->settings['userFolder']);
        if ($existingFrontendUser instanceof FrontendUser) {
            if ($existingFrontendUser->isDisable() === true) {
                // user is disabled, so maybe the opt-in failed - we send an opt-in-email again
                $url = $this->createOptInUri($existingFrontendUser->getUid());
                $optInUri = '<a href="' . $url .'">' . $url . '</a>';
                $emailContent = LocalizationUtility::translate('createFrontendUser.optInEmail.activate',
                    $this->request->getControllerExtensionName(),
                    [1 => $this->settings['newsletterTitle'], 2 => $optInUri]);
                $this->sendEmail($existingFrontendUser->getEmail(), $this->settings['newsletterTitle'], $emailContent);
            } else {
                // user exists and is active
                if (!empty($this->settings['fieldList'])) {
                    // user is editable, we send an edit profile link
                    $optInUri = $this->createOptInUri($existingFrontendUser->getUid(), 'edit');
                    $emailContent = LocalizationUtility::translate('createFrontendUser.optInEmail.edit.unsubscribeOnly',
                        $this->request->getControllerExtensionName(),
                        [1 => $this->settings['newsletterTitle'], 2 => $optInUri]);
                    $this->sendEmail($existingFrontendUser->getEmail(), $this->settings['newsletterTitle'],
                        $emailContent);
                } else {
                    // user is not editable, so we send an unsubscribe link
                    $optInUri = $this->createOptInUri($existingFrontendUser->getUid(), 'delete');
                    $emailContent = LocalizationUtility::translate('createFrontendUser.optInEmail.edit.unsubscribeOnly',
                        $this->request->getControllerExtensionName(),
                        [1 => $this->settings['newsletterTitle'], 2 => $optInUri]);
                    $this->sendEmail($existingFrontendUser->getEmail(), $this->settings['newsletterTitle'],
                        $emailContent);
                }
            }
        } else {
            $newFrontendUser->setUsername($newFrontendUser->getEmail());
            $newFrontendUser->setPassword($this->getHashedPassword($this->getRandomPassword()));
            $newFrontendUser->setActivateNewsletter(true);
            $newFrontendUser->setReceiveHtmlMail(true);
            $newFrontendUser->setDisable(true);
            $newFrontendUser->setPid((int)$this->settings['userFolder']);
            $this->frontendUserRepository->add($newFrontendUser);
            $this->persistenceManager->persistAll();
            $url = $this->createOptInUri($newFrontendUser->getUid());
            $optInUri = '<a href="' . $url .'">' . $url . '</a>';
            $emailContent = LocalizationUtility::translate('createFrontendUser.optInEmail.activate',
                $this->request->getControllerExtensionName(),
                [1 => $this->settings['newsletterTitle'], 2 => $optInUri]);
            $this->sendEmail($newFrontendUser->getEmail(), $this->settings['newsletterTitle'], $emailContent);
        }

        $this->redirect('pendingOptIn');
    }

    /**
     * Display information that account was created and opt-in e-mail was sent
     */
    public function pendingOptInAction(): ResponseInterface
    {
        return $this->htmlResponse();
    }

    /**
     * @param string $ruid
     * @param string $verify
     */
    public function activateAction(string $ruid = '', string $verify = '')
    {
        $frontendUserUid = (int)base64_decode($ruid);
        if (!$frontendUserUid) {
            return new ForwardResponse('invalidLink');
        } else {
            if ($verify === GeneralUtility::hmac($frontendUserUid)) {
                $frontendUser = $this->frontendUserRepository->findByUid($frontendUserUid, false);
                if ($frontendUser instanceof FrontendUser) {
                    $frontendUser->setDisable(false);
                    $this->frontendUserRepository->update($frontendUser);
                    $this->persistenceManager->persistAll();
                    $successMessage = LocalizationUtility::translate('activateFrontendUser.success',
                        $this->request->getControllerExtensionName(),
                        [1 => $this->settings['newsletterTitle']]);
                    $this->addFlashMessage($successMessage);
                    $this->redirect('edit', null, null, $this->request->getArguments());
                } else {
                    return new ForwardResponse('invalidLink');
                }
            } else {
                return new ForwardResponse('invalidLink');
            }
        }
    }

    /**
     * Display information that the opt-in link provided was invalid
     */
    public function invalidLinkAction(): ResponseInterface
    {
        return $this->htmlResponse();
    }

    /**
     * @param string $ruid
     * @param string $verify
     */
    public function editAction(string $ruid = '', string $verify = ''): ResponseInterface
    {
        $frontendUserUid = (int)base64_decode($ruid);
        if (!$frontendUserUid) {
            return new ForwardResponse('invalidLink');
        } else {
            if ($verify === GeneralUtility::hmac($frontendUserUid)) {
                $frontendUser = $this->frontendUserRepository->findByUid($frontendUserUid);
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
                    return new ForwardResponse('invalidLink');
                }
            } else {
                return new ForwardResponse('invalidLink');
            }
        }
        return $this->htmlResponse();
    }

    /**
     * @param FrontendUser $frontendUser
     * @param string $verify
     */
    public function updateAction(FrontendUser $frontendUser, string $verify = ''): ResponseInterface
    {
        if ($verify === GeneralUtility::hmac($frontendUser->getUid())) {
            $this->frontendUserRepository->update($frontendUser);
            $this->persistenceManager->persistAll();
            $successMessage = LocalizationUtility::translate('updateFrontendUser.success',
                $this->request->getControllerExtensionName());
            $this->addFlashMessage($successMessage);
            $this->redirect('edit', null, null,
                ['verify' => $verify, 'ruid' => base64_encode($frontendUser->getUid())]);
        } else {
            return new ForwardResponse('updateError');
        }
    }

    /**
     * Display information that updating the user was denied
     */
    public function updateErrorAction(): ResponseInterface
    {
        return $this->htmlResponse();
    }

    /**
     * @param string $ruid
     * @param string $verify
     */
    public function deleteAction(string $ruid = '', string $verify = ''): ResponseInterface
    {
        $frontendUserUid = (int)base64_decode($ruid);
        if (!$frontendUserUid) {
            return new ForwardResponse('deleteError');
        }

        if ($verify === GeneralUtility::hmac($frontendUserUid)) {
            $frontendUser = $this->frontendUserRepository->findByUid($frontendUserUid);
            if ($frontendUser instanceof FrontendUser) {
                $this->frontendUserRepository->remove($frontendUser);
                $this->persistenceManager->persistAll();
            } else {
                return new ForwardResponse('deleteError');
            }
        } else {
            return new ForwardResponse('deleteError');
        }
        return $this->htmlResponse();
    }

    /**
     * Display information that deleting the user was denied
     */
    public function deleteErrorAction(): ResponseInterface
    {
        return $this->htmlResponse();
    }

    /**
     * @param integer $uid
     * @param string $action
     * @return string
     */
    protected function createOptInUri(int $uid, string $action = 'activate'): string
    {
        $arguments = [
            'tx_newsletterregistration_newsletterregistration' => [
                'controller' => 'FrontendUser',
                'action' => $action,
                'ruid' => base64_encode($uid),
                'verify' => GeneralUtility::hmac($uid),
            ],
        ];

        return $this->uriBuilder->reset()->setCreateAbsoluteUri(true)->setArguments($arguments)->build();
    }

    /**
     * @param string $email
     * @param string $subject
     * @param string $content
     * @param string $replyTo
     * @param string $returnPath
     * @return bool
     */
    protected function sendEmail(string $email, string $subject, string $content, string $replyTo = '', string $returnPath = ''): bool
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
        $message->html($contentBeforeWrap . $content . $signature . $contentAfterWrap);
        $message->text($content);
        $message->send();
        return $message->isSent();
    }

    public function injectFrontendUserRepository(FrontendUserRepository $frontendUserRepository): void
    {
        $this->frontendUserRepository = $frontendUserRepository;
    }

    public function injectPersistenceManager(PersistenceManager $persistenceManager): void
    {
        $this->persistenceManager = $persistenceManager;
    }

    protected function getRandomPassword(): string
    {
        $randomPassword = GeneralUtility::makeInstance(Random::class)->generateRandomBytes(32);
        $hashInstance = GeneralUtility::makeInstance(PasswordHashFactory::class)->getDefaultHashInstance('FE');
        return $hashInstance->getHashedPassword($randomPassword);
    }

    protected function getHashedPassword(string $password): string
    {
        $hashInstance = GeneralUtility::makeInstance(PasswordHashFactory::class)->getDefaultHashInstance('FE');
        return $hashInstance->getHashedPassword($password);
    }

}
