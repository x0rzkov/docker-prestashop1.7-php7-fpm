<!-- Supercheckout Custom  block -->
<div class="tab-pane" id="custom_fields_data_content">
    <h4 class="visible-print">{l s='Supercheckout Custom Fields' mod='supercheckout'} <span class="badge"></span></h4>
    <div class="form-horizontal">
        <div class="form-group">
        {if $empty eq 0}
            <label class="control-label">{l s='Custom fields values' mod='supercheckout'}</label>
            <hr style='border-top: 1px solid #a0d0eb !important;margin-top: 4px !important;margin-bottom: 4px !important;' />
            {foreach from=$fields_data item=field_data}
                <div class="col-lg-9">
                    <div class="col-lg-3">
                        <p class="form-control-static" style="font-weight: bold;">{l s={$field_data['field_label']} mod='supercheckout'}:</p>
                    </div>
                    <div class="col-lg-6">
                        {if $field_data['type'] == 'file'}
                            {assign var=file_path value=$field_data['field_value']|unserialize}
                             <a style="padding: 4px;margin-bottom: 6px" class="btn btn-warning" href="{$kb_admin_controller nofilter}&id_field={$field_data['id_velsof_supercheckout_fields_data']}" ><i class="icon-download"></i> {l s='Download File' mod='supercheckout'}</a> {* Variable contains URL, could not escape*}
                             <a style="padding: 4px;margin-bottom: 6px" class="btn btn-warning" href="{$file_path['path'] nofilter}" target="_blank" ><i class="icon-eye"></i> {l s='Preview' mod='supercheckout'}</a> {* Variable contains URL, could not escape*}
                        {else}
                        <p class="form-control-static">{$field_data['field_value']}</p>
                        {/if}
                    </div>
                </div>
            {/foreach}
        {else}
            <div class="list-empty-msg">
                <i class="icon-warning-sign list-empty-icon"></i>
                {l s='No values from supercheckout module found.' mod='supercheckout'}
            </div>
        {/if}
        </div>
    </div>
</div>
        {* changes by rishabh jain *}
<div class="tab-pane" id="kb_gift_message_data_content">
    <h4 class="visible-print">{l s='Supercheckout Gift Message details' mod='supercheckout'} <span class="badge"></span></h4>
    <div class="form-horizontal">
        <div class="form-group">
        {if $empty_gift_message eq 0}
            <label class="control-label">{l s='Gift message details' mod='supercheckout'}</label>
            <hr style='border-top: 1px solid #a0d0eb !important;margin-top: 4px !important;margin-bottom: 4px !important;' />
            {*{foreach from=$fields_data item=field_data}*}
                <div class="col-lg-9">
                    <div class="col-lg-3">
                        <p class="form-control-static" style="font-weight: bold;">{l s='Sender' mod='supercheckout'}:</p>
                    </div>
                    <div class="col-lg-6">
                        <p class="form-control-static">{$sender}</p>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="col-lg-3">
                        <p class="form-control-static" style="font-weight: bold;">{l s='Receiver' mod='supercheckout'}:</p>
                    </div>
                    <div class="col-lg-6">
                        <p class="form-control-static">{$receiver}</p>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="col-lg-3">
                        <p class="form-control-static" style="font-weight: bold;">{l s='Message' mod='supercheckout'}:</p>
                    </div>
                    <div class="col-lg-6">
                        <p class="form-control-static">{$gift_msg}</p>
                    </div>
                </div>
            {*{/foreach}*}
        {else}
            <div class="list-empty-msg">
                <i class="icon-warning-sign list-empty-icon"></i>
                {l s='No gift message was added using supercheckout module for this order.' mod='supercheckout'}
            </div>
        {/if}
        </div>
    </div>
</div>
            {* changes over*}
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
* 
*}