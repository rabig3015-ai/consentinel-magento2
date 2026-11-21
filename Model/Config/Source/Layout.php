<?php
/**
 * Layout Option Source for Consentinel Configuration.
 *
 * @category  Consentinel
 * @package   Consentinel_CookieConsent
 * @copyright 2026 Consentinel (https://consentinel.com)
 * @license   GPL-2.0-or-later
 */

namespace Consentinel\CookieConsent\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Layout implements OptionSourceInterface
{
    /**
     * Options getter for banner layout formats.
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        return [
            ['value' => 'box', 'label' => __('Floating Card (Bottom Left)')],
            ['value' => 'bar_bottom', 'label' => __('Bottom Dock (Full Width)')],
            ['value' => 'bar_top', 'label' => __('Top Bar (Full Width)')],
        ];
    }

    /**
     * Get options in "key-value" format.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'box' => __('Floating Card (Bottom Left)'),
            'bar_bottom' => __('Bottom Dock (Full Width)'),
            'bar_top' => __('Top Bar (Full Width)'),
        ];
    }
}
