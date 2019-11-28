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
<div id="popin_combinations_{$product->id|intval}" class="popin_dmu bootstrap">
    <script type="application/javascript">
        var msg_conf_assoc_img = '{l s='Are you sure?' js=1 mod='dmuadminrecherche'}';
        var msg_error_assoc_img = '{l s='You must select at least one image and combinations' js=1 mod='dmuadminrecherche'}';
        var msg_conf_delete_img = '{l s='Would you like to remove the images associated with the selected combinations?' js=1 mod='dmuadminrecherche'}';
        var msg_error_select_combination = '{l s='You must select at least one combination' js=1 mod='dmuadminrecherche'}';
        var msg_error_delete_combination = '{l s='It is not possible to delete a combination while it still has some quantities in the Advanced Stock Management. You must delete its stock first.' js=1 mod='dmuadminrecherche'}';
    </script>
    <h3>{l s='Combinations:' mod='dmuadminrecherche'} {$product->name|escape:'html':'UTF-8'} ({l s='ID:' mod='dmuadminrecherche'} {$product->id|intval})</h3>
    <div class="row">
        <div id="refresh_result_comb">
            <div id="col_refresh_result_comb">
                <i class="icon-refresh icon-spin icon-fw"></i>
            </div>
        </div>
        <div class="form-horizontal col-sm-12">
            {$list|escape:'quotes':'UTF-8'|replace:"\'":"'"}
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-12">
            {if $images|count}
                <ul id="id_image_attr" class="list-inline">
                    {foreach from=$images key=k item=image}
                        <li>
                            <input type="checkbox" name="id_image_attr[]" value="{$image.id_image|intval}" id="id_image_attr_{$image.id_image|intval}" />
                            <label for="id_image_attr_{$image.id_image|intval}">
                                <img class="img-thumbnail" src="{$image.img_src|escape:'html':'UTF-8'}" alt="{$image.legend|escape:'html':'UTF-8'}" title="{$image.legend|escape:'html':'UTF-8'}" />
                            </label>
                        </li>
                    {/foreach}
                </ul>
            {else}
                <div class="alert alert-warning">{l s='You must upload an image before you can select one for your combination.' mod='dmuadminrecherche'}</div>
            {/if}
        </div>
    </div>
    <div id="error_combinations_{$product->id|intval}" class="alert alert-danger" style="display: none;"></div>
    <div class="row">
        <button class="btn btn-default col-sm-4 col-sm-offset-1 bulk_ok" onclick="ajax_assoc_images_combinations({$product->id|intval})">{l s='Associate the combinations with the selected images' mod='dmuadminrecherche'}</button>
        <button class="btn btn-default col-sm-4 col-sm-offset-2 bulk_ok" onclick="ajax_delete_images_combinations({$product->id|intval})">{l s='Delete images of the selected combinations' mod='dmuadminrecherche'}</button>
    </div>
    <br>
    <div>
        <p>
            {l s='The row in blue is the default combination.' mod='dmuadminrecherche'}<br>
            {l s='A default combination must be designated for each product.' mod='dmuadminrecherche'}<br>
            {if $advanced_stock_management && $warehouse_errors}
                {foreach $warehouse_errors as $error}
                    {$error|escape:'quotes':'UTF-8'|replace:"\'":"'"}
                {/foreach}
            {/if}
        </p>
    </div>
</div>