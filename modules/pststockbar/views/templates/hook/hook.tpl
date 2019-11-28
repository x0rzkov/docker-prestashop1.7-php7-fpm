{*
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

<div class="pstStockBar {if $psb_highlight_block}psb-highlight{/if}" data-max-qty="{$psb_max_qty|intval}">

    {if isset($psb_combinations) && is_array($psb_combinations) && count($psb_combinations)}
        {foreach from=$psb_combinations item='combi'}
            <div class="psb-combi-wrp psb-cw-{$combi['id_product_attribute']|intval}" {if $combi['id_product_attribute'] != $psb_id_combination_default}style="display: none;" {/if}>
                {include file=$psb_content_tpl psb_sales_number=$combi['sales_number'] psb_carts_number=$combi['carts_number'] psb_current_qty=$combi['qty'] psb_width=$combi['width'] psb_bar_class=$combi['bar_class']}
            </div>
        {/foreach}
    {else}
        {include file=$psb_content_tpl}
    {/if}

</div>
