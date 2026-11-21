<?php
/**
 * Head Injection Block for Consentinel.
 *
 * @category  Consentinel
 * @package   Consentinel_CookieConsent
 * @copyright 2026 Consentinel (https://consentinel.com)
 * @license   GPL-2.0-or-later
 */

namespace Consentinel\CookieConsent\Block;

use Consentinel\CookieConsent\Helper\Data as ConsentinelHelper;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Head extends Template
{
    /**
     * @var ConsentinelHelper
     */
    protected $helper;

    /**
     * @param Context $context
     * @param ConsentinelHelper $helper
     * @param array $data
     */
    public function __construct(
        Context $context,
        ConsentinelHelper $helper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->helper = $helper;
    }

    /**
     * Check if module is enabled.
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->helper->isEnabled();
    }

    /**
     * Get JSON configuration for window.ConsentinelConfig.
     *
     * @return string
     */
    public function getConfigJson(): string
    {
        return $this->helper->getEngineConfigJson();
    }

    /**
     * Get URL for consentinel.min.js web asset.
     *
     * @return string
     */
    public function getEngineScriptUrl(): string
    {
        return $this->getViewFileUrl('Consentinel_CookieConsent::js/consentinel.min.js');
    }
}
