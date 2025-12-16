<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || die();

(static function (): void {
    /* Registration plugin with FlexForm */
    ExtensionUtility::registerPlugin(
        'newsletterregistration',
        'newsletterregistration',
        'LLL:EXT:newsletterregistration/Resources/Private/Language/locallang.xlf:pluginLabel',
        'newsletterregistration-plugin-newsletter'
    );

    $pluginSignature = 'newsletterregistration_newsletterregistration';
    ExtensionManagementUtility::addToAllTCAtypes(
        'tt_content',
        '--div--;Configuration,pi_flexform,',
        $pluginSignature,
        'after:subheader'
    );
    ExtensionManagementUtility::addPiFlexFormValue(
        '*',
        'FILE:EXT:newsletterregistration/Configuration/FlexForm/flexform_newsletterregistration.xml',
        $pluginSignature
    );
})();
