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
<div id="popin_details_{$product->id|intval}" class="popin_dmu bootstrap">
    <h3>{l s='Details:' mod='dmuadminrecherche'} {$product->name|escape:'html':'UTF-8'} ({l s='ID:' mod='dmuadminrecherche'} {$product->id|intval})</h3>
    <div class="row">
        <div class="form-horizontal col-sm-12">
            <div class="form-group">
                <label for="ean13_detail_{$product->id|intval}" class="control-label col-sm-5">
                    <span class="label-tooltip" data-toggle="tooltip" title="{l s='(Europe, Japan)' mod='dmuadminrecherche'}">{l s='EAN13 or JAN' mod='dmuadminrecherche'}</span>
                </label>
                <div class="col-sm-7">
                    <input id="ean13_detail_{$product->id|intval}" type="text" name="ean13" value="{$product->ean13|escape:'html':'UTF-8'}" maxlength="13"/>
                </div>
            </div>
            <div class="form-group">
                <label for="upc_detail_{$product->id|intval}" class="control-label col-sm-5">
                    <span class="label-tooltip" data-toggle="tooltip" title="{l s='(US, Canada)' mod='dmuadminrecherche'}">{l s='UPC' mod='dmuadminrecherche'}</span>
                </label>
                <div class="col-sm-7">
                    <input id="upc_detail_{$product->id|intval}" type="text" name="upc" value="{$product->upc|escape:'html':'UTF-8'}" maxlength="12"/>
                </div>
            </div>
            {if $advanced_stock_management}
            <div class="form-group">
                <label for="location_detail_{$product->id|intval}" class="control-label col-sm-5">
                    {l s='Location (warehouse)' mod='dmuadminrecherche'}
                </label>
                <div class="col-sm-7">
                    {if $location || (!$location && $nb_warehouse == 1)}
                        <input id="location_detail_{$product->id|intval}" type="text" name="location" value="{$location|escape:'html':'UTF-8'}"/>
                    {elseif $is_combination}
                        <a href="{$url_product|escape:'quotes':'UTF-8'}" target="_blank">{l s='This product contains combinations, you must go through the product to change its location.' mod='dmuadminrecherche'}</a>
                    {elseif $nb_warehouse > 1}
                        <a href="{$url_product|escape:'quotes':'UTF-8'}" target="_blank">{l s='You use more than one warehouses, you must go through the product to change its location.' mod='dmuadminrecherche'}</a>
                    {else}
                        <a href="{$url_warehouse|escape:'quotes':'UTF-8'}" target="_blank">{l s='You must first create a warehouse before defining a location.' mod='dmuadminrecherche'}</a>
                    {/if}
                </div>
            </div>
            {/if}
            <div class="form-group">
                <label for="width_detail_{$product->id|intval}" class="control-label col-sm-5">
                    {l s='Width' mod='dmuadminrecherche'}
                </label>
                <div class="col-sm-7">
                    <div class="input-group">
                        <div class="input-group-addon">
                            {$dimension_unit|escape:'html':'UTF-8'}
                        </div>
                        <input id="width_detail_{$product->id|intval}" type="text" name="width" value="{$product->width|floatval}" onKeyUp="this.value = this.value.replace(/,/g, '.');" maxlength="14"/>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="height_detail_{$product->id|intval}" class="control-label col-sm-5">
                    {l s='Height' mod='dmuadminrecherche'}
                </label>
                <div class="col-sm-7">
                    <div class="input-group">
                        <div class="input-group-addon">
                            {$dimension_unit|escape:'html':'UTF-8'}
                        </div>
                        <input id="height_detail_{$product->id|intval}" type="text" name="height" value="{$product->height|floatval}" onKeyUp="this.value = this.value.replace(/,/g, '.');" maxlength="14"/>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="depth_detail_{$product->id|intval}" class="control-label col-sm-5">
                    {l s='Depth' mod='dmuadminrecherche'}
                </label>
                <div class="col-sm-7">
                    <div class="input-group">
                        <div class="input-group-addon">
                            {$dimension_unit|escape:'html':'UTF-8'}
                        </div>
                        <input id="depth_detail_{$product->id|intval}" type="text" name="depth" value="{$product->depth|floatval}" onKeyUp="this.value = this.value.replace(/,/g, '.');" maxlength="14"/>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="weight_detail_{$product->id|intval}" class="control-label col-sm-5">
                    {l s='Weight' mod='dmuadminrecherche'}
                </label>
                <div class="col-sm-7">
                    <div class="input-group">
                        <div class="input-group-addon">
                            {$weight_unit|escape:'html':'UTF-8'}
                        </div>
                        <input id="weight_detail_{$product->id|intval}" type="text" name="weight" value="{$product->weight|floatval}" onKeyUp="this.value = this.value.replace(/,/g, '.');" maxlength="14"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="error_details_{$product->id|intval}" class="alert alert-danger" style="display: none;"></div>
    <button class="btn btn-default col-sm-12 bulk_ok" onclick="change_details({$product->id|intval})">OK</button>
    <div class="col-sm-12 text-center popin_refresh">
        <i class="icon-refresh icon-spin icon-fw"></i>
    </div>
</div>