<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

/* Registration plugin */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Visol.' . $_EXTKEY,
	'Newsletterregistration',
	array(
		'FrontendUser' => 'new,create,edit,update,pendingOptIn,activate,invalidLink,updateError,delete',

	),
	// non-cacheable actions
	array(
		'FrontendUser' => 'new,create,edit,update,activate,delete'
	)
);