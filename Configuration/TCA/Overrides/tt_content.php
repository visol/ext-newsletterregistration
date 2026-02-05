<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use Visol\Newsletterregistration\Backend\Preview\RegistrationFormPreviewRenderer;

defined('TYPO3') || die();

(static function (): void {
    $pluginSignature = 'newsletterregistration_newsletterregistration';

    // Add CType to selector dropdown
    ExtensionManagementUtility::addTcaSelectItem(
        'tt_content',
        'CType',
        [
            'label' => 'LLL:EXT:newsletterregistration/Resources/Private/Language/locallang.xlf:pluginLabel',
            'value' => $pluginSignature,
            'icon' => 'newsletterregistration-plugin-newsletter',
            'group' => 'plugins',
            'description' => 'LLL:EXT:newsletterregistration/Resources/Private/Language/locallang.xlf:pluginDescription',
        ]
    );

    // Configure showitem structure
    $GLOBALS['TCA']['tt_content']['types'][$pluginSignature] = [
        'showitem' => '
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                --palette--;;general,
                --palette--;;headers,
            --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.plugin,
                pi_flexform,
            --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
                --palette--;;frames,
                --palette--;;appearanceLinks,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                --palette--;;language,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                --palette--;;hidden,
                --palette--;;access,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
                categories,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
                rowDescription,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
        ',
        'previewRenderer' => RegistrationFormPreviewRenderer::class,
        'columnsOverrides' => [
            'pi_flexform' => [
                'label' => 'LLL:EXT:newsletterregistration/Resources/Private/Language/locallang.xlf:flexform.sheetTitle',
            ],
        ],
    ];

    // Set icon for the CType
    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes'][$pluginSignature] = 'newsletterregistration-plugin-newsletter';

    // Add FlexForm configuration
    ExtensionManagementUtility::addPiFlexFormValue(
        '*',
        'FILE:EXT:newsletterregistration/Configuration/FlexForm/flexform_newsletterregistration.xml',
        $pluginSignature
    );
})();
