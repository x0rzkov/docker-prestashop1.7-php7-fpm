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
{extends file="helpers/list/list_header.tpl"}

{block name="preTable"}
    {if !isset($popin_combinations)}
    <div class="leadin"></div>
        {if !$id_shop}
            <div class="alert alert-warning">{l s='Please select a shop to edit your products' mod='dmuadminrecherche'}</div>
            <script type="text/javascript">
                $(document).ready(function(){
                    $('.table-responsive, .table-responsive-row, .pagination').hide();
                });
            </script>
        {/if}
    <div id="refresh_result">
        <div id="col_refresh_result">
            <i class="icon-refresh icon-spin icon-fw"></i>
        </div>
    </div>
    <script type="application/javascript">
        var defaultFormLanguage = {$defaultFormLanguage|intval};
        {if $fields_display}
        $(document).ready(function(){
        {foreach from=$fields_display item=param name=params}
            {if isset($param.title_link)}
                {if ($bulk_actions && $has_bulk_actions) || $ps1605}
                    {$start_th = ($smarty.foreach.params.index + 2)}
                {else}
                    {$start_th = ($smarty.foreach.params.index + 1)}
                {/if}
                {if $responsive_table}
                    $('.table-responsive-row th:nth-of-type({$start_th|intval}) .title_box').html('{$param.title_link|escape:'quotes':'UTF-8'}');
                {else}
                    $('.table-responsive th:nth-of-type({$start_th|intval}) .title_box').html('{$param.title_link|escape:'quotes':'UTF-8'}');
                {/if}
            {/if}
        {/foreach}
        {/if}
        });
    </script>
    {/if}
{/block}

{block name=override_header}
{if !$return_ajax}
    <input id="id_shop" type="hidden" value="{$id_shop|intval}" />
    <script type="text/javascript">
        var id_shop = {$id_shop|intval};
        var attrs = [];
        attrs[0] = [0, '---'];

        {foreach $attributeJs as $idgrp => $group}
            attrs[{$idgrp|intval}] = [0, '---'
                {foreach $group as $idattr => $attrname}
                , '{$idattr|intval}', '{$attrname|replace:"\r\n":''|replace:"\n":''|trim|escape:'quotes':'UTF-8'}'
                {/foreach}
            ];
        {/foreach}

        var caracts = [];
        caracts[0] = [0, '---'];

        {foreach $feature_valuesJs as $idgrp => $group}
            caracts[{$idgrp|intval}] = [0, '---'
                {foreach $group as $idattr => $attrname}
                , '{$idattr|intval}', '{$attrname|replace:"\r\n":''|replace:"\n":''|trim|escape:'quotes':'UTF-8'}'
                {/foreach}
            ];
        {/foreach}

        function confirm_duplicate(id_product)
        {
            $.alerts.okButton = '{l s='Yes' js=1 mod='dmuadminrecherche'}';
            $.alerts.cancelButton = '{l s='No' js=1 mod='dmuadminrecherche'}';
            jConfirm('{l s='This will copy the images too. If you wish to proceed, click "Yes". If not, click "No".' js=1 mod='dmuadminrecherche'}', '', function(confirm){
                duplicate(id_product, confirm);
            });
        }
    </script>
    <span style="display: none" id="trad_list">{l s='-- Choose --' mod='dmuadminrecherche'}</span>
    <div class="row">
        <div class="col-lg-12">
            <div id="filtre_recherche" class="panel">
                <h2 onclick="display_filter()">
                    <i id="img_filter" class="icon-{if !$show_filter}expand{else}collapse{/if}-alt"></i>
                    {l s='Search Filter' mod='dmuadminrecherche'}
                </h2>
                <div class="btn_reset">
                    <button class="btn btn-warning" onclick="reset_filter();"><i class="icon-eraser"></i> {l s='Reset' mod='dmuadminrecherche'}</button>
                </div>

                <div id="div_recherche" {if !$show_filter} style="display:none;"{/if}>
                    <div class="row">
                        <div class="col-xs-8">
                            <div class="row">
                                <div class="col-xs-6 form-horizontal">
                                    <div class="form-group">
                                        <label for="category" class="control-label col-md-3">{l s='Category:' mod='dmuadminrecherche'}</label>
                                        <div class="col-md-8">
                                            <select id="category" onchange="ajax_update('id_category', this.value);">
                                                <option value="0">{l s='--All--' mod='dmuadminrecherche'}</option>
                                                {$categories|escape:'quotes':'UTF-8'|replace:"\'":"'"}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="manufacturer" class="control-label col-md-3">{l s='Manufact.:' mod='dmuadminrecherche'}</label>
                                        <div class="col-md-8">
                                            <select id="manufacturer" onchange="ajax_update('id_manufacturer', this.value);">
                                            <option value="0">{l s='--All-- ' mod='dmuadminrecherche'}</option>
                                            {if !empty($manufacturers)}
                                                {foreach $manufacturers as $manufacturer}
                                                    <option value="{$manufacturer['id_manufacturer']|intval}"{if $manufacturer['id_manufacturer'] == $filters_selected.id_manufacturer} selected="selected"{/if}>{$manufacturer['name']|escape:'html':'UTF-8'}</option>
                                                {/foreach}
                                            {/if}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="supplier" class="control-label col-md-3">{l s='Supplier:' mod='dmuadminrecherche'}</label>
                                        <div class="col-md-8">
                                            <select id="supplier" onchange="ajax_update('id_supplier', this.value);">
                                                <option value="0">{l s='--All-- ' mod='dmuadminrecherche'}</option>
                                                {if !empty($suppliers)}
                                                    {foreach $suppliers as $supplier}
                                                        <option value="{$supplier['id_supplier']|intval}"{if $supplier['id_supplier'] == $filters_selected.id_supplier} selected="selected"{/if}>{$supplier['name']|escape:'html':'UTF-8'}</option>
                                                    {/foreach}
                                                {/if}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="statut_couleur" class="control-label col-md-3">{l s='Status:' mod='dmuadminrecherche'}</label>
                                        <div class="col-md-8">
                                            <select id="statut_couleur" onchange="ajax_update('id_status_color', this.value);">
                                                <option value="0" {if $filters_selected.id_status_color == 0} selected="selected"{/if}>{l s='--All-- ' mod='dmuadminrecherche'}</option>
                                                <option value="1" {if $filters_selected.id_status_color == 1} selected="selected"{/if}>{l s='Neutral' mod='dmuadminrecherche'}</option>
                                                <option value="2" {if $filters_selected.id_status_color == 2} selected="selected"{/if}>{l s='Green' mod='dmuadminrecherche'}</option>
                                                <option value="3" {if $filters_selected.id_status_color == 3} selected="selected"{/if}>{l s='Red' mod='dmuadminrecherche'}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-6 form-horizontal">
                                    <div class="form-group">
                                        <label for="attribute_group" class="control-label col-md-4">{l s='Attribute:' mod='dmuadminrecherche'}</label>
                                        <div class="col-md-7">
                                            <select name="attribute_group" id="attribute_group" onchange="populate_attrs();ajax_update('id_attribute_groupe', this.value);">
                                                <option value="0">{l s='--All-- ' mod='dmuadminrecherche'}</option>
                                                {if !empty($attributes_groups)}
                                                    {foreach $attributes_groups as $attribute_group}
                                                        {if isset($attributeJs[$attribute_group['id_attribute_group']])}
                                                        <option value="{$attribute_group['id_attribute_group']|intval}"{if $attribute_group['id_attribute_group'] == admin_rechercher_id_attribute_groupe} selected="selected"{/if}>
                                                        {$attribute_group['name']|escape:'html':'UTF-8'}&nbsp;&nbsp;</option>
                                                        {/if}
                                                    {/foreach}
                                                {/if}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="attribute" class="control-label col-md-4">{l s='Attribute value:' mod='dmuadminrecherche'}</label>
                                        <div class="col-md-7">
                                            <select name="attribute" id="attribute"
                                                    onchange="ajax_update('id_attribute', this.value);">
                                                <option value="0">---</option>
                                            </select>
                                            <input id="attribute_selected" type="hidden" value="{$filters_selected.id_attribute|intval}"/>
                                            <script type="text/javascript">populate_attrs();</script>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="caract_group" class="control-label col-md-4">{l s='Feature:' mod='dmuadminrecherche'}</label>
                                        <div class="col-md-7">
                                            <select name="caract_group" id="caract_group"
                                                    onchange="populate_caracts();ajax_update('id_feature', this.value);">
                                                <option value="0">{l s='--All--' mod='dmuadminrecherche'}</option>
                                                {if isset($features)}
                                                    {foreach $features as $feature}
                                                        {if isset($feature_valuesJs[$feature['id_feature']])}
                                                        <option value="{$feature['id_feature']|intval}"{if $feature['id_feature'] == admin_rechercher_id_feature} selected="selected"{/if}>
                                                        {$feature['name']|escape:'html':'UTF-8'}&nbsp;&nbsp;</option>
                                                        {/if}
                                                    {/foreach}
                                                {/if}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="caracte" class="control-label col-md-4">{l s='Feature value:' mod='dmuadminrecherche'}</label>
                                        <div class="col-md-7">
                                            <select name="caracte" id="caracte" onchange="ajax_update('id_feature_value', this.value);">
                                                <option value="0">---</option>
                                            </select>
                                            <input id="caract_selected" type="hidden" value="{$filters_selected.id_feature_value|intval}"/>
                                            <script type="text/javascript">populate_caracts();</script>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="search_field" class="row">
                                <div class="col-md-12 form-horizontal">
                                    <div class="form-group">
                                        <label class="control-label col-md-2" for="search_field_input">{l s='Search:' mod='dmuadminrecherche'}</label>
                                        <div class="col-md-8">
                                            <input id="search_field_input" type="text" autocomplete="off" value="{$filters_selected.query|escape:'html':'UTF-8'}"/>
                                        </div>
                                        <button class="btn btn-default" onclick="ajax_update('query', $('#search_field_input').val())"><i class="icon-search"></i> {l s='Search' mod='dmuadminrecherche'}</button>
                                        <p class="col-lg-offset-2 col-md-10 help-block">{l s='Research is conducted in the following fields:' mod='dmuadminrecherche'}
                                            <em>{l s='Name, description, short description, tags, reference, supplier reference, EAN13, ID product' mod='dmuadminrecherche'}</em>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="row">
                                <div class="col-md-12 form-horizontal">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">{l s='Activated:' mod='dmuadminrecherche'}</label>
                                        <div class="col-md-7">
                                            <span class="switch switch-3 prestashop-switch fixed-width-lg">
                                                <input type="radio" value="1" id="active_on" name="active" onchange="ajax_update('active', 1)"{if $filters_selected.active == 1} checked="checked"{/if} />
                                                <label for="active_on">{l s='Yes' mod='dmuadminrecherche'}</label>
                                                <input type="radio" value="0" id="active_off" name="active" onchange="ajax_update('active', 0)"{if $filters_selected.active == 2} checked="checked"{/if} />
                                                <label for="active_off">{l s='No' mod='dmuadminrecherche'}</label>
                                                <input type="radio" value="2" id="active_all" name="active" onchange="ajax_update('active', 2)"{if $filters_selected.active == 2} checked="checked"{/if} />
                                                <label for="active_all">&nbsp;{l s='All' mod='dmuadminrecherche'}</label>
                                                <a class="slide-button btn"></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">{l s='Pack:' mod='dmuadminrecherche'}</label>
                                        <div class="col-md-7">
                                            <span class="switch switch-3 prestashop-switch fixed-width-lg">
                                                <input type="radio" value="1" id="ppack_on" name="ppack" onchange="ajax_update('pack', 1)"{if $filters_selected.pack == 1} checked="checked"{/if} />
                                                <label for="ppack_on">{l s='Yes' mod='dmuadminrecherche'}</label>
                                                <input type="radio" value="0" id="ppack_off" name="ppack" onchange="ajax_update('pack', 0)"{if $filters_selected.pack == 0} checked="checked"{/if} />
                                                <label for="ppack_off">{l s='No' mod='dmuadminrecherche'}</label>
                                                <input type="radio" value="2" id="ppack_all" name="ppack" onchange="ajax_update('pack', 2)"{if $filters_selected.pack == 2} checked="checked"{/if} />
                                                <label for="ppack_all">&nbsp;{l s='All' mod='dmuadminrecherche'}</label>
                                                <a class="slide-button btn"></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">{l s='On sale / discounted:' mod='dmuadminrecherche'}</label>
                                        <div class="col-md-7">
                                            <span class="switch switch-3 prestashop-switch fixed-width-lg">
                                                <input type="radio" value="1" id="psolde_on" name="psolde" onchange="ajax_update('onsale', 1)"{if $filters_selected.onsale == 1} checked="checked"{/if} />
                                                <label for="psolde_on">{l s='Yes' mod='dmuadminrecherche'}</label>
                                                <input type="radio" value="0" id="psolde_off" name="psolde" onchange="ajax_update('onsale', 0)"{if $filters_selected.onsale == 0} checked="checked"{/if} />
                                                <label for="psolde_off">{l s='No' mod='dmuadminrecherche'}</label>
                                                <input type="radio" value="2" id="psolde_all" name="psolde" onchange="ajax_update('onsale', 2)"{if $filters_selected.onsale == 2} checked="checked"{/if} />
                                                <label for="psolde_all">&nbsp;{l s='All' mod='dmuadminrecherche'}</label>
                                                <a class="slide-button btn"></a>
                                            </span>
                                        </div>
                                    </div>
                                    {if $stock_managment}
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">{l s='Stock:' mod='dmuadminrecherche'}</label>
                                        <div class="col-md-7">
                                            <span class="switch switch-3 prestashop-switch fixed-width-lg">
                                                <input type="radio" value="1" id="pstock_on" name="pstock" onchange="ajax_update('stock', 1)"{if $filters_selected.stock == 1} checked="checked"{/if} />
                                                <label for="pstock_on">{l s='Yes' mod='dmuadminrecherche'}</label>
                                                <input type="radio" value="0" id="pstock_off" name="pstock" onchange="ajax_update('stock', 0)"{if $filters_selected.stock == 0} checked="checked"{/if} />
                                                <label for="pstock_off">{l s='No' mod='dmuadminrecherche'}</label>
                                                <input type="radio" value="2" id="pstock_all" name="pstock" onchange="ajax_update('stock', 2)"{if $filters_selected.stock == 2} checked="checked"{/if} />
                                                <label for="pstock_all">&nbsp;{l s='All' mod='dmuadminrecherche'}</label>
                                                <a class="slide-button btn"></a>
                                            </span>
                                        </div>
                                    </div>
                                    {/if}
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">{l s='To download:' mod='dmuadminrecherche'}</label>
                                        <div class="col-md-7">
                                            <span class="switch switch-3 prestashop-switch fixed-width-lg">
                                                <input type="radio" value="1" id="is_virtual_good_on" name="is_virtual_good" onchange="ajax_update('download', 1)"{if $filters_selected.download == 1} checked="checked"{/if} />
                                                <label for="is_virtual_good_on">{l s='Yes' mod='dmuadminrecherche'}</label>
                                                <input type="radio" value="0" id="is_virtual_good_off" name="is_virtual_good" onchange="ajax_update('download', 0)"{if $filters_selected.download == 0} checked="checked"{/if} />
                                                <label for="is_virtual_good_off">{l s='No' mod='dmuadminrecherche'}</label>
                                                <input type="radio" value="2" id="is_virtual_good_all" name="is_virtual_good" onchange="ajax_update('download', 2)"{if $filters_selected.download == 2} checked="checked"{/if} />
                                                <label for="is_virtual_good_all">&nbsp;{l s='All' mod='dmuadminrecherche'}</label>
                                                <a class="slide-button btn"></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">{l s='Without image:' mod='dmuadminrecherche'}</label>
                                        <div class="col-md-7">
                                            <span class="switch switch-3 prestashop-switch fixed-width-lg">
                                                <input type="radio" value="1" id="is_no_image_on" name="is_no_image" onchange="ajax_update('no_image', 1)"{if $filters_selected.no_image == 1} checked="checked"{/if} />
                                                <label for="is_no_image_on">{l s='Yes' mod='dmuadminrecherche'}</label>
                                                <input type="radio" value="0" id="is_no_image_off" name="is_no_image" onchange="ajax_update('no_image', 0)"{if $filters_selected.no_image == 0} checked="checked"{/if} />
                                                <label for="is_no_image_off">{l s='No' mod='dmuadminrecherche'}</label>
                                                <input type="radio" value="2" id="is_no_image_all" name="is_no_image" onchange="ajax_update('no_image', 2)"{if $filters_selected.no_image == 2} checked="checked"{/if} />
                                                <label for="is_no_image_all">&nbsp;{l s='All' mod='dmuadminrecherche'}</label>
                                                <a class="slide-button btn"></a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="tri_criteres" class="row">
        <div class="col-lg-2 form-horizontal">
            <div class="form-group">
                <label for="order_by" class="control-label col-lg-3">{l s='Sort' mod='dmuadminrecherche'}</label>
                <div class="col-lg-9">
                    <select id="order_by" onchange="change_order()">
                        <option value="a.date_add:asc"{if $order_by_select == 'a.date_add:asc'} selected="selected"{/if}>{l s='Creation date asc' mod='dmuadminrecherche'}</option>
                        <option value="a.date_add:desc"{if $order_by_select == 'a.date_add:desc'} selected="selected"{/if}>{l s='Creation date desc' mod='dmuadminrecherche'}</option>
                        <option value="b.name:asc"{if $order_by_select == 'b.name:asc'} selected="selected"{/if}>{l s='Name asc' mod='dmuadminrecherche'}</option>
                        <option value="b.name:desc"{if $order_by_select == 'b.name:desc'} selected="selected"{/if}>{l s='Name desc' mod='dmuadminrecherche'}</option>
                        <option value="a.reference:asc"{if $order_by_select == 'a.reference:asc'} selected="selected"{/if}>{l s='Reference asc' mod='dmuadminrecherche'}</option>
                        <option value="a.reference:desc"{if $order_by_select == 'a.reference:desc'} selected="selected"{/if}>{l s='Reference desc' mod='dmuadminrecherche'}</option>
                        <option value="ps.price:asc"{if $order_by_select == 'ps.price:asc'} selected="selected"{/if}>{if !$country_display_tax_label || $noTax}{l s='Retail price asc' mod='dmuadminrecherche'}{else}{l s='Retail price with tax asc' mod='dmuadminrecherche'}{/if}</option>
                        <option value="ps.price:desc"{if $order_by_select == 'ps.price:desc'} selected="selected"{/if}>{if !$country_display_tax_label || $noTax}{l s='Retail price desc' mod='dmuadminrecherche'}{else}{l s='Retail price with tax desc' mod='dmuadminrecherche'}{/if}</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div id="criteres_liste" class="panel">
                <fieldset class="row">
                    <legend>{l s='editable' mod='dmuadminrecherche'}</legend>
                    {foreach $columns as $column => $value}
                        {if empty($value.editable)}{continue}{/if}
                        <div class="col-md-2"><input type="checkbox" value="{$column|escape:'html':'UTF-8'}" class="critere" id="crit_{$column|escape:'html':'UTF-8'}"{if isset($fields_display[$column])} checked="checked"{/if} /><label for="crit_{$column|escape:'html':'UTF-8'}">{$value.title|escape:'html':'UTF-8'}</label></div>
                    {/foreach}
                </fieldset>
                <br />
                <fieldset class="row">
                    <legend>{l s='not editable' mod='dmuadminrecherche'}</legend>
                    {foreach $columns as $column => $value}
                        {if !empty($value.editable) || $column == 'action'}{continue}{/if}
                        <div class="col-md-2"><input type="checkbox" value="{$column|escape:'html':'UTF-8'}" class="critere" id="crit_{$column|escape:'html':'UTF-8'}"{if isset($fields_display[$column])} checked="checked"{/if} /><label for="crit_{$column|escape:'html':'UTF-8'}">{$value.title|escape:'html':'UTF-8'}</label></div>
                    {/foreach}
                </fieldset>
            </div>
        </div>
        <div class="col-lg-2 text-right">
            <button class="btn btn-default" id="show_criteres">
                <span class="show_crit"><i class="icon icon-chevron-down"></i> {l s='Show criteria' mod='dmuadminrecherche'}</span>
                <span class="hide_crit" style="display: none;"><i class="icon icon-chevron-up"></i> {l s='Close' mod='dmuadminrecherche'}</span>
            </button>
        </div>
    </div>
    <script type="text/javascript">
        var noTax = '{$noTax|escape:'html':'UTF-8'}';
        var taxesArray = [0];
        {foreach $tax_rules_groups as $tax_rules_group}
            taxesArray[{$tax_rules_group['id_tax_rules_group']|intval}]={if isset($taxesRatesByGroup[$tax_rules_group['id_tax_rules_group']])}{$taxesRatesByGroup[$tax_rules_group['id_tax_rules_group']]|floatval}{else}0{/if};
        {/foreach}
        var ecotaxTaxRate = {($ecotaxTaxRate|floatval / 100) + 1};
        var priceDisplayPrecision = {$smarty.const._PS_PRICE_DISPLAY_PRECISION_|intval};
        {if isset($PS_ALLOW_ACCENTED_CHARS_URL) && $PS_ALLOW_ACCENTED_CHARS_URL}
        var PS_ALLOW_ACCENTED_CHARS_URL = 1;
        {else}
        var PS_ALLOW_ACCENTED_CHARS_URL = 0;
        {/if}
    </script>
{/if}
    {if !isset($popin_combinations)}
        {assign var='simple_header' value=false}
        {assign var='show_filters' value=false}
    {/if}
{/block}