{*
* We offer the best and most useful modules PrestaShop and modifications for your online store.
*
* We are experts and professionals in PrestaShop
*
* @category  PrestaShop
* @category  Module
* @author    PresTeamShop.com <support@presteamshop.com>
* @copyright 2011-2019 PresTeamShop
* @license   see file: LICENSE.txt
*}
<div>
    {l s='Go to the' mod='onepagecheckoutps'}
    <a target="_blank" href="{$paramsBack.MODULE_DIR|escape:'htmlall':'UTF-8'}docs/index_{if $paramsBack.ISO_LANG eq 'es'}es{else}en{/if}.html#tab_biocryptology">{l s='user guide' mod='onepagecheckoutps'}</a> >
    {l s='option "How to create your domain in Biocryptology?"' mod='onepagecheckoutps'}
    <br/><br/>

    <b>* {l s='Post login URLs' mod='onepagecheckoutps'}</b>:
    {foreach $paramsBack.LANGUAGES item='language'}
        <input class="disabled" style="width: 100%;" type="text" value="{$paramsBack.LINK->getModuleLink('onepagecheckoutps', 'login', ['sv' => 'Biocryptology'], null, $language.id_lang)|escape:'htmlall':'UTF-8'}"/>
    {/foreach}
    <br/>
    <b>* {l s='Post logout URLs' mod='onepagecheckoutps'}</b>:
    {foreach $paramsBack.LANGUAGES item='language'}
        <input class="disabled" style="width: 100%;" type="text" value="{$paramsBack.LINK->getPageLink('index', true, $language.id_lang)|escape:'htmlall':'UTF-8'}"/>
    {/foreach}
</div>