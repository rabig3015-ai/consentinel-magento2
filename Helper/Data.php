<?php
/**
 * Consentinel Cookie Consent Configuration Helper.
 *
 * @category  Consentinel
 * @package   Consentinel_CookieConsent
 * @copyright 2026 Consentinel (https://consentinel.com)
 * @license   GPL-2.0-or-later
 */

namespace Consentinel\CookieConsent\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    const XML_PATH_ENABLED            = 'consentinel/general/enabled';
    const XML_PATH_ENABLE_GCM_V2      = 'consentinel/general/enable_gcm_v2';
    const XML_PATH_AUTO_SHOW_BANNER   = 'consentinel/general/auto_show_banner';

    const XML_PATH_LAYOUT             = 'consentinel/design/layout';
    const XML_PATH_PRIMARY_COLOR      = 'consentinel/design/primary_color';
    const XML_PATH_PRIMARY_TEXT_COLOR = 'consentinel/design/primary_text_color';
    const XML_PATH_BACKGROUND_COLOR   = 'consentinel/design/background_color';
    const XML_PATH_TEXT_COLOR         = 'consentinel/design/text_color';

    const XML_PATH_TITLE              = 'consentinel/content/title';
    const XML_PATH_DESCRIPTION        = 'consentinel/content/description';

    /**
     * @var Json
     */
    protected $jsonSerializer;

    /**
     * @param Context $context
     * @param Json $jsonSerializer
     */
    public function __construct(
        Context $context,
        Json $jsonSerializer
    ) {
        parent::__construct($context);
        $this->jsonSerializer = $jsonSerializer;
    }

    /**
     * Check if Consentinel is enabled.
     *
     * @param int|null $storeId
     * @return bool
     */
    public function isEnabled($storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ENABLED,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Check if Google Consent Mode v2 is enabled.
     *
     * @param int|null $storeId
     * @return bool
     */
    public function isGcmV2Enabled($storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ENABLE_GCM_V2,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Check if banner should automatically display.
     *
     * @param int|null $storeId
     * @return bool
     */
    public function isAutoShowBanner($storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_AUTO_SHOW_BANNER,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get layout configuration.
     *
     * @param int|null $storeId
     * @return string
     */
    public function getLayout($storeId = null): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_LAYOUT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        ) ?: 'box';
    }

    /**
     * Build EngineConfig array for Consentinel JS engine.
     *
     * @param int|null $storeId
     * @return array
     */
    public function getEngineConfig($storeId = null): array
    {
        $rawLayout = $this->getLayout($storeId);
        $position = 'bottom';
        $layout = 'box';

        if ($rawLayout === 'bar_top') {
            $layout = 'bar';
            $position = 'top';
        } elseif ($rawLayout === 'bar_bottom') {
            $layout = 'bar';
            $position = 'bottom';
        } else {
            $layout = 'box';
            $position = 'bottom';
        }

        $primaryColor = (string)$this->scopeConfig->getValue(self::XML_PATH_PRIMARY_COLOR, ScopeInterface::SCOPE_STORE, $storeId) ?: '#0f172a';
        $primaryTextColor = (string)$this->scopeConfig->getValue(self::XML_PATH_PRIMARY_TEXT_COLOR, ScopeInterface::SCOPE_STORE, $storeId) ?: '#ffffff';
        $backgroundColor = (string)$this->scopeConfig->getValue(self::XML_PATH_BACKGROUND_COLOR, ScopeInterface::SCOPE_STORE, $storeId) ?: '#ffffff';
        $textColor = (string)$this->scopeConfig->getValue(self::XML_PATH_TEXT_COLOR, ScopeInterface::SCOPE_STORE, $storeId) ?: '#1e293b';

        $title = (string)$this->scopeConfig->getValue(self::XML_PATH_TITLE, ScopeInterface::SCOPE_STORE, $storeId) ?: __('We value your privacy');
        $description = (string)$this->scopeConfig->getValue(self::XML_PATH_DESCRIPTION, ScopeInterface::SCOPE_STORE, $storeId)
            ?: __('We use cookies and similar technologies to enhance site navigation, analyze site usage, and assist in our marketing efforts.');

        return [
            'autoShowBanner' => $this->isAutoShowBanner($storeId),
            'enableGCMv2' => $this->isGcmV2Enabled($storeId),
            'bannerTheme' => [
                'primaryColor' => $primaryColor,
                'primaryTextColor' => $primaryTextColor,
                'backgroundColor' => $backgroundColor,
                'textColor' => $textColor,
                'position' => $position,
                'layout' => $layout,
            ],
            'bannerTexts' => [
                'title' => (string)$title,
                'description' => (string)$description,
            ]
        ];
    }

    /**
     * Get JSON-encoded config string for storefront injection.
     *
     * @param int|null $storeId
     * @return string
     */
    public function getEngineConfigJson($storeId = null): string
    {
        return $this->jsonSerializer->serialize($this->getEngineConfig($storeId));
    }
}
