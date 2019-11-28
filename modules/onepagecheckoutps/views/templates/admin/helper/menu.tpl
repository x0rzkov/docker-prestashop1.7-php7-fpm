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

<div class="hidden-xs hidden-sm col-sm-3 col-lg-2 pts-menu">
    <ul class="nav">
        <li class="pts-menu-title hidden-xs hidden-sm">
            <a>{$paramsBack.HELPER_FORM.title|escape:'htmlall':'UTF-8'}</a>
        </li>
        {foreach from=$paramsBack.HELPER_FORM.tabs item='tab' name='tabs'}
            <li class="{if (isset($CURRENT_FORM) && $CURRENT_FORM eq $tab.href) || (not isset($CURRENT_FORM) && $smarty.foreach.tabs.first)}active{/if}">
                <a href="#tab-{$tab.href|escape:'htmlall':'UTF-8'}" data-toggle="tab" class="{if isset($tab.sub_tab)}has-sub{/if}">
                    <i class='fa-pts fa-pts-{if isset($tab.icon)}{$tab.icon|escape:'htmlall':'UTF-8'}{else}cogs{/if} fa-pts-1x'></i>&nbsp;{$tab.label|escape:'htmlall':'UTF-8'}
                </a>
                {if isset($tab.sub_tab)}
                    <div class="sub-tabs" data-tab-parent="{$tab.href|escape:'htmlall':'UTF-8'}" style="display: none;overflow: hidden;">
                        <ul class="nav">
                            {foreach from=$tab.sub_tab item='sub_tab'}
                                <li class="{if (isset($CURRENT_FORM) && $CURRENT_FORM eq $sub_tab.href)}active{/if}">
                                    <a href="#tab-{$sub_tab.href|escape:'htmlall':'UTF-8'}" data-toggle="tab">
                                        <i class='fa-pts fa-pts-{if isset($sub_tab.icon)}{$sub_tab.icon|escape:'htmlall':'UTF-8'}{else}{$tab.icon|escape:'htmlall':'UTF-8'}{/if} fa-pts-1x'></i>&nbsp;{$sub_tab.label|escape:'htmlall':'UTF-8'}
                                    </a>
                                </li>
                            {/foreach}
                        </ul>
                    </div>
                {/if}
            </li>
        {/foreach}
        <li class="hidden-xs hidden-sm text-center">
            <a class="pts-menu-toggle">
                <i class="fa-pts fa-pts-align-justify pointer"></i>
            </a>
        </li>
    </ul>
</div>
