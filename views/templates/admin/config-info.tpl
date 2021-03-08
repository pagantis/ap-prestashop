{*
 * This file is part of the official Afterpay module for PrestaShop.
 *
 * @author    Afterpay <integrations@afterpay.com>
 * @copyright 2020 Afterpay
 * @license   proprietary
 *}

{block name="form"}
    <style>
        .column-left {
            text-align: left;
            float: left;
            width: 20%;
        }

        .column-right {
            text-align: right;
            float: right;
            width: 20%;
        }

        .column-center {
            text-align: left;
            display: inline-block;
            width: 60%;
            line-height: 18px;
        }
        .afterpay-content-form {
            overflow-x: hidden;
            overflow-y: hidden;
            text-align: center;
            width: 97%;
        }

        .afterpay-content-form input{
            margin-left: 15px;
            margin-right: 5px;
        }

        .afterpay-content-form label{
            margin-left: 15px;
        }

        .afterpay-content-form img{
            margin-top: 20px;
            display: inline-block;
            vertical-align: middle;
            float: none;
            width: 150px;
        }
        .second {
            margin-top: 10px;
        }
    </style>
    {$message|escape:'quotes':'UTF-8'}
    <div class="panel afterpay-content-form">
        <h3><i class="icon icon-credit-card"></i> {$header|escape:'htmlall':'UTF-8'}</h3>
        <div class="column-left">
            <a target="_blank" href="{l s='https://retailers.afterpay.com/uk/prestashop/?utm_source=prestashop&utm_medium=referral&utm_campaign=global_prestashop-lead-referrals_campaign_DEC-2020&utm_content=contact-us' mod='afterpay'}" class="btn btn-default" title="Login Afterpay"><i class="icon-user"></i> {$button1|escape:'htmlall':'UTF-8'}</a><br>
            <a target="_blank" href="{l s='https://developers.afterpay.com/docs/getting-started-with-afterpay-online' mod='afterpay'}" class="btn btn-default second" title="Getting Star"><i class="icon-user"></i> {$button2|escape:'htmlall':'UTF-8'}</a>
        </div>
        <div class="column-center">
            <p>
                {$centered_text|escape:'quotes':'UTF-8'}
            </p>
        </div>
        <div class="column-right">
            <img src="{$logo|escape:'htmlall':'UTF-8'}"/>
        </div>
    </div>
    {$form|escape:'quotes':'UTF-8'}
    {if version_compare($smarty.const._PS_VERSION_,'1.6','<')}
        <script type="text/javascript">
            var d = document.getElementById("module_form");
            d.className += " panel";
        </script>
    {/if}
{/block}