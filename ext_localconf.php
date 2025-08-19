<?php

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use Visol\Newsletterregistration\Controller\FrontendUserController;

if (!defined('TYPO3')) {
    die('Access denied.');
}

/* Registration plugin */
ExtensionUtility::configurePlugin(
    'newsletterregistration',
    'newsletterregistration',
    [
        FrontendUserController::class => 'new,create,edit,update,pendingOptIn,activate,invalidLink,updateError,delete',
    ], // non-cacheable actions
    [
        FrontendUserController::class => 'new,create,edit,update,activate,delete',
    ],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);
