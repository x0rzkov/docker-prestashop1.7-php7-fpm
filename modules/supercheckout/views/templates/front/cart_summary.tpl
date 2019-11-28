<script type="text/javascript">
    var subtotal_msg = "{l s='Subtotal' mod='supercheckout'}";
    var shipping_msg = "{l s='Shipping' mod='supercheckout'}";
    var taxex_msg = "{l s='Taxes' mod='supercheckout'}";
</script>
<div class="velsof_sc_overlay"></div>
<div class="table-responsive">
    <table class="supercheckout-summary table table-bordered shoppingTable">
        <!-- <thead>
             <tr>                                
                 <th style="display:{if $logged}{if $settings['cart_options']['product_name']['logged']['display'] eq 1}{else}none{/if}{else}{if $settings['cart_options']['product_name']['guest']['display'] eq 1}{else}none{/if}{/if};" class="supercheckout-name">{l s='Description' mod='supercheckout'}</th>
                 <th style="display:{if $logged}{if $settings['cart_options']['product_model']['logged']['display'] eq 1}{else}none{/if}{else}{if $settings['cart_options']['product_model']['guest']['display'] eq 1}{else}none{/if}{/if};" class="supercheckout-model">{l s='Model' mod='supercheckout'}</th>
        {*start-MK made changes to display availability column*}
        {if $PS_STOCK_MANAGEMENT}
        <th style="display:{if $logged}{if $settings['cart_options']['product_availability']['logged']['display'] eq 1}{else}none{/if}{else}{if $settings['cart_options']['product_availability']['guest']['display'] eq 1}{else}none{/if}{/if};" class="supercheckout-qty" style="text-align:center;">{l s='Availability' mod='supercheckout'}</th>
        {/if}
        {*End-MK made changes to display availability column*}
        <th style="display:{if $logged}{if $settings['cart_options']['product_qty']['logged']['display'] eq 1}{else}none{/if}{else}{if $settings['cart_options']['product_qty']['guest']['display'] eq 1}{else}none{/if}{/if};" class="supercheckout-qty" style="text-align:center;">{l s='Qty' mod='supercheckout'}</th>
        <th style="display:{if $logged}{if $settings['cart_options']['product_price']['logged']['display'] eq 1}{else}none{/if}{else}{if $settings['cart_options']['product_price']['guest']['display'] eq 1}{else}none{/if}{/if};" class="supercheckout-total">{l s='Price' mod='supercheckout'}</th>
        <th style="display:{if $logged}{if $settings['cart_options']['product_total']['logged']['display'] eq 1}{else}none{/if}{else}{if $settings['cart_options']['product_total']['guest']['display'] eq 1}{else}none{/if}{/if};" class="supercheckout-total">{l s='Total' mod='supercheckout'}</th>
        <th class="supercheckout-qty"></th>
    </tr>
</thead> -->
        <tbody>
            {assign var='image_display' value=0}
            {assign var='odd' value=0}
            {assign var='have_non_virtual_products' value=false}

            {foreach $products as $product}
                {if $product.is_virtual == 0}
                    {assign var='have_non_virtual_products' value=true}
                {/if}
                {assign var='productId' value=$product.id_product}
                {assign var='product_url' value=$link->getProductLink($product.id_product, $product.link_rewrite, $product.category, null, null, $product.id_shop, $product.id_product_attribute)}
                {assign var='productAttributeId' value=$product.id_product_attribute}
                {assign var='odd' value=($odd+1)%2}

                <tr id="product_{$product.id_product|intval}_{$product.id_product_attribute|intval}_{$product.id_address_delivery|intval}_{$product.id_customization|intval}">
                    {if $logged}
                        {$image_display = $settings['cart_options']['product_image']['logged']['display']}
                    {else}
                        {$image_display = $settings['cart_options']['product_image']['guest']['display']}
                    {/if}
                    {if $image_display eq 1}
                        <td>
                            <img class="product_img" width='{$settings['cart_image_size']['width']}' height='{$settings['cart_image_size']['height']}' src="{$product.cover.bySize.large_default.url|escape:'quotes'}" alt='{$product.name}' onclick="showEnlargedImage(this)"/>
                        </td>
                    {/if}
                    <td class="supercheckout-name text-left td-product" style="display:{if $logged}{if $settings['cart_options']['product_name']['logged']['display'] eq 1}{else}none{/if}{else}{if $settings['cart_options']['product_name']['guest']['display'] eq 1}{else}none{/if}{/if};">
                        {if $image_display eq 1}
                            <a href="{$product_url|escape:'quotes'}">{$product.name}</a>
                        {else}
                            <a data-toggle="popover" data-title="{$product.name}" data-content="<img width='{$settings['cart_image_size']['width']}' height='{$settings['cart_image_size']['height']}' src='{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')}' alt='{$product.name}' onclick='showEnlargedImage(this)' />" data-placement="right" href="{$product_url|escape:'quotes'}">{$product.name}</a>
                        {/if}

                        {if isset($product.attributes) && count($product.attributes) > 0}
                            {foreach from=$product.attributes key="attribute" item="value"}
                                <p> <b>{$attribute}: </b>{$value}</p>
                            {/foreach}
                        {/if}
                        <p style="display:{if $logged}{if $settings['cart_options']['product_model']['logged']['display'] eq 1}{else}none{/if}{else}{if $settings['cart_options']['product_model']['guest']['display'] eq 1}{else}none{/if}{/if};" class="supercheckout-model"><b>{l s='Model' mod='supercheckout'}:</b> {if $product.reference}{$product.reference}{/if}</p>
                        {if $PS_STOCK_MANAGEMENT}
                            <p style="display:{if $logged}{if $settings['cart_options']['product_availability']['logged']['display'] eq 1}{else}none{/if}{else}{if $settings['cart_options']['product_availability']['guest']['display'] eq 1}{else}none{/if}{/if};"><b>{l s='Availability' mod='supercheckout'}:</b> <span class="badge badge-success">
                                    {if $product.quantity_available <= 0}
                                        <span class="kblabel{if $product.quantity_available <= 0 && isset($product.allow_oosp) && !$product.allow_oosp} label-danger{elseif $product.quantity_available <= 0} label-warning{else} label-success{/if}">
                                            {if isset($product.allow_oosp) && $product.allow_oosp}
                                                {if isset($product.available_later) && $product.available_later}{$product.available_later}
                                                {else}
                                                    {l s='In Stock' mod='supercheckout'}
                                                {/if}

                                            {else}
                                                {l s='Out of stock' mod='supercheckout'}
                                            {/if}
                                        </span>
                                    {else}

                                        {if isset($product.available_now) && $product.available_now}
                                            <span class="kblabel label-success">{l s='In Stock' mod='supercheckout'}</span>
                                        {else if $product.stock_quantity < $product.quantity}
                                            <span class="kblabel label-danger">{l s='Out of stock' mod='supercheckout'}</span>
                                        {else}
                                            <span class="kblabel label-success">{l s='In Stock' mod='supercheckout'}</span>
                                        {/if}
                                    {/if}
                                </span></p>
                            {/if}
                            {if $product.customizations|count}
                                {foreach from=$product.customizations item="customization"}
                                    {foreach from=$customization.fields item="field"}
                                        <p style="max-width: 268px;">
                                        <b>{$field.label}:</b>
                                        {if $field.type == 'text'}
                                            {if (int)$field.id_module}
                                                {$field.text nofilter}{*escape not required as contains html*}
                                            {else}
                                                {$field.text}
                                            {/if}
                                        {elseif $field.type == 'image'}
                                            <img src="{$field.image.large.url}" onclick="showEnlargedImage(this)" style="width: 29%;height: auto;">
                                        {/if}    
                                    </p>
                                {/foreach}
                            {/foreach}
                        {/if}
                    </td>
                    <td style="display:{if $logged}{if $settings['cart_options']['product_qty']['logged']['display'] eq 1}{else}none{/if}{else}{if $settings['cart_options']['product_qty']['guest']['display'] eq 1}{else}none{/if}{/if};" class="supercheckout-qty supercheckout-product-qty-input" >
                        <div class="input-group">
                            <input type="hidden" value="{$product.quantity|intval}" name="quantity_{$product.id_product|intval}_{$product.id_product_attribute|intval}_{$product.id_address_delivery|intval}_{$product.id_customization|intval}_hidden" />
                            {* changes by rishabh jain for min qty update *}
                            <input type='hidden' name="quantity_{$product.id_product|intval}_{$product.id_product_attribute|intval}_{$product.id_address_delivery|intval}_{$product.id_customization|intval}_minqty" value="{$product.minimal_quantity|intval}">
                            {* changes over *}
                            {if isset($settings['qty_update_option']) && $settings['qty_update_option'] eq 0 }
                                <span class="input-group-btn">
                                    <button type="button" class="cart_quantity_down qty-btn increase_button quantity-left-minus btn btn-primary btn-number" onclick="upQty('quantity_{$product.id_product|intval}_{$product.id_product_attribute|intval}_{$product.id_address_delivery|intval}_{$product.id_customization|intval}')"  data-type="minus" data-field="">
                                        <span class="fas fa-chevron-up"></span>
                                    </button>
                                </span>
                                <input min="1" max="100" autocomplete="off" type="text" id="quantity" class="form-control input-number quantitybox" name="quantity_{$product.id_product|intval}_{$product.id_product_attribute|intval}_{$product.id_address_delivery|intval}_{$product.id_customization|intval}" value="{$product.quantity|intval}">
                                <span class="input-group-btn">
                                    <button type="button" class="cart_quantity_down qty-btn decrease_button quantity-right-plus btn btn-primary btn-number" data-type="plus" data-field="" onclick="downQty('quantity_{$product.id_product|intval}_{$product.id_product_attribute|intval}_{$product.id_address_delivery|intval}_{$product.id_customization|intval}')">
                                        <span class="fas fa-chevron-down"></span>
                                    </button>
                                </span>
                            {else}
                                <input min="1" max="100" autocomplete="off" type="text" id="quantity" class="form-control input-number quantitybox" name="quantity_{$product.id_product|intval}_{$product.id_product_attribute|intval}_{$product.id_address_delivery|intval}_{$product.id_customization|intval}" value="{$product.quantity|intval}">
                                <a href="javascript:void(0)" id="demo_2_s" title="{l s='update quantity' mod='supercheckout'}" onclick="updateQtyByBtn('quantity_{$product.id_product|intval}_{$product.id_product_attribute|intval}_{$product.id_address_delivery|intval}_{$product.id_customization|intval}')" ><small>{l s='Update' mod='supercheckout'}</small></a>    
                                    {/if}
                        </div>
                    </td>											
                    <td style="display:{if $logged}{if $settings['cart_options']['product_price']['logged']['display'] eq 1}{else}none{/if}{else}{if $settings['cart_options']['product_price']['guest']['display'] eq 1}{else}none{/if}{/if};" class="supercheckout-unit-total text-right td-price">
                        <span class="price">{$product.price nofilter}{*escape not required as contains html*}</span>
                        {if $product.unit_price_full}
                            <div class="unit-price-cart">{$product.unit_price_full nofilter}{*escape not required as contains html*}</div>
                        {/if}</td>
                    <td id="total_product_price_{$product.id_product|intval}_{$product.id_product_attribute|intval}_{$product.id_address_delivery|intval}" style="display:{if $logged}{if $settings['cart_options']['product_total']['logged']['display'] eq 1}{else}none{/if}{else}{if $settings['cart_options']['product_total']['guest']['display'] eq 1}{else}none{/if}{/if};" class="supercheckout-total text-right td-total">
                        {$product.total nofilter}{*escape not required as contains html*}</td>	


                    <td class="supercheckout-qty" style="display:{if $logged}{if $settings['cart_options']['product_name']['logged']['display'] eq 1 || $settings['cart_options']['product_model']['logged']['display'] eq 1 || $settings['cart_options']['product_qty']['logged']['display'] eq 1 || $settings['cart_options']['product_price']['logged']['display'] eq 1 || $settings['cart_options']['product_total']['logged']['display'] eq 1}{else}none{/if}{else}{if $settings['cart_options']['product_name']['guest']['display'] eq 1 || $settings['cart_options']['product_model']['guest']['display'] eq 1 || $settings['cart_options']['product_qty']['guest']['display'] eq 1 || $settings['cart_options']['product_price']['guest']['display'] eq 1 || $settings['cart_options']['product_total']['guest']['display'] eq 1}{else}none{/if}{/if};">
                        <span id="{$product.id_product|intval}_{$product.id_product_attribute|intval}_{$product.id_address_delivery|intval}_{$product.id_customization|intval}" onclick="deleteProductFromCart(this.id);" class="removeProduct supercheckout-product-delete delete_product"><i class="fas fa-times"></i></span></td>

                </tr>
            {/foreach}
        </tbody>
    </table>
</div>
<table class="supercheckout-totals table table-bordered totalTable">

    <tfoot>
        {foreach from=$subtotals item="subtotal"}

            {if isset($subtotal.value) && $subtotal.value}
                {if $subtotal.type == 'products'}
                    <tr id="supercehckout_summary_total_{$subtotal.type}" style="display:{if $logged}{if $settings['order_total_option']['product_sub_total']['logged']['display'] eq 1}{else}none{/if}{else}{if $settings['order_total_option']['product_sub_total']['guest']['display'] eq 1}{else}none{/if}{/if};">
                    {else if $subtotal.type == 'shipping'}
                    <tr id="supercehckout_summary_total_{$subtotal.type}" style="display:{if $logged}{if $settings['order_total_option']['shipping_price']['logged']['display'] eq 1}{else}none{/if}{else}{if $settings['order_total_option']['shipping_price']['guest']['display'] eq 1}{else}none{/if}{/if};">
                    {else if $subtotal.type == 'tax'}
                    <tr id="supercehckout_summary_total_{$subtotal.type}" style="display:{if $logged}{if $settings['order_total_option']['total_tax']['logged']['display'] eq 1}{else}none{/if}{else}{if $settings['order_total_option']['total_tax']['guest']['display'] eq 1}{else}none{/if}{/if};">
                    {else}
                    <tr id="supercehckout_summary_total_{$subtotal.type}">
                    {/if}
                    <td colspan="5" class="text-right title"><strong>{l s=$subtotal.label mod='supercheckout'}: </strong></td>
                    <td class="value text-right"><span id="supercehckout_total_{$subtotal.type}_value" class="price">{$subtotal.value nofilter}{*escape not required as contains html*}</span></td>
                </tr>
            {/if}
        {/foreach}
    </tfoot>
</table>

<div class="custom-panel rewardsection">
    {if $vouchers.allowed}
        {foreach $vouchers.added as $voucher}
            <div style="margin-bottom: 1%;" id="cart_discount_{$voucher.id_cart_rule}" class="cart_discount text-right" style="display:{if $logged}{if $settings['order_total_option']['voucher']['logged']['display'] eq 1}{else}none{/if}{else}{if $settings['order_total_option']['voucher']['guest']['display'] eq 1}{else}none{/if}{/if};">

                <span style="float:left;"><b>{$voucher.name}</b></span><a href="javascript:void(0)" style="float: left;margin-left: 2%;" onclick="removeDiscount('{$voucher.id_cart_rule|intval}')"><div title="{l s='Redeem' mod='supercheckout'}" class="removeProduct"><i class="fas fa-trash"></i></div></a>
                <span class="price text-right">{$voucher.reduction_formatted nofilter}{*escape not required as contains html*}</span>                            
            </div>
        {/foreach}
        <div class="rewardHeader" style="display:{if $logged}{if $settings['order_total_option']['voucher']['logged']['display'] eq 1}{else}none{/if}{else}{if $settings['order_total_option']['voucher']['guest']['display'] eq 1}{else}none{/if}{/if};">
            <a href="javascript:void(0)" onclick="$('.rewardBody').toggle();">{l s='Have a promo code?' mod='supercheckout'} </a>
        </div>
        <div class="rewardBody" style="display:none">
            <!--h2>Coupon / Voucher / Reward</h2-->
            <div id="supercheckout_voucher_input_row" class="form-group form-coupon" style="display:{if $logged}{if $settings['order_total_option']['voucher']['logged']['display'] eq 1}{else}none{/if}{else}{if $settings['order_total_option']['voucher']['guest']['display'] eq 1}{else}none{/if}{/if};">
                <!--label for="input-coupon" class="control-label">Enter your coupon here</label-->
                <div class="input-group" id="voucher-form">
                    <input type="hidden" value="1" name="submitDiscount">
                    <input name="discount_name" id="discount_name" type="text" placeholder="{l s='Enter your coupon here' mod='supercheckout'}" class="voucherText form-control"> 
                    <span class="input-group-btn"><button id="button-coupon" onClick="callCoupon();" type="button" data-loading-text="Loading..." class="btn btn-primary orangebuttonapply" style="min-height: 33px;">{l s='Apply' mod='supercheckout'}</button>
                    </span>
                </div>								
            </div>
        </div>
    {else}
        <div id="supercheckout_voucher_input_row" style="display:none;"></div>
    {/if}
    <div class="totalAmount" style="display:{if $logged}{if $settings['order_total_option']['total']['logged']['display'] eq 1}{else}none{/if}{else}{if $settings['order_total_option']['total']['guest']['display'] eq 1}{else}none{/if}{/if};">
        <h3>
            {if $priceDisplay == 1}
                {l s='Total Amount' mod='supercheckout'} {l s='(Tax excl.)' mod='supercheckout'}:
                <span id="total_price" class="price amountMoney">{$totals.total.value nofilter}{*escape not required as contains html*}</span>
                <input type="hidden" id="total_price_wfee" value="{$totals.total.value}">{*escape not required as contains html*}</td>
            {else}
                {l s='Total Amount' mod='supercheckout'} {l s='(Tax incl.)' mod='supercheckout'}
                <span id="total_price" class="price amountMoney">{$totals.total.value nofilter}{*escape not required as contains html*}</span>
                <input type="hidden" id="total_price_wfee" value="{$totals.total.value}">{*escape not required as contains html*}
            {/if}
        </h3>
    </div><br>
</div>

{if isset($kb_free_shipping_percent)}
    <div class="" id="kb_cart_summary_free_shipping" style="display:none;">
                {if $hidden_amount == 0}
                    <h3>{l s='Congratulations!! You have reached the minumum amount limit to get Free Shipping.' mod='supercheckout'} </h3>
                {else}
                    <h3>{l s='Almost there, Add ' mod='supercheckout'} {$kb_free_shipping_amount} {l s=' more to get Free Shipping' mod='supercheckout'}</h3>
                {/if}
                <div class="progress red">
                    <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar"
                         aria-valuenow="{$kb_free_shipping_percent}" aria-valuemin="0" aria-valuemax="100" style="width:{$kb_free_shipping_percent}%">
                        {$kb_free_shipping_percent}% {l s='Complete (success) ' mod='supercheckout'} 
                    </div>
                </div>
        </div>
{/if}

<div id="highlighted_cart_rules">
    {if count($other_available_vouchers) > 0}
        <p id="title" class="title-offers" style="font-weight: 600;color: black!important;">{l s='Take advantage of our exclusive offers' mod='supercheckout'}:</p>
        <div id="display_cart_vouchers">
            {foreach $other_available_vouchers as $voucher}
                {if $voucher.code != ''}<span onclick="$('#discount_name').val('{$voucher.code}');
                        return false;" class="voucher_name" data-code="{$voucher.code}">{$voucher.code}</span> - {/if}{$voucher.name}<br />
                    {/foreach}
                    </div>
                    {/if}
                    </div>

                    <!-- INSERT INTO #CART BLOCK -->
                    <!-- Start - Code to insert custom fields in cart block -->
                    <div class="div_custom_fields">
                        {foreach from=$array_fields item=field}
                            {if $field['position'] eq 'cart_block'}
                                <div class="supercheckout-blocks form-group">
                                    {if $field['type'] eq "textbox"}
                                        <label class="cursor_help" title="{$field['field_help_text']}">{$field['field_label']}{if $field['required'] eq "1"}<span style="display:inline;" class="supercheckout-required">*</span>{/if}</label>
                                        <input type="text" name="custom_fields[field_{$field['id_velsof_supercheckout_custom_fields']}]" value="{$field['default_value']}" class="supercheckout-large-field width_100 form-control">
                                        <span id="error_field_{$field['id_velsof_supercheckout_custom_fields']}" class="errorsmall_custom hidden_custom"></span>
                                    {/if}

                                    {if $field['type'] eq "textarea"}
                                        <label class="cursor_help" title="{$field['field_help_text']}">{$field['field_label']}{if $field['required'] eq "1"}<span style="display:inline;" class="supercheckout-required">*</span>{/if}</label>
                                        <textarea name="custom_fields[field_{$field['id_velsof_supercheckout_custom_fields']}]" class="supercheckout-large-field width_100 form-control" style="width: 100%; height: 100px;">{$field['default_value']}</textarea>
                                        <span id="error_field_{$field['id_velsof_supercheckout_custom_fields']}" class="errorsmall_custom hidden_custom"></span>
                                    {/if}

                                    {if $field['type'] eq "selectbox"}
                                        <label class="cursor_help" title="{$field['field_help_text']}">{$field['field_label']}{if $field['required'] eq "1"}<span style="display:inline;" class="supercheckout-required">*</span>{/if}</label>
                                        <select name="custom_fields[field_{$field['id_velsof_supercheckout_custom_fields']}]" class="supercheckout-large-field width_100 form-control">
                                            {foreach from=$field['options'] item=field_options}
                                                <option {if $field_options['default_value'] eq $field_options['option_value']}selected{/if} value="{$field_options['option_value']}">{$field_options['option_label']}</option>
                                            {/foreach}
                                        </select>
                                        <span id="error_field_{$field['id_velsof_supercheckout_custom_fields']}" class="errorsmall_custom hidden_custom"></span>
                                    {/if}

                                    {if $field['type'] eq "radio"}
                                        <label class="cursor_help" title="{$field['field_help_text']}">{$field['field_label']}{if $field['required'] eq "1"}<span style="display:inline;" class="supercheckout-required">*</span>{/if}</label>
                                        {assign var=radio_counter value=1}
                                        {foreach from=$field['options'] item=field_options}
                                            <div class="supercheckout-extra-wrap">
                                                <div class="radio" id="uniform-field_{$field['id_velsof_supercheckout_custom_fields']}"><span>
                                                        <input type="radio" name="custom_fields[field_{$field['id_velsof_supercheckout_custom_fields']}]" value="{$field_options['option_value']}" {if $field_options['default_value'] eq $field_options['option_value']}checked{/if}>
                                                        <label for="field_{$field['id_velsof_supercheckout_custom_fields']}">{$field_options['option_label']}</label>
                                                    </span></div>
                                                
                                            </div>
                                            {assign var=radio_counter value=$radio_counter+1}
                                        {/foreach}
                                        <span id="error_field_{$field['id_velsof_supercheckout_custom_fields']}" class="errorsmall_custom hidden_custom"></span>
                                    {/if}

                                    {if $field['type'] eq "checkbox"}
                                        <label class="cursor_help" title="{$field['field_help_text']}">{$field['field_label']}{if $field['required'] eq "1"}<span style="display:inline;" class="supercheckout-required">*</span>{/if}</label>
                                        {foreach from=$field['options'] item=field_options}
                                            <div class="input-box input-field_{$field['id_velsof_supercheckout_custom_fields']}">
                                                <div class="checker checkbox" id="uniform-field_{$field['id_velsof_supercheckout_custom_fields']}">
                                                    <span class="checked">
                                                        <input {if $field_options['default_value'] eq $field_options['option_value']}checked{/if} type="checkbox" name="custom_fields[field_{$field['id_velsof_supercheckout_custom_fields']}][]" value="{$field_options['option_value']}">
                                                        <label for="field_{$field['id_velsof_supercheckout_custom_fields']}"><b>{$field_options['option_label']}</b></label>
                                                    </span>
                                                </div>
                                                
                                            </div>
                                        {/foreach}
                                        <span id="error_field_{$field['id_velsof_supercheckout_custom_fields']}" class="errorsmall_custom hidden_custom"></span>
                                    {/if}

                                    {* Start: Code added by Anshul for date field *}
                                    {if $field['type'] eq "date"}                         
                                        <label class="cursor_help" title="{$field['field_help_text']}">{$field['field_label']}{if $field['required'] eq "1"}<span style="display:inline;" class="supercheckout-required">*</span>{/if}</label>
                                        <input style="position: relative;" type="text" id="" name="custom_fields[field_{$field['id_velsof_supercheckout_custom_fields']}]" value="{$field['default_value']}" class="supercheckout-large-field width_100 kb_sc_custom_field_date form-control" readonly="true">
                                        <span id="error_field_{$field['id_velsof_supercheckout_custom_fields']}" class="errorsmall_custom hidden_custom"></span>
                                        {if isset($field['validation_type']) && $field['validation_type'] == 'isDate'}
                                            <span style="color:#999999">
                                                {l s='Date format is Y-m-d' mod='supercheckout'}
                                            </span>
                                        {/if}
                                    {/if}
                                    {* Code added by Anshul for date field *}

                                    {* Start: Code added by Anshul for file field *}
                                    {if $field['type'] eq "file"}                         
                                        <label class="cursor_help" title="{$field['field_help_text']}">{$field['field_label']}{if $field['required'] eq "1"}<span style="display:inline;" class="supercheckout-required">*</span>{/if}</label>
                                        <input type="file" data-buttonText="{l s='Choose file' mod='supercheckout'}" id="kb_sc_custom_field_file_{$field['id_velsof_supercheckout_custom_fields']}" name="custom_fields[field_{$field['id_velsof_supercheckout_custom_fields']}]" value="{$field['default_value']}" class="supercheckout-large-field width_100 kbfiletype form-control">
                                        <span id="error_field_{$field['id_velsof_supercheckout_custom_fields']}" class="errorsmall_custom hidden_custom"></span>
                                        {if isset($field['validation_type']) && $field['validation_type'] == 'isFile'}
                                            <span style="color:#999999">
                                                {l s='Supported file formats are PDF, JPEG, PNG, DOCX, CSV & GIF.' mod='supercheckout'}
                                            </span>
                                        {/if}
                                    {/if}
                                    {* Code added by Anshul for file field *}
                                </div>
                            {/if}
                        {/foreach}
                    </div>
                    <!-- End - Code to insert custom fields in registration form block -->

                    {*
                    * DISCLAIMER
                    *
                    * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
                    * versions in the future. If you wish to customize PrestaShop for your
                    * needs please refer tohttp://www.prestashop.com for more information.
                    * We offer the best and most useful modules PrestaShop and modifications for your online store.
                    *
                    * @category  PrestaShop Module
                    * @author    knowband.com <support@knowband.com>
                    * @copyright 2016 Knowband
                    *}