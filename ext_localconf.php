<?php

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use Visol\Newsletterregistration\Controller\FrontendUserController;

defined('TYPO3') || die();

(static function (): void {
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
})();

// Register custom icons for the extension (TYPO3 v13 Best Practice)
if (class_exists(\Visol\Newsletterregistration\Icon::class)) {
    \Visol\Newsletterregistration\Icon::registerIcons();
}
