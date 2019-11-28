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
{strip}
    <div class="row">
        <div class="col-lg-{if isset($input.col)}{$input.col|intval}{else}12{/if}{if !isset($input.label)} col-lg-offset-3{/if} psb-sl-wrp psb-sl-wrp-{$psvd|escape:'html':'UTF-8'}">
            <div class="row">
                <table class="table" id="psb_lvl_table">
                    <thead>
                    <tr>
                        <th>{l s='Quantity' mod='pststockbar'}</th>
                        <th>{l s='Color' mod='pststockbar'}</th>
                        <th>{l s='Displayed text' mod='pststockbar'}</th>
                        <th>&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody>
                    {foreach from=$input.levels item="level"}
                        <tr>
                            <td class="psb_qty_td">
                                <span class="psb-lvl-qty-sign"><=</span>
                                <input type="text" class="form-control psb-lvl-qty" value="{$level->max_qty|intval}" name="lvl[{$level->id|intval}][max_qty]">
                            </td>
                            <td>
                                <input class="psbColorPickerLevel" type="text" value="{$level->color|escape:'html':'UTF-8'}" name="lvl[{$level->id|intval}][color]">
                            </td>
                            {assign var='name_id' value="psb-level-text`$level->id`"}
                            {assign var='name' value="lvl[`$level->id`][text]"}
                            <td>{$pststockbar->generateInput(['type' => 'text', 'lang' => true, 'name' => $name, 'class' => 'psb-level-text', 'id' => $name_id, 'values' => $level->text, 'array_value' => true]) nofilter}</td>
                            <td>
                                <button class="button btn btn-default psb-lvl-del" type="button" title="{l s='Delete' mod='pststockbar'}">&times;</button>
                                <input type="hidden" name="lvl[{$level->id|intval}][id]" value="{$level->id|intval}">
                                <input type="hidden" name="lvl[{$level->id|intval}][delete]" class="psb-lvl-todel" value="0">
                            </td>
                        </tr>
                    {/foreach}
                    </tbody>
                    <tfoot>
                    <tr id="psb_lvl_tpl" style="display: none;">
                        <td>
                            <span class="psb-lvl-qty-sign"><=</span>
                            <input type="text" class="form-control psb-lvl-qty" value="" name="lvl[__id__][max_qty]">
                        </td>
                        <td>
                            <input class="psbColorPickerLevel" type="text" name="lvl[__id__][color]">
                        </td>
                        <td>{$pststockbar->generateInput(['type' => 'text', 'lang' => true, 'name' => 'lvl[__id__][text]', 'class' => 'psb-level-text', 'id' => 'psb-level-text__id__', 'values' => [], 'array_value' => true, 'delay_translatable' => true]) nofilter}</td>
                        <td>
                            <button class="button btn btn-default psb-lvl-del" type="button" title="{l s='Delete' mod='pststockbar'}">&times;</button>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <button class="button btn btn-success psb-lvl-add" type="button" title="{l s='Add level' mod='pststockbar'}">+</button>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
{/strip}