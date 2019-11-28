<div class="supercheckout-blocks js-additional-information definition-list additional-information">
    {$payment_method_content.additionalInformation nofilter}{*escape not required as contains html*}
</div>
<div id="pay-with-form">
    {if $payment_method_content.form}
        {$payment_method_content.form nofilter}{*escape not required as contains html*}
    {else}
        <form id="payment-form" method="POST" action="{$payment_method_content.action nofilter}{*escape not required as contains url*}">
            {foreach from=$payment_method_content.inputs item=input}
                <input type="{$input.type}" name="{$input.name}" value="{$input.value}">
            {/foreach}
          <button style="display:none" id="pay-with-{$payment_method_content.id}" type="submit"></button>
        </form>
    {/if}
</div>
{*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer tohttp://www.prestashop.com for more information.
* We offer the best and most useful modules PrestaShop and modifications for your online store.
*
* @category  PrestaShop Module
* @author    knowband.com <support@knowband.com>
* @copyright 2016 Knowband
*}