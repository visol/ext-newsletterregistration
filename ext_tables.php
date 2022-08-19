<?php
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
if (!defined('TYPO3')) {
    die('Access denied.');
}

/* Registration plugin with FlexForm */
ExtensionUtility::registerPlugin('newsletterregistration', 'Newsletterregistration',
    'LLL:EXT:newsletterregistration/Resources/Private/Language/locallang.xlf:pluginLabel',
    'EXT:newsletterregistration/ext_icon.gif');

$pluginSignature = str_replace('_', '', 'newsletterregistration') . '_newsletterregistration';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
ExtensionManagementUtility::addPiFlexFormValue($pluginSignature,
    'FILE:EXT:newsletterregistration/Configuration/FlexForm/flexform_newsletterregistration.xml');

/* TypoScript configuration */
ExtensionManagementUtility::addStaticFile('newsletterregistration', 'Configuration/TypoScript',
    'Newsletter Registration');
