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
{foreach $price_impact as $impact}
<div id="bulk_{$impact|escape:'html':'UTF-8'}_price" class="popin_dmu bootstrap">
    <h2>{if $impact == 'increase'}{l s='Increase the price' mod='dmuadminrecherche'}{else}{l s='Reduce the price' mod='dmuadminrecherche'}{/if}</h2>
    <div class="row">
        <div class="form-horizontal col-sm-12">
            <div class="form-group">
                <label for="{$impact|escape:'html':'UTF-8'}_price_tax" class="control-label col-sm-4">
                    {l s='Price impact:' mod='dmuadminrecherche'}
                </label>
                <div class="col-sm-8">
                    <select name="{$impact|escape:'html':'UTF-8'}_price_tax" id="{$impact|escape:'html':'UTF-8'}_price_tax">
                        <option value="ti">{l s='Tax incl.' mod='dmuadminrecherche'}</option>
                        <option value="te">{l s='Tax excl.' mod='dmuadminrecherche'}</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="{$impact|escape:'html':'UTF-8'}_price_value" class="control-label col-sm-4">
                    {l s='Value:' mod='dmuadminrecherche'}
                </label>
                <div class="col-sm-8">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="icon icon-{if $impact == 'increase'}plus{else}minus{/if}"></i>
                        </div>
                        <input id="{$impact|escape:'html':'UTF-8'}_price_value" type="text" name="{$impact|escape:'html':'UTF-8'}_price_value"/>
                        <div class="input-group-addon"></div>
                        <select name="{$impact|escape:'html':'UTF-8'}_price_type" id="{$impact|escape:'html':'UTF-8'}_price_type">
                            <option value="percent">%</option>
                            <option value="amount">{$currency->sign|escape:'html':'UTF-8'}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="error_{$impact|escape:'html':'UTF-8'}_price" class="alert alert-danger"></div>
    <button class="btn btn-default col-sm-12 bulk_ok" onclick="price_impact({if $impact == 'increase'}1{else}0{/if})">OK</button>
    <div class="col-sm-12 text-center popin_refresh">
        <i class="icon-refresh icon-spin icon-fw"></i>
    </div>
</div>
{/foreach}