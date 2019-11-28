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
<!--------------- Start - GDPR Changes -------------------->
<!-- Start - Modal Popup Add new GDPR Privacy Setting-->
<script>
    var canNotLeaveBoxesEmpty = "{l s='This field can not be empty.Please check for all languages.' mod='supercheckout'}";
    var noDataFound = "{l s='No data found.' mod='supercheckout'}";
</script>
<style>
    .rm_filter_date {
        margin-left: 20px;
    }
    .rm_filter_date .btn{
        width: 70px;
        display:inline-block;
    }
.pure-table{
        border-collapse:collapse;
        border-spacing:0;
        empty-cells:show;
        border:1px solid #cbcbcb;
        margin-right: 5px;
        text-align: left;
}
.velsof-pure-table
{    
    width:100%;
}

.pure-table tr{
        border-bottom: 1px solid #cbcbcb;
}
.pure-table caption{
        color:#000;
        font:italic 85%/1 arial,sans-serif;
        padding:1em 0;
        text-align:center
}
.pure-table td,.pure-table th{
        border-left:1px solid #cbcbcb;
        border-width:0 0 0 1px;
        font-size:inherit;
        font-weight: normal;
        margin:0;
        overflow:visible;
        padding:.5em 1em
}
.pure-table td:first-child,.pure-table th:first-child{
        border-left-width:0
}
.pure-table thead
{
        /*        background:#e0e0e0;*/
        color:#000;
        vertical-align:bottom
}
.pure-table td
{
        background-color:transparent
}
.pure-table-odd td
{
        background-color:#F7F7F7
}
.pure-table th
{
        /*background-color:#f2f2f2*/
font-weight: bold;
}
.pure-table-striped tr:nth-child(2n-1) td
{
        background-color:#f2f2f2
}
.pure-table-bordered td
{
        border-bottom:1px solid #cbcbcb
}
.pure-table-bordered tbody>tr:last-child td,.pure-table-horizontal tbody>tr:last-child td
{
        border-bottom-width:0
}
.pure-table-horizontal td,.pure-table-horizontal th
{
        border-width:0 0 1px;
        border-bottom:1px solid #cbcbcb
}
.pure-table-horizontal tbody>tr:last-child td
{
        border-bottom-width:0
}
</style>

<div class="modal fade" id="modal_add_new_gdpr_privacy_form" tab-index="-1" aria-hidden="true" aria-labelledby="modal-incentive-form">
    <div class="modal-dialog" style="width:50%">

        <div class="modal-content">
            <div class="modal-header">
                <span class="font_popup_header">{l s='New GDPR Privacy setting' mod='supercheckout'}</span>
                <button type="button" class="close" onclick="closeModalPopup('modal_add_new_gdpr_privacy_form')"><span aria-hidden="true">×</span><span class="sr-only">{l s='Close' mod='supercheckout'}</span></button>
            </div>
            <div class="modal-body" style="padding-bottom:0;">
                <div class="row">
                    <div class="span" style="margin-left:0; width:100%;">
                        <div id="modal_gdpr_form_process_status" class="modal_process_status_blk alert" style="display:none;"></div>
                    </div>
                </div>

                {*                                                                <form id="form_add_custom_field" style="margin-bottom:0;">*}
                <div style="overflow-y:auto !important;" id="gdpr_policy_add_form">
                    <table class="list form" style="width:100%">
                        <tbody id="custom_table_tbody">

                            <tr class="supercheckout_gdpr_policy_field_form_fields">
                                <td class="right"><span class="control-label">{l s='Privacy Policy Label' mod='supercheckout'}</span>
                                    <i class="icon-question-sign tooltip_color" data-toggle="tooltip" data-placement="top" data-original-title="{l s='Label of the privacy policy.' mod='supercheckout'}"></i>
                                </td>
                                <td class="supercheckout_popup_form_field">
                                    <div class="span">
                                        <span class='float_left margin_right_20'>
                                            {foreach $languages as $language}
                                                <input class="required_entry supercheckout_gdpr_policy_label {if $language_current|escape:'htmlall':'UTF-8' neq $language['id_lang']|escape:'htmlall':'UTF-8'}hidden_custom{/if}" type="text" id='gdpr_policy_label_language_{$language['id_lang']|escape:'htmlall':'UTF-8'}' name="gdpr_policy_fields[field_label][{$language['id_lang']|escape:'htmlall':'UTF-8'}]">
                                            {/foreach}
                                        </span>
                                        <span class='float_left'>
                                            <select class="width_small" name="languages" onchange="changeLanguageBox(this, 'gdpr_policy_label')">
                                                {foreach $languages as $language}
                                                    <option value="{$language['id_lang']|escape:'htmlall':'UTF-8'}" {if $language_current|escape:'htmlall':'UTF-8' eq $language['id_lang']|escape:'htmlall':'UTF-8'}selected{/if}>{$language['language_code']|escape:'htmlall':'UTF-8'}</option>
                                                {/foreach}
                                            </select>
                                        </span>
                                        <span id="error_message_gdpr_policy_label" class="error_message new_line hidden_custom">Error!</span>
                                    </div>
                                </td>
                            </tr>

                            <tr class="supercheckout_gdpr_policy_field_form_fields">
                                <td class="right"><span class="control-label">{l s='Page URL to Link The Label (optional)' mod='supercheckout'}</span>
                                    <i class="icon-question-sign tooltip_color" data-toggle="tooltip" data-placement="top" data-original-title="{l s='Page URL of privacy policy page where you have define your privacy policy.' mod='supercheckout'}"></i>
                                    <p class="help-block">{l s='Page URL of privacy policy page where you have define your privacy policy.' mod='supercheckout'}</p>
                                </td>
                                <td class="supercheckout_popup_form_field">
                                    <div class="" style="margin-left:10px">
                                       {* Start - Code Modified by Priyanshu on 14-June-2019  to fix the issue of Privacy Policy URL field *}
                                        <select name="gdpr_policy_fields[privacy_link]">                                                     
                                            <option value="0"> -- </option>
                                            {if is_array($available_cms) && !empty($available_cms)}
                                                {foreach $available_cms as $cms_page}
                                                    <option value="{$cms_page['id_cms']}">{$cms_page['meta_title']} </option>
                                                {/foreach}
                                            {/if}
                                        </select>
                                        {* End - Code Modified by Priyanshu on 14-June-2019  to fix the issue of Privacy Policy URL field *}
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td class="right"><span class="control-label">{l s='Required' mod='supercheckout'}</span>
                                    <i class="icon-question-sign tooltip_color" data-toggle="tooltip" data-placement="top" data-original-title="{l s='Mark Privacy Policy acceptance required or not required.' mod='supercheckout'}"></i>
                                </td>
                                <td class="supercheckout_popup_form_field">
                                    <div class="form-group">
                                        <div class="col-lg-9">
                                            <span class="switch prestashop-switch fixed-width-lg">
                                                <input type="radio" name="gdpr_policy_fields[required]" id="gdpr_policy_fields[required]_on" value="1">
                                                <label for="gdpr_policy_fields[required]_on">{l s='Yes' mod='supercheckout'}</label>
                                                <input type="radio" name="gdpr_policy_fields[required]" id="gdpr_policy_fields[required]_off" value="0" checked="checked">
                                                <label for="gdpr_policy_fields[required]_off">{l s='No' mod='supercheckout'}</label>
                                                <a class="slide-button btn"></a>
                                            </span>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td class="right"><span class="control-label">{l s='Active' mod='supercheckout'}</span>
                                    <i class="icon-question-sign tooltip_color" data-toggle="tooltip" data-placement="top" data-original-title="{l s='Set the Privacy as active or inactive.' mod='supercheckout'}"></i>
                                </td>
                                <td class="supercheckout_popup_form_field">
                                    <div class="form-group">
                                        <div class="col-lg-9">
                                            <span class="switch prestashop-switch fixed-width-lg">
                                                <input type="radio" name="gdpr_policy_fields[active]" id="gdpr_policy_fields[active]_on" value="1" checked="checked">
                                                <label for="gdpr_policy_fields[active]_on">{l s='Yes' mod='supercheckout'}</label>
                                                <input type="radio" name="gdpr_policy_fields[active]" id="gdpr_policy_fields[active]_off" value="0">
                                                <label for="gdpr_policy_fields[active]_off">{l s='No' mod='supercheckout'}</label>
                                                <a class="slide-button btn"></a>
                                            </span>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                {*                                                                </form>*}
            </div>
            <div class="modal-footer no_border">
                <button type="button" onclick="closeModalPopup('modal_add_new_gdpr_privacy_form')" class="btn btn-default">{l s='Close' mod='supercheckout'}</button>
                <button type="button" onclick="submitGDPRSettingForm()" class="btn btn-primary">
                    {l s='Save' mod='supercheckout'}
                    <img id='loader_add_policy_form' class='loader_save_button hidden_custom' src='{$module_dir_url|escape:'quotes':'UTF-8'}/supercheckout/views/img/admin/ajax_loader.gif'/> {*Variable contains url, escape not required*}
                </button>
                {*                                                                    <img class="modal_incentive_form_progress" class="hidden" src="/../../../img/admin/ajax_loader.gif">*}
            </div>
        </div>
    </div>
</div>

<!-- End - Modal Popup Add new Custom Field -->

<!-- Start -Modal Popup Edit Custom Field -->
<div class="modal fade" id="modal_edit_gdpr_policy_form" tab-index="-1" aria-hidden="true" aria-laodal_edit_gdpr_policy_formbelledby="modal-incentive-form">
    <!-- Render edit form here -->
</div>
<!-- End - Modal Popup Edit Custom Field -->
<!--------------- Start - GDPR Settings -------------------->
<div id="tab_gdpr" class="tab-pane tab-form">
    <div class="block">
        <h4 class='velsof-tab-heading'>{l s='GDPR Settings' mod='supercheckout'}</h4>                                                                                                                       
        <div>
            <table class="form">
                <tbody>
                                {* Start: Added by Anshul for deleting request from email marketing services*}
                                <tr>
                                    <td class="name vertical_top_align"><span class="control-label">{l s='Enable Delete From Email Marketing Services on request' mod='supercheckout'}: </span>                                                                
                                        <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="bottom" data-original-title="{l s='Enable to delete customer email From Email Marketing Services (MailChimp, SendinBlue & Klaviyo) on request' mod='supercheckout'}"></i>
                                    </td>
                                    <td class="settings">
                                        <input type="hidden" value="0" name="velocity_supercheckout[email_marketing_delete]" />
                                        {if $velocity_supercheckout['email_marketing_delete'] eq 1}
                                            {if $IE7 eq true}
                                                <div>
                                                    <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[email_marketing_delete]"  checked="checked" />
                                                </div>
                                            {else}
                                                <div class="make-switch" data-on="primary" data-off="default">
                                                    <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[email_marketing_delete]"  checked="checked" />
                                                </div>
                                            {/if}                                                                    
                                        {else}
                                            {if $IE7 eq true}
                                                <div>
                                                    <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[email_marketing_delete]"  />
                                                </div>
                                            {else}
                                                <div class="make-switch" data-on="primary" data-off="default">
                                                    <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[email_marketing_delete]"/>
                                                </div>
                                            {/if}
                                        {/if}
                                    </td>
                                </tr>
                                {* End: Added by Anshul for deleting request from email marketing services*}
                </tbody>
            </table><br><br>
            <table class="table_custom_bordered" id="table_gdpr_policy_data" style="width: 100%;">
       
                <thead>
                    <tr>
                        <th>{l s='S. No.' mod='supercheckout'}</th>
                        <th>{l s='Policy Label' mod='supercheckout'}</th>
                        <th>{l s='Required' mod='supercheckout'}</th>
                        <th>{l s='Active' mod='supercheckout'}</th>
                        <th class="center">{l s='Action' mod='supercheckout'}</th>
                    </tr>
                </thead>
                <tbody id="tbody_gdpr_policy_data">
                 
                    {assign var="counter" value="1"}
                    {foreach from=$gdpr_policy_details item=array_field}
                        <tr class="pure-table-{if $counter%2 == 0}even{else}odd{/if}" id="tr_policy_table_{$array_field['policy_id']|escape:'htmlall':'UTF-8'}">
                            <td>{$counter|escape:'htmlall':'UTF-8'}</td>
                            <td class="width_25"><div class="div_250px_ellipsis">{$array_field['description']|escape:'htmlall':'UTF-8'}</div></td>
                            <td>{if $array_field['is_manadatory']|escape:'htmlall':'UTF-8' eq '1'}{l s='Yes' mod='supercheckout'}{else}{l s='No' mod='supercheckout'}{/if}</td>
                            <td>{if $array_field['status']|escape:'htmlall':'UTF-8' eq '1'}{l s='Yes' mod='supercheckout'}{else}{l s='No' mod='supercheckout'}{/if}</td>
                            <td class="center" style="padding: 12px;">
                                <div style="display:inline-block;float:left;">
                                    <a style="padding:5px" href="javascript://" onclick="displayEditGDPRPolicyPopup({$array_field['policy_id']|escape:'htmlall':'UTF-8'})" type="11" class="velsof-glyphicons2 glyphicons pencil"><i title="{l s='Edit this policy' mod='supercheckout'}"></i></a>                                                                                                
                                </div>
                                <div style="display:inline-block;">
                                    <a style="padding:5px" href="javascript://" onclick="deleteGDPRPolicyRow({$array_field['policy_id']|escape:'htmlall':'UTF-8'})" type="11" class="velsof-glyphicons2 glyphicons bin"><i title="{l s='Delete this policy' mod='supercheckout'}"></i></a>
                                </div>
                            </td>
                        </tr>
                        {assign var=counter value=$counter+1}
                    {/foreach}

                    <tr id="tr_gdpr_policy_add_new">
                        <td colspan="4" class="opacity_0"></td>
                        <td class="left center">
                            <a style="cursor: pointer; text-decoration: none;" onclick="addNewGDPRPrivacyPopup()" data-toggle="modal">
                                <span><i class="process-icon-new"></i></span>
                            </a>
                            {l s='Add New' mod='supercheckout'}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!--------------- END - GDPR Settings -------------------->

<!--------------- Start - Check Customer Privacy-------------------->
<div id="tab_accepted_privacy" class="tab-pane tab-form">
    <div class="block">
        <h4 class='heading-mosaic velsof-header'>{l s='Check Customer Consent' mod='supercheckout'}</h4>
        <div class="widget">
            <div class="widget-head">
                <h4 class="heading">{l s='Filter Customer Consent' mod='supercheckout'}</h4>
            </div>
            <div class="widget-body" style="display:block;">
                <div class="row" style="margin-bottom:5px">
                    <span class="span0 rm_filter_date">
                        <h5>{l s='Order Reference No /Customer Email ' mod='supercheckout'}:</h5>
                        <div class="row rm_filter_input_block">
                            <input type="text" id="supercheckout_gdpr_filter" name="supercheckout_gdpr_filter" value="" />
                            <span id="error_message_supercheckout_gdpr_filter" class="error_message new_line hidden_custom">{l s='This field can not be empty.' mod='supercheckout'}</span>
                        </div>
                    </span>
                    <span class="span0 rm_filter_date" style="width: 235px;">
                        <h5>&nbsp;</h5>
                        <div class="row" style="margin-left:0">
                            <span class="btn btn-block velsof-btn-block btn-success" onclick="getAcceptedCustomerPolicy()">{l s='FILTER' mod='supercheckout'}</span>
                            <span class="btn btn-block velsof-btn-block btn-primary" onclick="resetCustomerPolicy()" style="margin-bottom:5px">{l s='Reset' mod='supercheckout'}</span>
                            <img id="rm_loader" src="{$module_dir|escape:'htmlall':'UTF-8'}views/img/loader_small.gif" style="display:none;">
                        </div>
                    </span>
                    <div id="rm_date_error" class="rm-date-error"></div>
                </div>
            </div>
        </div>
        <div id="supercheckout_customer_consent_container" class="">
            <div class="widget">
                <div class="widget-head">
                    <h4 class="heading">{l s='Accepted Customer Consent Details' mod='supercheckout'}</h4>
                </div>
                <div class="row graph_container">
                    <div id="supercheckout_customer_consent" style="width: 98%; margin: 6px auto; height:auto;">
                        <div class="rm_no_data"><span>{l s='No data found.' mod='supercheckout'}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--------------- END - Check Customer Privacy -------------------->

<!--------------- End - GDPR Changes -------------------->