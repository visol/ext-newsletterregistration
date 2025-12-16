<?php
namespace Visol\Newsletterregistration;

use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;
use TYPO3\CMS\Core\Imaging\IconRegistry;

class Icon
{
    public static function registerIcons(): void
    {
        $iconRegistry = self::getIconRegistry();
        $iconRegistry->registerIcon(
            'newsletterregistration-plugin-newsletter',
            SvgIconProvider::class,
            ['source' => 'EXT:newsletterregistration/Resources/Public/Icons/newsletterregistration.svg']
        );
    }

    protected static function getIconRegistry(): IconRegistry
    {
        return $GLOBALS['TYPO3_ICON_REGISTRY'] ?? \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(IconRegistry::class);
    }
}
