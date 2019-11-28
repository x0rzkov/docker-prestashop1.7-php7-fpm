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

{if !($psb_hide_full_stock && $psb_width == 100) && !($psb_hide_empty_stock && $psb_width == 0)}
    {if ($psb_show_proofs_orders && $psb_sales_number) || ($psb_show_proofs_carts && $psb_carts_number)}
        <div class="psb-sales-number">
            {if $psb_show_proofs_orders && $psb_sales_number}
                <div class="psb-sn-purchased">
                    {if $psb_sales_number == 1}
                        {l s='[1]1 person[/1] has purchased this item %s' sprintf=[$psb_stats_period_txt] tags=['<b>'] mod='pststockbar'}
                    {else}
                        {l s='[1]%d people[/1] have purchased this item %s' sprintf=[$psb_sales_number, $psb_stats_period_txt] tags=['<b>'] mod='pststockbar'}
                    {/if}
                </div>
            {/if}
            {if $psb_show_proofs_carts && $psb_carts_number}
                <div class="psb-sn-added">
                    {if $psb_carts_number == 1}
                        {l s='[1]1 person[/1] recently added this item to the cart' sprintf=[] tags=['<b>'] mod='pststockbar'}
                    {else}
                        {l s='[1]%d people[/1] recently added this item to the cart' sprintf=[$psb_carts_number] tags=['<b>'] mod='pststockbar'}
                    {/if}
                </div>
            {/if}
        </div>
    {/if}

    {strip}
        {if $psb_custom_levels}
            <div class="pst-bar-info-lvl">
                {PstStockBarLevel::getTextByQty($psb_current_qty, $psb_id_product)|escape:'html':'UTF-8'}
            </div>
        {elseif $psb_show_qty}
            {if $psb_width > 66}
                <div class="pst-bar-info pst-bar-info-many">{l s='%s items in stock' sprintf=[$psb_current_qty] mod='pststockbar'}</div>
            {elseif $psb_width == 0}
                <div class="pst-bar-info pst-bar-info-oos">{l s='There are not enough products in stock' mod='pststockbar'}</div>
            {else}
                {if $psb_current_qty == 1}
                    <div class="pst-bar-info pst-bar-info-1">{l s='Only 1 item left in stock' sprintf=[$psb_current_qty] mod='pststockbar'}</div>
                {else}
                    <div class="pst-bar-info pst-bar-info-few">{l s='Only %s items left in stock' sprintf=[$psb_current_qty] mod='pststockbar'}</div>
                {/if}
            {/if}
        {/if}
        {if $psb_theme == '1-bar'}
            <div class="pst-bar-wrp">
                <div class="pst-bar {$psb_bar_class|escape:'quotes':'UTF-8'}" style="width: {$psb_width|floatval}%;"></div>
            </div>
        {else}
            <div class="pst-bar-wrp">
                {if !$psb_custom_levels}
                    <span class="psb-label">{l s='Availability:' mod='pststockbar'}</span>
                {/if}
                <span class="{if $psb_theme == '2-dots'}psb-dots-wrp{else}psb-scale-wrp{/if} {$psb_bar_class|escape:'quotes':'UTF-8'} psb-sections-{$psb_sections|intval}">
                    {for $i = 1 to $psb_sections}
                        <span class="psb-dot {if $i <= ceil($psb_width / (100/$psb_sections))}psb-dot-active{/if}"></span>
                    {/for}
                </span>
                {if $psb_width == 0 && !$psb_custom_levels}<span class="psb-oos">{l s='(Out of stock)' mod='pststockbar'}</span>{/if}
            </div>
        {/if}
    {/strip}
{/if}