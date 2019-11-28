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
<div id="popin_seo_{$product->id|intval}" class="popin_dmu bootstrap">
    <form action="" id="form_seo_{$product->id|intval}">
        <input type="hidden" name="seo_id_product" id="seo_id_product" value="{$product->id|intval}">
        <h3>{l s='SEO:' mod='dmuadminrecherche'} {$product->name[$id_lang]|escape:'html':'UTF-8'} ({l s='ID:' mod='dmuadminrecherche'} {$product->id|intval})</h3>
        <div class="row">
            <div class="form-horizontal col-sm-12">
                <div class="form-group">
                    <label for="meta_title_seo_{$product->id|intval}" class="control-label col-sm-2">
                        {l s='Meta title' mod='dmuadminrecherche'}
                    </label>
                    <div class="col-sm-10">
                        {include file="./input_text_lang.tpl"
                        languages=$languages
                        input_name='meta_title_seo'
                        input_value=$product->meta_title
                        maxchar=70
                        }
                    </div>
                </div>
                <div class="form-group">
                    <label for="meta_description_seo_{$product->id|intval}" class="control-label col-sm-2">
                        {l s='Meta description' mod='dmuadminrecherche'}
                    </label>
                    <div class="col-sm-10">
                        {include file="./textarea_lang.tpl"
                        languages=$languages
                        input_name='meta_description_seo'
                        input_value=$product->meta_description
                        maxchar=160
                        }
                    </div>
                </div>
                <div class="form-group">
                    <label for="link_rewrite_seo_{$product->id|intval}" class="control-label col-sm-2">
                        {l s='Friendly URL' mod='dmuadminrecherche'}
                    </label>
                    <div class="col-sm-10">
                        {include file="./input_text_lang.tpl"
                        languages=$languages
                        input_value=$product->link_rewrite
                        input_name='link_rewrite_seo'}
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div id="error_seo_{$product->id|intval}" class="alert alert-danger" style="display: none;"></div>
    <button class="btn btn-default col-sm-12 bulk_ok" onclick="change_seo({$product->id|intval})">OK</button>
    <div class="col-sm-12 text-center popin_refresh">
        <i class="icon-refresh icon-spin icon-fw"></i>
    </div>
</div>
<script type="text/javascript">
    hideOtherLanguage({$id_lang|intval});
</script>