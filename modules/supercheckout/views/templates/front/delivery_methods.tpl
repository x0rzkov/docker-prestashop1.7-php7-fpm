{if isset($settings['hide_ship_pay']) && $settings['hide_ship_pay'] eq 1 && $address_selector == 'new'}
    <span class="permanent-warning" style="display: inline-block;"> {l s='Save your address first in order to check actual shipping methods & cost' mod='supercheckout'} </span>
{else}
<div class="velsof_sc_overlay"></div>
{if isset($is_virtual_cart) && $is_virtual_cart}
    <input id="input_virtual_carrier" class="hidden" type="hidden" name="id_carrier" value="0" />
    <div class="supercheckout-checkout-content" style="display:block">
        <div class="not-required-msg" style="display: block;">{l s='No Delivery Method Required' mod='supercheckout'}</div>
    </div>
{else}
    {if isset($shipping_errors) && is_array($shipping_errors)}
        {foreach from=$shipping_errors item='shippig_error'}
            <div class="supercheckout-checkout-content" style="display:block">
                <div class="permanent-warning" style="display: block;">{$shippig_error}</div>
            </div>
        {/foreach}
    {else}
        <div class="supercheckout-checkout-content" style="display:block"></div>
        <div id="hook-display-before-carrier">
            {$hookDisplayBeforeCarrier nofilter}{*escape not required as contains html*}
        </div>
        {if $delivery_options|count}
            {assign var='selected' value=0}
            <ul>
            {foreach from=$delivery_options item=carrier key=carrier_id}
                
                <li class="highlight">
                    <div class="radio ">
                    {if !empty($delivery_option) && $delivery_option == $carrier_id  && $selected == 0}
                       {* <li class="highlight alert-info">
                            <div class="radio ">*}
                                <input class='supercheckout_shipping_option delivery_option_radio' type="radio" name="delivery_option[{$id_address|intval}]" value="{$carrier_id nofilter}{*escape not required as contains html*}" id="shipping_method_{$id_address|intval}_{$carrier.id|intval}" checked="checked" />
                                {$selected = 1}
                            {else if isset($default_shipping_method) && $carrier.id == $default_shipping_method && $selected == 0}
                                {*<li class="highlight alert-info">
                                    <div class="radio ">*}
                                        <input class='supercheckout_shipping_option delivery_option_radio' type="radio" name="delivery_option[{$id_address|intval}]" value="{$carrier_id nofilter}{*escape not required as contains html*}" id="shipping_method_{$id_address|intval}_{$carrier.id|intval}" checked="checked" />
                                    {else}
                                       {* <li class="highlight">
                                            <div class="radio ">*}
                                                <input class='supercheckout_shipping_option delivery_option_radio' type="radio" name="delivery_option[{$id_address|intval}]" value="{$carrier_id nofilter}{*escape not required as contains html*}" id="shipping_method_{$id_address|intval}_{$carrier.id|intval}" />
                                            {/if}

                                            <label for="shipping_method_{$id_address|intval}_{$carrier.id|intval}">
                                                {if $display_carrier_style neq 0}
                                                    <img src="{$carrier.logo nofilter}{*escape not required as contains url*}" alt="{$carrier.name}" {if isset($carrier.logo_width) && $carrier.logo_width != "" && $carrier.logo_width != 'auto'}width="{$carrier.logo_width}"{else}width='50'{/if} {if isset($carrier.logo_height) && $carrier.logo_height != "" && $carrier.logo_height != "auto"}height="{$carrier.logo_height}"{/if}/>{if $display_carrier_style neq 2}{/if}
                                                {/if}
                                                {if $display_carrier_style neq 2}
                                                    {$carrier.name}                                                       
                                                {/if}
                                                <span class="supercheckout-shipping-small-title shippingPrice">{$carrier.price nofilter}</span></label>{*escape not required*}
                                            <p class="shippingInfo supercheckout-shipping-small-title">{$carrier.delay}</p>
                    </div>
                                    </li>
                                       
                                    {/foreach}
                                   </ul>
                                {else}
                                    <div class="supercheckout-checkout-content" style="display:block">
                                        <div class="permanent-warning" style="display: block;">{l s='No Delivery Method Available' mod='supercheckout'}</div>
                                    </div>
                                {/if}
                                <div id="hook-display-after-carrier">
                                    {$hookDisplayAfterCarrier nofilter}{*escape not required as contains html*}
                                </div>
                            {/if}
                        {/if}
{/if}
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