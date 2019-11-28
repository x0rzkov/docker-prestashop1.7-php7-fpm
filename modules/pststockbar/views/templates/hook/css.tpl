{**
* NOTICE OF LICENSE
*
* This file is licenced under the Software License Agreement.
* With the purchase or the installation of the software in your application
* you accept the licence agreement.
*
* @author    Presta.Site
* @copyright 2019 Presta.Site
* @license   LICENSE.txt
*}

.pstStockBar .pst-bar.psb-high {
    background: {$psb_color_high1|escape:'html':'UTF-8'};
    background: -moz-linear-gradient(left, {$psb_color_high1|escape:'html':'UTF-8'} 0%, {$psb_color_high2|escape:'html':'UTF-8'} 100%);
    background: -webkit-linear-gradient(left, {$psb_color_high1|escape:'html':'UTF-8'} 0%, {$psb_color_high2|escape:'html':'UTF-8'} 100%);
    background: linear-gradient(to right, {$psb_color_high1|escape:'html':'UTF-8'} 0%, {$psb_color_high2|escape:'html':'UTF-8'} 100%);
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='{$psb_color_high1|escape:'html':'UTF-8'}', endColorstr='{$psb_color_high2|escape:'html':'UTF-8'}',GradientType=1 );
}
.pstStockBar .psb-dots-wrp.psb-high .psb-dot-active,
.pstStockBar .psb-scale-wrp.psb-high .psb-dot-active {
    background: {$psb_color_high1|escape:'html':'UTF-8'};
}
.pstStockBar .pst-bar.psb-medium {
    background: {$psb_color_medium1|escape:'html':'UTF-8'};
    background: -moz-linear-gradient(left, {$psb_color_medium1|escape:'html':'UTF-8'} 0%, {$psb_color_medium2|escape:'html':'UTF-8'} 100%);
    background: -webkit-linear-gradient(left, {$psb_color_medium1|escape:'html':'UTF-8'} 0%, {$psb_color_medium2|escape:'html':'UTF-8'} 100%);
    background: linear-gradient(to right, {$psb_color_medium1|escape:'html':'UTF-8'} 0%, {$psb_color_medium2|escape:'html':'UTF-8'} 100%);
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='{$psb_color_medium1|escape:'html':'UTF-8'}', endColorstr='{$psb_color_medium2|escape:'html':'UTF-8'}',GradientType=1 );
}
.pstStockBar .psb-dots-wrp.psb-medium .psb-dot-active,
.pstStockBar .psb-scale-wrp.psb-medium .psb-dot-active {
    background: {$psb_color_medium1|escape:'html':'UTF-8'};
}
.pstStockBar .pst-bar.psb-low {
    background: {$psb_color_low1|escape:'html':'UTF-8'};
    background: -moz-linear-gradient(left, {$psb_color_low1|escape:'html':'UTF-8'} 0%, {$psb_color_low2|escape:'html':'UTF-8'} 100%);
    background: -webkit-linear-gradient(left, {$psb_color_low1|escape:'html':'UTF-8'} 0%, {$psb_color_low2|escape:'html':'UTF-8'} 100%);
    background: linear-gradient(to right, {$psb_color_low1|escape:'html':'UTF-8'} 0%, {$psb_color_low2|escape:'html':'UTF-8'} 100%);
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='{$psb_color_low1|escape:'html':'UTF-8'}', endColorstr='{$psb_color_low2|escape:'html':'UTF-8'}',GradientType=1 );
}
.pstStockBar .psb-dots-wrp.psb-low .psb-dot-active,
.pstStockBar .psb-scale-wrp.psb-low .psb-dot-active {
    background: {$psb_color_low1|escape:'html':'UTF-8'};
}
{if $psb_max_width}
    .pstStockBar .pst-bar-wrp {
        max-width: {$psb_max_width|intval}px;
    }
{/if}

{if $psb_hide_default_qty}
    #pQuantityAvailable, #availability_statut, #product-availability, #last_quantities {
        display: none !important;
    }
{/if}

{if $psb_custom_levels}
    {foreach from=$psb_levels item='level'}
        {assign var='color1' value=$level->color}
        {assign var='color2' value=$psb->adjustColor($level->color)}
        .pstStockBar .pst-bar.psb-lvl-{$level->id|intval} {
            background: {$color1|escape:'html':'UTF-8'};
            background: -moz-linear-gradient(left, {$color1|escape:'html':'UTF-8'} 0%, {$color2|escape:'html':'UTF-8'} 100%);
            background: -webkit-linear-gradient(left, {$color1|escape:'html':'UTF-8'} 0%, {$color2|escape:'html':'UTF-8'} 100%);
            background: linear-gradient(to right, {$color1|escape:'html':'UTF-8'} 0%, {$color2|escape:'html':'UTF-8'} 100%);
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='{$color1|escape:'html':'UTF-8'}', endColorstr='{$color2|escape:'html':'UTF-8'}',GradientType=1 );
        }
        .pstStockBar .psb-dots-wrp.psb-lvl-{$level->id|intval} .psb-dot-active,
        .pstStockBar .psb-scale-wrp.psb-lvl-{$level->id|intval} .psb-dot-active {
            background: {$level->color|escape:'html':'UTF-8'};
        }
    {/foreach}
{/if}

{if isset($psb_custom_css) && $psb_custom_css}
    {$psb_custom_css|escape:'html':'UTF-8'|replace:'&gt;':'>'}
{/if}
