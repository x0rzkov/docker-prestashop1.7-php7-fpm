{if $empty eq 0}
<div class='supercheckout_custom_fields_block'>
    <div class="box box-small cleafix">
        <h4 class="page-subheading "><strong>{l s='Supercheckout Custom Fields' mod='supercheckout'}</strong></h4>
        {if $empty eq 0}
            {foreach from=$fields_data item=field_data}
                <p>
                    <strong class="dark">{l s={$field_data['field_label']} mod='supercheckout'}:</strong>
                    {if $field_data['type'] == 'file'}
                        {assign var=file_path value=$field_data['field_value']|unserialize}
                         <a style="padding: 4px;margin-bottom: 6px" class="btn btn-warning" href="{$kb_front_controller nofilter}&id_field={$field_data['id_velsof_supercheckout_fields_data']}" ><i class="icon-download"></i> {l s='Download File' mod='supercheckout'}</a> {* Variable contains URL, could not escape*}
                         <a style="padding: 4px;margin-bottom: 6px" class="btn btn-warning" href="{$file_path['path'] nofilter}" target="_blank" ><i class="icon-eye"></i> {l s='Preview' mod='supercheckout'}</a> {* Variable contains URL, could not escape*}
                    {else}
                        <span class="color-myaccount">{$field_data['field_value']}</span>
                    {/if}
                </p>
            {/foreach}
        {else}
            <div class="list-empty-msg">
                <i class="icon-warning-sign list-empty-icon"></i>
                {l s='No values found.' mod='supercheckout'}
            </div>
        {/if}
    </div>
</div>
{/if}
{*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
* We offer the best and most useful modules PrestaShop and modifications for your online store.
*
* @category  PrestaShop Module
* @author    velsof.com <support@velsof.com>
* @copyright 2017 Velocity Software Solutions Pvt Ltd
* @license   see file: LICENSE.txt
*
* Description
*
* Product Update Block Page
*}