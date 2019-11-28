{*
    * We offer the best and most useful modules PrestaShop and modifications for your online store.
    *
    * We are experts and professionals in PrestaShop
    *
    * @author    PresTeamShop.com <support@presteamshop.com>
    * @copyright 2011-2019 PresTeamShop
    * @license   see file: LICENSE.txt
    * @category  PrestaShop
    * @category  Module
*}

{block name='step_payment'}
    {if $total_order <= 0}
        <span id="free_order" class="alert alert-warning col-xs-12 col-12 text-md-center">{l s='Free Order.' mod='onepagecheckoutps'}</span>
    {else}
        {hook h='displayPaymentTop'}
        <div id="payment_method_container" class="payment-options">
            {foreach from=$payment_options item="module_options" key="name_module"}
                {foreach from=$module_options item="option"}
                    <div class="module_payment_container">
                        <div class="row pts-vcenter payment-option" for="{$option.action}">
                            <div class="payment_input col-xs-1 col-1">
                                <input type="radio" id="{$option.id}" name="payment-option" class="payment_radio not_unifrom not_uniform" value="{$name_module}" data-module-name="{$option.module_name}">
                                <input type="hidden" id="url_module_payment_{$option.id_module_payment}" value="{$option.action}">
                            </div>
                            {if !empty($option.logo) and $CONFIGS.OPC_SHOW_IMAGE_PAYMENT}
                                <div class="payment_image col-xs-3 col-3">
                                    <img src="{$option.logo}" title="{$option.call_to_action_text}" class="img-thumbnail {$name_module}">
                                </div>
                            {/if}
                            <div class="payment_content {if !empty($option.logo)}col-xs-8 col-8{else}col-xs-11 col-11{/if}">
                                <span>
                                    {if isset($option.title_opc)}
                                        {$option.title_opc nofilter}
                                    {else}
                                        {$option.call_to_action_text nofilter}
                                    {/if}
                                </span>
                                {if isset($option.description_opc)}
                                    <p>
                                        {$option.description_opc}
                                    </p>
                                {/if}
                            </div>
                        </div>
                        {if $CONFIGS.OPC_SHOW_DETAIL_PAYMENT}
                            {if $option.additionalInformation}
                                <div id="payment_content_html_{$option.id}" class="payment_content_html hidden">
                                    {$option.additionalInformation nofilter}
                                </div>
                            {/if}
                        {/if}
                        <div
                            id="pay-with-{$option.id}-form"
                            class="js-payment-option-form {if $option.id != $selected_payment_option} ps-hidden {/if}"
                        >
                            {if $option.form}
                                {$option.form nofilter}
                            {else}
                                <form id="payment-form" method="POST" action="{$option.action nofilter}">
                                    {foreach from=$option.inputs item=input}
                                        <input type="{$input.type}" name="{$input.name}" value="{$input.value}">
                                    {/foreach}
                                    <button style="display:none" id="pay-with-{$option.id}" type="submit"></button>
                                </form>
                            {/if}
                        </div>
                    </div>
                {/foreach}
            {foreachelse}
                <p class="alert alert-danger">
                    {l s='Unfortunately, there are no payment method available.' mod='onepagecheckoutps'}
                </p>
            {/foreach}
        </div>

        {hook h='displayPaymentByBinaries'}
    {/if}
{/block}