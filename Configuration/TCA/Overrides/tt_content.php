<?php
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die();

/* Registration plugin with FlexForm */
ExtensionUtility::registerPlugin(
    'newsletterregistration',
    'Newsletterregistration',
    'LLL:EXT:newsletterregistration/Resources/Private/Language/locallang.xlf:pluginLabel',
    'EXT:newsletterregistration/ext_icon.gif'
);

$pluginSignature = 'newsletterregistration_newsletterregistration';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
ExtensionManagementUtility::addPiFlexFormValue(
    $pluginSignature,
    'FILE:EXT:newsletterregistration/Configuration/FlexForm/flexform_newsletterregistration.xml'
);
