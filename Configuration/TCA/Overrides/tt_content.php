<?php
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

/* Registration plugin with FlexForm */
ExtensionUtility::registerPlugin(
    'newsletterregistration',
    'Newsletterregistration',
    'LLL:EXT:newsletterregistration/Resources/Private/Language/locallang.xlf:pluginLabel',
    'EXT:newsletterregistration/ext_icon.gif'
);

$pluginSignature = 'newsletterregistration_newsletterregistration';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tt_content', '--div--;Configuration,pi_flexform,', $pluginSignature, 'after:subheader');
ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:newsletterregistration/Configuration/FlexForm/flexform_newsletterregistration.xml',
    $pluginSignature
);
