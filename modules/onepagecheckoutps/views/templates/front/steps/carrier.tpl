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

{if !$register_customer}
    <div id="onepagecheckoutps_step_two_container" class="{$classes|escape:'htmlall':'UTF-8'} {if isset($is_virtual_cart) && $is_virtual_cart}hidden{/if}">
        <h5 class="onepagecheckoutps_p_step onepagecheckoutps_p_step_two">
            <i class="fa-pts fa-pts-truck fa-pts-2x"></i>
            {l s='Shipping method' mod='onepagecheckoutps'}
        </h5>
        <div id="onepagecheckoutps_step_two"></div>
    </div>
{/if}