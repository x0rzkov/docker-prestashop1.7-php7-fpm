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
{extends file="helpers/list/list_content.tpl"}

{block name="td_content"}
    {if isset($tr.$key) || $tr.$key === null}
        {if $combination_active && !empty($params.show_action) && $params.show_action == 'combinations' && !empty($params.show_action_param) && $tr[$params.show_action_param]}
            {$params.type = 'dmu_text'}
        {/if}
        {* Statut *}
        {if isset($params.type) && $params.type == 'dmu_status'}
            <a title="{$tr.comment|escape:'html':'UTF-8'}" id="pastille_status_{$tr.$identifier|intval}" href="javascript:;" onclick="show_status({$tr.$identifier|intval});">
                {if $tr.status == 1}
                    <i class="icon-circle text-muted"></i>
                {elseif $tr.status == 2}
                    <i class="icon-circle green_status"></i>
                {elseif $tr.status == 3}
                    <i class="icon-circle red_status"></i>
                {else}
                    --
                {/if}
            </a>
            <div class="div_status" id="div_status_{$tr.$identifier|intval}"></div>
        {* image *}
        {elseif isset($params.type) && $params.type == 'dmu_image'}
            <a id="product_img_{$tr.$identifier|intval}" class="product_img" href="{$params.link_bo|escape:'html':'UTF-8'}{$tr.$identifier|intval}" target="_blank">
                <img class="imgm img-thumbnail" src="{$tr.img_src|escape:'html':'UTF-8'}" alt="">
            </a>
            <div id="zoom_photo_{$tr.$identifier|intval}" class="zoom_photo">
                <img width="200" height="200" src="{$tr.img_src|escape:'html':'UTF-8'}" alt="">
                <a class="btn btn-default" href="{$params.link_bo|escape:'html':'UTF-8'}{$tr.$identifier|intval}" target="_blank"><i class="icon-pencil"></i> {l s='Edit' mod='dmuadminrecherche'}</a>
                {if $tr.product_url}<a class="btn btn-default" href="{$tr.product_url|escape:'html':'UTF-8'}" target="_blank"><i class="icon-eye"></i> {l s='Preview' mod='dmuadminrecherche'}</a>{/if}
            </div>
        {* texte input *}
        {elseif isset($params.type) && $params.type == 'dmu_text_input'}
            {assign var='badge' value=''}
            {if !empty($params.badge_danger) && ($tr.$key <= 0 || $tr.$key == '')}{assign var='badge' value=' badge badge-danger'}{/if}
            <span id="{$key|escape:'html':'UTF-8'}_txt_{if !empty($params.combination)}{$id_product|intval}_{$tr.$identifier|intval}{else}{$tr.$identifier|intval}_0{/if}"{if $badge} class="{$badge|escape:'html':'UTF-8'}"{/if}{if !empty($params.badge_danger)} data-badge="1"{/if}>
                {if $tr.$key || $tr.$key === '0'}
                    {if !empty($params.price)}
                        {if $tr.$key|floatval != 0}
                            {displayPrice price=$tr.$key}
                        {else}
                            --
                        {/if}
                    {else}
                        {$tr.$key|escape:'html':'UTF-8'}
                    {/if}
                {else}
                    --
                {/if}
            </span>
            {if !empty($params.lang) && $languages|count > 1}
                {foreach $languages as $language}
                    <div class="translatable-field" id="lang-{$key|escape:'html':'UTF-8'}-{$tr.$identifier|intval}-{$language.id_lang|intval}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
                    <div class="col-lg-9">
                        <div class="col-lg-10">
                            <input type="text" name="{$key|escape:'html':'UTF-8'}_value" id="{$key|escape:'html':'UTF-8'}_value{if !empty($params.combination)}_{$id_product|intval}_{$tr.$identifier|intval}{else}_{$tr.$identifier|intval}_0{/if}_{$language.id_lang|intval}"{if !empty($params.lang)} data-lang="{$language.id_lang|intval}"{/if} class="input_dmu_text" value="{if $language.id_lang == $defaultFormLanguage}{$tr.$key|escape:'html':'UTF-8'}{/if}"{if !empty($params.size)} style="width:{$params.size|intval}px"{/if}>
                        </div>
                        <div class="col-lg-2">
                            <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
                                {$language.iso_code|escape:'html':'UTF-8'}
                                <i class="icon-caret-down"></i>
                            </button>
                            <ul class="dropdown-menu">
                                {foreach from=$languages item=language}
                                <li><a href="javascript:hideOtherLanguageDmu('{$key|escape:'html':'UTF-8'}',{$tr.$identifier|intval},0,{$language.id_lang|intval});" tabindex="-1">{$language.name|escape:'html':'UTF-8'}</a></li>
                                {/foreach}
                            </ul>
                        </div>
                    </div>
                    </div>
                {/foreach}
            {else}
                <input type="text" name="{$key|escape:'html':'UTF-8'}_value" id="{$key|escape:'html':'UTF-8'}_value{if !empty($params.combination)}_{$id_product|intval}_{$tr.$identifier|intval}{else}_{$tr.$identifier|intval}_0{/if}_{if isset($defaultFormLanguage) && !empty($params.lang)}{$defaultFormLanguage|intval}{else}0{/if}" class="input_dmu_text" value="{if !empty($params.float)}{$tr.$key|floatval}{else}{$tr.$key|escape:'html':'UTF-8'}{/if}"{if !empty($params.size)} style="width:{$params.size|intval}px"{/if}>
            {/if}
        {* texte *}
        {elseif isset($params.type) && $params.type == 'dmu_text'}
            {assign var='badge' value=''}
            {if !empty($params.badge_danger) && ($tr.$key <= 0 || $tr.$key == '')}{assign var='badge' value=' badge badge-danger'}{/if}
            <span id="{$key|escape:'html':'UTF-8'}_txt_{if !empty($params.combination)}{$id_product|intval}_{$tr.$identifier|intval}{else}{$tr.$identifier|intval}_0{/if}"{if !empty($params.show_action)} onclick="show_{$params.show_action|escape:'html':'UTF-8'}({$tr.$identifier|intval})"{/if}{if $badge} class="{$badge|escape:'html':'UTF-8'}"{/if}{if !empty($params.badge_danger)} data-badge="1"{/if}>
                {if $tr.$key || $tr.$key === '0'}
                    {if !empty($params.price)}
                        {if $tr.$key|floatval != 0}
                            {displayPrice price=$tr.$key}
                        {else}
                            --
                        {/if}
                    {else}
                        {$tr.$key|escape:'html':'UTF-8'}
                    {/if}
                {else}
                    --
                {/if}
            </span>
        {* liste *}
        {elseif isset($params.type) && $params.type == 'dmu_list'}
            <span id="{$key|escape:'html':'UTF-8'}_id_{$tr.$identifier|intval}" data-select="{$tr[$params.key]|escape:'html':'UTF-8'}"{if !empty($params.required)} data-required="{$params.required|escape:'html':'UTF-8'}"{/if}>{if $tr.$key}{$tr.$key|escape:'html':'UTF-8'}{else}--{/if}</span>
        {* active *}
        {elseif isset($params.type) && $params.type == 'dmu_active'}
            <a id="active_{$tr.$identifier|intval}" class="list-action-enable {if $tr.$key}action-enabled{else}action-disabled{/if}" href="javascript:;" title="{if $tr.$key}{l s='Enabled' mod='dmuadminrecherche'}{else}{l s='Disabled' mod='dmuadminrecherche'}{/if}">
                <i class="icon-check{if !$tr.$key} hidden{/if}"></i>
                <i class="icon-remove{if $tr.$key} hidden{/if}"></i>
            </a>
        {* action *}
        {elseif isset($params.type) && $params.type == 'dmu_action'}
            <a class="btn btn-default" title="{l s='Details' mod='dmuadminrecherche'}" href="javascript:;" onclick="show_details({$tr.$identifier|intval})"><i class="icon-file-text-o"></i></a>
            <a class="btn btn-default" title="{l s='Descriptions' mod='dmuadminrecherche'}" href="javascript:;" onclick="show_descriptions({$tr.$identifier|intval})"><i class="icon-align-left"></i></a>
            {if $combination_active}<a class="btn btn-default" title="{l s='Combinations' mod='dmuadminrecherche'}" href="javascript:;" onclick="show_combinations({$tr.$identifier|intval})"><i class="icon-asterisk"></i></a>{/if}
            <a class="btn btn-default" title="{l s='Prices' mod='dmuadminrecherche'}" href="javascript:;" onclick="show_prices({$tr.$identifier|intval})"><i class="icon-dollar"></i></a>
            <a class="btn btn-default" title="{l s='SEO' mod='dmuadminrecherche'}" href="javascript:;" onclick="show_seo({$tr.$identifier|intval})"><i class="icon-globe"></i></a>
            {if $feature_active}<a class="btn btn-default" title="{l s='Features' mod='dmuadminrecherche'}" href="javascript:;" onclick="show_features({$tr.$identifier|intval})"><i class="icon-pencil-square-o"></i></a>{/if}
            <a class="btn btn-default" title="{l s='Duplicate' mod='dmuadminrecherche'}" href="javascript:;" onclick="confirm_duplicate({$tr.$identifier|intval})"><i class="icon-copy"></i></a>
        {elseif isset($params.type) && $params.type == 'dmu_action_comb'}
            {if $tr.$key != 'default'}
                <a class="btn btn-default" href="javascript:default_product_combination({$id_product|intval},{$tr.$identifier|intval});" title="{l s='Default' mod='dmuadminrecherche'}"><i class="icon-asterisk"></i></a>
            {/if}
            <a class="btn btn-default" href="javascript:delete_product_combination({$id_product|intval},{$tr.$identifier|intval});" title="{l s='Delete' mod='dmuadminrecherche'}"><i class="icon-trash"></i></a>
        {elseif isset($params.type) && $params.type == 'price'}
            <span id="{$key|escape:'html':'UTF-8'}_{$tr.$identifier|intval}">{displayPrice price=$tr.$key}</span>
        {* image déclinaison *}
        {elseif isset($params.type) && $params.type == 'dmu_image_comb'}
            {assign var='first_img' value=$tr.$key|current}
            {if $first_img}
                <img class="imgm img-thumbnail" src="{$first_img|escape:'html':'UTF-8'}" alt="" onmouseover="$('#img_combination_{$tr.$identifier|intval}').show()" onmouseout="$('#img_combination_{$tr.$identifier|intval}').hide()">
            {/if}
            <div id="img_combination_{$tr.$identifier|intval}" class="img_combination">
                {foreach from=$tr.$key item=image name=images}
                    <img class="img-thumbnail" src="{$image|escape:'html':'UTF-8'}" alt="" />
                {/foreach}
            </div>
        {else}
            {$smarty.block.parent}
        {/if}
    {else}
        {$smarty.block.parent}
    {/if}
{/block}