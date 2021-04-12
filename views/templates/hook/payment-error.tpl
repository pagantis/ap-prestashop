{*
 * This file is part of the official Afterpay module for PrestaShop.
 *
 * @author    Afterpay <integrations@afterpay.com>
 * @copyright 2020 Afterpay
 * @license   proprietary
 *}
<style>
    .afterpay-declined-header {
        color: #7a7a7a;
        position: relative;
        line-height: 35px;
        text-align: center;
        font-size: 18px;
        width: 95%;
    }
    .afterpay-more-info-text {
        font-family: "FontAwesome";
        font-size: 16px;
        color: #777;
        text-align: center;
    }
    .ps-version-1-6 {
        color: #b2fce4;
    }
    .ps-version-1-6 a{
        color: #b2fce4;
    }
</style>
<div class="afterpay-declined-header ps-version-{$PS_VERSION|escape:'htmlall':'UTF-8'}">
    {l s='PAYMENT ERROR' mod='afterpayofficial'}
</div>
<div class="afterpay-more-info-text ps-version-{$PS_VERSION|escape:'htmlall':'UTF-8'}">
    {l s='We are sorry to inform you that an error ocurred while processing your payment.' mod='afterpayofficial'}
    <br><br>
    {$ERROR_TEXT1|escape:'htmlall':'UTF-8'}
    <br><br>
    {$ERROR_TEXT2|escape:'htmlall':'UTF-8'}
    <br>
    <a href="{l s='https://developers.afterpay.com/afterpay-online/docs/customer-support' mod='afterpayofficial'}">
        {l s='https://developers.afterpay.com/afterpay-online/docs/customer-support' mod='afterpayofficial'}
    </a>
</div>
