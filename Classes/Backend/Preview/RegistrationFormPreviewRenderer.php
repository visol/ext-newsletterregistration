<?php

declare(strict_types=1);

namespace Visol\Newsletterregistration\Backend\Preview;

use TYPO3\CMS\Backend\Preview\StandardContentPreviewRenderer;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Backend\View\BackendLayout\Grid\GridColumnItem;
use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * Preview renderer for the newsletter registration content element.
 *
 * Compatibility: TYPO3 12.4+ (12 LTS, 13 LTS)
 */
class RegistrationFormPreviewRenderer extends StandardContentPreviewRenderer
{
    public function renderPageModulePreviewHeader(GridColumnItem $item): string
    {
        $record = $item->getRecord();
        $itemLabels = $item->getContext()->getItemLabels();
        $table = $item->getTable();
        $outHeader = '<div style="display: flex;width: 100%;justify-content: space-between;">';
        $outHeader .= '<div>';

        $headerLayout = (string) ($record['header_layout'] ?? '');
        if ($headerLayout === '100') {
            $headerLayoutHiddenLabel = $this->getLanguageService()->sL('LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header_layout.I.6');
            $outHeader .= '<div class="element-preview-header-status">' . htmlspecialchars($headerLayoutHiddenLabel) . '</div>';
        }

        $date = (string) ($record['date'] ?? '');
        if ($date !== '0' && $date !== '') {
            $dateLabel = $itemLabels['date'] . ' ' . BackendUtility::date($record['date']);
            $outHeader .= '<div class="element-preview-header-date">' . htmlspecialchars($dateLabel) . ' </div>';
        }

        $labelField = $GLOBALS['TCA'][$table]['ctrl']['label'] ?? '';
        $label = (string) ($record[$labelField] ?? '');
        if ($label !== '') {
            $outHeader .= '<div class="element-preview-header-header">' . $this->linkEditContent($this->renderText($label), $record, $table) . '</div>';
        }

        $subHeader = (string) ($record['subheader'] ?? '');
        if ($subHeader !== '') {
            $outHeader .= '<i class="element-preview-header-subheader">' . $this->linkEditContent($this->renderText($subHeader), $record) . '</i>';
        }
        $outHeader .= '</div>';

        if ($this->getBackendUser()->isAdmin()) {
            $outHeader .= '<div>';
            $outHeader .= ' <code>[' . $record['CType'] . ': ' . $record['uid'] . ']</code>';
            $outHeader .= '</div>';
        }

        return $outHeader . '</div>';
    }

    public function renderPageModulePreviewContent(GridColumnItem $item): string
    {
        $record = $item->getRecord();

        // Parse FlexForm XML to array
        $flexFormData = [];
        if (!empty($record['pi_flexform'])) {
            $flexFormService = GeneralUtility::makeInstance(FlexFormService::class);
            $flexFormData = $flexFormService->convertFlexFormContentToArray($record['pi_flexform']);

            // Convert comma-separated fieldList to array
            if (!empty($flexFormData['settings']['fieldList'])) {
                $flexFormData['settings']['fieldList'] = GeneralUtility::trimExplode(
                    ',',
                    $flexFormData['settings']['fieldList'],
                    true
                );
            }
        }

        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->getRenderingContext()->getTemplatePaths()->setTemplatePathAndFilename(
            GeneralUtility::getFileAbsFileName(
                'EXT:newsletterregistration/Resources/Private/Templates/Backend/Preview/RegistrationForm.html'
            )
        );
        $view->assignMultiple([
            'record' => $record,
            'flexFormData' => $flexFormData,
            'debug' => [
                'hasPiFlexform' => isset($record['pi_flexform']),
                'piFlexformEmpty' => empty($record['pi_flexform']),
                'piFlexformType' => gettype($record['pi_flexform'] ?? null),
                'flexFormDataKeys' => array_keys($flexFormData),
            ],
        ]);

        return $view->render();
    }
}
