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
* @copyright 2015 Knowband
*}
<div class="modal-dialog" style="width:50%">

    <div class="modal-content">
        <div class="modal-header">
            <span class="font_popup_header">{l s='Edit GDPR Privacy setting' mod='supercheckout'}</span>
            <button type="button" class="close" onclick="closeModalPopup('modal_edit_gdpr_policy_form')"><span aria-hidden="true">×</span><span class="sr-only">{l s='Close' mod='supercheckout'}</span></button>
        </div>
        <div class="modal-body" style="padding-bottom:0;">
            <div class="row">
                <div class="span" style="margin-left:0; width:100%;">
                    <div id="modal_gdpr_form_process_status" class="modal_process_status_blk alert" style="display:none;"></div>
                </div>
            </div>
            <div style="overflow-y:auto !important;" id="gdpr_policy_edit_form">
                
                <input class="hidden_custom" type="hidden" name="edit_gdpr_policy[policy_id]" value="{$policy_id|escape:'htmlall':'UTF-8'}">
                
                <table class="list form" style="width:100%">
                    <tbody id="custom_table_tbody">

                        <tr class="supercheckout_gdpr_policy_field_form_fields">
                            <td class="right"><span class="control-label">{l s='Privacy Policy Label' mod='supercheckout'}</span>
                                <i class="icon-question-sign tooltip_color" data-toggle="tooltip" data-placement="top" title="{l s='Label of the privacy policy.' mod='supercheckout'}"></i>
                            </td>
                            <td class="supercheckout_popup_form_field">
                                <div class="span">
                                    <span class='float_left margin_right_20'>
                                        {foreach $languages as $language}
                                            <input class="required_entry supercheckout_edit_gdpr_policy_label {if $language_current neq $language['id_lang']}hidden_custom{/if}" type="text" id='edit_gdpr_policy_label_language_{$language['id_lang']|escape:'htmlall':'UTF-8'}' name="edit_gdpr_policy[field_label][{$language['id_lang']|escape:'htmlall':'UTF-8'}]" value="{$gdpr_policy_lang_details[$language['id_lang']]['description']|escape:'htmlall':'UTF-8'}">
                                        {/foreach}
                                    </span>
                                    <span class='float_left'>
                                        <select class="width_small" name="languages" onchange="changeLanguageBox(this, 'edit_gdpr_policy_label')">
                                            {foreach $languages as $language}
                                                <option value="{$language['id_lang']|escape:'htmlall':'UTF-8'}" {if $language_current eq $language['id_lang']}selected{/if}>{$language['language_code']|escape:'htmlall':'UTF-8'}</option>
                                            {/foreach}
                                        </select>
                                    </span>
                                    <span id="error_message_gdpr_policy_label_edit" class="error_message new_line hidden_custom">Error!</span>
                                </div>
                            </td>
                        </tr>

                        <tr class="supercheckout_gdpr_policy_field_form_fields">
                            <td class="right"><span class="control-label">{l s='Page URL to Link The Label (optional)' mod='supercheckout'}</span>
                                <i class="icon-question-sign tooltip_color" data-toggle="tooltip" data-placement="top" title="{l s='Page URL of privacy policy page where you have define your privacy policy.' mod='supercheckout'}"></i>
                                <p class="help-block">{l s='Page URL of privacy policy page where you have define your privacy policy.' mod='supercheckout'}</p>
                            </td>
                            <td class="supercheckout_popup_form_field">
                                <div class="" style="margin-left:10px">
                                   {* Start - Code Modified by Priyanshu on 14-June-2019  to fix the issue of Privacy Policy URL field *}
                                    <select name="edit_gdpr_policy[privacy_link]">                                                     
                                        <option value="0"> -- </option>
                                        {if is_array($available_cms) && !empty($available_cms)}
                                            {foreach $available_cms as $cms_page}
                                                <option value="{$cms_page['id_cms']}" {if $gdpr_policy_basic_details['url'] eq $cms_page['id_cms']} selected="" {/if}>{$cms_page['meta_title']} </option>
                                            {/foreach}
                                        {/if}
                                    </select>
                                    {* End - Code Modified by Priyanshu on 14-June-2019  to fix the issue of Privacy Policy URL field *}  
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="right"><span class="control-label">{l s='Required' mod='supercheckout'}</span>
                                <i class="icon-question-sign tooltip_color" data-toggle="tooltip" data-placement="top" title="{l s='Mark Privacy Policy acceptance required or not required.' mod='supercheckout'}"></i>
                            </td>
                            <td class="supercheckout_popup_form_field">
                                <div class="form-group">
                                    <div class="col-lg-9">
                                        <span class="switch prestashop-switch fixed-width-lg">
                                            <input type="radio" name="edit_gdpr_policy[required]" id="edit_gdpr_policy[required]_on" value="1" {if $gdpr_policy_basic_details['is_manadatory']|escape:'htmlall':'UTF-8' eq "1"}checked="checked"{/if}>
                                            <label for="edit_gdpr_policy[required]_on">{l s='Yes' mod='supercheckout'}</label>
                                            <input type="radio" name="edit_gdpr_policy[required]" id="edit_gdpr_policy[required]_off" value="0" {if $gdpr_policy_basic_details['is_manadatory']|escape:'htmlall':'UTF-8' eq "0"}checked="checked"{/if}>
                                            <label for="edit_gdpr_policy[required]_off">{l s='No' mod='supercheckout'}</label>
                                            <a class="slide-button btn"></a>
                                        </span>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="right"><span class="control-label">{l s='Active' mod='supercheckout'}</span>
                                <i class="icon-question-sign tooltip_color" data-toggle="tooltip" data-placement="top" title="{l s='Set the Privacy as active or inactive.' mod='supercheckout'}"></i>
                            </td>
                            <td class="supercheckout_popup_form_field">
                                <div class="form-group">
                                    <div class="col-lg-9">
                                        <span class="switch prestashop-switch fixed-width-lg">
                                            <input type="radio" name="edit_gdpr_policy[active]" id="edit_gdpr_policy[active]_on" value="1" {if $gdpr_policy_basic_details['status']|escape:'htmlall':'UTF-8' eq "1"}checked="checked"{/if}>
                                            <label for="edit_gdpr_policy[active]_on">{l s='Yes' mod='supercheckout'}</label>
                                            <input type="radio" name="edit_gdpr_policy[active]" id="edit_gdpr_policy[active]_off" value="0" {if $gdpr_policy_basic_details['status']|escape:'htmlall':'UTF-8' eq "0"}checked="checked"{/if}>
                                            <label for="edit_gdpr_policy[active]_off">{l s='No' mod='supercheckout'}</label>
                                            <a class="slide-button btn"></a>
                                        </span>
                                    </div>
                                </div>
                            </td>
                        </tr>

                    </tbody>
                </table>

            </div>
        </div>
        <div class="modal-footer no_border">
            <button type="button" onclick="closeModalPopup('modal_edit_gdpr_policy_form')" class="btn btn-default">{l s='Close' mod='supercheckout'}</button>
            <button type="button" onclick="submitEditGDPRSettingForm()" class="btn btn-primary">
                {l s='Save' mod='supercheckout'}
                <img id='loader_edit_policy_form' class='loader_save_button hidden_custom' src='{$module_dir_url|escape:'htmlall':'UTF-8'}/supercheckout/views/img/admin/ajax_loader.gif'/>
            </button>
        </div>
    </div>
</div>