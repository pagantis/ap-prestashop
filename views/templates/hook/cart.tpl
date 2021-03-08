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
<style>
    .payment-method-note.afterpay-cart-note {
        padding: 20px 0 10px 0;
        text-align: left;
    }
    .payment-method-note.afterpay-cart-note.ps-1-6{
        float: right;
        width: 350px;
    }
    .afterpay-cart-note span {
        font-size: 0.85rem;
    }
    .afterpay-cart-note.ps-1-6 span {
        font-size: 1.20rem;
    }
    .afterpay-price-text {
        font-weight: bold;
    }
    .afterpay-more-info {
        text-align: right;
        font-size: 0.85rem;
    }
    .afterpay-cart-note.ps-1-6 .afterpay-more-info {
        font-size: 1.20rem;
    }

</style>
<div class="payment-method-note afterpay-cart-note ps-{$PS_VERSION|escape:'htmlall':'UTF-8'}" style="">
    <div class="AfterpaySimulator ps-version-{$PS_VERSION|escape:'htmlall':'UTF-8'}">
        <style>
            afterpay-placement {
                white-space: break-spaces;
                color: black;
                font-weight: bold;
            }
        </style>
        <afterpay-placement
                data-locale="{$ISO_COUNTRY_CODE|escape:'htmlall':'UTF-8'}"
                data-currency="{$CURRENCY|escape:'htmlall':'UTF-8'}"
                data-amount-selector="{$PRICE_SELECTOR|escape:'htmlall':'UTF-8'}"
                data-size="sm"
                data-intro-text="false"
                data-show-interest-free="false">
        </afterpay-placement>
    </div>
    <span><strong>{$DESCRIPTION_TEXT_ONE|escape:'htmlall':'UTF-8'}</strong></span>
    <br><br>
    <span>{$DESCRIPTION_TEXT_TWO|escape:'htmlall':'UTF-8'}</span>
    <br/>
</div>