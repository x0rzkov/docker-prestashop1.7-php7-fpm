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
<div id="popin_descriptions_{$product->id|intval}" class="popin_dmu bootstrap">
    <form action="" id="form_description_{$product->id|intval}">
        <input type="hidden" name="description_id_product" id="description_id_product" value="{$product->id|intval}">
        <h3>{l s='Descriptions:' mod='dmuadminrecherche'} {$product->name[$id_lang]|escape:'html':'UTF-8'} ({l s='ID:' mod='dmuadminrecherche'} {$product->id|intval})</h3>
        <div class="row">
            <div class="form-horizontal col-sm-12">
                <div class="form-group">
                    <label for="description_short_desc_{$product->id|intval}" class="control-label col-sm-2">
                        <span class="label-tooltip" data-toggle="tooltip" title="{l s='Appears in the product list(s), and at the top of the product page.' mod='dmuadminrecherche'}">
                            {l s='Short description' mod='dmuadminrecherche'}
                        </span>
                    </label>
                    <div class="col-sm-10">
                        {include
                            file="./textarea_lang.tpl"
                            languages=$languages
                            input_name='description_short_desc'
                            class="autoload_rte"
                            input_value=$product->description_short
                            max=$PS_PRODUCT_SHORT_DESC_LIMIT
                            product=$product}
                    </div>
                </div>
                <div class="form-group">
                    <label for="description_desc_{$product->id|intval}" class="control-label col-sm-2">
                        <span class="label-tooltip" data-toggle="tooltip" title="{l s='Appears in the body of the product page.' mod='dmuadminrecherche'}">
                            {l s='Description' mod='dmuadminrecherche'}
                        </span>
                    </label>
                    <div class="col-sm-10">
                        {include
                            file="./textarea_lang.tpl"
                            languages=$languages
                            input_name='description_desc'
                            class="autoload_rte"
                            input_value=$product->description
                            product=$product}
                    </div>
                </div>
                <div class="form-group">
                    <label for="tags_desc_{$product->id|intval}" class="control-label col-sm-2">
                        <span class="label-tooltip" data-toggle="tooltip"
                              title="{l s='Will be displayed in the tags block when enabled. Tags help customers easily find your products.' mod='dmuadminrecherche'}">
                            {l s='Tags' mod='dmuadminrecherche'}
                        </span>
                    </label>
                    <div class="col-sm-10">
                        {if $languages|count > 1}
                        <div class="row">
                            {/if}
                            {foreach from=$languages item=language}
                            {literal}
                                <script type="text/javascript">
                                    $().ready(function () {
                                        var input_id = '{/literal}tags_desc_{$product->id|intval}_{$language.id_lang|intval}{literal}';
                                        $('#'+input_id).tagify({delimiters: [13,44], addTagPrompt: '{/literal}{l s='Add tag' js=1 mod='dmuadminrecherche'}{literal}'});
                                        $({/literal}'#{$table|escape:'html':'UTF-8'}{literal}_form').submit( function() {
                                            $(this).find('#'+input_id).val($('#'+input_id).tagify('serialize'));
                                        });
                                    });
                                </script>
                            {/literal}
                            {if $languages|count > 1}
                                <div class="translatable-field lang-{$language.id_lang|intval}">
                                    <div class="col-lg-9">
                                        {/if}
                                        <input type="text" id="tags_desc_{$product->id|intval}_{$language.id_lang|intval}" class="tagify updateCurrentText" name="tags_{$product->id|intval}_{$language.id_lang|intval}" value="{$product->getTags($language.id_lang, true)|htmlentitiesUTF8}" />
                                        {if $languages|count > 1}
                                    </div>
                                    <div class="col-lg-2">
                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                            {$language.iso_code|escape:'html':'UTF-8'}
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            {foreach from=$languages item=language}
                                                <li>
                                                    <a href="javascript:hideOtherLanguage({$language.id_lang|intval});">{$language.name|escape:'html':'UTF-8'}</a>
                                                </li>
                                            {/foreach}
                                        </ul>
                                    </div>
                                </div>
                            {/if}
                            {/foreach}
                            {if $languages|count > 1}
                        </div>
                        {/if}
                    </div>
                    <div class="col-lg-10 col-lg-offset-2">
                        <div class="help-block">{l s='Each tag has to be followed by a comma. The following characters are forbidden: %s' sprintf=['!<>;?=+#"°{}_$%.'] mod='dmuadminrecherche'}</div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div id="error_descriptions_{$product->id|intval}" class="alert alert-danger" style="display: none;"></div>
    <button class="btn btn-default col-sm-12 bulk_ok" onclick="change_descriptions({$product->id|intval})">OK</button>
    <div class="col-sm-12 text-center popin_refresh">
        <i class="icon-refresh icon-spin icon-fw"></i>
    </div>
</div>
<script type="text/javascript">
    var iso = '{$iso_tiny_mce|escape:'html':'UTF-8'}';
    var pathCSS = '{$smarty.const._THEME_CSS_DIR_|escape:'html':'UTF-8'}';
    var ad = '{$ad|escape:'html':'UTF-8'}';

    hideOtherLanguage({$id_lang|intval});
</script>