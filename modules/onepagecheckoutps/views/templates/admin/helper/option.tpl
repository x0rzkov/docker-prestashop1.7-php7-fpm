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

{if isset($option.multiple) and $option.multiple}
    <option value="{$value}" {if isset($option.selected_options) and not empty($option.selected_options)
        and in_array($value, ","|explode:$option.selected_options)}selected="true"{/if}
        {foreach from=$item key="key" item="value"}
            data-{$key|escape:'htmlall':'UTF-8'}="{$value|escape:"quotes"}"
        {/foreach}
        >{$text|escape:'htmlall':'UTF-8'}</option>
{else}
    <option value="{$value|escape:'htmlall':'UTF-8'}" {if isset($option.default_option) and $option.default_option eq $value}selected="true"{/if}
    {foreach from=$item key="key" item="value"}
        data-{$key|escape:'htmlall':'UTF-8'}="{$value|escape:"quotes"}"
    {/foreach}
    >{$text|escape:'htmlall':'UTF-8'}</option>
{/if}