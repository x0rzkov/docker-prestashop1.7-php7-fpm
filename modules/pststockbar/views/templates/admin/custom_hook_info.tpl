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

<div id="psb-hook-instructions" class="ps{$psvd|intval}">
    {if $psv == 1.5}
        <br/><fieldset><legend>{l s='Additional instructions' mod='pststockbar'} (<a href="#" class="psb-ins-toggle" data-alt="{l s='hide' mod='pststockbar'}">{l s='show' mod='pststockbar'}</a>)</legend>
    {else}
        <div class="panel">
            <div class="panel-heading">
                <i class="icon-cogs"></i> {l s='Additional instructions' mod='pststockbar'}
                (<a href="#" class="psb-ins-toggle" data-alt="{l s='hide' mod='pststockbar'}">{l s='show' mod='pststockbar'}</a>)
            </div>
    {/if}

            <div id="psb-ins-wrp" style="display: none;">
                <p>
                    {l s='You can use a custom hook to insert the stock indicator into some specific place in your template.' mod='pststockbar'}
                    <b>{l s='For example, it is useful when none of existing hooks in your theme suits your needs.' mod='pststockbar'}</b>
                </p>

                <h4>{l s='Adding a custom hook to the product page:' mod='pststockbar'}</h4>
                <ol>
                    <li>{l s='Change the option "Position at the product page" to "-- (custom hook only) --".' mod='pststockbar'}</li>
                    <li>
                        {l s='Locate your template file.' mod='pststockbar'}
                        {l s='Its location may depend on your theme, but usually you need to edit this file:' mod='pststockbar'}
                        {if $psvd == 15 || $psvd == 16}
                            <b>/themes/{$psb_context->shop->theme_name|escape:'html':'UTF-8'}/product.tpl</b>
                        {elseif $psvd == 17}
                            <b>/themes/{$psb_context->shop->theme_name|escape:'html':'UTF-8'}/templates/catalog/_partials/product-add-to-cart.tpl</b> {l s='(or another suitable file in that directory)' mod='pststockbar'}
                        {/if}
                    </li>
                    <li>{l s='Make a backup of that file. It will allow you to revert all changes, especially if something goes wrong.' mod='pststockbar'}</li>
                    <li>
                        {l s='Find the needed place in that template and insert there the following code:' mod='pststockbar'}
                        <b>{literal}{hook h='pstStockBar' id_product=$product->id}{/literal}</b>
                    </li>
                    <li>{l s='Save the changes and clear the PrestaShop cache.' mod='pststockbar'}</li>
                    <li>{l s='All done, now you can view the product page and see the result.' mod='pststockbar'}</li>
                </ol>

                <hr>

                <h4>{l s='Adding a custom hook to the product list:' mod='pststockbar'}</h4>
                <ol>
                    <li>{l s='Change the option "Position in the product list" to "-- (custom hook only) --".' mod='pststockbar'}</li>
                    <li>
                        {l s='Locate your template file.' mod='pststockbar'}
                        {l s='Its location may depend on your theme, but usually you need to edit this file:' mod='pststockbar'}
                        {if $psvd == 15 || $psvd == 16}
                            <b>/themes/{$psb_context->shop->theme_name|escape:'html':'UTF-8'}/product-list.tpl</b>
                        {elseif $psvd == 17}
                            <b>/themes/{$psb_context->shop->theme_name|escape:'html':'UTF-8'}/templates/catalog/_partials/miniatures/product.tpl</b>
                        {/if}
                    </li>
                    <li>{l s='Make a backup of that file. It will allow you to revert all changes, especially if something goes wrong.' mod='pststockbar'}</li>
                    <li>
                        {l s='Find the needed place in that template and insert there the following code:' mod='pststockbar'}
                        <b>{literal}{hook h='pstStockBar' id_product=$product.id_product psb_list=true}{/literal}</b>
                    </li>
                    <li>{l s='Save the changes and clear the PrestaShop cache.' mod='pststockbar'}</li>
                    <li>{l s='All done, now you can view the result.' mod='pststockbar'}</li>
                </ol>
            </div>

    {if $psv == 1.5}
        </fieldset><br/>
    {else}
        </div>
    {/if}
</div>
