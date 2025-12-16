<?php

declare(strict_types=1);

namespace Visol\Newsletterregistration\Updates;

use TYPO3\CMS\Install\Attribute\UpgradeWizard;
use TYPO3\CMS\Install\Updates\AbstractListTypeToCTypeUpdate;

#[UpgradeWizard('visolNewsletterregistrationCTypeMigration')]
final class VisolNewsletterregistrationCTypeMigration extends AbstractListTypeToCTypeUpdate
{
    public function getTitle(): string
    {
        return 'Migrate "Visol Newsletterregistration" plugins to content elements.';
    }

    public function getDescription(): string
    {
        return 'The "Visol Newsletterregistration" plugins are now registered as content element. Update migrates existing records and backend user permissions.';
    }

    /**
     * Migrate newsletterregistration plugin from list_type to CType.
     *
     * In TYPO3 12 and earlier, the plugin was registered with list_type.
     * In TYPO3 13, it's registered as a content element (CType).
     *
     * This migration:
     * - Finds all tt_content records with CType='list' and list_type='newsletterregistration_newsletterregistration'
     * - Converts them to CType='newsletterregistration_newsletterregistration'
     * - Updates backend user group permissions accordingly
     *
     * @return array<string, string>
     */
    protected function getListTypeToCTypeMapping(): array
    {
        return [
            'newsletterregistration_newsletterregistration' => 'newsletterregistration_newsletterregistration',
        ];
    }
}
