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
<div id="popin_features_{$product->id|intval}" class="popin_dmu bootstrap">
    <form action="" id="form_features_{$product->id|intval}">
        <input type="hidden" name="features_id_product" id="features_id_product" value="{$product->id|intval}">
        <h3>{l s='Features:' mod='dmuadminrecherche'} {$product->name|escape:'html':'UTF-8'} ({l s='ID:' mod='dmuadminrecherche'} {$product->id|intval})</h3>
        <div class="row">
            <div class="form-horizontal col-sm-12">
                <table class="table">
                    <thead>
                    <tr>
                        <th><span class="title_box">{l s='Feature' mod='dmuadminrecherche'}</span></th>
                        <th><span class="title_box">{l s='Pre-defined value' mod='dmuadminrecherche'}</span></th>
                        <th><span class="title_box"><u>{l s='or' mod='dmuadminrecherche'}</u> {l s='Customized value' mod='dmuadminrecherche'}</span></th>
                    </tr>
                    </thead>

                    <tbody>
                    {foreach from=$features item=feature}

                        <tr>
                            <td>{$feature.name|escape:'html':'UTF-8'}</td>
                            <td>
                                {if sizeof($feature.featureValues)}
                                    <select id="feature_{$feature.id_feature|intval}_value" name="feature_value[{$feature.id_feature|intval}]"
                                            onchange="$('.custom_{$feature.id_feature|intval}').val('');">
                                        <option value="0">---</option>
                                        {foreach from=$feature.featureValues item=value}
                                            <option value="{$value.id_feature_value|intval}"{if $feature.current_item == $value.id_feature_value}selected="selected"{/if} >
                                                {$value.value|truncate:40|escape:'html':'UTF-8'}
                                            </option>
                                        {/foreach}
                                    </select>
                                {else}
                                    <input type="hidden" name="feature_value[{$feature.id_feature|intval}]" value="0" />
                                    <span class="text-nowrap">{l s='N/A' mod='dmuadminrecherche'} -
                                        <a target="_blank" href="{$link->getAdminLink('AdminFeatures')|escape:'html':'UTF-8'}&amp;addfeature_value&amp;id_feature={$feature.id_feature|intval}"
                                           class="confirm_leave btn btn-link"><i class="icon-plus-sign"></i> {l s='Add pre-defined values first' mod='dmuadminrecherche'} <i class="icon-external-link-sign"></i></a>
                                    </span>
                                {/if}
                            </td>
                            <td>

                                <div class="row lang-0" style='display: none;'>
                                    <div class="col-lg-9">
						            <textarea class="custom_{$feature.id_feature|intval}_ALL textarea-autosize" name="custom_ALL[{$feature.id_feature|intval}]"
                                    cols="40" style="background-color:#CCF" rows="1" onkeyup="{foreach from=$languages key=k item=language}$('.custom_{$feature.id_feature|intval}_{$language.id_lang|intval}').val($(this).val());{/foreach}">{$feature.val[1].value|escape:'html':'UTF-8'|default:""}</textarea>

                                    </div>
                                    {if $languages|count > 1}
                                        <div class="col-lg-3">
                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                                {l s='ALL' mod='dmuadminrecherche'}
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                {foreach from=$languages item=language}
                                                    <li>
                                                        <a href="javascript:void(0);" onclick="restore_lng($(this),{$language.id_lang|intval});">{$language.iso_code|escape:'html':'UTF-8'}</a>
                                                    </li>
                                                {/foreach}
                                            </ul>
                                        </div>
                                    {/if}
                                </div>

                                {foreach from=$languages key=k item=language}
                                    {if $languages|count > 1}
                                        <div class="row translatable-field lang-{$language.id_lang|intval}">
                                        <div class="col-lg-9">
                                    {/if}
                                    <textarea
                                            class="custom_{$feature.id_feature|intval}_{$language.id_lang|intval} textarea-autosize"
                                            name="custom_{$language.id_lang|intval}[{$feature.id_feature|intval}]"
                                            cols="40"
                                            rows="1"
                                            onkeyup="if (isArrowKey(event)) return ;$('#feature_{$feature.id_feature|intval}_value').val(0);" >{$feature.val[$language.id_lang].value|escape:'html':'UTF-8'|default:""}</textarea>

                                    {if $languages|count > 1}
                                        </div>
                                        <div class="col-lg-3">
                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                                {$language.iso_code|escape:'html':'UTF-8'}
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="javascript:void(0);" onclick="all_languages($(this));">{l s='ALL' mod='dmuadminrecherche'}</a></li>
                                                {foreach from=$languages item=language}
                                                    <li>
                                                        <a href="javascript:hideOtherLanguage({$language.id_lang|intval});">{$language.iso_code|escape:'html':'UTF-8'}</a>
                                                    </li>
                                                {/foreach}
                                            </ul>
                                        </div>
                                        </div>
                                    {/if}
                                {/foreach}
                            </td>

                        </tr>
                        {foreachelse}
                        <tr>
                            <td colspan="3" style="text-align:center;"><i class="icon-warning-sign"></i> {l s='No features have been defined' mod='dmuadminrecherche'}</td>
                        </tr>
                    {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
    </form>
    <div id="error_features_{$product->id|intval}" class="alert alert-danger" style="display: none;"></div>
    <button class="btn btn-default col-sm-12 bulk_ok" onclick="change_features({$product->id|intval})">OK</button>
    <div class="col-sm-12 text-center popin_refresh">
        <i class="icon-refresh icon-spin icon-fw"></i>
    </div>
</div>

<script type="text/javascript">
    $(".textarea-autosize").off().autosize();
    hideOtherLanguage({$id_lang|intval});
    {literal}

    function all_languages(pos)
    {
        {/literal}
        {if isset($languages) && is_array($languages)}
        {foreach from=$languages key=k item=language}
        pos.parents('td').find('.lang-{$language.id_lang|intval}').addClass('nolang-{$language.id_lang|intval}').removeClass('lang-{$language.id_lang|intval}');
        {/foreach}
        {/if}
        pos.parents('td').find('.translatable-field').hide();
        pos.parents('td').find('.lang-0').show();
        {literal}
    }

    function restore_lng(pos,i)
    {
        {/literal}
        {if isset($languages) && is_array($languages)}
        {foreach from=$languages key=k item=language}
        pos.parents('td').find('.nolang-{$language.id_lang|intval}').addClass('lang-{$language.id_lang|intval}').removeClass('nolang-{$language.id_lang|intval}');
        {/foreach}
        {/if}
        {literal}
        pos.parents('td').find('.lang-0').hide();
        hideOtherLanguage(i);
    }
</script>
{/literal}