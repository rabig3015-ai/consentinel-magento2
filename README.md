# Consentinel Cookie Consent & Pre-Consent Script Blocker for Magento 2

> **Enterprise Cookie Consent, Pre-Consent Script Interception & Google Consent Mode v2 for Adobe Commerce & Magento 2 Open Source.**  
> Module Name: `Consentinel_CookieConsent`  
> Package: `consentinel/module-cookie-consent`

---

## Overview

Most cookie banners on Magento 2 / Adobe Commerce only render visual popups while Google Tag Manager, Google Analytics, Meta Pixel, TikTok, and advertising pixels continue firing in the background.

**Consentinel** delivers zero-leak compliance by intercepting dynamic script injection (`document.createElement('script')`), network beacons (`fetch`, `XMLHttpRequest`, `navigator.sendBeacon`), and DOM mutations before user consent is granted.

---

## Key Features

- 🛡️ **True Pre-Consent Script Blocker**: Automatically quarantines marketing and analytics trackers until explicit visitor approval.
- ⚡ **High-Priority Head Injection**: Injected at the very top of `head.additional` via layout XML (`before="-"`), ensuring execution before GTM, Google Analytics 4, Meta Pixel, and Adobe Commerce tracking.
- 🚦 **Google Consent Mode v2**: Full support for `ad_storage`, `analytics_storage`, `ad_user_data`, and `ad_personalization`, default-denied on initial load and dynamically updated upon consent.
- 🎨 **Flexible Layout & Styling**:
  - **Floating Card**: Modern floating card on the bottom-left of the storefront.
  - **Bottom Dock**: Full-width fixed bottom bar with zero Cumulative Layout Shift (CLS).
  - **Top Bar**: Full-width top notification banner.
  - Granular color controls for brand accent color, background, and text.
- 🛒 **Store-View & Multi-Website Scoped**: Fully configurable per Website and Store View in the Magento Admin panel.
- ⚡ **Lightweight & High-Performance**: Under 15KB gzipped, zero external dependencies, no render-blocking delays.

---

## Directory Structure

```text
apps/magento2-module/
├── registration.php                        # ComponentRegistrar definition
├── composer.json                           # Magento 2 module package definition
├── etc/
│   ├── module.xml                          # Module declaration & sequence
│   ├── config.xml                          # Default system configuration
│   └── adminhtml/
│       └── system.xml                      # Stores -> Configuration settings
├── Block/
│   └── Head.php                            # Head injection block
├── Helper/
│   └── Data.php                            # Configuration reader & serializer
├── Model/
│   └── Config/
│       └── Source/
│           └── Layout.php                  # Layout option source
├── view/
│   └── frontend/
│       ├── layout/
│       │   └── default.xml                 # Injects block into head.additional (before="-")
│       ├── templates/
│       │   └── head/
│       │       └── consentinel.phtml       # Renders config and JS script tag
│       └── web/
│           └── js/
│               └── consentinel.min.js      # Standalone compiled engine asset
└── README.md
```

---

## Installation

### Method 1: Via Composer (Recommended)

1. Add the package to your Magento root `composer.json` or private repository:
   ```bash
   composer require consentinel/module-cookie-consent
   ```
2. Enable the module and run setup upgrade:
   ```bash
   bin/magento module:enable Consentinel_CookieConsent
   bin/magento setup:upgrade
   bin/magento setup:di:compile
   bin/magento setup:static-content:deploy -f
   bin/magento cache:flush
   ```

### Method 2: Manual Installation (`app/code`)

1. Create directory `app/code/Consentinel/CookieConsent` in your Magento 2 root:
   ```bash
   mkdir -p app/code/Consentinel/CookieConsent
   ```
2. Copy the contents of `apps/magento2-module/` into `app/code/Consentinel/CookieConsent`.
3. Enable and compile:
   ```bash
   bin/magento module:enable Consentinel_CookieConsent
   bin/magento setup:upgrade
   bin/magento setup:di:compile
   bin/magento cache:flush
   ```

---

## Configuration

In the Magento Admin panel, navigate to:  
**Stores → Configuration → Consentinel → Cookie Consent & Pre-Consent Blocker**

### 1. General Settings
- **Enable Consentinel**: Set to **Yes** to activate pre-consent script blocking and the banner.
- **Enable Google Consent Mode v2**: Set to **Yes** (Recommended) to initialize Google tags with default-denied consent mode signals.
- **Auto-Show Banner**: Display banner to visitors who have not yet consented.

### 2. Layout & Styling
- **Banner Layout**: Choose between:
  - `Floating Card (Bottom Left)`
  - `Bottom Dock (Full Width)`
  - `Top Bar (Full Width)`
- **Primary Accent Color**: Hex color code for the Accept All button and active category toggles (e.g., `#0f172a`).
- **Button Text Color**: Hex color code for button text (e.g., `#ffffff`).
- **Background Color**: Hex color code for the banner container background (e.g., `#ffffff`).
- **Text Color**: Hex color code for banner text and headings (e.g., `#1e293b`).

### 3. Content & Messaging
- **Banner Title**: Heading displayed on the banner (default: `We value your privacy`).
- **Banner Description**: Compliance copy explaining cookie and tracking usage.

After saving changes, flush the Magento configuration cache:
```bash
bin/magento cache:flush config
```

---

## How It Works

### High Priority In Head
The module hooks into `head.additional` using `before="-"`:
```xml
<referenceBlock name="head.additional">
    <block class="Consentinel\CookieConsent\Block\Head"
           name="consentinel_head"
           template="Consentinel_CookieConsent::head/consentinel.phtml"
           before="-" />
</referenceBlock>
```
This guarantees the script executes before third-party tracking extensions, Google Tag Manager, or Meta Pixel blocks.

### Quarantine & Release Cycle
1. When unconsented visitors visit any page, tracking scripts injected via GTM or standard `<script>` tags are quarantined.
2. Google Consent Mode v2 signals are dispatched synchronously:
   ```javascript
   gtag('consent', 'default', {
     'ad_storage': 'denied',
     'analytics_storage': 'denied',
     'ad_user_data': 'denied',
     'ad_personalization': 'denied'
   });
   ```
3. When the visitor clicks **Accept All** or chooses specific categories in **Preferences**, quarantined scripts for approved categories are automatically released and executed in their intended sequence.

---

## Content Security Policy (CSP)

If using Magento 2.4+ CSP in `restrict` mode:
- Consentinel loads its script locally from `view/frontend/web/js/consentinel.min.js` (same origin).
- The inline configuration script uses a standard hash/nonce if required by your CSP policy.

---

## Uninstallation

To remove the module:
```bash
bin/magento module:disable Consentinel_CookieConsent
bin/magento setup:upgrade
bin/magento cache:flush
```
If installed via Composer:
```bash
composer remove consentinel/module-cookie-consent
```

---

## License

GPL-2.0-or-later. Copyright 2026 Consentinel.
