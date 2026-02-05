<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;

$extIconPath = 'EXT:newsletterregistration/Resources/Public/Icons/';

return [
    'newsletterregistration-plugin-newsletter' => [
        'provider' => SvgIconProvider::class,
        'source' => $extIconPath . 'newsletterregistration.svg',
    ],
];
