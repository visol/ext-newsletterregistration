<?php

declare(strict_types=1);

use Visol\Newsletterregistration\Domain\Model\FrontendUser;

return [
    FrontendUser::class => [
        'tableName' => 'fe_users',
        'properties' => [
            'activateNewsletter' => [
                'fieldName' => 'module_sys_dmail_newsletter'
            ],
            'receiveHtmlMail' => [
                'fieldName' => 'module_sys_dmail_html'
            ],
        ]
    ],
];
