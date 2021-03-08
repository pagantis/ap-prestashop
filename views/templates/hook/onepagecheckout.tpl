{*
 * This file is part of the official Afterpay module for PrestaShop.
 *
 * @author    Afterpay <integrations@afterpay.com>
 * @copyright 2020 Afterpay
 * @license   proprietary
 *}
<style>
    p.payment_module.Afterpay.ps_version_1-7 {
        margin-left: -5px;
        margin-top: -15px;
        margin-bottom: 0px;
    }
    p.payment_module a.afterpay-checkout {
        background: url('{$ICON|escape:'htmlall':'UTF-8'}') 5px 5px no-repeat #fbfbfb;
        background-size: 79px;
    }
    p.payment_module a.afterpay-checkout.ps_version_1-7 {
        background: none;
    }
    p.payment_module a.afterpay-checkout.ps_version_1-6 {
        background-color: #fbfbfb;
        max-height: 90px;
    }
    p.payment_module a.afterpay-checkout.ps_version_1-6:after {
        display: block;
        content: "\f054";
        position: absolute;
        right: 15px;
        margin-top: -11px;
        top: 50%;
        font-family: "FontAwesome";
        font-size: 25px;
        height: 22px;
        width: 14px;
        color: #777;
    }
    p.payment_module a:hover {
        background-color: #f6f6f6;
    }

    #afterpay-method-content {
        color: #7a7a7a;
        border: 1px solid #000;
        margin-bottom: 10px;
    }
    .afterpay-header {
        color: #7a7a7a;
        position: relative;
        text-align: center;
        background-color: #b2fce4;
        padding: 5px 10px 10px 0px;
        overflow: visible;
    }
    .afterpay-header img {
        height: 28px;
    }

    .afterpay-header-img {
        display: inline;
    }

    .afterpay-header-text1 {
        display: inline;
        text-align: center;
        color: black;
        font-weight: bold;
    }
    .afterpay-header-text2 {
        display: inline-block;
        text-align: center;
    }
    .afterpay-checkout-ps1-6-logo {
        height: 45px;
        margin-left: 10px;
        top: 25%;
        position: absolute;
    }
    .afterpay-more-info-text {
        padding: 1em 1em;
        text-align: center;
    }
    .afterpay-more-info {
        text-align: center !important;
    }
    .afterpay-terms {
        margin-top: 10px;
        display: inline-block;
    }
</style>
{if $PS_VERSION !== '1-7'}
    <div class="row">
        <div class="col-xs-12">
            <p class="payment_module">
                <a class="afterpay-checkout afterpay-checkout ps_version_{$PS_VERSION|escape:'htmlall':'UTF-8'}" href="{$PAYMENT_URL|escape:'htmlall':'UTF-8'}">
                    {$TITLE|escape:'htmlall':'UTF-8'}
                    <img class="afterpay-checkout-ps{$PS_VERSION|escape:'htmlall':'UTF-8'}-logo" src="{$LOGO|escape:'htmlall':'UTF-8'}">
                </a>
            </p>
        </div>
    </div>
{/if}
{if $PS_VERSION === '1-7'}
<section>
    <div class="payment-method ps_version_{$PS_VERSION|escape:'htmlall':'UTF-8'}" id="afterpay-method" >
        <div class="payment-method-content afterpay ps_version_{$PS_VERSION|escape:'htmlall':'UTF-8'}" id="afterpay-method-content">
            <div class="afterpay-header">
                <div class="afterpay-header-img">
                    <img src="{$LOGO_BADGE|escape:'htmlall':'UTF-8'}">
                </div>
                <div class="afterpay-header-text1">
                    {$MORE_HEADER1|escape:'htmlall':'UTF-8'}
                </div>
                <div class="afterpay-header-text2">
                    {$MORE_HEADER2|escape:'htmlall':'UTF-8'}
                </div>
            </div>
            <div class="afterpay-more-info-text">
                <div class="afterpay-more-info">
                    {$MOREINFO_ONE|escape:'htmlall':'UTF-8'}
                </div>
                <afterpay-placement
                        data-type="price-table"
                        data-amount="{$TOTAL_AMOUNT|escape:'htmlall':'UTF-8'}"
                        data-price-table-theme="white"
                        data-locale="{$ISO_COUNTRY_CODE|escape:'htmlall':'UTF-8'}"
                        data-currency="{$CURRENCY|escape:'htmlall':'UTF-8'}">
                </afterpay-placement>
                <a class="afterpay-terms" href="{$TERMS_AND_CONDITIONS_LINK|escape:'htmlall':'UTF-8'}" TARGET="_blank">
                    {$TERMS_AND_CONDITIONS|escape:'htmlall':'UTF-8'}
                </a>
            </div>
        </div>
    </div>
</section>
{/if}
