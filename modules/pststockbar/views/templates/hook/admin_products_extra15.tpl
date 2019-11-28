{**
* NOTICE OF LICENSE
*
* This file is licenced under the Software License Agreement.
* With the purchase or the installation of the software in your application
* you accept the licence agreement.
*
* @author    Presta.Site
* @copyright 2018 Presta.Site
* @license   LICENSE.txt
*}
<div id="{$module_name|escape:'html':'UTF-8'}" class="panel product-tab pstab15">
    <input type="hidden" name="submitted_tabs[]" value="{$module_name|escape:'html':'UTF-8'}" />
    <input type="hidden" name="{$module_name|escape:'html':'UTF-8'}-submit" value="1" />
    <h4>{$psb_display_name|escape:'html':'UTF-8'}</h4>

    <div class="form-group" id="psb_product_data_wrp" data-id-product="{$psb_id_product|intval}">
        <div class="col-lg-1"><span class="pull-right"></span></div>

        {if $psb_custom_stock_levels}
            <div class="psb_csl_wrp">
                <label class="control-label col-lg-2">
                     {l s='Stock levels:' mod='pststockbar'}
                </label>
                <div class="col-lg-5">
                    <div class="input-group" id="psb_csl_table_wrp">
                        {include file="../admin/_configure/helpers/form/_stock_levels.tpl" input=$psb_sl_input}
                    </div>
                    <input type="hidden" name="id_product" value="{$psb_id_product|intval}">
                </div>

                <div class="psb_csl_btn_wrp">
                    <div id="psb_csl_success" class="conf" style="display: none;">{l s='Saved' mod='pststockbar'}</div>
                    <div id="psb_csl_error" class="alert alert-danger" style="display: none;"></div>
                    <button id="psb_csl_btn_save" class="button btn btn-success" type="button">{l s='Update' mod='pststockbar'}</button>
                </div>
            </div>
        {else}
            <label class="control-label col-lg-2">
                <span class="label-tooltip" data-toggle="tooltip"
                      data-original-title='{l s='Maximum product quantity in the stock bar.' mod='pststockbar'}'
                      title='{l s='Maximum product quantity in the stock bar.' mod='pststockbar'}'>
                     {l s='Full stock quantity:' mod='pststockbar'}
                </span>
            </label>
            <div class="col-lg-5">
                <div class="input-group">
                    <input type="text" name="max_qty" value="{if isset($psb_max_qty)}{$psb_max_qty|intval}{/if}">
                </div>
            </div>
        {/if}
    </div>
</div>
