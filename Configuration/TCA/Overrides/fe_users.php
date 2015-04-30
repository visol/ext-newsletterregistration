<?php
defined('TYPO3_MODE') or die();

$temporaryColumns = array (
	'gender' => array (
		'label' => 'LLL:EXT:newsletterregistration/Resources/Private/Language/locallang_db.xlf:fe_users.gender',
		'config' => array (
			'type'    => 'radio',
			'default' => 'm',
			'items'   => array(
				array('LLL:EXT:newsletterregistration/Resources/Private/Language/locallang_db.xlf:fe_users.gender.m', 'm'),
				array('LLL:EXT:newsletterregistration/Resources/Private/Language/locallang_db.xlf:fe_users.gender.f', 'f')
			)
		)
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
	'fe_users',
	$temporaryColumns,
	TRUE
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
	'fe_users',
	'gender',
	'',
	'after:company'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
	'fe_users',
	'title,first_name,last_name',
	'',
	'after:gender'
);

$GLOBALS['TCA']['fe_users']['columns']['usergroup']['config']['minitems'] = 0;