{*
 * This file is part of the official Afterpay module for PrestaShop.
 *
 * @author    Afterpay <integrations@afterpay.com>
 * @copyright 2020 Afterpay
 * @license   proprietary
*}

<!-- Afterpay.js  -->
<script
        src="{$SDK_URL|escape:'javascript':'UTF-8'}"
        data-min="{$AFTERPAY_MIN_AMOUNT|escape:'javascript':'UTF-8'}"
        data-max="{$AFTERPAY_MAX_AMOUNT|escape:'javascript':'UTF-8'}"
        async>
</script>
<!-- Afterpay.js -->
<div class="AfterpaySimulator ps-version-{$PS_VERSION|escape:'htmlall':'UTF-8'}">
    <style>
        afterpay-placement {
            white-space: break-spaces;
            color: black;
        }
    </style>
    <afterpay-placement
            data-locale="{$ISO_COUNTRY_CODE|escape:'htmlall':'UTF-8'}"
            data-currency="{$CURRENCY|escape:'htmlall':'UTF-8'}"
            data-amount-selector="{$PRICE_SELECTOR|escape:'htmlall':'UTF-8'}"
            data-size="sm">
    </afterpay-placement>
</div>
