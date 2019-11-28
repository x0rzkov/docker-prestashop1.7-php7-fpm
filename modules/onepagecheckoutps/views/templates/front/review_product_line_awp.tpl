{*
    * We offer the best and most useful modules PrestaShop and modifications for your online store.
    *
    * We are experts and professionals in PrestaShop
    *
    * @author    PresTeamShop.com <support@presteamshop.com>
    * @copyright 2011-2019 PresTeamShop
    * @license   see file: LICENSE.txt
    * @category  PrestaShop
    * @category  Module
*}

{assign var="product_link" value=$link->getProductLink($product.id_product, $product.link_rewrite, $product.category, null, null, $product.id_shop, $product.id_product_attribute)|escape:'htmlall':'UTF-8'}
{if isset($awp_url_rewrite) and $awp_url_rewrite}
	{assign var="awp_product_link" value="?"}
{else}
	{assign var="awp_product_link" value="&"}
{/if}
{assign var="awp_product_link" value=$awp_product_link|cat:'ipa='|cat:$product.id_product_attribute|cat:'&ins='|cat:$product.instructions_valid}
{if $product_link|strpos:'#' > 0}
	{assign var='amp_pos' value=$product_link|strpos:'#'}
	{assign var='product_link' value=$product_link|substr:0:$amp_pos}
{/if}
{assign var='product_link' value=$product_link|cat:$awp_product_link}
<script type="text/javascript">
    if (typeof awpProducts == 'undefined')
        var awpProducts = new Array();
    awpProducts['{$product.id_product}_{$product.id_product_attribute}'] = '{$product.instructions_valid}';
</script>

<div class="row {if isset($productLast) and $productLast && (not isset($ignoreProductLast) or !$ignoreProductLast)}last_item{elseif isset($productFirst) and $productFirst}first_item{/if} {if isset($customizedDatas.$productId.$productAttributeId) AND $quantityDisplayed == 0}alternate_item{/if} cart_item address_{$product.id_address_delivery|intval}"
     id="product_{$product.id_product|intval}_{$product.id_product_attribute|intval}_0_{$product.id_address_delivery|intval}{if !empty($product.gift)}_gift{/if}">
    <div class="col-md-1 col-xs-3 col-3 text-md-center image_product">
        <a href="{$product.url}" title="{$product.name}">
            <img class="img-fluid media-object" src="{$product.cover.small.url}" alt="{$product.name}">
        </a>
        {if $CONFIGS.OPC_SHOW_ZOOM_IMAGE_PRODUCT}
            <div class="image_zoom">
                <img class="media-object" src="{$product.cover.medium.url}" alt="{$product.name}">
            </div>
        {/if}
    </div>
    <div class="col-md-{if $CONFIGS.OPC_SHOW_UNIT_PRICE}4{else}6{/if} col-xs-9 col-9 cart_description">
        <p class="s_title_block">
            {if !$CONFIGS.OPC_REMOVE_LINK_PRODUCTS}
                <a href="{$product.url}" title="{$product.name}">
            {/if}
                {$product.name}
                {if $product.reference and $CONFIGS.OPC_SHOW_REFERENCE}
                    <span class="product_reference">
                        ({l s='Ref.' mod='onepagecheckoutps'}&nbsp;{$product.reference})
                    </span>
                {/if}
            {if !$CONFIGS.OPC_REMOVE_LINK_PRODUCTS}
                </a>
            {/if}
        </p>
        {if isset($product.attributes) && $product.attributes}
            <span class="product_attributes">
                {foreach from=$product.attributes key="attribute" item="value"}
                    <div class="product-line-info">
                        <span class="">{$attribute}:</span>
                        <span class="">{$value}</span>
                    </div>
                {/foreach}
            </span>
        {/if}

        {if isset($product.productmega)}
            {if isset($mega.extraAttrLong)}{$mega.extraAttrLong nofilter}{/if}
            <br/>
            <strong>{$mega.measure}</strong>
            {if isset($mega.personalization) && $mega.personalization neq ''}
                <br/><div class="mp-personalization">{$mega.personalization nofilter}</div>
            {/if}
        {/if}

        {if isset($product.instructions) && $product.instructions}
            <a href="{$product_link}">{$product.instructions nofilter}</a>
        {/if}

        {if $product.weight neq 0 and $CONFIGS.OPC_SHOW_WEIGHT}
            <span class="product_weight">
                <span>{l s='Weight' mod='onepagecheckoutps'}&nbsp;:&nbsp;</span>
                {$product.weight|string_format:"%.3f"|escape:'htmlall':'UTF-8'}{$PS_WEIGHT_UNIT}
            </span>
        {/if}

        {if $ps_stock_management and $CONFIGS.OPC_SHOW_AVAILABILITY}
            <div class="cart_avail">
                {*<span class="badge {if $product.availability == 'available'}badge-success product-available{elseif $product.availability == 'last_remaining_items'}badge-warning product-last-items{else}badge-danger product-unavailable{/if}">*}
                <span class="badge {if $product.quantity_available <= 0 || $product.cart_quantity > $product.quantity_available}badge-danger product-unavailable{else}badge-success product-available{/if}">
                    {if $product.quantity_available <= 0 || $product.cart_quantity > $product.quantity_available}
                        {if (isset($product.available_later) && $product.available_later) || (isset($PS_LABEL_OOS_PRODUCTS_BOA) && $PS_LABEL_OOS_PRODUCTS_BOA)}
                            {if isset($product.available_later) && $product.available_later}
                                {$product.available_later}
                            {else}
                                {$PS_LABEL_OOS_PRODUCTS_BOA}
                            {/if}
                        {else}
                            {$product.availability_message}
                        {/if}
                    {else}
                        {if $product.quantity > $product.quantity_available}
                            {if isset($product.available_later) && $product.available_later}
                                {$product.available_later}
                            {else}
                                {$product.availability_message}
                            {/if}
                        {else}
                            {if isset($product.available_now) && $product.available_now}
                                {$product.available_now}
                            {else}
                                {$product.availability_message}
                            {/if}
                        {/if}
                    {/if}
                </span>
                {if $CONFIGS.OPC_SHOW_DELIVERY_TIME && !empty($product.delivery_information_opc)}
                    <br/>
                    <span class="delivery-information">{$product.delivery_information_opc}</span>
                {/if}
                {if !$product.is_virtual}{hook h="displayProductDeliveryTime" product=$product}{/if}
            </div>
        {/if}
    </div>

    <div class="hidden-sm-up row clear"></div>

    {if $CONFIGS.OPC_SHOW_UNIT_PRICE}
        <div class="col-xs-3 col-3 text-md-right hidden-sm-up">
            <label><b>{l s='Unit price' mod='onepagecheckoutps'}:</b></label>
        </div>
        <div class="col-md-3 col-xs-9 col-9 text-md-center text-xs-left text-sm-left">
            <span class="" id="product_price_7_34_0">
                <span class="price special-price">{if isset($product.productmega)}{$mega.spricewt}{else}{$product.price}{/if}</span>
                <br>
                {if $product.price neq $product.regular_price}
                    <span class="old-price">{$product.regular_price}</span>
                {/if}
                {if $product.discount_type eq 'amount' and $product.discount_amount neq ''}
                    <span class="price-percent-reduction small">({$product.discount_amount})</span>
                {elseif $product.discount_type eq 'percentage' and $product.discount_percentage neq ''}
                    <span class="price-percent-reduction small">({$product.discount_percentage})</span>
                {/if}
            </span>
            {if $product.unit_price_full}
                <div class="unit-price-cart">{$product.unit_price_full}</div>
            {/if}
        </div>
    {/if}

    <div class="hidden-sm-up row clear"></div>

    <div class="col-xs-3 col-3 text-md-right hidden-sm-up">
        <label><b>{l s='Quantity' mod='onepagecheckoutps'}:</b></label>
    </div>
    <div class="col-md-2 col-xs-9 col-9 text-md-center">
        <div class="input-group bootstrap-touchspin">
            <span class="input-group-addon bootstrap-touchspin-prefix" style="display: none;"></span>
            <input
                class="cart-line-product-quantity"
                data-down-url="{$product.down_quantity_url}&special_instructions={$product.instructions_valid}&special_instructions_id={$product.instructions_id}{if isset($product.productmega)}&id_megacart={$mega.id_megacart}{/if}"
                data-up-url="{$product.up_quantity_url}&special_instructions={$product.instructions_valid}&special_instructions_id={$product.instructions_id}{if isset($product.productmega)}&id_megacart={$mega.id_megacart}{/if}"
                data-update-url="{$product.update_quantity_url}&special_instructions={$product.instructions_valid}&special_instructions_id={$product.instructions_id}{if isset($product.productmega)}&id_megacart={$mega.id_megacart}{/if}"
                data-product-id="{$product.id_product}"
                awp-data-product-attribute-id="{$product.id_product_attribute}"
                {if isset($product.productmega)}data-mega-id="{$mega.id_megacart}"{/if}
                type="text"
                value="{if isset($product.productmega)}{$mega.quantity}{else}{$product.quantity}{/if}"
                name="product-quantity-spin"
                min="{$product.minimal_quantity}"
              />
            <span class="input-group-addon bootstrap-touchspin-postfix" style="display: none;"></span>
            <span class="input-group-btn-vertical">
                <button class="btn btn-touchspin js-touchspin bootstrap-touchspin-up" type="button">
                    <i class="fa-pts fa-pts-chevron-up"></i>
                </button>
                <button class="btn btn-touchspin js-touchspin bootstrap-touchspin-down" type="button">
                    <i class="fa-pts fa-pts-chevron-down"></i>
                </button>
            </span>
            <a
                class                       = "remove-from-cart"
                rel                         = "nofollow"
                href                        = "{$product.remove_from_cart_url}&special_instructions={$product.instructions_valid}{if isset($product.productmega)}&id_megacart={$mega.id_megacart}{/if}"
                data-link-action            = "delete-from-cart"
                data-id-product             = "{$product.id_product|escape:'javascript'}"
                data-id-product-attribute   = "{$product.id_product_attribute|escape:'javascript'}_{$product.instructions_valid|escape:'javascript'}"
                data-id-customization   	  = "{$product.id_customization|escape:'javascript'}"
            >
                <i class="fa-pts fa-pts-trash-o fa-pts-1x"></i>
            </a>
        </div>

        {hook h='displayCartExtraProductActions' product=$product}
    </div>

    <div class="hidden-sm-up row clear"></div>

    <div class="col-xs-3 col-3 text-md-right hidden-sm-up">
        <label><b>{l s='Total' mod='onepagecheckoutps'}:</b></label>
    </div>
    <div class="col-md-2 col-xs-9 col-9 text-md-right text-xs-left text-sm-left">
        <span class="product-price pull-right">{if isset($product.productmega)}{$mega.stotalwt}{else}{$product.total}{/if}</span>
    </div>
</div>