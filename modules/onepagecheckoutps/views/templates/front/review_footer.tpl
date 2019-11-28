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

<div class="row clear clearfix"></div>

{if $CONFIGS.OPC_SHOW_ORDER_MESSAGE}
    <div id="div_leave_message">
        <p>{l s='If you would like to add a comment about your order, please write it below.' mod='onepagecheckoutps'}</p>
        <textarea name="message" id="message" class="form-control" rows="2">{strip}
        {if isset($oldMessage)}{$oldMessage|escape:'htmlall':'UTF-8'}{/if}
        {/strip}</textarea>
    </div>
{else}
    <hr class="separate-terms">
{/if}

{if $is_logged && $CONFIGS.OPC_ENABLE_PRIVACY_POLICY && $CONFIGS.OPC_REQUIRE_PP_BEFORE_BUY}
    <div id="div_privacy_policy">
        <p id="p_privacy_policy">
            <label for="privacy_policy">
                <input type="checkbox" class="not_unifrom not_uniform" name="privacy_policy" id="privacy_policy" value="1" />
                {l s='I have read and accept the Privacy Policy.' mod='onepagecheckoutps'}
                <span class="read">{l s='(read)' mod='onepagecheckoutps'}</span>
            </label>
        </p>
    </div>
{/if}

{if $conditions_to_approve|count and $CONFIGS.OPC_ENABLE_TERMS_CONDITIONS}
    <div id="conditions-to-approve">
        <ul>
          {foreach from=$conditions_to_approve item="condition" key="condition_name"}
            <li>
                <label class="js-terms" for="conditions_to_approve[{$condition_name}]">
                    <input  id    = "conditions_to_approve[{$condition_name}]"
                            name  = "conditions_to_approve[{$condition_name}]"
                            required
                            type  = "checkbox"
                            value = "1"
                            class = "ps-shown-by-js"
                    >
                    {$condition nofilter}
                </label>
            </li>
          {/foreach}
        </ul>
    </div>
{/if}

<span id="container_float_review_point"></span>

<div id="container_float_review">
    <div id="buttons_footer_review" class="row">
        <div class="col-xs-12 col-12">
        {if $CONFIGS.OPC_SHOW_LINK_CONTINUE_SHOPPING}
            <button type="button" id="btn_continue_shopping" class="btn btn-link btn-sm pull-left"
                    {if not empty($CONFIGS.OPC_LINK_CONTINUE_SHOPPING)}data-link="{$CONFIGS.OPC_LINK_CONTINUE_SHOPPING|escape:'htmlall':'UTF-8'}"{/if}>
                <i class="fa-pts fa-pts-chevron-left fa-pts-1x"></i>
                {l s='Continue shopping' mod='onepagecheckoutps'}
            </button>
        {/if}
            <button type="button" id="btn_place_order" class="btn btn-primary btn-lg pull-right" >
                <i class="fa-pts fa-pts-shopping-cart fa-pts-1x"></i>
                {l s='Checkout' mod='onepagecheckoutps'}
            </button>
        </div>
    </div>
</div>

{if $CONFIGS.OPC_ENABLE_HOOK_SHOPPING_CART && !$CONFIGS.OPC_COMPATIBILITY_REVIEW}
    <div id="HOOK_SHOPPING_CART" class="row">
        {block name='hook_shopping_cart'}
            {hook h='displayShoppingCart'}
        {/block}
    </div>
    <div id="hook_shopping_cart_footer">
        {hook h='displayShoppingCartFooter'}
    </div>
{/if}

{block name='display_reassurance'}
    {hook h='displayReassurance'}
{/block}

<div class="row">
    {include file='./custom_html/review.tpl'}
</div>