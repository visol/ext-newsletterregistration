<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

(static function (): void {
    $temporaryColumns = [
        'gender' => [
            'label' => 'LLL:EXT:newsletterregistration/Resources/Private/Language/locallang_db.xlf:fe_users.gender',
            'config' => [
                'type' => 'radio',
                'default' => 'm',
                'items' => [
                    ['label' => 'LLL:EXT:newsletterregistration/Resources/Private/Language/locallang_db.xlf:fe_users.gender.m', 'value' => 'm'],
                    ['label' => 'LLL:EXT:newsletterregistration/Resources/Private/Language/locallang_db.xlf:fe_users.gender.f', 'value' => 'f'],
                ],
            ],
        ],
    ];

    ExtensionManagementUtility::addTCAcolumns('fe_users', $temporaryColumns);

    ExtensionManagementUtility::addToAllTCAtypes('fe_users', 'gender', '', 'after:company');

    ExtensionManagementUtility::addToAllTCAtypes(
        'fe_users',
        'title,first_name,last_name',
        '',
        'after:gender'
    );

    $GLOBALS['TCA']['fe_users']['columns']['usergroup']['config']['minitems'] = 0;
})();
