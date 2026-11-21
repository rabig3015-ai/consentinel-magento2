<?php
/**
 * Consentinel Cookie Consent Magento 2 Module Registration.
 *
 * @category  Consentinel
 * @package   Consentinel_CookieConsent
 * @author    Consentinel <support@consentinel.com>
 * @copyright 2026 Consentinel (https://consentinel.com)
 * @license   GPL-2.0-or-later
 */

use Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(
    ComponentRegistrar::MODULE,
    'Consentinel_CookieConsent',
    __DIR__
);
