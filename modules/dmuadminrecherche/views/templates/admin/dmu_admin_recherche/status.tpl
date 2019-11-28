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
<div id="popin_status_{$id_product|intval}" class="popin_dmu bootstrap">
    <h3>{l s='Change status:' mod='dmuadminrecherche'} {$product_name|escape:'html':'UTF-8'} ({l s='ID:' mod='dmuadminrecherche'} {$id_product|intval})</h3>
    <div class="row">
        <div class="form-horizontal col-sm-12">
            <div class="form-group">
                <label for="id_status_{$id_product|intval}" class="control-label col-sm-4">
                    {l s='Status:' mod='dmuadminrecherche'}
                </label>
                <div class="col-sm-8">
                    <select name="id_status" id="id_status_{$id_product|intval}">
                        <option value="1"{if $status == 1} selected="selected"{/if}>{l s='Neutral' mod='dmuadminrecherche'}</option>
                        <option value="2"{if $status == 2} selected="selected"{/if}>{l s='Green' mod='dmuadminrecherche'}</option>
                        <option value="3"{if $status == 3} selected="selected"{/if}>{l s='Red' mod='dmuadminrecherche'}</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="comment_status_{$id_product|intval}" class="control-label col-sm-4">
                    {l s='Comment:' mod='dmuadminrecherche'}
                </label>
                <div class="col-sm-8">
                    <textarea name="comment_status" id="comment_status_{$id_product|intval}" rows="4" cols="30" class="rte">{$comment|htmlentities}</textarea>
                </div>
            </div>
        </div>
    </div>
    <button class="btn btn-default col-sm-12 bulk_ok" onclick="change_status({$id_product|intval})">OK</button>
    <div class="col-sm-12 text-center popin_refresh">
        <i class="icon-refresh icon-spin icon-fw"></i>
    </div>
</div>