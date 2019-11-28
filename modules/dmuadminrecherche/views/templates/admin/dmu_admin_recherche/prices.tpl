{**
* NOTICE OF LICENSE
*
* This source file is subject to a commercial license from SARL DREAM ME UP
* Use, copy, modification or distribution of this source file without written
* license agreement from the SARL DREAM ME UP is strictly forbidden.
*
*   .--.
*   |   |.--..-. .--, .--.--.   .--.--. .-.   .  . .,-.
*   |   ;|  (.-'(   | |  |  |   |  |  |(.-'   |  | |   )
*   '--' '   `--'`-'`-'  '  `-  '  '  `-`--'  `--`-|`-'
*        w w w . d r e a m - m e - u p . f r       '
*
* @author    Dream me up <prestashop@dream-me-up.fr>
* @copyright 2007 - 2016 Dream me up
* @license   All Rights Reserved
*}
{capture assign=priceDisplayPrecisionFormat}{'%.'|cat:$smarty.const._PS_PRICE_DISPLAY_PRECISION_|cat:'f'|escape:'html':'UTF-8'}{/capture}
<div id="popin_prices_{$product->id|intval}" class="popin_dmu bootstrap">
    <h3>{l s='Prices:' mod='dmuadminrecherche'} {$product->name|escape:'html':'UTF-8'} ({l s='ID:' mod='dmuadminrecherche'} {$product->id|intval})</h3>
    <div class="row">
        <div class="form-horizontal col-sm-12">
            <div class="form-group">
                <label for="wholesale_price_price_{$product->id|intval}" class="control-label col-sm-4">
                    {if !$country_display_tax_label || $noTax}{l s='Wholesale price' mod='dmuadminrecherche'}{else}{l s='Pre-tax wholesale price' mod='dmuadminrecherche'}{/if}
                </label>
                <div class="col-sm-7">
                    <div class="input-group">
                        <div class="input-group-addon">
                            {$currency->sign|escape:'html':'UTF-8'}
                        </div>
                        <input id="wholesale_price_price_{$product->id|intval}" type="text" name="wholesale_price" value="{{toolsConvertPrice price=$product->wholesale_price}|string_format:$priceDisplayPrecisionFormat}" onchange="this.value = this.value.replace(/,/g, '.');" maxlength="27"/>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="priceTE_price_{$product->id|intval}" class="control-label col-sm-4">
                    {if !$country_display_tax_label || $noTax}{l s='Retail price' mod='dmuadminrecherche'}{else}{l s='Pre-tax retail price' mod='dmuadminrecherche'}{/if}
                </label>
                <div class="col-sm-7">
                    <div class="input-group">
                        <div class="input-group-addon">
                            {$currency->sign|escape:'html':'UTF-8'}
                        </div>
                        <input id="priceTE_price_{$product->id|intval}" type="text" name="priceTE" value="{{toolsConvertPrice price=$product->price}|string_format:'%.6f'}" maxlength="27" onchange="noComma('priceTE_price_{$product->id|intval}');" onkeyup="if (isArrowKey(event)) return ;calcPriceTI({$product->id|intval});" />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="id_tax_rules_group_price_{$product->id|intval}" class="control-label col-sm-4">
                    {l s='Tax rule' mod='dmuadminrecherche'}
                </label>
                <div class="col-sm-7">
                    <select onchange="calcPriceTI({$product->id|intval}); unitPriceWithTax({$product->id|intval});" name="id_tax_rules_group" id="id_tax_rules_group_price_{$product->id|intval}" {if $noTax}disabled="disabled"{/if} >
                        <option value="0">{l s='No Tax' mod='dmuadminrecherche'}</option>
                        {foreach from=$tax_rules_groups item=tax_rules_group}
                            <option value="{$tax_rules_group.id_tax_rules_group|intval}" {if $product->getIdTaxRulesGroup() == $tax_rules_group.id_tax_rules_group}selected="selected"{/if} >
                                {$tax_rules_group['name']|escape:'html':'UTF-8'}
                            </option>
                        {/foreach}
                    </select>
                </div>
            </div>
            {if $noTax}
                <div class="form-group">
                    <div class="col-lg-7 col-lg-offset-5">
                        <div class="alert">
                            {l s='Taxes are currently disabled:' mod='dmuadminrecherche'}<br>
                            <a href="{$link->getAdminLink('AdminTaxes')|escape:'html':'UTF-8'}" target="_blank">{l s='Click here to open the Taxes configuration page.' mod='dmuadminrecherche'}</a>
                            <input type="hidden" value="{$product->getIdTaxRulesGroup()|escape:'html':'UTF-8'}" name="id_tax_rules_group" />
                        </div>
                    </div>
                </div>
            {/if}
            <div class="form-group"{if !$ps_use_ecotax} style="display:none;"{/if}>
                <label for="ecotax_price_{$product->id|intval}" class="control-label col-sm-4">
                    {l s='Ecotax (tax incl.)' mod='dmuadminrecherche'}
                </label>
                <div class="col-sm-7">
                    <div class="input-group">
                        <div class="input-group-addon">
                            {$currency->sign|escape:'html':'UTF-8'}
                        </div>
                        <input id="ecotax_price_{$product->id|intval}" type="text" name="ecotax" value="{$product->ecotax|string_format:$priceDisplayPrecisionFormat}" onKeyUp="if (isArrowKey(event)) return ;calcPriceTE({$product->id|intval}); this.value = this.value.replace(/,/g, '.'); if (parseInt(this.value) > getE('priceTE_price_{$product->id|intval}').value) this.value = getE('priceTE_price_{$product->id|intval}').value; if (isNaN(this.value)) this.value = 0;" maxlength="27"/>
                    </div>
                </div>
            </div>
            <div class="form-group" {if !$country_display_tax_label || $noTax}style="display:none;"{/if}>
                <label for="priceTI_price_{$product->id|intval}" class="control-label col-sm-4">
                    {l s='Retail price with tax' mod='dmuadminrecherche'}
                </label>
                <div class="col-sm-7">
                    <div class="input-group">
                        <div class="input-group-addon">
                            {$currency->sign|escape:'html':'UTF-8'}
                        </div>
                        <input id="priceTI_price_{$product->id|intval}" type="text" name="priceTI" value="" onchange="noComma('priceTI_price_{$product->id|intval}');" onKeyUp="if (isArrowKey(event)) return ;calcPriceTE({$product->id|intval})" maxlength="27"/>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="unit_price_price_{$product->id|intval}" class="control-label col-sm-4">
                    {l s='Unit price (tax excl.)' mod='dmuadminrecherche'}
                </label>
                <div class="col-sm-7">
                    <div class="input-group">
                        <div class="input-group-addon">
                            {$currency->sign|escape:'html':'UTF-8'}
                        </div>
                        <input id="unit_price_price_{$product->id|intval}" name="unit_price" type="text" value="{$unit_price|string_format:'%.6f'}" maxlength="27" onkeyup="if (isArrowKey(event)) return ;this.value = this.value.replace(/,/g, '.'); unitPriceWithTax({$product->id|intval});"/>
                        <span class="input-group-addon">{l s='per' mod='dmuadminrecherche'}</span>
                        <input id="unity_price_{$product->id|intval}" name="unity" type="text" value="{$product->unity|htmlentitiesUTF8}"  maxlength="255" onkeyup="if (isArrowKey(event)) return ;unitySecond({$product->id|intval});" onchange="unitySecond({$product->id|intval});"/>
                    </div>
                </div>
            </div>
            <div class="form-group unit_price"{if !$product->unity} style="display: none;"{/if}>
                <div class="col-sm-7 col-sm-offset-4">
                    <div class="alert alert-warning">
                        <span>{l s='or' mod='dmuadminrecherche'}
                            {$currency->sign|escape:'html':'UTF-8'}<span id="unit_price_with_tax_{$product->id|intval}">0.00</span>
                            {l s='per' mod='dmuadminrecherche'} <span id="unity_second_{$product->id|intval}">{$product->unity|escape:'html':'UTF-8'}</span>{if $ps_tax && $country_display_tax_label} {l s='(tax incl.)' mod='dmuadminrecherche'}{/if}
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <span class="col-sm-4"></span>
                <div class="col-sm-7">
                    <div class="checkbox">
                        <label class="control-label on_sale" for="on_sale_price_{$product->id|intval}" >
                            <input type="checkbox" name="on_sale" id="on_sale_price_{$product->id|intval}" {if $product->on_sale}checked="checked"{/if} value="1" />
                            {l s='Display the "on sale" icon on the product page, and in the text found within the product listing.' mod='dmuadminrecherche'}
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <div class="alert alert-warning">
                        <strong>{l s='Final retail price:' mod='dmuadminrecherche'}</strong>
                        <span>
                            {if $currency->prefix}{$currency->prefix|escape:'html':'UTF-8'}{elseif $currency->format % 2}{$currency->sign|escape:'html':'UTF-8'}{/if}
                            <span id="finalPrice_{$product->id|intval}">0.00</span>
                            {if $currency->suffix}{$currency->suffix|escape:'html':'UTF-8'}{elseif !($currency->format % 1)}{$currency->sign|escape:'html':'UTF-8'}{/if}
                            <span{if !$ps_tax} style="display:none;"{/if}> ({l s='tax incl.' mod='dmuadminrecherche'})</span>
                        </span>
                        <span{if !$ps_tax} style="display:none;"{/if} >
                        {if $country_display_tax_label}
                            /
                        {/if}
                            {if $currency->prefix}{$currency->prefix|escape:'html':'UTF-8'}{elseif $currency->format % 2}{$currency->sign|escape:'html':'UTF-8'}{/if}
                            <span id="finalPriceWithoutTax_{$product->id|intval}"></span>
                            {if $currency->suffix}{$currency->suffix|escape:'html':'UTF-8'}{elseif !($currency->format % 1)}{$currency->sign|escape:'html':'UTF-8'}{/if}
                            {if $country_display_tax_label}({l s='tax excl.' mod='dmuadminrecherche'}){/if}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="error_prices_{$product->id|intval}" class="alert alert-danger" style="display: none;"></div>
    <button class="btn btn-default col-sm-12 bulk_ok" onclick="change_prices({$product->id|intval})">OK</button>
    <div class="col-sm-12 text-center popin_refresh">
        <i class="icon-refresh icon-spin icon-fw"></i>
    </div>
</div>