<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

if (!defined('TYPO3')) {
    die('Access denied.');
}

/* TypoScript configuration */
ExtensionManagementUtility::addStaticFile(
    'newsletterregistration',
    'Configuration/TypoScript',
    'Newsletter Registration'
);
