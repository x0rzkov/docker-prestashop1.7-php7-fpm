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

<div>
    {l s='Go to the' mod='onepagecheckoutps'}
    <a target="_blank" href="{$paramsBack.MODULE_DIR|escape:'htmlall':'UTF-8'}docs/index_{if $paramsBack.ISO_LANG eq 'es'}es{else}en{/if}.html#tab_google">{l s='user guide' mod='onepagecheckoutps'}</a> >
    {l s='option "How to create an application on Google?"' mod='onepagecheckoutps'}
    <br/><br/>

    <b>* {l s='AUTHORIZED JAVASCRIPT ORIGINS' mod='onepagecheckoutps'}:</b>
    <input style="width: 100%;" type="text" onclick="this.focus();this.select();" value="{$paramsBack.SHOP_PROTOCOL|escape:'htmlall':'UTF-8'}{$paramsBack.SHOP->domain|escape:'htmlall':'UTF-8'}"></input>
    <br />

    <b>* {l s='AUTHORIZED REDIRECT URI' mod='onepagecheckoutps'}:</b>
    <br />
    {foreach $paramsBack.LANGUAGES item='language'}
        <input type="text" value="{$paramsBack.LINK->getModuleLink('onepagecheckoutps', 'login', ['sv' => 'Google'], null, $language.id_lang)|escape:'htmlall':'UTF-8'}"/>
    {/foreach}
</div>