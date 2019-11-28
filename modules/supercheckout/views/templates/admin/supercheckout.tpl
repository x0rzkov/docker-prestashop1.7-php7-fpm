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
<script type="text/javascript">
    var uncheckAddressFieldMsg = "{l s='You cannot uncheck this field due to required field' mod='supercheckout' js=1}";
    var scp_ajax_action = "{$action|escape:'quotes'}";
    var request_error = "{l s='Please provide required information with valid data.' mod='supercheckout'}";
    var fb_app_id_error = "{l s='Facebook App Id is required.' mod='supercheckout'}";
    var fb_app_secret_error = "{l s='Facebook App Secret is required.' mod='supercheckout'}";
    var ggl_client_id_error = "{l s='Google Client Id is required.' mod='supercheckout'}";
    var ggl_app_secret_error = "{l s='Google App Secret is required.' mod='supercheckout'}";
    var mailchimp_api_key_error = "{l s='Enter valid Mailchimp Api Key and select a list after getting lists to enable Mailchimp feature.' mod='supercheckout'}";
    var google_api_key_error = "{l s='Google API Key is required.' mod='supercheckout'}";
    var double_quotes_warning = "{l s='Do not use double quotes.If used then html content will not be saved.' mod='supercheckout'}";
    var empty_width_error = "{l s='Width can not be empty.' mod='supercheckout'}";
    var empty_height_error = "{l s='Height can not be empty.' mod='supercheckout'}";
    var valid_width_error = "{l s='Width should be positive integer or auto.' mod='supercheckout'}";
    var valid_height_error = "{l s='Height should be positive integer or auto.' mod='supercheckout'}";
    var valid_width_error_product_image = "{l s='Width should be positive integer.' mod='supercheckout'}";
    var valid_height_error_product_image = "{l s='Height should be positive integer.' mod='supercheckout'}";
    
    var module_path = "{$module_dir|escape:'quotes'}";
    var remove_cnfrm_msg = "{l s='Are you really want to remove the image?' mod='supercheckout'}";
    var canNotLeaveAllBoxesEmpty = "{l s='Can not leave all the language boxes empty. Please fill at least one box.' mod='supercheckout'}";
    //var pleaseProvideInValidFormat = 'Please provide value(s) in valid format.  (valid format is: value|Label)';
    var pleaseProvideInValidFormat1 = "{l s='Please provide value(s) in valid format.' mod='supercheckout'}";
    var pleaseProvideInValidFormat2 = "{l s='valid format is' mod='supercheckout'}";
    var pleaseProvideInValidFormat = pleaseProvideInValidFormat1 + " " +'(' + pleaseProvideInValidFormat2 + ': value|Label)';
    var areYouSureToDelete = "{l s='Are you sure to delete the row?' mod='supercheckout'}";
    
    var no_list_msg = "{l s='No list exists for this API key!' mod='supercheckout'}";
    
    /* Start - Added by Raghu on 18-Aug-2017 for adding the Yes and No translated text into JS Varibles for Translations issues while editing/adding a custom field */
    var no_text = "{l s='No' mod='supercheckout'}";
    var yes_text = "{l s='Yes' mod='supercheckout'}";
    /* End - Added by Raghu on 18-Aug-2017 for adding the Yes and No translated text into JS Varibles for Translations issues while editing/adding a custom field */
    
    /* Start - Code Added by Raghu on 22-Aug-2017 for fixing 'Custom Fields Blocks Translations issues while editing/adding a custom field' */
    var customer_registration_block_txt = "{l s='Customer Registration Block' mod='supercheckout'}";
    var cart_block_txt = "{l s='Cart Block' mod='supercheckout'}";
    var payment_address_form_txt = "{l s='Payment Address Form' mod='supercheckout'}";
    var shipping_address_form_txt = "{l s='Shipping Address Form' mod='supercheckout'}";
    /* End - Code Added by Raghu on 22-Aug-2017 for fixing 'Custom Fields Blocks Translations issues while editing/adding a custom field' */
    
    /* Start - Code Added by Raghu on 22-Aug-2017 for fixing 'Custom Fields type translations' issue */
    var text_box_txt = "{l s='Text Box' mod='supercheckout'}";
    var radio_button_txt = "{l s='Radio Buttons' mod='supercheckout'}";
    var text_area_txt = "{l s='Text Area' mod='supercheckout'}";
    var select_box_txt = "{l s='Select Box' mod='supercheckout'}";
    var check_boxes_txt = "{l s='Check Boxes' mod='supercheckout'}";
    /* End - Code Added by Raghu on 22-Aug-2017 for fixing 'Custom Fields type translations' issue */
    
    /* Start - Code Added by Anshul on 27-June-2019 for fixing 'Custom Fields type translations' issue */
    var date = "{l s='Date' mod='supercheckout'}";
    var file = "{l s='File' mod='supercheckout'}";
    var ip_address_error = "{l s='One of the entered IP address is not valid.' mod='supercheckout'}";
    var ip_address_error2 = "{l s='IP address is separated by wrong separator. It must be comma.' mod='supercheckout'}";
    velovalidation.setErrorLanguage({
        valid_amount: "{l s='Field should be numeric.' mod='supercheckout'}",
        valid_decimal: "{l s='Field can have only upto two decimal values.' mod='supercheckout'}",
        positive_amount: "{l s='Field should be positive.' mod='supercheckout'}",
    });
    var big_amount_error = "{l s='The Amount is too big.' mod='supercheckout'}";
    /* End - Code Added by Anshul on 27-June-2019 for fixing 'Custom Fields type translations' issue */
</script>
{*GDPR Change*}
<style>
    .alert-success{
        display:none;
    }

    .modal_supercheckout {
        display:    none;
        position:   fixed;
        z-index:    100000;
        top:        0;
        left:       0;
        height:     100%;
        width:      100%;
        background: rgba( 255, 255, 255, .8 )
            url('{$module_dir|escape:'htmlall':'UTF-8'}/views/img/spinner.gif')
            50% 50%
            no-repeat;
    }

    /* When the body has the loading class, we turn
       the scrollbar off with overflow:hidden */
    body.loading {
        overflow: hidden;
    }

    /* Anytime the body has the loading class, our
       modal element will be visible */
    body.loading .modal_supercheckout {
        display: block;
    }

</style>
<div class="modal_supercheckout"></div>
<div class="module_confirmation conf confirm alert alert-success" id="div_supercheckout_success" style="display:none">
    {l s='Operation performed successfully!' mod='supercheckout'}
</div>


<div class="module_confirmation conf confirm alert alert-success hidden_custom" id="div_custom_fields_success">
    <button type="button" class="close" data-dismiss="alert">×</button>
    {l s='Operation performed successfully!' mod='supercheckout'}
</div>
{*GDPR Change*}
<div id="velsof_supercheckout_container" class="content">
    <div class="box">
               <div class="navbar main hidden-print">
            <!-- Brand & save buttons -->
            <ul class="pull-left">
                <div style="position: inherit;color: black;font-size: 15px;min-width: 700px;padding-left: 50px;padding-top: 5px;">
                    {l s='Have some doubt or issue? Get prompt help from us.' mod='supercheckout'}
                </div>
                <li class="themer_eyedropper" data-toggle="collapse" data-target="#themer"></li>
            </ul>
            <div class="topbuttons">                
                <a href="javascript:void(0)" onclick="validate_data()"><span id="save_post_setting" class="btn btn-block btn-success action-btn">{l s='Save' mod='supercheckout'}</span></a>&nbsp;&nbsp;&nbsp;<a href="{$cancel_action}"><span class="btn btn-block btn-danger action-btn">{l s='Cancel' mod='supercheckout'}</span></a>
                <span class="gritter-add-primary btn btn-default btn-block hidden">{l s='For notifications on saving' mod='supercheckout'}</span>
            </div>
        </div>
        <div class="velsof-container">
            <div class="widget velsof-widget-left">
                <div class="widget-body velsof-widget-left">                    
                    <div id="wrapper">
                        <div id="menuVel" class="hidden-print ui-resizable">
                            <div class="slimScrollDiv">
                                <div class="slim-scroll">
                                    <ul>
                                        <li class="active {if $ps_version eq 15}vss-tab-ver15{/if}"><a class="glyphicons settings" id="velsof_general_tab" href="#tab_general_settings" data-toggle="tab"><i></i><span>{l s='General Settings' mod='supercheckout'}</span></a></li>
                                        <li class="{if $ps_version eq 15}vss-tab-ver15{/if}"><a class="glyphicons brush" href="#tab_customizer" data-toggle="tab"><i></i><span>{l s='Customizer' mod='supercheckout'}</span></a></li>
                                        <li class="{if $ps_version eq 15}vss-tab-ver15{/if}"><a class="glyphicons keys" id="velsof_tab_login" href="#tab_login" data-toggle="tab"><i></i><span>{l s='Login' mod='supercheckout'}</span></a></li>                                            
                                        <li class="{if $ps_version eq 15}vss-tab-ver15{/if}"><a class="glyphicons envelope" id="velsof_tab_mailchimp" href="#tab_mailchimp" data-toggle="tab"><i></i><span>{l s='Email Marketing' mod='supercheckout'}</span></a></li>                                            
                                        <li class="{if $ps_version eq 15}vss-tab-ver15{/if}"><a class="glyphicons home" href="#tab_Addr" data-toggle="tab"><i></i><span>{l s='Addresses' mod='supercheckout'}</span></a></li>
                                        <li class="{if $ps_version eq 15}vss-tab-ver15{/if}"><a class="glyphicons credit_card" id="velsof_tab_payment_method" href="#tab_payment_method" data-toggle="tab"><i></i><span>{l s='Payment Method' mod='supercheckout'}</span></a></li>
                                        <li class="{if $ps_version eq 15}vss-tab-ver15{/if}"><a class="glyphicons cargo" id="velsof_tab_delivery_method" href="#tab_shipping_method" data-toggle="tab"><i></i><span>{l s='Delivery Method' mod='supercheckout'}</span></a></li>
                                        <li class="{if $ps_version eq 15}vss-tab-ver15{/if}"><a class="glyphicons boat" href="#tab_ship_to_pay" data-toggle="tab"><i></i><span>{l s='Ship2pay' mod='supercheckout'}</span></a></li>
                                        <li class="{if $ps_version eq 15}vss-tab-ver15{/if}"><a class="glyphicons embed_close" href="#tab_custom_fields" data-toggle="tab"><i></i><span>{l s='Custom Fields' mod='supercheckout'}</span></a></li>
                                        <li class="{if $ps_version eq 15}vss-tab-ver15{/if}"><a class="glyphicons shopping_cart" id="velsof_tab_cart" href="#tab_cart" data-toggle="tab"><i></i><span>{l s='Cart' mod='supercheckout'}</span></a></li>
                                        {*GDPR Change*}
                                        <li class="{if $ps_version eq 15}vss-tab-ver15{/if}"><a class="glyphicons ok" id="velsof_tab_accepted_privacy" href="#tab_accepted_privacy" data-toggle="tab"><i></i><span>{l s='Customer Consent' mod='supercheckout'}</span></a></li>
                                        <li class="{if $ps_version eq 15}vss-tab-ver15{/if}"><a class="glyphicons settings" id="velsof_tab_gdpr" href="#tab_gdpr" data-toggle="tab"><i></i><span>{l s='GDPR settings' mod='supercheckout'}</span></a></li>
                                        {*GDPR Change*}
                                        <li class="{if $ps_version eq 15}vss-tab-ver15{/if}"><a class="glyphicons podium" id="velsof_tab_design" href="#tab_design" data-toggle="tab"><i></i><span>{l s='Design' mod='supercheckout'}</span></a></li>
                                        <li class="{if $ps_version eq 15}vss-tab-ver15{/if}"><a class="glyphicons circle_question_mark" href="#tab_faq" data-toggle="tab"><i></i><span>{l s='FAQs' mod='supercheckout'}</span></a></li>                                            
                                        <li class="{if $ps_version eq 15}vss-tab-ver15{/if}"><a class="glyphicons bookmark" target="_blank" href="https://addons.prestashop.com/en/149_knowband" target="_blank"><i></i><span>{l s='Other Plugins' mod='supercheckout'}</span></a></li>
                                    </ul>
                                    <div class="clearfix"></div>
                                    <!--                                        <div class="separator bottom"></div> -->
                                </div>
                            </div>
                            <div class="ui-resizable-handle ui-resizable-e" style="z-index: 1000;"></div>
                        </div>

                        <div id="content_knowband">
                            <div class="box">
                                <div class="content tabs">


                                    <div class="layout">
                                        <div class="tab-content even-height">
                                            <!--------------- Start - General Setings -------------------->
                                            <form action="{$action}" method="post" enctype="multipart/form-data" id="supercheckout_configuration_form">
                                                <input type="hidden" name="{$submit_action}" value="1" >
                                                <input type="hidden" name="velocity_supercheckout[adv_id]" value="{$velocity_supercheckout['adv_id']}" >
                                                <input type="hidden" name="velocity_supercheckout[temp_cart_image_size][width]" value="{$velocity_supercheckout['cart_image_size']['width']}" />
                                                <input type="hidden" name="velocity_supercheckout[temp_cart_image_size][height]" value="{$velocity_supercheckout['cart_image_size']['height']}" />
                                                <div id="tab_general_settings" class="tab-pane active tab-form">
                                                    <div class="block">
                                                        <h4 class='velsof-tab-heading'>{l s='General Settings' mod='supercheckout'}</h4>
                                                        <table class="form">
                                                            <tr>
                                                                <td class="name vertical_top_align"><span class="control-label">{l s='Enable/Disable' mod='supercheckout'}: </span>
                                                                    <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="bottom" data-original-title="{l s='Enable/Disable Tooltip' mod='supercheckout'}"></i>
                                                                </td>
                                                                <td class="settings">
                                                                    <input type="hidden" value="0" name="velocity_supercheckout[enable]" />
                                                                    {if $velocity_supercheckout['enable'] eq 1}
                                                                        {if $IE7 eq true}
                                                                            <div>
                                                                                <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[enable]" id="supercheckout_enable" checked="checked" />
                                                                            </div>
                                                                        {else}
                                                                            <div class="make-switch" data-on="primary" data-off="default">
                                                                                <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[enable]" id="supercheckout_enable" checked="checked" />
                                                                            </div>
                                                                        {/if}                                                                    
                                                                    {else}
                                                                        {if $IE7 eq true}
                                                                            <div>
                                                                                <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[enable]" id="supercheckout_enable" />
                                                                            </div>
                                                                        {else}
                                                                            <div class="make-switch" data-on="primary" data-off="default">
                                                                                <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[enable]" id="supercheckout_enable"/>
                                                                            </div>
                                                                        {/if}
                                                                    {/if}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="name vertical_top_align"><span class="control-label" >{l s='Enable Guest Checkout' mod='supercheckout'}: </span>
                                                                    <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enable Guest Checkout Tooltip' mod='supercheckout'}"></i>
                                                                </td>
                                                                <td class="settings">
                                                                    <input type="hidden" value="0" name="velocity_supercheckout[enable_guest_checkout]" />
                                                                    {if $velocity_supercheckout['enable_guest_checkout'] eq 1}
                                                                        {if $IE7 eq true}
                                                                            <div>
                                                                                <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[enable_guest_checkout]" id="supercheckout_enable_newsletter" checked="checked" />
                                                                            </div>
                                                                        {else}
                                                                            <div class="make-switch" data-on="primary" data-off="default">
                                                                                <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[enable_guest_checkout]" id="supercheckout_enable_newsletter" checked="checked" />
                                                                            </div>
                                                                        {/if}                                                                    
                                                                    {else}
                                                                        {if $IE7 eq true}
                                                                            <div>
                                                                                <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[enable_guest_checkout]" id="supercheckout_enable_newsletter" />
                                                                            </div>
                                                                        {else}
                                                                            <div class="make-switch" data-on="primary" data-off="default">
                                                                                <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[enable_guest_checkout]" id="supercheckout_enable_newsletter"/>
                                                                            </div>
                                                                        {/if}
                                                                    {/if}


                                                                </td>
                                                            </tr>                                                                    

                                                            <tr>
                                                                <td class="name vertical_top_align"><span class="control-label" >{l s='Register Guest' mod='supercheckout'}: </span>
                                                                    <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Register Guest Tooltip' mod='supercheckout'}"></i>
                                                                </td>
                                                                <td class="settings">
                                                                    <input type="hidden" value="0" name="velocity_supercheckout[enable_guest_register]" />
                                                                    {if $velocity_supercheckout['enable_guest_register'] eq 1}
                                                                        {if $IE7 eq true}
                                                                            <div>
                                                                                <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[enable_guest_register]" id="supercheckout_enable_guest_register" checked="checked" />
                                                                            </div>
                                                                        {else}
                                                                            <div class="make-switch" data-on="primary" data-off="default">
                                                                                <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[enable_guest_register]" id="supercheckout_enable_guest_register" checked="checked" />
                                                                            </div>
                                                                        {/if}                                                                    
                                                                    {else}
                                                                        {if $IE7 eq true}
                                                                            <div>
                                                                                <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[enable_guest_register]" id="supercheckout_enable_guest_register" />
                                                                            </div>
                                                                        {else}
                                                                            <div class="make-switch" data-on="primary" data-off="default">
                                                                                <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[enable_guest_register]" id="supercheckout_enable_guest_register"/>
                                                                            </div>
                                                                        {/if}
                                                                    {/if}
                                                                </td>
                                                            </tr>
                                                            <tr><td class="name vertical_top_align"><span class="control-label" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='If set to OFF, it will hide delivery adress automatically and show invoice address for cart with virual products only' mod='supercheckout'}">{l s='Delivery address for virtual cart' mod='supercheckout'}: </span>
{*                                                                    <i class="icon-question-sign supercheckout-tooltip-color" ></i>*}
                                                                </td>
                                                                <td class="settings">
                                                                    <input type="hidden" value="0" name="velocity_supercheckout[hide_delivery_for_virtual]" />
                                                                    {if $velocity_supercheckout['hide_delivery_for_virtual'] eq 1}
                                                                        {if $IE7 eq true}
                                                                            <div>
                                                                                <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[hide_delivery_for_virtual]" id="supercheckout_hide_delivery_for_virtual" checked="checked" />
                                                                            </div>
                                                                        {else}
                                                                            <div class="make-switch" data-on="primary" data-off="default">
                                                                                <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[hide_delivery_for_virtual]" id="supercheckout_hide_delivery_for_virtual" checked="checked" />
                                                                            </div>
                                                                        {/if}                                                                    
                                                                    {else}
                                                                        {if $IE7 eq true}
                                                                            <div>
                                                                                <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[hide_delivery_for_virtual]" id="supercheckout_hide_delivery_for_virtual" />
                                                                            </div>
                                                                        {else}
                                                                            <div class="make-switch" data-on="primary" data-off="default">
                                                                                <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[hide_delivery_for_virtual]" id="supercheckout_hide_delivery_for_virtual"/>
                                                                            </div>
                                                                        {/if}
                                                                    {/if}
                                                                </td><tr>
                                                            <tr>
                                                                <td class="name vertical_top_align">
                                                                    <span>{l s='Default Option at Checkout' mod='supercheckout'}: </span><i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Default Option at Checkout Tooltip' mod='supercheckout'}"></i>
                                                                </td>
                                                                <td class="left settings">
                                                                    <div class="widget-body uniformjs" style="padding: 0 !important;">
                                                                        <label class="radio coupon_type_radio">
                                                                            <input type="radio" class="radio coupon_type_radio" name="velocity_supercheckout[checkout_option]" value="0"  {if $velocity_supercheckout['checkout_option'] eq 0} checked="checked" {/if} />{l s='Login' mod='supercheckout'}                                                                        
                                                                        </label>
                                                                        <label class="radio coupon_type_radio">
                                                                            <input type="radio" class="radio coupon_type_radio" name="velocity_supercheckout[checkout_option]" value="1" {if $velocity_supercheckout['checkout_option'] eq 1} checked="checked" {/if} />{l s='Guest' mod='supercheckout'}                                                                        
                                                                        </label>
                                                                        <label class="radio coupon_type_radio">
                                                                            <input type="radio" class="radio coupon_type_radio" name="velocity_supercheckout[checkout_option]" value="2" {if $velocity_supercheckout['checkout_option'] eq 2} checked="checked" {/if} />{l s='Register' mod='supercheckout'}                                                                        
                                                                        </label>
                                                                    </div>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="name vertical_top_align"><span class="control-label">{l s='Testing Mode' mod='supercheckout'}: </span>                                                                
                                                                    <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enable this if you want to test this plugin before making it live.' mod='supercheckout'}"></i>                                                                            
                                                                </td>
                                                                <td class="settings">
                                                                    <input type="hidden" value="0" name="velocity_supercheckout[super_test_mode]" />
                                                                    {if isset($velocity_supercheckout['super_test_mode']) and $velocity_supercheckout['super_test_mode'] eq 1}
                                                                        {if $IE7 eq true}
                                                                            <div>
                                                                                <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[super_test_mode]" id="supercheckout_test_mode" checked="checked" />
                                                                            </div>
                                                                        {else}
                                                                            <div class="make-switch" data-on="primary" data-off="default">
                                                                                <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[super_test_mode]" id="supercheckout_test_mode" checked="checked" />
                                                                            </div>
                                                                        {/if}                                                                    
                                                                    {else}
                                                                        {if $IE7 eq true}
                                                                            <div>
                                                                                <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[super_test_mode]" id="supercheckout_test_mode" />
                                                                            </div>
                                                                        {else}
                                                                            <div class="make-switch" data-on="primary" data-off="default">
                                                                                <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[super_test_mode]" id="supercheckout_test_mode"/>
                                                                            </div>
                                                                        {/if}
                                                                    {/if}                                                                            
                                                                </td>
                                                            </tr>
                                                            <tr id="front_module_url" style="display: none;">
                                                                <td colspan="2">
                                                                    <div class="span" style="padding:20px;">
                                                                        <div>
                                                                            <div style="float:left;"><b>{l s='Testing URL' mod='supercheckout'}:</b></div>


                                                                            <div style="float:left;width: 400px;border-radius: 3px;margin-left: 10px;border: 1px solid #E2E2E2;padding: 5px;">
                                                                                <input type="text" value="{$module_url}" id="testurlfield">
                                                                            </div> 
                                                                            <div style="
                                                                                 float: left;
                                                                                 margin-left: 10px;
                                                                                 "><input type="button" data-clipboard-action="copy" data-clipboard-target="#testurlfield" value="{l s='Copy' mod='supercheckout'}" class="testurlbutton btn" style="
                                                                                     width: 100px;
                                                                                     color: #FFFFFF;
                                                                                     font-weight: bold;
                                                                                     background: #2EACCE;
                                                                                     "></div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>

                                                                     {* Added by Anshul to show the checkout on IP address bais *}
                                                                     <tr style="display:none;">
                                                                <td class="name vertical_top_align"><span class="control-label">{l s='Add IP Address' mod='supercheckout'}: </span>                                                                
                                                                    <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Add IP address for which you want to enable the Supercheckout. Leave empty if want to show everywhere. You can add multiple IP addresses only by separating using comma(,).' mod='supercheckout'}"></i>
                                                                </td>
                                                                <td class="settings">
                                                                    <span style="display: inline-block;width:90%;">
                                                                        <input type="text" class="text-width" name="velocity_supercheckout[ip_addresses]" value="{if isset($velocity_supercheckout['ip_addresses'])}{$velocity_supercheckout['ip_addresses']|escape:'htmlall':'UTF-8'}{/if}" />
                                                                        <input type="hidden" class="text-width" name="velocity_supercheckout[[ip_addresses]" value="{if isset($velocity_supercheckout['ip_addresses'])}{$velocity_supercheckout['ip_addresses']|escape:'htmlall':'UTF-8'}{/if}" />
                                                                    </span><br>
                                                                    <span class="velsof_sc_desc">{l s='In case of multiple IPs, press enter after entering the one IP.' mod='supercheckout'}</span>
                                                                    <span id="ip_addresses_error" class="supercheckout_error" ></span>
                                                                </td>
                                                            </tr>
                                                            {* Added by Anshul to show the checkout on IP address bais *}
                                                            {* Added by Anshul to show the free shipping banner *}
                                                            <tr >
                                                                <td class="name vertical_top_align"><span class="control-label">{l s='Add Minimum Amount for showing free shipping banner' mod='supercheckout'}: </span>                                                                
                                                                    <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Add the amount after which the free shipping will applicable. Please note that this feature is only to inform a client about the free shipping amount so you will enter the amount here according to your free shipping setting.' mod='supercheckout'}"></i>
                                                                </td>
                                                                <td class="settings">
                                                                    <span style="display: inline-block;width:90%;">
                                                                        <input style="width:20%" type="text" class="text-width" name="velocity_supercheckout[free_shipping_amount]" value="{if isset($velocity_supercheckout['free_shipping_amount'])}{$velocity_supercheckout['free_shipping_amount']|escape:'htmlall':'UTF-8'}{/if}" />
                                                                    </span><br>
                                                                    <p id="free_shipping_amount_error" class="supercheckout_error" ></p>
                                                                    <span class="velsof_sc_desc" style="font-size:11px">{l s='Leave this field empty if do not want to show Free Shipping banner and it will work on the basis of default currency. Please do not add currency sign here.' mod='supercheckout'}</span>
                                                                </td>
                                                            </tr>
                                                            {* Added by Anshul to show the free shipping banner *}

                                                             

                                                        </table>

                                                    </div>

                                                    <!--<div class="row">
                                                        <div class="span">
                                                            <p style="margin-bottom: 0; margin-right: 5px">
                                                                <span style="font-weight: bold; font-size: 15px;">Note:</span>
                                                                <span style="color: black; margin-left: 5px;">Please Make sure that <span style='color: red;'>Advanced Parameters-> Performance-> Debug Mode-> Disable all overrides button-> is Set to No</span>.</span>
                                                                </p>
                                                        </div>
                                                    </div>
                                                <hr style="margin-top:5px;">-->
                                                </div>

                                                <!--------------- End - General Settings -------------------->
                                                <!--------------- Start - Customize -------------------->
                                                <div id="tab_customizer" class="tab-pane tab-form">
                                                    <div class="block">
                                                        <h4 class='velsof-tab-heading' style="font-size: 20px;" >{l s='Customizer' mod='supercheckout'}</h4>
                                                        <table class="form">

                                                            <tr>
                                                                <td class="name vertical_top_align" style="padding: 15px;">
                                                                    <span>{l s='Button Background Color' mod='supercheckout'}: </span><i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="bottom" data-original-title="{l s='Change the Button Background Color' mod='supercheckout'}"></i>
                                                                </td>
                                                                <td class="settings" style="padding: 15px;">
                                                                    <div class="widget-body uniformjs" style="padding: 0 !important;">

                                                                        <input type="text" class="color form-control colorizer-input" onchange="bg_changer(this.color);" name="velocity_supercheckout[customizer][button_color]"  value="{$velocity_supercheckout['customizer']['button_color']}"/>

                                                                    </div>
                                                                </td>
                                                                <td>&nbsp;</td>

                                                            </tr>
                                                            <tr>
                                                                <td class="name vertical_top_align" style="padding: 15px;">
                                                                    <span>{l s='Button Border Color' mod='supercheckout'}: </span><i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Change the Button Border Color' mod='supercheckout'}"></i>
                                                                </td>
                                                                <td class="settings" style="padding: 15px;">
                                                                    <div class="widget-body uniformjs" style="padding: 0 !important;">

                                                                        <input type="text" class="color form-control colorizer-input"  onchange="border_changer(this.color);" name="velocity_supercheckout[customizer][button_border_color]" value="{$velocity_supercheckout['customizer']['button_border_color']}"/>



                                                                        <div id="button_preview" style="background-color:#{$velocity_supercheckout['customizer']['button_color']};border: 1px solid #{$velocity_supercheckout['customizer']['button_border_color']} !important;color: #{$velocity_supercheckout['customizer']['button_text_color']} !important;border-bottom:3px solid #{$velocity_supercheckout['customizer']['border_bottom_color']} !important;width: 160px;

                                                                             display: inline-block;
                                                                             text-align: center;
                                                                             float: left;
                                                                             margin-left: 65%;
                                                                             padding: 10px;
                                                                             font-size: 16px;
                                                                             border-radius: 5px;
                                                                             margin-top: -38px;
                                                                             ">
                                                                            <span> {l s='Button Preview' mod='supercheckout'}</span>
                                                                        </div>
                                                                    </div>
                                                                </td>



                                                            </tr>
                                                            <tr>
                                                                <td class="name vertical_top_align" style="padding: 15px;">
                                                                    <span>{l s='Button Text Color' mod='supercheckout'}: </span><i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Change the Button Text Color' mod='supercheckout'}"></i>
                                                                </td>
                                                                <td class="settings" style="padding: 15px;">
                                                                    <div class="widget-body uniformjs" style="padding: 0 !important;">

                                                                        <input type="text" class="color form-control colorizer-input"  onchange="text_changer(this.color);"name="velocity_supercheckout[customizer][button_text_color]"  value="{$velocity_supercheckout['customizer']['button_text_color']}"/>


                                                                    </div>
                                                                </td>

                                                            </tr>

                                                            <tr>
                                                                <td class="name vertical_top_align" style="padding: 15px;">
                                                                    <span>{l s='Button Border Bottom Color' mod='supercheckout'}: </span><i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Change the Button Border Bottom Color' mod='supercheckout'}"></i>
                                                                </td>
                                                                <td class="settings" style="padding: 15px;">
                                                                    <div class="widget-body uniformjs" style="padding: 0 !important;">

                                                                        <input type="text" class="color form-control colorizer-input" onchange="border_bottom_changer(this.color);"name="velocity_supercheckout[customizer][border_bottom_color]"  value="{$velocity_supercheckout['customizer']['border_bottom_color']}"  />


                                                                    </div>
                                                                </td>

                                                            </tr>
                                                            
                                                            {* Start: Added By Anshul*}
                                                            <tr>
                                                                <td class="name vertical_top_align" style="padding: 15px;">
                                                                    <span>{l s='My Account Button Background Color' mod='supercheckout'}: </span><i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="bottom" data-original-title="{l s='Change the My Account Button Background Color' mod='supercheckout'}"></i>
                                                                </td>
                                                                <td class="settings" style="padding: 15px;">
                                                                    <div class="widget-body uniformjs" style="padding: 0 !important;">

                                                                        <input type="text" class="color form-control colorizer-input" onchange="" name="velocity_supercheckout[customizer][my_account_button_color]"  value="{$velocity_supercheckout['customizer']['my_account_button_color']}"/>

                                                                    </div>
                                                                </td>
                                                                <td>&nbsp;</td>

                                                            </tr>
                                                            <tr>
                                                                <td class="name vertical_top_align" style="padding: 15px;">
                                                                    <span>{l s='Logout Button Background Color' mod='supercheckout'}: </span><i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="bottom" data-original-title="{l s='Change the Log out Button Background Color' mod='supercheckout'}"></i>
                                                                </td>
                                                                <td class="settings" style="padding: 15px;">
                                                                    <div class="widget-body uniformjs" style="padding: 0 !important;">

                                                                        <input type="text" class="color form-control colorizer-input" onchange="" name="velocity_supercheckout[customizer][logout_button_color]"  value="{$velocity_supercheckout['customizer']['logout_button_color']}"/>

                                                                    </div>
                                                                </td>
                                                                <td>&nbsp;</td>

                                                            </tr>
                                                            <tr>
                                                                <td class="name vertical_top_align" style="padding: 15px;">
                                                                    <span>{l s='Update Address Button Background Color' mod='supercheckout'}: </span><i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="bottom" data-original-title="{l s='Change the Update Address Button Background Color' mod='supercheckout'}"></i>
                                                                </td>
                                                                <td class="settings" style="padding: 15px;">
                                                                    <div class="widget-body uniformjs" style="padding: 0 !important;">

                                                                        <input type="text" class="color form-control colorizer-input" onchange="" name="velocity_supercheckout[customizer][update_address_button_color]"  value="{$velocity_supercheckout['customizer']['update_address_button_color']}"/>

                                                                    </div>
                                                                </td>
                                                                <td>&nbsp;</td>

                                                            </tr>
                                                            <tr>
                                                                <td class="name vertical_top_align" style="padding: 15px;">
                                                                    <span>{l s='Remove Address Button Background Color' mod='supercheckout'}: </span><i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="bottom" data-original-title="{l s='Change the Remove Button Background Color' mod='supercheckout'}"></i>
                                                                </td>
                                                                <td class="settings" style="padding: 15px;">
                                                                    <div class="widget-body uniformjs" style="padding: 0 !important;">

                                                                        <input type="text" class="color form-control colorizer-input" onchange="" name="velocity_supercheckout[customizer][remove_address_button_color]"  value="{$velocity_supercheckout['customizer']['remove_address_button_color']}"/>

                                                                    </div>
                                                                </td>
                                                                <td>&nbsp;</td>

                                                            </tr>
                                                            
                                                            <tr>
                                                                <td class="name vertical_top_align" style="padding: 15px;">
                                                                    <span>{l s='Progress Bar Background Color' mod='supercheckout'}: </span><i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="bottom" data-original-title="{l s='Change the Progress Bar Background Color' mod='supercheckout'}"></i>
                                                                </td>
                                                                <td class="settings" style="padding: 15px;">
                                                                    <div class="widget-body uniformjs" style="padding: 0 !important;">

                                                                        <input type="text" class="color form-control colorizer-input" onchange="" name="velocity_supercheckout[customizer][progressbar_button_color]"  value="{$velocity_supercheckout['customizer']['progressbar_button_color']}"/>

                                                                    </div>
                                                                </td>
                                                                <td>&nbsp;</td>

                                                            </tr>
                                                            <tr>
                                                                <td class="name vertical_top_align" style="padding: 15px;">
                                                                    <span>{l s='Color for Heading Edge, Quantity up & down, checkboxes' mod='supercheckout'}: </span><i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="bottom" data-original-title="{l s='Change the Heading Edge, Quantity up & down, checkboxes colors.' mod='supercheckout'}"></i>
                                                                </td>
                                                                <td class="settings" style="padding: 15px;">
                                                                    <div class="widget-body uniformjs" style="padding: 0 !important;">

                                                                        <input type="text" class="color form-control colorizer-input" onchange="" name="velocity_supercheckout[customizer][theme_common_color]"  value="{$velocity_supercheckout['customizer']['theme_common_color']}"/>

                                                                    </div>
                                                                </td>
                                                                <td>&nbsp;</td>

                                                            </tr>
                                                            <tr>
                                                                <td class="name vertical_top_align" style="padding: 15px;">
                                                                    <span>{l s='Product Name Color' mod='supercheckout'}: </span><i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="bottom" data-original-title="{l s='Change the product name color.' mod='supercheckout'}"></i>
                                                                </td>
                                                                <td class="settings" style="padding: 15px;">
                                                                    <div class="widget-body uniformjs" style="padding: 0 !important;">

                                                                        <input type="text" class="color form-control colorizer-input" onchange="" name="velocity_supercheckout[customizer][product_name_color]"  value="{$velocity_supercheckout['customizer']['product_name_color']}"/>

                                                                    </div>
                                                                </td>
                                                                <td>&nbsp;</td>

                                                            </tr>
                                                            {* End: Added By Anshul*}
                                                            
                                                            <tr>
                                                                <td class="name vertical_top_align" style="padding: 15px;">
                                                                    <span>{l s='Custom CSS' mod='supercheckout'}: </span><i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Provide some CSS code for changes in the front end of SuperCheckout' mod='supercheckout'}"></i>
                                                                </td>
                                                                <td class="settings" style="padding: 15px;">
                                                                    <textarea rows="5" style="resize: both;" class="vss_sc_ver15" name="velocity_supercheckout[custom_css]">{if isset($velocity_supercheckout['custom_css'])}{$velocity_supercheckout['custom_css'] nofilter}{*escape not required as contains CSS*}{/if}</textarea>
                                                                </td>                                                                        
                                                            </tr>
                                                            <tr>
                                                                <td class="name vertical_top_align" style="padding: 15px;">
                                                                    <span>{l s='Custom JS' mod='supercheckout'}: </span><i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Provide some javascript code for changes in the front end of SuperCheckout' mod='supercheckout'}"></i>
                                                                </td>
                                                                <td class="settings" style="padding: 15px;">
                                                                    <textarea rows="5" style="resize: both;" class="vss_sc_ver15" name="velocity_supercheckout[custom_js]">{if isset($velocity_supercheckout['custom_js'])}{$velocity_supercheckout['custom_js'] nofilter}{*escape not required as contains JS*}{/if}</textarea>
                                                                </td>                                                                        
                                                            </tr>


                                                        </table>

                                                    </div>
                                                </div>
                                                <!--------------- End - Customize -------------------->

                                                <!--------------- Start - Login -------------------->

                                                <div id="tab_login" class="tab-pane tab-form">
                                                    <div class="block">
                                                        <h4 class='velsof-tab-heading'>{l s='Login' mod='supercheckout'}<span class="mandatory_notify">{l s='(*) are mandatory fields' mod='supercheckout'}</span></h4>
                                                        <div class="block">
                                                            <table class="form">
                                                                {if isset($velocity_supercheckout['mobile_login_active'])}
                                                                    <tr>
                                                                        <td class="name vertical_top_align" ><span class="control-label">{l s='Compatible With Mobile Login' mod='supercheckout'}: </span>                                                                
                                                                            <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="bottom" data-original-title="{l s='Make it compatible with Knowband Mobile Login Module' mod='supercheckout'}"></i>
                                                                        </td>
                                                                        <td class="settings" style="padding-bottom:10px;">
                                                                            <input type="hidden" value="0" name="velocity_supercheckout[mobile_login][enable]" />
                                                                            {if isset($velocity_supercheckout['mobile_login']['enable']) && $velocity_supercheckout['mobile_login']['enable'] eq 1}
                                                                                {if $IE7 eq true}
                                                                                    <div>
                                                                                        <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[mobile_login][enable]" id="supercheckout_mobile_login" checked="checked" />
                                                                                    </div>
                                                                                {else}
                                                                                    <div class="make-switch" data-on="primary" data-off="default">
                                                                                        <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[mobile_login][enable]" id="supercheckout_mobile_login" checked="checked" />
                                                                                    </div>
                                                                                {/if}                                                                    
                                                                            {else}
                                                                                {if $IE7 eq true}
                                                                                    <div>
                                                                                        <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[mobile_login][enable]" id="supercheckout_mobile_login" />
                                                                                    </div>
                                                                                {else}
                                                                                    <div class="make-switch" data-on="primary" data-off="default">
                                                                                        <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[mobile_login][enable]" id="supercheckout_mobile_login"/>
                                                                                    </div>
                                                                                {/if}
                                                                            {/if}
                                                                        </td>
                                                                    </tr>
                                                                {/if}

                                                                <tr>
                                                                    <td class="name vertical_top_align" ><span class="control-label">{l s='Show popup' mod='supercheckout'}: </span>                                                                
                                                                        <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="bottom" data-original-title="{l s='Show popup rather than redirect when customer clicks on Facebook or Google button' mod='supercheckout'}"></i>
                                                                    </td>
                                                                    <td class="settings" style="padding-bottom:10px;">
                                                                        <input type="hidden" value="0" name="velocity_supercheckout[social_login_popup][enable]" />
                                                                        {if $velocity_supercheckout['social_login_popup']['enable'] eq 1}
                                                                            {if $IE7 eq true}
                                                                                <div>
                                                                                    <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[social_login_popup][enable]" id="supercheckout_social_login_popup" checked="checked" />
                                                                                </div>
                                                                            {else}
                                                                                <div class="make-switch" data-on="primary" data-off="default">
                                                                                    <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[social_login_popup][enable]" id="supercheckout_social_login_popup" checked="checked" />
                                                                                </div>
                                                                            {/if}                                                                    
                                                                        {else}
                                                                            {if $IE7 eq true}
                                                                                <div>
                                                                                    <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[social_login_popup][enable]" id="supercheckout_social_login_popup" />
                                                                                </div>
                                                                            {else}
                                                                                <div class="make-switch" data-on="primary" data-off="default">
                                                                                    <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[social_login_popup][enable]" id="supercheckout_social_login_popup"/>
                                                                                </div>
                                                                            {/if}
                                                                        {/if}
                                                                    </td>
                                                                </tr>


                                                                <td class="name vertical_top_align"><span class="control-label">{l s='Enable Facebook Login' mod='supercheckout'}: </span>  
                                                                    <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enable Facebook Login Tooltip' mod='supercheckout'}"></i>
                                                                </td>
                                                                <td class="settings">
                                                                    <input type="hidden" value="0" name="velocity_supercheckout[fb_login][enable]" />
                                                                    {if $velocity_supercheckout['fb_login']['enable'] eq 1}
                                                                        {if $IE7 eq true}
                                                                            <div>
                                                                                <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[fb_login][enable]" id="supercheckout_fb_login" checked="checked" />
                                                                            </div>
                                                                        {else}
                                                                            <div class="make-switch" data-on="primary" data-off="default">
                                                                                <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[fb_login][enable]" id="supercheckout_fb_login" checked="checked" />
                                                                            </div>
                                                                        {/if}                                                                    
                                                                    {else}
                                                                        {if $IE7 eq true}
                                                                            <div>
                                                                                <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[fb_login][enable]" id="supercheckout_fb_login" />
                                                                            </div>
                                                                        {else}
                                                                            <div class="make-switch" data-on="primary" data-off="default">
                                                                                <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[fb_login][enable]" id="supercheckout_fb_login"/>
                                                                            </div>
                                                                        {/if}
                                                                    {/if}
                                                                    <span class="pad-right" style="font-size:14px;font-weight:500;float:right; "><a href="javascript:void(0)" onclick="configurationAccordian('facebook');" {if $ps_version eq 15}style="color: #428bca;"{/if}>{l s='Click here to see Steps to configure Facebook app ' mod='supercheckout'}</a></span>
                                                                </td>
                                                                </tr>

                                                                <tr>
                                                                    <td class="name vertical_top_align"><span class="control-label"><span class="asterisk">*</span>{l s='Facebook App Id' mod='supercheckout'}: </span>                                                                
                                                                        <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Facebook App Id Tooltip' mod='supercheckout'}"></i>
                                                                    </td>
                                                                    <td class="settings">
                                                                        <input type="text" class="text-width" name="velocity_supercheckout[fb_login][app_id]" id="velocity_supercheckout_fb_app_id" value="{$velocity_supercheckout['fb_login']['app_id']}"/>
                                                                        <span id="fb_app_id_error" class="supercheckout_error" ></span>
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td class="name vertical_top_align" ><span class="control-label"><span class="asterisk">*</span>{l s='Facebook App Secret' mod='supercheckout'}: </span>                                                                
                                                                        <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Facebook App Secret Tooltip' mod='supercheckout'}"></i>
                                                                    </td>
                                                                    <td class="settings" >
                                                                        <input type="text" class="text-width" name="velocity_supercheckout[fb_login][app_secret]" id="velocity_supercheckout_fb_app_secret" value="{$velocity_supercheckout['fb_login']['app_secret']}"/>
                                                                        <span id="fb_app_secret_error" class="supercheckout_error" ></span>
                                                                    </td>

                                                                <tr>
                                                                    <td class="name vertical_top_align"><span class="control-label">{l s='Enable Google Login' mod='supercheckout'}: </span>                                                                
                                                                        <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enable Google Login Tooltip' mod='supercheckout'}"></i>
                                                                    </td>
                                                                    <td class="settings">
                                                                        <input type="hidden" value="0" name="velocity_supercheckout[google_login][enable]" />
                                                                        {if $velocity_supercheckout['google_login']['enable'] eq 1}
                                                                            {if $IE7 eq true}
                                                                                <div>
                                                                                    <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[google_login][enable]" id="supercheckout_google_login" checked="checked" />
                                                                                </div>
                                                                            {else}
                                                                                <div class="make-switch" data-on="primary" data-off="default">
                                                                                    <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[google_login][enable]" id="supercheckout_google_login" checked="checked" />
                                                                                </div>
                                                                            {/if}                                                                    
                                                                        {else}
                                                                            {if $IE7 eq true}
                                                                                <div>
                                                                                    <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[google_login][enable]" id="supercheckout_google_login" />
                                                                                </div>
                                                                            {else}
                                                                                <div class="make-switch" data-on="primary" data-off="default">
                                                                                    <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[google_login][enable]" id="supercheckout_google_login"/>
                                                                                </div>
                                                                            {/if}
                                                                        {/if}
                                                                        <span class="pad-right" style="font-size:14px;font-weight:500;float:right;"><a href="javascript:void(0)" onclick="configurationAccordian('google');" {if $ps_version eq 15}style="color: #428bca;"{/if}>{l s='Click here to see Steps to configure Google App ' mod='supercheckout'}</a></span>
                                                                    </td>
                                                                </tr>

                                                                <tr style="display: none;">
                                                                    <td class="name vertical_top_align"><span class="control-label">{l s='Google App Id' mod='supercheckout'}: </span>                                                                
                                                                        <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Google App Id Tooltip' mod='supercheckout'}"></i>
                                                                    </td>
                                                                    <td class="settings">
                                                                        <input type="text" class="text-width" name="velocity_supercheckout[google_login][app_id]" value="{$velocity_supercheckout['google_login']['app_id']}"/>
                                                                        <span id="gl_app_id_error" class="supercheckout_error" ></span>
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td class="name vertical_top_align"><span class="control-label"><span class="asterisk">*</span>{l s='Google Client Id' mod='supercheckout'}: </span>                                                                
                                                                        <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Google Client Id Tooltip' mod='supercheckout'}"></i>
                                                                    </td>
                                                                    <td class="settings">
                                                                        <input type="text" class="text-width" name="velocity_supercheckout[google_login][client_id]" id="velocity_supercheckout_ggl_client_id" value="{$velocity_supercheckout['google_login']['client_id']}"/>
                                                                        <span id="gl_client_id_error" class="supercheckout_error" ></span>
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td class="name vertical_top_align"><span class="control-label"><span class="asterisk">*</span>{l s='Google App Secret' mod='supercheckout'}: </span>                                                                
                                                                        <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Google App Secret Tooltip' mod='supercheckout'}"></i>
                                                                    </td>
                                                                    <td class="settings">
                                                                        <input type="text" class="text-width" name="velocity_supercheckout[google_login][app_secret]" id="velocity_supercheckout_ggl_app_secret" value="{$velocity_supercheckout['google_login']['app_secret']}"/>
                                                                        <span id="gl_app_secret_error" class="supercheckout_error" ></span>
                                                                    </td>
                                                                </tr>

                                                            </table>

                                                            <div style= "  text-align:center;padding: 25px; height:140px;margin: 40px;margin-bottom:0px; background: aliceblue;{if $ps_version eq 15}height: 100px;{/if}" id="loginizer_link">
                                                                <div><span style="font-size:18px;" >{l s='Want to add more social login options for your customers?' mod='supercheckout'}</span>
                                                                    <br>
                                                                    <br>
                                                                    <a target="_blank" href="http://addons.prestashop.com/en/social-commerce-facebook-prestashop-modules/18220-social-network-for-login-9-in-1-fast-secure.html"><span style="margin-left:30%;max-width:40% !important;font-size:18px;" class='btn btn-block btn-success action-btn'>{l s='Add more buttons' mod='supercheckout'}</span></a><div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div id="facebook_acc" style="display:none;">
                                                                <h4 class='velsof-tab-heading'>{l s='Steps To Configure Facebook App:' mod='supercheckout'}</h4>
                                                                <div id="facebook_accordian" class="accordian_container">
                                                                    <h3>{l s='Step 1' mod='supercheckout'} </h3>
                                                                    <div class="accdiv">
                                                                        <span class="pad-right"><a href="https://developers.facebook.com/apps/" target="_blank" style="color: #00aff0;">{l s='Click here to get Facebook app id and app secret' mod='supercheckout'}</a></span>
                                                                    </div>
                                                                    
                                                                    <h3>{l s='Step 2' mod='supercheckout'}</h3>
                                                                    <div class="accdiv">
                                                                             <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/admin/manual_steps/facebook/facebook2.jpg' />
                                                                    </div>
                                                                    <h3>{l s='Step 3, 4' mod='supercheckout'}</h3>
                                                                    <div class="accdiv">
                                                                             <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/admin/manual_steps/facebook/facebook3,4.jpg' />
                                                                    </div>
                                                                    <h3>{l s='Step 5' mod='supercheckout'}</h3>
                                                                    <div class="accdiv">
                                                                        <pre><b>{l s='Click on Add Product screen, Click on Facebook Login Setup Button' mod='supercheckout'}</b></pre>
                                                                        <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/admin/manual_steps/facebook/Step_5.png' />
                                                                             
                                                                        <pre style="margin-top:30px"><b>{l s='Now Click on the Setting under Facebook Login' mod='supercheckout'}</b></pre>
                                                                        <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/admin/manual_steps/facebook/Step_6.png' />                                                                             
                                                                        <pre style="margin-top:30px"><b>{l s='Enter the following URL in Valid OAuth Redirect URL' mod='supercheckout'}</b>
                                                                        <br/>{$front_root_url|escape:'htmlall':'UTF-8'}{'index.php?fc=module&module=supercheckout&controller=supercheckout&login_type=fb'}                                                                        
                                                                        </pre>
                                                                        <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/admin/manual_steps/facebook/Step_7.png' />                                                                                                                                                          
                                                                    </div>
                                                                    <h3>{l s='Step 6' mod='supercheckout'}</h3>
                                                                    <div class="accdiv">
                                                                        <pre><b>{l s='Now go to Settings -> Basics from left menu' mod='supercheckout'}</b></pre>
                                                                         <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/admin/manual_steps/facebook/facebook6.jpg' />
                                                                    </div>
                                                                    <h3>{l s='Step 7' mod='supercheckout'}</h3>
                                                                    <div class="accdiv">
                                                                             <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/admin/manual_steps/facebook/facebook7.jpg' />
                                                                    </div>
                                                                    <h3>{l s='Step 8, 9, 10' mod='supercheckout'}</h3>
                                                                    <div class="accdiv">
                                                                            <pre><b>{l s='For Step #8 use App Domain: ' mod='supercheckout'}</b>{$domain|escape:'htmlall':'UTF-8'}<br><b>{l s='For Step #9 use Site Url: ' mod='supercheckout'}</b>{$manual_dir|escape:'htmlall':'UTF-8'}</pre>
                                                                            <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/admin/manual_steps/facebook/facebook8,9,10.jpg' />
                                                                    </div>
                                                                    <h3>{l s='Step 11, 12' mod='supercheckout'}</h3>
                                                                    <div class="accdiv">
                                                                            <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/admin/manual_steps/facebook/facebook11,12.jpg' />
                                                                    </div>
                                                                    <h3>{l s='Step 13, 14' mod='supercheckout'}</h3>
                                                                    <div class="accdiv">
                                                                             <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/admin/manual_steps/facebook/facebook13,14.jpg' />
                                                                    </div>
                                                                    <h3>{l s='Step 15, 16' mod='supercheckout'}</h3>
                                                                    <div class="accdiv">
                                                                             <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/admin/manual_steps/facebook/facebook15,16.jpg' />
                                                                    </div>
                                                                    <h3>{l s='Step 17, 18' mod='supercheckout'}</h3>
                                                                    <div class="accdiv">
                                                                             <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/admin/manual_steps/facebook/facebook17,18.png' />
                                                                    </div>

                                                                </div>
                                                            </div>
                                                            <div id="google_acc" style="display:none;">
                                                                <h4 class='velsof-tab-heading'>{l s='Steps To Configure Google App:' mod='supercheckout'}</h4>
                                                                <div id="google_accordian" class="accordian_container">
                                                                    <h3>{l s='Step 1' mod='supercheckout'}</h3>
                                                                    <div class="accdiv">
                                                                        <span class="pad-right"><a href="https://console.developers.google.com/project" target="_blank" style="color: #00aff0;">{l s='Click here to get Google  client id and client secret' mod='supercheckout'}</a></span>
                                                                    </div>
                                                                    <h3>{l s='Step 2, 3' mod='supercheckout'}</h3>
                                                                    <div class="accdiv">
                                                                             <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/admin/manual_steps/google/google_3.png' />
                                                                    </div>
                                                                    <h3>{l s='Step 4, 5' mod='supercheckout'}</h3>
                                                                    <div class="accdiv">
                                                                             <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/admin/manual_steps/google/google_4_5.png' />
                                                                    </div>
                                                                    <h3>{l s='Step 6, 7, 8' mod='supercheckout'}</h3>
                                                                    <div class="accdiv">
                                                                             <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/admin/manual_steps/google/google6,7,8.jpg' />
                                                                    </div>
                                                                    <h3>{l s='Step 9' mod='supercheckout'}</h3>
                                                                    <div class="accdiv">
                                                                             <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/admin/manual_steps/google/google9.jpg' />
                                                                    </div>
                                                                    <h3>{l s='Step 10, 11' mod='supercheckout'}</h3>
                                                                    <div class="accdiv">
                                                                             <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/admin/manual_steps/google/google10,11.jpg' />
                                                                    </div>
                                                                    <h3>{l s='Step 12, 13, 14, 15' mod='supercheckout'}</h3>
                                                                    <div class="accdiv">
                                                                            <pre><b>{l s='Use Authorized javascript origins: ' mod='supercheckout'}</b>{$manual_dir|escape:'htmlall':'UTF-8'}</b></br><b>{l s='Use Authorized Redirect Url: ' mod='supercheckout'}</b>{$front_root_url|escape:'htmlall':'UTF-8'}{'index.php?fc=module&module=supercheckout&controller=supercheckout&login_type=google'}</pre>
                                                                             <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/admin/manual_steps/google/google12,13,14,15.jpg' />
                                                                    </div>
                                                                    <h3>{l s='Step 16, 17' mod='supercheckout'}</h3>
                                                                    <div class="accdiv">
                                                                             <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/admin/manual_steps/google/google16,17.jpg' />
                                                                    </div>
                                                                    <h3>{l s='Step 18, 19' mod='supercheckout'}</h3>
                                                                    <div class="accdiv">
                                                                             <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/admin/manual_steps/google/google18,19.png' />
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>    
                                                    </div>
                                                </div>

                                                <!--------------- End - Login -------------------->    

                                                <!--------------- Start - Mailchimp -------------------->

                                                <div id="tab_mailchimp" class="tab-pane tab-form">
                                                    <div class="block">
                                                        <h4 class='velsof-tab-heading'>{l s='Email Marketing' mod='supercheckout'}</h4>
                                                        <div class="block">
                                                            <table class="form">
                                                                <tr>
                                                                    <td class="name vertical_top_align"><span class="control-label">{l s='Enable MailChimp' mod='supercheckout'}: </span>                                                                
                                                                        <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="bottom" data-original-title="{l s='Enable/Disable Mailchimp' mod='supercheckout'}"></i>
                                                                    </td>
                                                                    <td class="settings">
                                                                        <input type="hidden" value="0" name="velocity_supercheckout[mailchimp][enable]" />
                                                                        {if $velocity_supercheckout['mailchimp']['enable'] eq 1}
                                                                            {if $IE7 eq true}
                                                                                <div>
                                                                                    <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[mailchimp][enable]" id="supercheckout_mailchimp_enable" checked="checked" />
                                                                                </div>
                                                                            {else}
                                                                                <div class="make-switch" data-on="primary" data-off="default">
                                                                                    <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[mailchimp][enable]" id="supercheckout_mailchimp_enable" checked="checked" />
                                                                                </div>
                                                                            {/if}                                                                    
                                                                        {else}
                                                                            {if $IE7 eq true}
                                                                                <div>
                                                                                    <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[mailchimp][enable]" id="supercheckout_mailchimp_enable" />
                                                                                </div>
                                                                            {else}
                                                                                <div class="make-switch" data-on="primary" data-off="default">
                                                                                    <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[mailchimp][enable]" id="supercheckout_mailchimp_enable"/>
                                                                                </div>
                                                                            {/if}
                                                                        {/if}
                                                                        <div class="widget-body uniformjs" style="padding-top: 1%;">


                                                                            <label class="checkboxinline no-bold">
                                                                                <input type="checkbox" class="checkbox input-checkbox-option" name="velocity_supercheckout[mailchimp][default]" value="1" {if isset($velocity_supercheckout['mailchimp']['default']) && $velocity_supercheckout['mailchimp']['default'] eq 1}checked="checked"{/if} />{l s='Subscribe customers as soon as they come out from Email field' mod='supercheckout'}                                                                        
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="name vertical_top_align"><span class="control-label">{l s='MailChimp Api Key' mod='supercheckout'}: </span>                                                                
                                                                        <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enter MailChimp Api Key' mod='supercheckout'}"></i>
                                                                    </td>
                                                                    <td class="settings">
                                                                        <span style="display: inline-block;width:90%;">
                                                                            <input type="text" class="text-width" name="velocity_supercheckout[mailchimp][api]" value="{if isset($velocity_supercheckout['mailchimp']['api'])}{$velocity_supercheckout['mailchimp']['api']}{/if}" id="supercheckout_mailchimp_key"/>
                                                                            <input type="hidden" class="text-width" name="velocity_supercheckout[mailchimp][list]" value="{if isset($velocity_supercheckout['mailchimp']['list'])}{$velocity_supercheckout['mailchimp']['list']}{/if}" id="supercheckout_mailchimp_list"/>
                                                                        </span>
                                                                        <span ><input type="button" style="padding: 7.2px 12px;" value="{l s='Get List' mod='supercheckout'}" onclick="getMailChimpList()" id="mailchimp_listbtn" class="btn">
                                                                        </span>
                                                                        <span id="mailchimp_api_key_error" class="supercheckout_error" ></span>
                                                                    </td>
                                                                </tr> 

                                                                <tr>
                                                                    <td class="name vertical_top_align"><span class="control-label">{l s='MailChimp List' mod='supercheckout'}: </span>                                                                
                                                                        <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Select MailChimp List ' mod='supercheckout'}"></i>
                                                                    </td>
                                                                    <td class="settings">
                                                                        <div id="mailchimp_loading" style="background-image: url('../modules/supercheckout/views/img/admin/loading.gif');background-repeat: no-repeat;height:20px;display: none;"></div>
                                                                        <div id="supercheckout_list"></div>
                                                                    </td>
                                                                </tr>  

                                                                
                                                                
                                                                {* Code add by Anshul for implementing the sendinblue *}
                                                                
                                                                             <tr>
                                                                    <td class="name vertical_top_align"><span class="control-label">{l s='Enable SendinBlue' mod='supercheckout'}: </span>                                                                
                                                                        <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="bottom" data-original-title="{l s='Enable/Disable SendinBlue' mod='supercheckout'}"></i>
                                                                </td>
                                                                    <td class="settings">
                                                                        <input type="hidden" value="0" name="velocity_supercheckout[SendinBlue][enable]" />
                                                                        {if $velocity_supercheckout['SendinBlue']['enable'] eq 1}
                                                                            {if $IE7 eq true}
                                                                                <div>
                                                                                    <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[SendinBlue][enable]" id="supercheckout_SendinBlue_enable" checked="checked" />
                                                                                </div>
                                                                            {else}
                                                                                <div class="make-switch" data-on="primary" data-off="default">
                                                                                    <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[SendinBlue][enable]" id="supercheckout_SendinBlue_enable" checked="checked" />
                                                                                </div>
                                                                            {/if}                                                                    
                                                                        {else}
                                                                            {if $IE7 eq true}
                                                                                <div>
                                                                                    <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[SendinBlue][enable]" id="supercheckout_SendinBlue_enable" />
                                                                                </div>
                                                                            {else}
                                                                                <div class="make-switch" data-on="primary" data-off="default">
                                                                                    <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[SendinBlue][enable]" id="supercheckout_SendinBlue_enable"/>
                                                                                </div>
                                                                            {/if}
                                                                        {/if}
                                                                        <div class="widget-body uniformjs" style="padding-top: 1%;">
                                                                   
                                                                            
                                                                <label class="checkboxinline no-bold">
                                                                                <input type="checkbox" class="checkbox input-checkbox-option" name="velocity_supercheckout[SendinBlue][default]" value="1" {if isset($velocity_supercheckout['SendinBlue']['default']) && $velocity_supercheckout['SendinBlue']['default'] eq 1}checked="checked"{/if} />{l s='Subscribe customers as soon as they come out from Email field' mod='supercheckout'}                                                                        
                                                                </label>
                                                                </div>
                                                                </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="name vertical_top_align"><span class="control-label">{l s='SendinBlue Api Key' mod='supercheckout'}: </span>                                                                
                                                                        <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enter SendinBlue Api Key' mod='supercheckout'}"></i>
                                                                    </td>
                                                                    <td class="settings">
                                                                        <span style="display: inline-block;width:90%;">
                                                                            <input type="text" class="text-width" name="velocity_supercheckout[SendinBlue][api]" value="{if isset($velocity_supercheckout['SendinBlue']['api'])}{$velocity_supercheckout['SendinBlue']['api']|escape:'htmlall':'UTF-8'}{/if}" id="supercheckout_SendinBlue_key"/>
                                                                            <input type="hidden" class="text-width" name="velocity_supercheckout[SendinBlue][list]" value="{if isset($velocity_supercheckout['SendinBlue']['list'])}{$velocity_supercheckout['SendinBlue']['list']|escape:'htmlall':'UTF-8'}{/if}" id="supercheckout_SendinBlue_list"/>
                                                                        </span>
                                                                        <span style="width: 9%; display: inline-block;"><input type="button" style="padding: 7.2px 12px;" value="{l s='Get Lists' mod='supercheckout'}" onclick="getSendinBlueList()" id="SendinBlue_listbtn" class="btn">
                                                                        </span>
                                                                        <span id="SendinBlue_api_key_error" class="supercheckout_error" ></span>
                                                                    </td>
                                                                </tr> 
                                                            
                                                                <tr>
                                                                    <td class="name vertical_top_align"><span class="control-label">{l s='SendinBlue List' mod='supercheckout'}: </span>                                                                
                                                                        <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Select SendinBlue List ' mod='supercheckout'}"></i>
                                                                    </td>
                                                                    <td class="settings">
                                                                        <div id="SendinBlue_loading" style="background-image: url('../modules/supercheckout/views/img/admin/loading.gif');background-repeat: no-repeat;height:20px;display: none;"></div>
                                                                        <div id="SendinBlue_supercheckout_list"></div>
                                                                    </td>
                                                                </tr>  
                                                                {* Code add by Anshul for implementing the sendinblue *}
                                                                {* Code add by Anshul for implementing the klaviyo *}
                                                                             <tr>
                                                                    <td class="name vertical_top_align"><span class="control-label">{l s='Enable Klaviyo' mod='supercheckout'}: </span>                                                                
                                                                        <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="bottom" data-original-title="{l s='Enable/Disable Klaviyo' mod='supercheckout'}"></i>
                                                                    </td>
                                                                    <td class="settings">
                                                                        <input type="hidden" value="0" name="velocity_supercheckout[klaviyo][enable]" />
                                                                        {if $velocity_supercheckout['klaviyo']['enable'] eq 1}
                                                                            {if $IE7 eq true}
                                                                                <div>
                                                                                    <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[klaviyo][enable]" id="supercheckout_klaviyo_enable" checked="checked" />
                                                                </div>
                                                                            {else}
                                                                                <div class="make-switch" data-on="primary" data-off="default">
                                                                                    <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[klaviyo][enable]" id="supercheckout_klaviyo_enable" checked="checked" />
                                                                                </div>
                                                                            {/if}                                                                    
                                                                        {else}
                                                                            {if $IE7 eq true}
                                                                                <div>
                                                                                    <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[klaviyo][enable]" id="supercheckout_klaviyo_enable" />
                                                                                </div>
                                                                            {else}
                                                                                <div class="make-switch" data-on="primary" data-off="default">
                                                                                    <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[klaviyo][enable]" id="supercheckout_klaviyo_enable"/>
                                                                                </div>
                                                                            {/if}
                                                                        {/if}
                                                                        <div class="widget-body uniformjs" style="padding-top: 1%;">


                                                                            <label class="checkboxinline no-bold">
                                                                                <input type="checkbox" class="checkbox input-checkbox-option" name="velocity_supercheckout[klaviyo][default]" value="1" {if isset($velocity_supercheckout['klaviyo']['default']) && $velocity_supercheckout['klaviyo']['default'] eq 1}checked="checked"{/if} />{l s='Subscribe customers as soon as they come out from Email field' mod='supercheckout'}                                                                        
                                                                            </label>
                                                                        </div>
                                                                </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="name vertical_top_align"><span class="control-label">{l s='klaviyo Api Key' mod='supercheckout'}: </span>                                                                
                                                                        <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enter klaviyo Api Key' mod='supercheckout'}"></i>
                                                                    </td>
                                                                    <td class="settings">
                                                                        <span style="display: inline-block;width:90%;">
                                                                            <input type="text" class="text-width" name="velocity_supercheckout[klaviyo][api]" value="{if isset($velocity_supercheckout['klaviyo']['api'])}{$velocity_supercheckout['klaviyo']['api']|escape:'htmlall':'UTF-8'}{/if}" id="supercheckout_klaviyo_key"/>
                                                                            <input type="hidden" class="text-width" name="velocity_supercheckout[klaviyo][list]" value="{if isset($velocity_supercheckout['klaviyo']['list'])}{$velocity_supercheckout['klaviyo']['list']|escape:'htmlall':'UTF-8'}{/if}" id="supercheckout_klaviyo_list"/>
                                                                        </span>
                                                                        <span style="width: 9%; display: inline-block;"><input type="button" style="padding: 7.2px 12px;" value="{l s='Get Lists' mod='supercheckout'}" onclick="getklaviyoList()" id="klaviyo_listbtn" class="btn">
                                                                        </span>
                                                                        <span id="klaviyo_api_key_error" class="supercheckout_error" ></span>
                                                                    </td>
                                                                </tr> 

                                                                <tr>
                                                                    <td class="name vertical_top_align"><span class="control-label">{l s='klaviyo List' mod='supercheckout'}: </span>                                                                
                                                                        <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Select klaviyo List ' mod='supercheckout'}"></i>
                                                                    </td>
                                                                    <td class="settings">
                                                                        <div id="klaviyo_loading" style="background-image: url('../modules/supercheckout/views/img/admin/loading.gif');background-repeat: no-repeat;height:20px;display: none;"></div>
                                                                        <div id="klaviyo_supercheckout_list"></div>
                                                                    </td>
                                                                </tr>  
                                                                {* Code add by Anshul for implementing the klaviyo *}
                                                                
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>


                                                <!--------------- End - Mailchimp -------------------->    

                                                <!--------------- Start - Addresses -------------------->

                                                <div id="tab_Addr" class="tab-pane tab-form">
                                                    {assign var='conditional' value=''}
                                                    <div class="block">
                                                        <hr style="margin-bottom:5px;">
                                                        <div class="row">
                                                            <div class="span">
                                                                <p style="margin-bottom: 0; margin-right: 5px">
                                                                    <span style="font-weight: bold; font-size: 15px;">{l s='Note:' mod='supercheckout'}</span><br>
                                                                    <span style="  color: rgb(217, 83, 79);font-weight: bold;}">{l s='Please do not hide fields with * if they are mandatory in following Prestashop settings.' mod='supercheckout'} ({l s='Check FAQs for more details' mod='supercheckout'})</span><br/>{l s='1. Localization->Countries->Edit country' mod='supercheckout'}<br/>{l s='2. Customers->Addresses->Set required fields for this section.' mod='supercheckout'}
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <hr style="margin-top:5px;">
                                                        <table class="form">
                                                            <tr>
                                                                <td class="name vertical_top_align"><span class="control-label">{l s='Inline Validations' mod='supercheckout'}: </span>
                                                                    <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enable/Disable Inline Validations' mod='supercheckout'}"></i>
                                                                </td>
                                                                <td class="settings">
                                                                    <input type="hidden" value="0" name="velocity_supercheckout[inline_validation][enable]" />
                                                                    {if isset($velocity_supercheckout['inline_validation']['enable']) && $velocity_supercheckout['inline_validation']['enable'] eq 1}
                                                                        {if $IE7 eq true}
                                                                            <div>
                                                                                <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[inline_validation][enable]" checked="checked" />
                                                                            </div>
                                                                        {else}
                                                                            <div class="make-switch" data-on="primary" data-off="default">
                                                                                <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[inline_validation][enable]" checked="checked" />
                                                                            </div>
                                                                        {/if}                                                                    
                                                                    {else}
                                                                        {if $IE7 eq true}
                                                                            <div>
                                                                                <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[inline_validation][enable]" />
                                                                            </div>
                                                                        {else}
                                                                            <div class="make-switch" data-on="primary" data-off="default">
                                                                                <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[inline_validation][enable]" />
                                                                            </div>
                                                                        {/if}
                                                                    {/if}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="name vertical_top_align"><span class="control-label">{l s='Enable Save Address' mod='supercheckout'}: </span>                                                                
                                                                    <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Show/hide Save address button on our checkout page to save address of customer before placing order' mod='supercheckout'}"></i>
                                                                </td>
                                                                <td class="settings">
                                                                    <input type="hidden" value="0" name="velocity_supercheckout[enable_save_address]" />
                                                                    {if isset($velocity_supercheckout['enable_save_address']) && $velocity_supercheckout['enable_save_address'] eq 1}
                                                                        {if $IE7 eq true}
                                                                            <div>
                                                                                <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[enable_save_address]" id="supercheckout_enable_save_address" checked="checked" />
                                                                            </div>
                                                                        {else}
                                                                            <div class="make-switch" data-on="primary" data-off="default">
                                                                                <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[enable_save_address]" id="supercheckout_enable_save_address" checked="checked" />
                                                                            </div>
                                                                        {/if}                                                                    
                                                                    {else}
                                                                        {if $IE7 eq true}
                                                                            <div>
                                                                                <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[enable_save_address]" id="supercheckout_enable_save_address" />
                                                                            </div>
                                                                        {else}
                                                                            <div class="make-switch" data-on="primary" data-off="default">
                                                                                <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[enable_save_address]" id="supercheckout_enable_save_address"/>
                                                                            </div>
                                                                        {/if}
                                                                    {/if}
                                                                </td>
                                                            </tr>
                                                                                                                        {* Below tr added by Nimish on 16 March 2018 for Auto detect Country Feature*}
                                                            <tr> 
                                                                <td class="name vertical_top_align"><span class="control-label">{l s='Enable Auto Detect Country' mod='supercheckout'}: </span>                                                                
                                                                    <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enable to auto detect/selection of country in country dropdown in the address' mod='supercheckout'}"></i>
                                                                </td>
                                                                <td class="settings">
                                                                    <input type="hidden" value="0" name="velocity_supercheckout[enable_auto_detect_country]" />
                                                                    {if isset($velocity_supercheckout['enable_auto_detect_country']) && $velocity_supercheckout['enable_auto_detect_country'] eq 1}
                                                                        {if $IE7 eq true}
                                                                            <div>
                                                                                <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[enable_auto_detect_country]" id="supercheckout_enable_auto_detect_country" checked="checked" />
                                                                            </div>
                                                                        {else}
                                                                            <div class="make-switch" data-on="primary" data-off="default">
                                                                                <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[enable_auto_detect_country]" id="supercheckout_enable_auto_detect_country" checked="checked" />
                                                                            </div>
                                                                        {/if}                                                                    
                                                                    {else}
                                                                        {if $IE7 eq true}
                                                                            <div>
                                                                                <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[enable_auto_detect_country]" id="supercheckout_enable_auto_detect_country" />
                                                                            </div>
                                                                        {else}
                                                                            <div class="make-switch" data-on="primary" data-off="default">
                                                                                <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[enable_auto_detect_country]" id="supercheckout_enable_auto_detect_country"/>
                                                                            </div>
                                                                        {/if}
                                                                    {/if}

                                                                </td>
                                                            </tr>
                                                            {* Above tr added by Nimish on 16 March 2018 for Auto detect Country Feature*}
                                                            
                                                            
                                                            {* Start: BOC added by Anshul for hiding the shipping and payment address before saving the address *}
                                                            <tr> 
                                                                <td class="name vertical_top_align"><span class="control-label">{l s='Hide Shipping & Payment Methods Until Address is Saved. Please enable the Save Address button for the same.' mod='supercheckout'}: </span>                                                                
                                                                    <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enable to hide shipping & payment methods until address saved' mod='supercheckout'}"></i>
                                                                </td>
                                                                <td class="settings">
                                                                    <input type="hidden" value="0" name="velocity_supercheckout[hide_ship_pay]" />
                                                                    {if isset($velocity_supercheckout['hide_ship_pay']) && $velocity_supercheckout['hide_ship_pay'] eq 1}
                                                                        {if $IE7 eq true}
                                                                            <div>
                                                                                <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[hide_ship_pay]" checked="checked" />
                                                                            </div>
                                                                        {else}
                                                                            <div class="make-switch" data-on="primary" data-off="default">
                                                                                <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[hide_ship_pay]" checked="checked" />
                                                                            </div>
                                                                        {/if}                                                                    
                                                                    {else}
                                                                        {if $IE7 eq true}
                                                                            <div>
                                                                                <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[hide_ship_pay]"/>
                                                                            </div>
                                                                        {else}
                                                                            <div class="make-switch" data-on="primary" data-off="default">
                                                                                <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[hide_ship_pay]" />
                                                                            </div>
                                                                        {/if}
                                                                    {/if}

                                                                </td>
                                                            </tr>
                                                            {* End: BOC added by Anshul for hiding the shipping and payment address before saving the address *}
                                                                                                                                                                                                                                           
                                                            
                                                            {* Below tr added by Nimish on 20 March 2018 for Google Auto Address Integration Feature*}
                                                            <tr>
                                                                <td class="name vertical_top_align"><span class="control-label">{l s='Enable Google Auto Address Fill' mod='supercheckout'}: </span>  
                                                                    <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enable Auto fillup of address using Google Places Integration' mod='supercheckout'}"></i>
                                                                </td>
                                                                <td class="settings">
                                                                    <input type="hidden" value="0" name="velocity_supercheckout[google_auto_address][enable]" />
                                                                    {if $velocity_supercheckout['google_auto_address']['enable'] eq 1}
                                                                        {if $IE7 eq true}
                                                                            <div>
                                                                                <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[google_auto_address][enable]" id="supercheckout_google_auto_address" checked="checked" />
                                                                            </div>
                                                                        {else}
                                                                            <div class="make-switch" data-on="primary" data-off="default">
                                                                                <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[google_auto_address][enable]" id="supercheckout_google_auto_address" checked="checked" />
                                                                            </div>
                                                                        {/if}                                                                    
                                                                    {else}
                                                                        {if $IE7 eq true}
                                                                            <div>
                                                                                <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[google_auto_address][enable]" id="supercheckout_google_auto_address" />
                                                                            </div>
                                                                        {else}
                                                                            <div class="make-switch" data-on="primary" data-off="default">
                                                                                <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[google_auto_address][enable]" id="supercheckout_google_auto_address"/>
                                                                            </div>
                                                                        {/if}
                                                                    {/if}
                                                                    <span class="pad-right" style="font-size:14px;font-weight:500;float:right;"><a href="javascript:void(0)" onclick="configurationAccordian('google_auto_address');" {if $ps_version eq 15}style="color: #428bca;"{/if}>{l s='Click here to see Steps to configure Google API Key ' mod='supercheckout'}</a></span>
                                                                </td>
                                                                </tr>

                                                                <tr id="google_api_key" style="display:none;">
                                                                    <td class="name vertical_top_align"><span class="control-label"><span class="asterisk">*</span>{l s='Google API Key' mod='supercheckout'}: </span>                                                                
                                                                        <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Google API Key' mod='supercheckout'}"></i>
                                                                    </td>
                                                                    <td class="settings">
                                                                        <input type="text" class="text-width" name="velocity_supercheckout[google_auto_address][api_key]" id="velocity_supercheckout_google_api_key" value="{$velocity_supercheckout['google_auto_address']['api_key']|escape:'htmlall':'UTF-8'}"/>
                                                                        <span id="google_api_key_error" class="supercheckout_error" ></span>
                                                                    </td>
                                                                </tr>
                                                            {* Above tr added by Nimish on 20 March 2018 for Google Auto Address Integration Feature*}
                                                        </table>
                                                        <h4 class='velsof-tab-heading'>{l s='Customer Personal' mod='supercheckout'}</h4>
                                                        <table class="form alternate">
                                                            <thead>
                                                                <tr>
                                                                    <th></th>
                                                                    <th class="left drag-col-2 col-pad-left">{l s='Guest Customer' mod='supercheckout'}</th>
                                                                    <th class="left drag-col-2">{l s='Logged in Customer' mod='supercheckout'}</th>
                                                                    <th></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="sortable ui-sortable">
                                                                {foreach from=$velocity_supercheckout['customer_personal'] key='k' item = 'p_addr'}
                                                                    <tr id="customer_personal_{$velocity_supercheckout['customer_personal'][$k]['id']}_input" class="sort-item" sort-data="{if isset($velocity_supercheckout['customer_personal'][$k]['sort_order'])}{$velocity_supercheckout['customer_personal'][$k]['sort_order']|intval}{/if}">
                                                                <input type="hidden" value="{$velocity_supercheckout['customer_personal'][$k]['id']}" name="velocity_supercheckout[customer_personal][{$k}][id]" />
                                                                <input type="hidden" value="{$velocity_supercheckout['customer_personal'][$k]['title']}" name="velocity_supercheckout[customer_personal][{$k}][title]" />
                                                                <td class="name">
                                                                    <span>{l s=$velocity_supercheckout['customer_personal'][$k]['title'] mod='supercheckout'}:<input class="sort" class="input-sm form-control col-md-12" type="text" value="{if isset($velocity_supercheckout['customer_personal'][$k]['sort_order'])}{$velocity_supercheckout['customer_personal'][$k]['sort_order']|intval}{/if}" name="velocity_supercheckout[customer_personal][{$k}][sort_order]" /></span>
                                                                </td>
                                                                <td class="left drag-col-2 col-pad-left">
                                                                    <div class="widget-body uniformjs" style="padding: 0 !important;">
                                                                        <label class="checkboxinline no-bold">
                                                                            <input id="cus_personal_guest_{$k}_require" type="checkbox" class="checkbox input-checkbox-option require_address_field" name="velocity_supercheckout[customer_personal][{$k}][guest][require]" value="{$velocity_supercheckout['customer_personal'][$k]['guest']['require']|intval}" {if $velocity_supercheckout['customer_personal'][$k]['guest']['require'] eq 1}checked="checked"{/if} />{l s='Require' mod='supercheckout'}                                                                        
                                                                        </label>
                                                                        <label class="checkboxinline no-bold">
                                                                            <input id="cus_personal_guest_{$k}_display" type="checkbox" class="checkbox input-checkbox-option display_address_field" name="velocity_supercheckout[customer_personal][{$k}][guest][display]" value="{$velocity_supercheckout['customer_personal'][$k]['guest']['display']|intval}" {if $velocity_supercheckout['customer_personal'][$k]['guest']['display'] eq 1}checked="checked"{/if} />{l s='Show' mod='supercheckout'}                                                                        
                                                                        </label>
                                                                    </div>
                                                                </td>
                                                                <td class="left drag-col-2">
                                                                    <div class="widget-body uniformjs" style="padding: 0 !important;">
                                                                        <label class="checkboxinline no-bold">
                                                                            <input id="cus_personal_logged_{$k}_require" type="checkbox" class="checkbox input-checkbox-option require_address_field" name="velocity_supercheckout[customer_personal][{$k}][logged][require]" value="{$velocity_supercheckout['customer_personal'][$k]['logged']['require']|intval}" {if $velocity_supercheckout['customer_personal'][$k]['logged']['require'] eq 1}checked="checked"{/if} />{l s='Require' mod='supercheckout'}                                                                        
                                                                        </label>
                                                                        <label class="checkboxinline no-bold">
                                                                            <input id="cus_personal_logged_{$k}_display" type="checkbox" class="checkbox input-checkbox-option display_address_field" name="velocity_supercheckout[customer_personal][{$k}][logged][display]" value="{$velocity_supercheckout['customer_personal'][$k]['logged']['display']|intval}" {if $velocity_supercheckout['customer_personal'][$k]['logged']['display'] eq 1}checked="checked"{/if} />{l s='Show' mod='supercheckout'}                                                                        
                                                                        </label>
                                                                    </div>
                                                                </td>
                                                                <td class="reorder">
                                                                    <i class="icon-reorder"></i>
                                                                    <span style='font-style: italic; margin-left: 5px;'>{l s='Drag to Sort' mod='supercheckout'}</span>
                                                                </td>
                                                                </tr>
                                                            {/foreach}
                                                            </tbody>
                                                        </table>    
                                                    </div>

                                                    <div class="block"><br>
                                                        <h4 class='velsof-tab-heading'>{l s='Customer Subscription' mod='supercheckout'}</h4>
                                                        <table class="form alternate">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width:9.5%;"></th>
                                                                    <th class="left drag-col-2 col-pad-left">{l s='Guest Customer' mod='supercheckout'}</th>
                                                                    <th></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="sortable ui-sortable">
                                                                {foreach from=$velocity_supercheckout['customer_subscription'] key='k' item = 'p_addr'}
                                                                    <tr id="customer_subsription_{$velocity_supercheckout['customer_subscription'][$k]['id']}_input" class="sort-item" sort-data="{if isset($velocity_supercheckout['customer_subscription'][$k]['sort_order'])}{$velocity_supercheckout['customer_subscription'][$k]['sort_order']|intval}{/if}">
                                                                <input type="hidden" value="{$velocity_supercheckout['customer_subscription'][$k]['id']}" name="velocity_supercheckout[customer_subscription][{$k}][id]" />
                                                                <input type="hidden" value="{$velocity_supercheckout['customer_subscription'][$k]['title']}" name="velocity_supercheckout[customer_subscription][{$k}][title]" />
                                                                <td class="name">
                                                                    <span>{l s=$velocity_supercheckout['customer_subscription'][$k]['title'] mod='supercheckout'}:<input class="sort" class="input-sm form-control col-md-12" type="text" value="{if isset($velocity_supercheckout['customer_subscription'][$k]['sort_order'])}{$velocity_supercheckout['customer_subscription'][$k]['sort_order']|intval}{/if}" name="velocity_supercheckout[customer_subscription][{$k}][sort_order]" /></span>
                                                                </td>
                                                                <td class="left drag-col-2 col-pad-left">
                                                                    <div class="widget-body uniformjs" style="padding: 0 !important;">
                                                                        <label class="checkboxinline no-bold">
                                                                            <input id="cus_subsription_guest_{$k}_checked" type="checkbox" class="checkbox input-checkbox-option" name="velocity_supercheckout[customer_subscription][{$k}][guest][checked]" value="{$velocity_supercheckout['customer_subscription'][$k]['guest']['checked']|intval}" {if $velocity_supercheckout['customer_subscription'][$k]['guest']['checked'] eq 1}checked="checked"{/if} />{l s='Show as Checked' mod='supercheckout'}                                                                        
                                                                        </label>
                                                                        <label class="checkboxinline no-bold">
                                                                            <input id="cus_subsription_guest_{$k}_display" type="checkbox" class="checkbox input-checkbox-option" name="velocity_supercheckout[customer_subscription][{$k}][guest][display]" value="{$velocity_supercheckout['customer_subscription'][$k]['guest']['display']|intval}" {if $velocity_supercheckout['customer_subscription'][$k]['guest']['display'] eq 1}checked="checked"{/if} />{l s='Show' mod='supercheckout'}                                                                        
                                                                        </label>
                                                                    </div>
                                                                </td>
                                                                <td class="reorder">
                                                                    <i class="icon-reorder"></i>
                                                                    <span style='font-style: italic; margin-left: 5px;'>{l s='Drag to Sort' mod='supercheckout'}</span>
                                                                </td>
                                                                </tr>
                                                            {/foreach}
                                                            </tbody>
                                                        </table>    
                                                    </div>

                                                    <div class="block"><br>
                                                        <table class="form alternate">
                                                            <thead>
                                                                <tr>
                                                                    <th></th>
                                                                    <th class="left drag-col-2 col-pad-left">{l s='Guest Customer' mod='supercheckout'}</th>
                                                                    <th class="left drag-col-2">{l s='Logged in Customer' mod='supercheckout'}</th>
                                                                    <th></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="">
                                                                <tr id="use_delivery_for_payment_add" class="">
                                                                    <td class="name">
                                                                        <span><b>{l s='Use Delivery Address as Invoice Address' mod='supercheckout'}</b>:</span>
                                                                    </td>
                                                                    <td class="left drag-col-2 col-pad-left">
                                                                        <div class="widget-body uniformjs" style="padding: 0 !important;">
                                                                            <label class="checkboxinline no-bold">
                                                                                <input id="use_delivery_for_payment_add_guest" type="checkbox" class="checkbox input-checkbox-option" name="velocity_supercheckout[use_delivery_for_payment_add][guest]" value="{$velocity_supercheckout['use_delivery_for_payment_add']['guest']|intval}" {if $velocity_supercheckout['use_delivery_for_payment_add']['guest'] eq 1}checked="checked"{/if} />{l s='Show as Checked' mod='supercheckout'}                                                                        
                                                                            </label>
                                                                            <label class="checkboxinline no-bold">
                                                                                <input id="show_use_delivery_for_payment_add_guest" type="checkbox" class="checkbox input-checkbox-option" name="velocity_supercheckout[show_use_delivery_for_payment_add][guest]" value="{$velocity_supercheckout['show_use_delivery_for_payment_add']['guest']|intval}" {if $velocity_supercheckout['show_use_delivery_for_payment_add']['guest'] eq 1}checked="checked"{/if} />{l s='Show' mod='supercheckout'}                                                                        
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td class="left drag-col-2">
                                                                        <div class="widget-body uniformjs" style="padding: 0 !important;">
                                                                            <label class="checkboxinline no-bold">
                                                                                <input id="use_delivery_for_payment_add_logged" type="checkbox" class="checkbox input-checkbox-option" name="velocity_supercheckout[use_delivery_for_payment_add][logged]" value="{$velocity_supercheckout['use_delivery_for_payment_add']['logged']|intval}" {if $velocity_supercheckout['use_delivery_for_payment_add']['logged'] eq 1}checked="checked"{/if} />{l s='Show as Checked' mod='supercheckout'}                                                                        
                                                                            </label>
                                                                            <label class="checkboxinline no-bold">
                                                                                <input id="show_use_delivery_for_payment_add_logged" type="checkbox" class="checkbox input-checkbox-option" name="velocity_supercheckout[show_use_delivery_for_payment_add][logged]" value="{$velocity_supercheckout['show_use_delivery_for_payment_add']['logged']|intval}" {if $velocity_supercheckout['show_use_delivery_for_payment_add']['logged'] eq 1}checked="checked"{/if} />{l s='Show' mod='supercheckout'}
                                                                            </label>
                                                                        </div>
                                                                    </td>


                                                                    <td class="reorder"></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>    
                                                    </div>

                                                    <div class="block"><br><br>
                                                        <h4 class='velsof-tab-heading'>{l s='Delivery Address' mod='supercheckout'}</h4>
                                                        <table class="form alternate">
                                                            <thead>
                                                                <tr>
                                                                    <th></th>
                                                                    <th class="left drag-col-2 col-pad-left">{l s='Guest Customer' mod='supercheckout'}</th>
                                                                    <th class="left drag-col-2">{l s='Logged in Customer' mod='supercheckout'}</th>
                                                                    <th></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="sortable ui-sortable">
                                                                {foreach from=$velocity_supercheckout['shipping_address'] key='k' item = 'p_addr'}
                                                                    <tr id="customer_personal_{$velocity_supercheckout['shipping_address'][$k]['id']}_input" class="sort-item" sort-data="{if isset($velocity_supercheckout['shipping_address'][$k]['sort_order'])}{$velocity_supercheckout['shipping_address'][$k]['sort_order']|intval}{/if}">
                                                                <input type="hidden" value="{$velocity_supercheckout['shipping_address'][$k]['id']}" name="velocity_supercheckout[shipping_address][{$k}][id]" />
                                                                <input type="hidden" value="{$velocity_supercheckout['shipping_address'][$k]['title']}" name="velocity_supercheckout[shipping_address][{$k}][title]" />
                                                                <input type="hidden" value="{$velocity_supercheckout['shipping_address'][$k]['conditional']}" name="velocity_supercheckout[shipping_address][{$k}][conditional]" />
                                                                <td class="name">
                                                                    <span>{l s=$velocity_supercheckout['shipping_address'][$k]['title'] mod='supercheckout'}:<input class="sort" class="input-sm form-control col-md-12" type="text" value="{if isset($velocity_supercheckout['shipping_address'][$k]['sort_order'])|intval}{$velocity_supercheckout['shipping_address'][$k]['sort_order']|intval}{/if}" name="velocity_supercheckout[shipping_address][{$k}][sort_order]" /></span>
                                                                </td>
                                                                {$conditional = $velocity_supercheckout['shipping_address'][$k]['conditional']}
                                                                <td class="left drag-col-2 col-pad-left">
                                                                    <div class="widget-body uniformjs" style="padding: 0 !important;">
                                                                        <label class="checkboxinline no-bold">
                                                                            {if $k eq 'vat_number'}
                                                                                <div style="width: 70px;text-align: center;">
                                                                                    <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip" data-placement="top" data-original-title="{l s='To make this field mandatory please go to' mod='supercheckout'} {l s='Customers->Addresses->Set required fields for this section' mod='supercheckout'}"></i>{l s='Require' mod='supercheckout'}
                                                                                </div>
                                                                            {else}
                                                                                <input id="shipping_address_guest_{$k}_require" type="checkbox" class="checkbox input-checkbox-option require_address_field" name="velocity_supercheckout[shipping_address][{$k}][guest][require]" value="{$velocity_supercheckout['shipping_address'][$k]['guest']['require']|intval}" {if $velocity_supercheckout['shipping_address'][$k]['guest']['require'] eq 1}checked="checked"{/if} />{l s='Require' mod='supercheckout'}
                                                                            {/if}
                                                                        </label>
                                                                        <label class="checkboxinline no-bold">
                                                                            <input id="shipping_address_guest_{$k}_display" type="checkbox" class="checkbox input-checkbox-option display_address_field" name="velocity_supercheckout[shipping_address][{$k}][guest][display]" value="{$velocity_supercheckout['shipping_address'][$k]['guest']['display']|intval}" {if $velocity_supercheckout['shipping_address'][$k]['guest']['display'] eq 1}checked="checked"{/if} />{l s='Show' mod='supercheckout'}                                                                        
                                                                        </label>
                                                                        {if in_array($k, $highlighted_fields)}
                                                                            <span style="color:red; margin-left: 5px;">*</span>
                                                                        {/if}
                                                                    </div>
                                                                </td>
                                                                <td class="left drag-col-2">
                                                                    <div class="widget-body uniformjs" style="padding: 0 !important;">
                                                                        <label class="checkboxinline no-bold">
                                                                            {if $k eq 'vat_number'}
                                                                                <div style="width: 70px;text-align: center;">
                                                                                    <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip" data-placement="top" data-original-title="{l s='To make this field mandatory please go to' mod='supercheckout'} {l s='Customers->Addresses->Set required fields for this section' mod='supercheckout'}"></i>{l s='Require' mod='supercheckout'}
                                                                                </div>
                                                                            {else}
                                                                                <input id="shipping_address_logged_{$k}_require" type="checkbox" class="checkbox input-checkbox-option require_address_field" name="velocity_supercheckout[shipping_address][{$k}][logged][require]" value="{$velocity_supercheckout['shipping_address'][$k]['logged']['require']|intval}" {if $velocity_supercheckout['shipping_address'][$k]['logged']['require'] eq 1}checked="checked"{/if} />{l s='Require' mod='supercheckout'}
                                                                            {/if}
                                                                        </label>
                                                                        <label class="checkboxinline no-bold">
                                                                            <input id="shipping_address_logged_{$k}_display" type="checkbox" class="checkbox input-checkbox-option display_address_field" name="velocity_supercheckout[shipping_address][{$k}][logged][display]" value="{$velocity_supercheckout['shipping_address'][$k]['logged']['display']|intval}" {if $velocity_supercheckout['shipping_address'][$k]['logged']['display'] eq 1}checked="checked"{/if} />{l s='Show' mod='supercheckout'}
                                                                        </label>
                                                                        {if in_array($k, $highlighted_fields)}
                                                                            <span style="color:red; margin-left: 5px;">*</span>
                                                                        {/if}
                                                                    </div>
                                                                </td>
                                                                <td class="reorder">
                                                                    <i class="icon-reorder"></i>
                                                                    <span style='font-style: italic; margin-left: 5px;'>{l s='Drag to Sort' mod='supercheckout'}</span>
                                                                </td>
                                                                </tr>
                                                            {/foreach}
                                                            </tbody>
                                                        </table>    
                                                    </div>
                                                    <div class="block"><br>
                                                        <h4 class='velsof-tab-heading'>{l s='Invoice Address' mod='supercheckout'}</h4>
                                                        <table class="form alternate">
                                                            <thead>
                                                                <tr>
                                                                    <th></th>
                                                                    <th class="left drag-col-2 col-pad-left">{l s='Guest Customer' mod='supercheckout'}</th>
                                                                    <th class="left drag-col-2">{l s='Logged in Customer' mod='supercheckout'}</th>
                                                                    <th></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="sortable ui-sortable">
                                                                {foreach from=$velocity_supercheckout['payment_address'] key='k' item = 'p_addr'}
                                                                    <tr id="customer_personal_{$velocity_supercheckout['payment_address'][$k]['id']}_input" class="sort-item" sort-data="{if isset($velocity_supercheckout['payment_address'][$k]['sort_order'])}{$velocity_supercheckout['payment_address'][$k]['sort_order']|intval}{/if}">
                                                                <input type="hidden" value="{$velocity_supercheckout['payment_address'][$k]['id']}" name="velocity_supercheckout[payment_address][{$k}][id]" />
                                                                <input type="hidden" value="{$velocity_supercheckout['payment_address'][$k]['title']}" name="velocity_supercheckout[payment_address][{$k}][title]" />
                                                                <input type="hidden" value="{$velocity_supercheckout['payment_address'][$k]['conditional']}" name="velocity_supercheckout[payment_address][{$k}][conditional]" />
                                                                <td class="name">
                                                                    <span>{l s=$velocity_supercheckout['payment_address'][$k]['title'] mod='supercheckout'}:<input class="sort" class="input-sm form-control col-md-12" type="text" value="{if isset($velocity_supercheckout['payment_address'][$k]['sort_order'])}{$velocity_supercheckout['payment_address'][$k]['sort_order']|intval}{/if}" name="velocity_supercheckout[payment_address][{$k}][sort_order]" /></span>
                                                                </td>
                                                                {$conditional = $velocity_supercheckout['payment_address'][$k]['conditional']}
                                                                <td class="left drag-col-2 col-pad-left">
                                                                    <div class="widget-body uniformjs" style="padding: 0 !important;">
                                                                        <label class="checkboxinline no-bold">
                                                                            {if $k eq 'vat_number'}
                                                                                <div style="width: 70px;text-align: center;">
                                                                                    <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip" data-placement="top" data-original-title="{l s='To make this field mandatory please go to' mod='supercheckout'} {l s='Customers->Addresses->Set required fields for this section' mod='supercheckout'}"></i>{l s='Require' mod='supercheckout'}
                                                                                </div>
                                                                            {else}
                                                                                <input id="payment_address_guest_{$k}_require" type="checkbox" class="checkbox input-checkbox-option require_address_field" name="velocity_supercheckout[payment_address][{$k}][guest][require]" value="{$velocity_supercheckout['payment_address'][$k]['guest']['require']|intval}" {if $velocity_supercheckout['payment_address'][$k]['guest']['require'] eq 1}checked="checked"{/if} />{l s='Require' mod='supercheckout'}
                                                                            {/if}
                                                                        </label>
                                                                        <label class="checkboxinline no-bold">
                                                                            <input id="payment_address_guest_{$k}_display" type="checkbox" class="checkbox input-checkbox-option display_address_field" name="velocity_supercheckout[payment_address][{$k}][guest][display]" value="{$velocity_supercheckout['payment_address'][$k]['guest']['display']|intval}" {if $velocity_supercheckout['payment_address'][$k]['guest']['display'] eq 1}checked="checked"{/if} />{l s='Show' mod='supercheckout'}                                                                        
                                                                        </label>
                                                                        {if in_array($k, $highlighted_fields)}
                                                                            <span style="color:red; margin-left: 5px;">*</span>
                                                                        {/if}
                                                                    </div>
                                                                </td>
                                                                <td class="left drag-col-2">
                                                                    <div class="widget-body uniformjs" style="padding: 0 !important;">
                                                                        <label class="checkboxinline no-bold">
                                                                            {if $k eq 'vat_number'}
                                                                                <div style="width: 70px;text-align: center;">
                                                                                    <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip" data-placement="top" data-original-title="{l s='To make this field mandatory please go to' mod='supercheckout'} {l s='Customers->Addresses->Set required fields for this section' mod='supercheckout'}"></i>{l s='Require' mod='supercheckout'}
                                                                                </div>
                                                                            {else}
                                                                                <input id="payment_address_logged_{$k}_require" type="checkbox" class="checkbox input-checkbox-option require_address_field" name="velocity_supercheckout[payment_address][{$k}][logged][require]" value="{$velocity_supercheckout['payment_address'][$k]['logged']['require']|intval}" {if $velocity_supercheckout['payment_address'][$k]['logged']['require'] eq 1}checked="checked"{/if} />{l s='Require' mod='supercheckout'}
                                                                            {/if}
                                                                        </label>
                                                                        <label class="checkboxinline no-bold">
                                                                            <input id="payment_address_logged_{$k}_display" type="checkbox" class="checkbox input-checkbox-option display_address_field" name="velocity_supercheckout[payment_address][{$k}][logged][display]" value="{$velocity_supercheckout['payment_address'][$k]['logged']['display']|intval}" {if $velocity_supercheckout['payment_address'][$k]['logged']['display'] eq 1}checked="checked"{/if} />{l s='Show' mod='supercheckout'}                                                                        
                                                                        </label>
                                                                        {if in_array($k, $highlighted_fields)}
                                                                            <span style="color:red; margin-left: 5px;">*</span>
                                                                        {/if}
                                                                    </div>
                                                                </td>
                                                                <td class="reorder">
                                                                    <i class="icon-reorder"></i>
                                                                    <span style='font-style: italic; margin-left: 5px;'>{l s='Drag to Sort' mod='supercheckout'}</span>
                                                                </td>
                                                                </tr>
                                                            {/foreach}
                                                            </tbody>
                                                        </table>
                                                        <div id="google_auto_address_acc" >
                                                            <h4 class='velsof-tab-heading'>{l s='Steps To Configure Google API:' mod='supercheckout'}</h4>
                                                            <div id="google_auto_address_accordian" class="accordian_container">
                                                                <h3>{l s='Step 1' mod='supercheckout'}</h3>
                                                                <div class="accdiv">
                                                                    <span class="pad-right"><a href="https://console.developers.google.com/project/_/apiui/apis/enabled" target="_blank" style="color: #00aff0;">{l s='Click here to go to the Google API Console.' mod='supercheckout'}</a></span>
                                                                </div>

                                                                <h3>{l s='Step 2' mod='supercheckout'}</h3>
                                                                <div class="accdiv">
                                                                        <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/admin/manual_steps/google_auto_address/google_auto_addr_2.png' />
                                                                </div>
                                                                <h3>{l s='Step 3' mod='supercheckout'}</h3>
                                                                <div class="accdiv">
                                                                        <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/admin/manual_steps/google_auto_address/google_auto_addr_3.png' />
                                                                </div>
                                                                <h3>{l s='Step 4' mod='supercheckout'}</h3>
                                                                <div class="accdiv">
                                                                        <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/admin/manual_steps/google_auto_address/google_auto_addr_4.png' />
                                                                </div>
                                                                <h3>{l s='Step 5' mod='supercheckout'}</h3>
                                                                <div class="accdiv">
                                                                        <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/admin/manual_steps/google_auto_address/google_auto_addr_5.png' />
                                                                </div>
                                                                <h3>{l s='Step 6' mod='supercheckout'}</h3>
                                                                <div class="accdiv">
                                                                        <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/admin/manual_steps/google_auto_address/google_auto_addr_56.png' />
                                                                </div>
                                                                <h3>{l s='Step 7' mod='supercheckout'}</h3>
                                                                <div class="accdiv">
                                                                        <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/admin/manual_steps/google_auto_address/google_auto_addr_6.png' />
                                                                </div>
                                                                <h3>{l s='Step 8' mod='supercheckout'}</h3>
                                                                <div class="accdiv">                                                                            
                                                                        <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/admin/manual_steps/google_auto_address/google_auto_addr_7.png' />
                                                                </div>
                                                                <h3>{l s='Step 9' mod='supercheckout'}</h3>
                                                                <div class="accdiv">
                                                                        <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/admin/manual_steps/google_auto_address/google_auto_addr_8.png' />
                                                                </div>                                                                    
                                                            </div>
                                                        </div>
                                                    </div>                                                        
                                                </div>

                                                <!--------------- End - Addresses -------------------->

                                                <!--------------- Start - Payment Method -------------------->

                                                <div id="tab_payment_method" class="tab-pane tab-form">
                                                    <div class="block">
                                                        <h4 class='velsof-tab-heading'>{l s='Payment Method' mod='supercheckout'}</h4>
                                                        <table class="form">
                                                            <tr>
                                                                <td class="name vertical_top_align"><span class="control-label">{l s='Display Methods' mod='supercheckout'}: </span>                                                                
                                                                    <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="bottom" data-original-title="{l s='Display Methods Tooltip' mod='supercheckout'}"></i>
                                                                </td>
                                                                <td class="settings">
                                                                    <input type="hidden" value="0" name="velocity_supercheckout[payment_method][enable]" />
                                                                    {if $velocity_supercheckout['payment_method']['enable'] eq 1}
                                                                        {if $IE7 eq true}
                                                                            <div>
                                                                                <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[payment_method][enable]" checked="checked" />
                                                                            </div>
                                                                        {else}
                                                                            <div class="make-switch" data-on="primary" data-off="default">
                                                                                <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[payment_method][enable]" checked="checked" />
                                                                            </div>
                                                                        {/if}                                                                    
                                                                    {else}
                                                                        {if $IE7 eq true}
                                                                            <div>
                                                                                <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[payment_method][enable]" />
                                                                            </div>
                                                                        {else}
                                                                            <div class="make-switch" data-on="primary" data-off="default">
                                                                                <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[payment_method][enable]" />
                                                                            </div>
                                                                        {/if}
                                                                    {/if}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="name vertical_top_align">
                                                                    <span>{l s='Display Style' mod='supercheckout'}: </span><i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Method Display Style Tooltip' mod='supercheckout'}"></i>
                                                                </td>
                                                                <td class="left settings">
                                                                    <div class="widget-body uniformjs" style="padding: 0 !important;">
                                                                        <label class="radio coupon_type_radio">
                                                                            <input type="radio" class="radio coupon_type_radio" name="velocity_supercheckout[payment_method][display_style]" value="0"  {if $velocity_supercheckout['payment_method']['display_style'] eq 0} checked="checked" {/if} />{l s='Text Only' mod='supercheckout'}                                                                        
                                                                        </label>
                                                                        <label class="radio coupon_type_radio">
                                                                            <input type="radio" class="radio coupon_type_radio" name="velocity_supercheckout[payment_method][display_style]" value="1" {if $velocity_supercheckout['payment_method']['display_style'] eq 1} checked="checked" {/if} />{l s='Text With Image' mod='supercheckout'}                                                                        
                                                                        </label>
                                                                        <label class="radio coupon_type_radio">
                                                                            <input type="radio" class="radio coupon_type_radio" name="velocity_supercheckout[payment_method][display_style]" value="2" {if $velocity_supercheckout['payment_method']['display_style'] eq 2} checked="checked" {/if} />{l s='Image Only' mod='supercheckout'}                                                                        
                                                                        </label>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="name vertical_top_align"><span class="control-label">{l s='Selected Default Method' mod='supercheckout'}: </span>                                                                
                                                                    <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Selected Default Payment Method Tooltip' mod='supercheckout'}"></i>
                                                                    <p style="font-size: 11px; color: #666;"><b>{l s='Note' mod='supercheckout'}: </b>{l s='In case you select a Payment Method with sub payment options here, than the last sub payment options will be selected by default.' mod='supercheckout'}</p>
                                                                </td>
                                                                <td class="settings">
                                                                    <div class='span4'>
                                                                        <select {if $ps_version eq 15}class="selectpicker vss_sc_ver15"{/if} name="velocity_supercheckout[payment_method][default]" >
                                                                            {foreach from=$payment_methods item="pay_methods"}
                                                                                {if $pay_methods['id_module'] eq $velocity_supercheckout['payment_method']['default']}
                                                                                    <option value="{$pay_methods['id_module']|intval}" selected='selected'>{$pay_methods['display_name']}</option>
                                                                                {else}
                                                                                    <option value="{$pay_methods['id_module']|intval}">{$pay_methods['display_name']}</option>
                                                                                {/if}
                                                                            {/foreach}
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan='2'><br>
                                                                    <p>
                                                                        <b>{l s='Note' mod='supercheckout'}:</b>
                                                                        {l s='Payment Method Style Note' mod='supercheckout'}
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                        <h4 class='velsof-tab-heading'>{l s='Change logo and Title of Payment Methods' mod='supercheckout'}</h4>
                                                        <div id="payment-accordian" class="accordian_container">
                                                            {foreach from=$payment_methods item="pay_methods"}
                                                                <h3>{$pay_methods['display_name']}</h3>
                                                                <div class="accdiv-logo">
                                                                    <table class="form">
                                                                        <tr>
                                                                            <td class="name vertical_top_align"><span>{l s='Title' mod='supercheckout'}: </span>                                                                
                                                                                <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enter payment method title' mod='supercheckout'}"></i>
                                                                            </td>

                                                                            <td class="settings">
                                                                                <table class="lang-title">
                                                                                    {foreach from=$languages item='lang'}

                                                                                        <tr>

                                                                                            <td><div class="span6">
                                                                                                    <input type="text" class="text-width" name="velocity_supercheckout_payment[payment_method][{$pay_methods['id_module']|intval}][title][{$lang['id_lang']|intval}]" value="{if isset($velocity_supercheckout_payment['payment_method'][{$pay_methods['id_module']|intval}]['title'][{$lang['id_lang']|intval}])}{$velocity_supercheckout_payment['payment_method'][{$pay_methods['id_module']|intval}]['title'][{$lang['id_lang']|intval}]}{/if}"/>

                                                                                                </div>
                                                                                            </td>
                                                                                            <td><div class='span0'><img src="{$img_lang_dir}{$lang['id_lang']}.jpg" alt="{$lang['name']}" title="{$lang['name']}"/></div></td>
                                                                                        </tr>

                                                                                    {/foreach}
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="name vertical_top_align"><span>{l s='Logo Settings' mod='supercheckout'}: </span>                                                                
                                                                                <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Set payment method logo with dimensions ' mod='supercheckout'}"></i>
                                                                            </td>
                                                                            <td class="settings"><div>
                                                                                    <div class="logo-img" style='padding-left: 10px;padding-top:10px;margin-bottom:15px;'>
                                                                                        {if isset($velocity_supercheckout_payment['payment_method'][{$pay_methods['id_module']|intval}]['logo']['title']) && $velocity_supercheckout_payment['payment_method'][{$pay_methods['id_module']|intval}]['logo']['title'] != ""}
                                                                                            {if !file_exists("{$root_dir}/modules/supercheckout/views/img/admin/uploads/{$velocity_supercheckout_payment['payment_method'][{$pay_methods['id_module']|intval}]['logo']['title']}")}
                                                                                                <input type="hidden" name="velocity_supercheckout_payment[payment_method][{$pay_methods['id_module']|intval}][logo][title]" id="payment_image_title_{$pay_methods['id_module']|intval}" value="" />
                                                                                                <div><img id="payment-img-{$pay_methods['id_module']|intval}" src="{$module_dir}views/img/admin/no-image.jpg"   style="border: 1px solid #ccc; padding:2px; height: 115px;"/></div>
                                                                                                {else}
                                                                                                <input type="hidden" name="velocity_supercheckout_payment[payment_method][{$pay_methods['id_module']|intval}][logo][title]"  id="payment_image_title_{$pay_methods['id_module']|intval}" value="{$velocity_supercheckout_payment['payment_method'][{$pay_methods['id_module']|intval}]['logo']['title']}" />
                                                                                                <div><img id="payment-img-{$pay_methods['id_module']|intval}" src="{$module_dir}views/img/admin/uploads/{$velocity_supercheckout_payment['payment_method'][{$pay_methods['id_module']|intval}]['logo']['title']}"   onerror="this.src='{$module_dir}views/img/admin/no-image.jpg'" style="border: 1px solid #ccc; padding:2px; height: 115px;"/></div>
                                                                                                {/if}
                                                                                            {else}
                                                                                            <input type="hidden" name="velocity_supercheckout_payment[payment_method][{$pay_methods['id_module']|intval}][logo][title]" id="payment_image_title_{$pay_methods['id_module']|intval}" value="" />
                                                                                            <div><img id="payment-img-{$pay_methods['id_module']|intval}" src="{$module_dir}views/img/admin/no-image.jpg"   style="border: 1px solid #ccc; padding:2px; height: 115px;"/></div>
                                                                                            {/if}


                                                                                    </div>


                                                                                    <div style='padding-left: 10px;'>
                                                                                        <span style="display: inline-block;"> <input type="file" name="velocity_supercheckout_payment[payment_method][{$pay_methods['id_module']|intval}][logo][name]" id="payment-img-{$pay_methods['id_module']|intval}_file" onchange="readPaymentURL({$pay_methods['id_module']|intval}, 'payment-img-{$pay_methods['id_module']|intval}')" value=""></span><span><input type='button' class="btn btn-primary" onclick="removeFile({$pay_methods['id_module']|intval});" value='{l s='Remove' mod='supercheckout'}' /></span> <span id="payment-img-{$pay_methods['id_module']|intval}_msg" style="margin-left:10px; display:none;">{l s='Only Images allowed' mod='supercheckout'}</span>
                                                                                    </div>
                                                                                </div>
                                                                                <div style="margin-top: 10px;display:flex;padding-left: 10px;">
                                                                                    <span style="padding: 5px 10px 0px 0px;">{l s='Width' mod='supercheckout'}</span>
                                                                                    <div class="input-group" style="width: 40%;"><div style="float: left; width: 100%;"><input type="text" style="width: 90%"  class="form-control" id="velocity_supercheckout_payment_method_logo_width_{$pay_methods['id_module']|intval}" name="velocity_supercheckout_payment[payment_method][{$pay_methods['id_module']|intval}][logo][resolution][width]" value="{if isset($velocity_supercheckout_payment['payment_method'][{$pay_methods['id_module']|intval}]['logo']['resolution']['width'])}{$velocity_supercheckout_payment['payment_method'][{$pay_methods['id_module']|intval}]['logo']['resolution']['width']}{else}auto{/if}"/><span class="input-group-addon" style="width: 10%; float: left; padding: 8.4px 0;">{l s='px' mod='supercheckout'}</span></div><span id="payment_method_logo_width_error_{$pay_methods['id_module']|intval}" class="supercheckout_error"></span></div>
                                                                                    <span style="padding: 5px 10px 0px 10px;">{l s='Height' mod='supercheckout'}</span>		<div class="input-group" style="width: 40%;"><div style="float: left; width: 100%;"><input type="text" style="width: 90%" class="form-control" id="velocity_supercheckout_payment_method_logo_height_{$pay_methods['id_module']|intval}" name="velocity_supercheckout_payment[payment_method][{$pay_methods['id_module']|intval}][logo][resolution][height]" value="{if isset($velocity_supercheckout_payment['payment_method'][{$pay_methods['id_module']|intval}]['logo']['resolution']['height'])}{$velocity_supercheckout_payment['payment_method'][{$pay_methods['id_module']|intval}]['logo']['resolution']['height']}{else}auto{/if}"/><span class="input-group-addon" style="width: 10%; float: left; padding: 8.4px 0;">{l s='px' mod='supercheckout'}</span></div><span id="payment_method_logo_height_error_{$pay_methods['id_module']|intval}" class="supercheckout_error"></span></div>
                                                                                </div><p class="help-block" style='padding-left: 10px;'> {l s='(To maintain aspect ratio of image, keep either both height and width value as auto or any of them value as auto)' mod='supercheckout'}</p>
                                                                            </td>
                                                                        </tr>


                                                                    </table>
                                                                </div>
                                                            {/foreach}
                                                        </div>

                                                    </div>
                                                </div>

                                                <!--------------- End - Payment Method -------------------->

                                                <!--------------- Start - Shipping Method -------------------->

                                                <div id="tab_shipping_method" class="tab-pane tab-form">
                                                    <div class="block">
                                                        <h4 class='velsof-tab-heading'>{l s='Delivery Method' mod='supercheckout'}</h4>
                                                        <table class="form">
                                                            <tr>
                                                                <td class="name vertical_top_align"><span class="control-label">{l s='Display Methods' mod='supercheckout'}: </span>                                                                
                                                                    <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="bottom" data-original-title="{l s='Display Methods Tooltip' mod='supercheckout'}"></i>
                                                                </td>
                                                                <td class="settings">
                                                                    <input type="hidden" value="0" name="velocity_supercheckout[shipping_method][enable]" />
                                                                    {if $velocity_supercheckout['shipping_method']['enable'] eq 1}
                                                                        {if $IE7 eq true}
                                                                            <div>
                                                                                <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[shipping_method][enable]" checked="checked" />
                                                                            </div>
                                                                        {else}
                                                                            <div class="make-switch" data-on="primary" data-off="default">
                                                                                <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[shipping_method][enable]" checked="checked" />
                                                                            </div>
                                                                        {/if}                                                                    
                                                                    {else}
                                                                        {if $IE7 eq true}
                                                                            <div>
                                                                                <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[shipping_method][enable]" />
                                                                            </div>
                                                                        {else}
                                                                            <div class="make-switch" data-on="primary" data-off="default">
                                                                                <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[shipping_method][enable]"/>
                                                                            </div>
                                                                        {/if}
                                                                    {/if}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="name vertical_top_align">
                                                                    <span>{l s='Display Style' mod='supercheckout'}: </span><i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Method Display Style Tooltip' mod='supercheckout'}"></i>
                                                                </td>
                                                                <td class="left settings">
                                                                    <div class="widget-body uniformjs" style="padding: 0 !important;">
                                                                        <label class="radio coupon_type_radio">
                                                                            <input type="radio" class="radio coupon_type_radio" name="velocity_supercheckout[shipping_method][display_style]" value="0"  {if $velocity_supercheckout['shipping_method']['display_style'] eq 0} checked="checked" {/if} />{l s='Text Only' mod='supercheckout'}                                                                        
                                                                        </label>
                                                                        <label class="radio coupon_type_radio">
                                                                            <input type="radio" class="radio coupon_type_radio" name="velocity_supercheckout[shipping_method][display_style]" value="1" {if $velocity_supercheckout['shipping_method']['display_style'] eq 1} checked="checked" {/if} />{l s='Text With Image' mod='supercheckout'}                                                                        
                                                                        </label>
                                                                        <label class="radio coupon_type_radio">
                                                                            <input type="radio" class="radio coupon_type_radio" name="velocity_supercheckout[shipping_method][display_style]" value="2" {if $velocity_supercheckout['shipping_method']['display_style'] eq 2} checked="checked" {/if} />{l s='Image Only' mod='supercheckout'}                                                                        
                                                                        </label>
                                                                    </div>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="name vertical_top_align"><span class="control-label">{l s='Selected Default Method' mod='supercheckout'}: </span>                                                                
                                                                    <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Selected Default Shipping Method Tooltip' mod='supercheckout'}"></i>
                                                                </td>
                                                                <td class="settings">
                                                                    <div class='span4'>
                                                                        <select {if $ps_version eq 15}class="selectpicker vss_sc_ver15"{/if} name="velocity_supercheckout[shipping_method][default]" >
                                                                            {foreach from=$carriers item="carrier"}
                                                                                {if $carrier['id_carrier'] eq $velocity_supercheckout['shipping_method']['default']}
                                                                                    <option value="{$carrier['id_carrier']|intval}" selected='selected'>{$carrier['name']}</option>
                                                                                {else}
                                                                                    <option value="{$carrier['id_carrier']|intval}">{$carrier['name']}</option>
                                                                                {/if}
                                                                            {/foreach}
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan='2'><br>
                                                                    <p>
                                                                        <b>{l s='Note' mod='supercheckout'}:</b>
                                                                        {l s='Delivery Method Style Note' mod='supercheckout'}
                                                                    </p>
                                                                </td>
                                                            </tr>

                                                        </table>  
                                                        <h4 class='velsof-tab-heading'>{l s='Change logo and Title of Delivery Methods' mod='supercheckout'}</h4>
                                                        <div id="delivery-accordian" class="accordian_container">
                                                            {foreach from=$carriers item="carrier"}
                                                                <h3>{$carrier['name']}</h3>
                                                                <div class="accdiv-logo">
                                                                    <table class="form">
                                                                        <tr>
                                                                            <td class="name vertical_top_align"><span>{l s='Title' mod='supercheckout'}: </span>                                                                
                                                                                <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enter Delivery method title' mod='supercheckout'}"></i>
                                                                            </td>

                                                                            <td class="settings">
                                                                                <table class="lang-title">
                                                                                    {foreach from=$languages item='lang'}

                                                                                        <tr>

                                                                                            <td><div class="span6">
                                                                                                    <input type="text" class="text-width" name="velocity_supercheckout_payment[delivery_method][{$carrier['id_carrier']|intval}][title][{$lang['id_lang']|intval}]" value="{if isset($velocity_supercheckout_payment['delivery_method'][{$carrier['id_carrier']|intval}]['title'][{$lang['id_lang']|intval}])}{$velocity_supercheckout_payment['delivery_method'][{$carrier['id_carrier']|intval}]['title'][{$lang['id_lang']|intval}]}{else}{$carrier['name']}{/if}"/>                                                                                                                                                

                                                                                                </div>
                                                                                            </td>
                                                                                            <td><div class='span0'><img src="{$img_lang_dir}{$lang['id_lang']}.jpg" alt="{$lang['name']}" title="{$lang['name']}"/></div></td>
                                                                                        </tr>

                                                                                    {/foreach}
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="name vertical_top_align"><span>{l s='Logo Setting' mod='supercheckout'}: </span>                                                                
                                                                                <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Set delivery method logo with dimensions' mod='supercheckout'}"></i>
                                                                            </td>
                                                                            <td class="settings"><div>
                                                                                    <div class="logo-img" style='padding-left: 10px;padding-top:10px;margin-bottom:15px;'>
                                                                                        {if isset($velocity_supercheckout_payment['delivery_method'][{$carrier['id_carrier']|intval}]['logo']['title']) && $velocity_supercheckout_payment['delivery_method'][{$carrier['id_carrier']|intval}]['logo']['title'] != ""}
                                                                                            {if !file_exists("{$root_dir}/modules/supercheckout/views/img/admin/uploads/{$velocity_supercheckout_payment['delivery_method'][{$carrier['id_carrier']|intval}]['logo']['title']}")}
                                                                                                <input type="hidden" name="velocity_supercheckout_payment[delivery_method][{$carrier['id_carrier']|intval}][logo][title]" id="delivery_image_title_{$carrier['id_carrier']|intval}" value="" />
                                                                                                <div><img id="delivery-img-{$carrier['id_carrier']|intval}" src="{$module_dir}views/img/admin/no-image.jpg"   style="border: 1px solid #ccc; padding:2px; height: 115px;"/></div>
                                                                                                {else}
                                                                                                <input type="hidden" name="velocity_supercheckout_payment[delivery_method][{$carrier['id_carrier']|intval}][logo][title]"  id="delivery_image_title_{$carrier['id_carrier']|intval}" value="{$velocity_supercheckout_payment['delivery_method'][{$carrier['id_carrier']|intval}]['logo']['title']}" />
                                                                                                <div>

                                                                                                    <img id="delivery-img-{$carrier['id_carrier']|intval}" src="{$module_dir}views/img/admin/uploads/{$velocity_supercheckout_payment['delivery_method'][{$carrier['id_carrier']|intval}]['logo']['title']}"   onerror="this.src='{$module_dir}views/img/admin/no-image.jpg';" style="border: 1px solid #ccc; padding:2px; height: 115px;"/>

                                                                                                </div>
                                                                                            {/if}
                                                                                        {else}
                                                                                            <input type="hidden" name="velocity_supercheckout_payment[delivery_method][{$carrier['id_carrier']|intval}][logo][title]" id="delivery_image_title_{$carrier['id_carrier']|intval}" value="" />
                                                                                            <div><img id="delivery-img-{$carrier['id_carrier']|intval}" src="{$module_dir}views/img/admin/no-image.jpg"   style="border: 1px solid #ccc; padding:2px; height: 115px;"/></div>
                                                                                            {/if}


                                                                                    </div>


                                                                                    <div style='padding-left: 10px;'>
                                                                                        <span style="display: inline-block;"> <input type="file" name="velocity_supercheckout_payment[delivery_method][{$carrier['id_carrier']|intval}][logo][name]" id="delivery-img-{$carrier['id_carrier']|intval}_file" onchange="readDeliveryURL({$carrier['id_carrier']|intval}, 'delivery-img-{$carrier['id_carrier']|intval}')" value=""></span><span><input type='button' class="btn btn-primary" onclick="removeDeliveryFile({$carrier['id_carrier']|intval});" value='{l s='Remove' mod='supercheckout'}' /></span> <span id="delivery-img-{$carrier['id_carrier']|intval}_msg" style="margin-left:10px; display:none;">{l s='Only Images allowed' mod='supercheckout'}</span>
                                                                                    </div>
                                                                                </div>
                                                                                <div style="margin-top: 10px;display:flex;padding-left: 10px;">
                                                                                    <span style="padding: 5px 10px 0px 0px;">{l s='Width' mod='supercheckout'}</span>
                                                                                    <div class="input-group" style="width: 40%;"><div style="float: left; width: 100%;"><input type="text" style="width: 90%" class="form-control" id="velocity_supercheckout_delivery_method_logo_width_{$carrier['id_carrier']|intval}" name="velocity_supercheckout_payment[delivery_method][{$carrier['id_carrier']|intval}][logo][resolution][width]" value="{if isset($velocity_supercheckout_payment['delivery_method'][{$carrier['id_carrier']|intval}]['logo']['resolution']['width'])}{$velocity_supercheckout_payment['delivery_method'][{$carrier['id_carrier']|intval}]['logo']['resolution']['width']}{else}auto{/if}"/><span class="input-group-addon" style="width: 10%; float: left; padding: 8.4px 0;">{l s='px' mod='supercheckout'}</span></div><span id="delivery_method_logo_width_error_{$carrier['id_carrier']|intval}" class="supercheckout_error"></span></div>
                                                                                    <span style="padding: 5px 10px 0px 10px;">{l s='Height' mod='supercheckout'}</span><div class="input-group" style="width: 40%;"><div style="float: left; width: 100%;"><input type="text" style="width: 90%" class="form-control" id="velocity_supercheckout_delivery_method_logo_height_{$carrier['id_carrier']|intval}"  name="velocity_supercheckout_payment[delivery_method][{$carrier['id_carrier']|intval}][logo][resolution][height]" value="{if isset($velocity_supercheckout_payment['delivery_method'][{$carrier['id_carrier']|intval}]['logo']['resolution']['height'])}{$velocity_supercheckout_payment['delivery_method'][{$carrier['id_carrier']|intval}]['logo']['resolution']['height']}{else}auto{/if}"/><span class="input-group-addon" style="width: 10%; float: left; padding: 8.4px 0;">{l s='px' mod='supercheckout'}</span></div><span id="delivery_method_logo_height_error_{$carrier['id_carrier']|intval}" class="supercheckout_error"></span></div>
                                                                                </div><p class="help-block" style='padding-left: 10px;'> {l s='(To maintain aspect ratio of image, keep either both height and width value as auto or any of them value as auto)' mod='supercheckout'}</p>
                                                                            </td>
                                                                        </tr>


                                                                    </table>
                                                                </div>
                                                            {/foreach}
                                                        </div>
                                                    </div>
                                                </div>

                                                <!--------------- End - Shipping Method -------------------->

                                                <!--------------- Start - Ship to pay -------------------->

                                                <div id="tab_ship_to_pay" class="tab-pane tab-form">
                                                    <div class="block">
                                                        <h4 class='velsof-tab-heading'>{l s='Ship2pay' mod='supercheckout'}</h4>
                                                        <div style="text-shadow:none;background: #f8fcfe !important;color: #31b0d5 !important;" class="alert alert-info">
                                                            {l s='Hide payment methods for customers based upon their shipping method selection.' mod='supercheckout'}<br>
                                                            {l s='Click on respective payment method to disable it for desired shipping method, do not forget to click above on save button to save all settings.' mod='supercheckout'}
                                                        </div>
                                                        {foreach from=$carriers item="carrier"}

                                                            <div style="margin-left: 5%;  margin-top:25px;width: 40%;  float: left;  border: 1px solid rgb(0, 0, 0);"> 

                                                                <div style="text-align: center;  font-size: 16px;  border-bottom: 1px solid;  padding: 5px;  background-color: aliceblue;">    
                                                                    <span><a style="float:left;" class="ship2pay-glyphicons glyphicons cargo"><i></i></a></span><span style="padding-left: 14px;">{$carrier['name']}</span>
                                                                </div>

                                                                {foreach from=$payment_methods item="pay_methods"}								    

                                                                    {if isset($velocity_supercheckout['ship_to_pay'][{$carrier['id_carrier']|intval}][{$pay_methods['id_module']|intval}]) AND $velocity_supercheckout['ship_to_pay'][{$carrier['id_carrier']|intval}][{$pay_methods['id_module']|intval}] eq 1}
                                                                        <div style="border: 1px solid #B13131;background-color: rgb(224, 69, 69)" class="ship2pay-div" id="velocity_supercheckout[ship_to_pay][{$carrier['id_carrier']|intval}][{$pay_methods['id_module']|intval}]">
                                                                            <input style="display:none;" type="checkbox" name="velocity_supercheckout[ship_to_pay][{$carrier['id_carrier']|intval}][{$pay_methods['id_module']|intval}]" checked="checked" value="1">
                                                                            <span class="tickcross-sign">&#10060;</span>{$pay_methods['display_name']}
                                                                        </div>
                                                                    {else}
                                                                        <div style="border: 1px solid #257925;background-color: rgb(83, 199, 83);" class="ship2pay-div" id="velocity_supercheckout[ship_to_pay][{$carrier['id_carrier']|intval}][{$pay_methods['id_module']|intval}]">
                                                                            <input style="display:none;" type="checkbox" name="velocity_supercheckout[ship_to_pay][{$carrier['id_carrier']|intval}][{$pay_methods['id_module']|intval}]" value="1"> 
                                                                            <span class="tickcross-sign">&#10004;</span>{$pay_methods['display_name']}
                                                                        </div>

                                                                    {/if}
                                                                {/foreach}
                                                            </div>

                                                        {/foreach}
                                                    </div>
                                                </div>

                                                <!--------------- End - Ship to pay -------------------->
                                                
                                                <!--------------- Start - Custom Fields -------------------->
                                                
                                                <!-- Start - Modal Popup Add new Custom Field -->
                                                
                                                <div class="modal fade" id="modal_add_new_custom_field_form" tab-index="-1" aria-hidden="true" aria-labelledby="modal-incentive-form">
                                                    <div class="modal-dialog" style="width:50%">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <span class="font_popup_header">{l s='New Custom Field' mod='supercheckout'}</span>
                                                                <button type="button" class="close" onclick="closeModalPopup('modal_add_new_custom_field_form')"><span aria-hidden="true">×</span><span class="sr-only">{l s='Close' mod='supercheckout'}</span></button>
                                                            </div>
                                                            <div class="modal-body" style="padding-bottom:0;">
                                                                <div class="row">
                                                                    <div class="span" style="margin-left:0; width:100%;">
                                                                        <div id="modal_incentive_form_process_status" class="modal_process_status_blk alert" style="display:none;"></div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div style="overflow-y:auto !important;">
                                                                    <table class="list form" style="width:100%">
                                                                        <tbody id="custom_table_tbody">
                                                                            <tr class="supercheckout_custom_field_form_fields">
                                                                                <td class="right"><span class="control-label">{l s='Field Label' mod='supercheckout'}</span>
                                                                                    <i class="icon-question-sign tooltip_color" data-toggle="tooltip" data-placement="top" data-original-title="{l s='Label of the custom field.' mod='supercheckout'}"></i>
                                                                                </td>
                                                                                <td class="supercheckout_popup_form_field">
                                                                                    <div class="span">
                                                                                        <span class='float_left margin_right_20'>
                                                                                            {foreach $languages as $language}
                                                                                                <input class="required_entry supercheckout_field_label {if $language_current neq $language['id_lang']}hidden_custom{/if}" type="text" id='field_label_language_{$language['id_lang']}' name="custom_fields[field_label][{$language['id_lang']}]">
                                                                                            {/foreach}
                                                                                        </span>
                                                                                        <span class='float_left'>
                                                                                            <select class="width_small" name="languages" onchange="changeLanguageBox(this, 'field_label')">
                                                                                                {foreach $languages as $language}
                                                                                                    <option value="{$language['id_lang']}" {if $language_current eq $language['id_lang']}selected{/if}>{$language['language_code']}</option>
                                                                                                {/foreach}
                                                                                            </select>
                                                                                        </span>
                                                                                        <span id="error_message_field_label" class="error_message new_line hidden_custom">Error!</span>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            
                                                                            <tr class="supercheckout_custom_field_form_fields">
                                                                                <td class="right"><span class="control-label">{l s='Help Text (optional)' mod='supercheckout'}</span>
                                                                                    <i class="icon-question-sign tooltip_color" data-toggle="tooltip" data-placement="top" data-original-title="{l s='Help text for the custom field.' mod='supercheckout'}"></i>
                                                                                </td>
                                                                                <td class="supercheckout_popup_form_field">
                                                                                    <div class="span">
                                                                                        <span class='float_left margin_right_20'>
                                                                                            {foreach $languages as $language}
                                                                                                <input class="supercheckout_help_text {if $language_current neq $language['id_lang']}hidden_custom{/if}" type="text" id='help_text_language_{$language['id_lang']}' name="custom_fields[help_text][{$language['id_lang']}]">
                                                                                            {/foreach}
                                                                                        </span>
                                                                                        <span class='float_left'>
                                                                                            <select class="width_small" name="languages" onchange="changeLanguageBox(this, 'help_text')">
                                                                                                {foreach $languages as $language}
                                                                                                    <option value="{$language['id_lang']}" {if $language_current eq $language['id_lang']}selected{/if}>{$language['language_code']}</option>
                                                                                                {/foreach}
                                                                                            </select>
                                                                                        </span>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            
                                                                            <tr>
                                                                                <td class="right"><span class="control-label"><span class="required">*</span>{l s='Type' mod='supercheckout'}</span>
                                                                                    <i class="icon-question-sign tooltip_color" data-toggle="tooltip" data-placement="top" data-original-title="{l s='Type of the custom input field.' mod='supercheckout'}"></i>
                                                                                </td>
                                                                                <td class="supercheckout_popup_form_field">
                                                                                    <div class="span5">
                                                                                        <select class="dropdn_templates" id="supercheckout_custom_field_type" name="custom_fields[type]" onchange="checkFieldType(this)">
                                                                                            <option value="textbox">{l s='Text Box' mod='supercheckout'}</option>
                                                                                            <option value="selectbox">{l s='Select Box' mod='supercheckout'}</option>
                                                                                            <option value="textarea">{l s='Text Area' mod='supercheckout'}</option>
                                                                                            <option value="radio">{l s='Radio Buttons' mod='supercheckout'}</option>
                                                                                            <option value="checkbox">{l s='Check Boxes' mod='supercheckout'}</option>
                                                                                            <option value="date">{l s='Date' mod='supercheckout'}</option>
                                                                                            <option value="file">{l s='File' mod='supercheckout'}</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            
                                                                            <tr class="supercheckout_custom_field_form_fields hidden_custom" id="field_options">
                                                                                <td class="right"><span class="control-label"><span class="required">*</span>{l s='Field Options' mod='supercheckout'}</span>
                                                                                    <i class="icon-question-sign" data-toggle="tooltip" data-placement="top" data-original-title="{l s='Enter the data for options of the field.' mod='supercheckout'}"></i>
                                                                                    <p class="help-block">{l s='Enter only one option in 1 line.' mod='supercheckout'}</p>
                                                                                    <p class="help-block">{l s='Avoid blank lines.' mod='supercheckout'}</p>
                                                                                    {*<p class="help-block">{l s='Accepted format example: m|Male' mod='supercheckout'}</p>
                                                                                    <p class="help-block">{l s='                         f|Female' mod='supercheckout'}</p>*}
                                                                                    <p class="help-block">{l s='Accepted format example' mod='supercheckout'}: m|{l s='Male' mod='supercheckout'}</p>
                                                                                    <p class="help-block">f|{l s='Female' mod='supercheckout'}</p>
                                                                                </td>
                                                                                <td class="supercheckout_popup_form_field">
                                                                                    <div class="span">
                                                                                        <span class='float_left margin_right_20'>
                                                                                            {foreach $languages as $language}
                                                                                                <textarea class="supercheckout_field_options {if $language_current neq $language['id_lang']}hidden_custom{/if}" id='field_options_language_{$language['id_lang']}' name="custom_fields[field_options][{$language['id_lang']}]"></textarea>
                                                                                            {/foreach}
                                                                                        </span>
                                                                                        <span class='float_left'>
                                                                                            <select class="width_small" name="languages" onchange="changeLanguageBox(this, 'field_options')">
                                                                                                {foreach $languages as $language}
                                                                                                    <option value="{$language['id_lang']}" {if $language_current eq $language['id_lang']}selected{/if}>{$language['language_code']}</option>
                                                                                                {/foreach}
                                                                                            </select>
                                                                                        </span>
                                                                                        <span id="error_message_field_options" class="error_message new_line hidden_custom">Error!</span>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            
                                                                            <tr>
                                                                                <td class="right"><span class="control-label"><span class="required">*</span>{l s='Position' mod='supercheckout'}</span>
                                                                                    <i class="icon-question-sign tooltip_color" data-toggle="tooltip" data-placement="top" data-original-title="{l s='Position of custom input field where it will be displayed.' mod='supercheckout'}"></i>
                                                                                </td>
                                                                                <td class="supercheckout_popup_form_field">
                                                                                    <div class="span5">
                                                                                        <select class="dropdn_templates" name="custom_fields[position]">
                                                                                            <option value="shipping_address_form">{l s='Shipping Address Form' mod='supercheckout'}</option>
                                                                                            <option value="payment_address_form">{l s='Payment Address Form' mod='supercheckout'}</option>
                                                                                            <option value="cart_block">{l s='Cart Block' mod='supercheckout'}</option>
                                                                                            <option value="customer_registration_block">{l s='Customer Registration Block' mod='supercheckout'}</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            
                                                                            <tr class="supercheckout_custom_field_form_fields">
                                                                                <td class="right"><span class="control-label">{l s='Default Value (optional)' mod='supercheckout'}</span>
                                                                                    <i class="icon-question-sign tooltip_color" data-toggle="tooltip" data-placement="top" data-original-title="{l s='Default value of the custom input field.' mod='supercheckout'}"></i>
                                                                                    <p class="help-block">{l s='For selectbox, radio or checkboxes, set the default value like this.' mod='supercheckout'} {l s=' Option' mod='supercheckout'}:- n|No, {l s='Default Value' mod='supercheckout'}:- n</p>
                                                                                </td>
                                                                                <td class="supercheckout_popup_form_field">
                                                                                    <div class="span">
                                                                                        <input class="" type="text" name="custom_fields[default_value]" value="">
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            
                                                                            <tr>
                                                                                <td class="right"><span class="control-label"><span class="required">*</span>{l s='Validation Type' mod='supercheckout'}</span>
                                                                                    <i class="icon-question-sign tooltip_color" data-toggle="tooltip" data-placement="top" data-original-title="{l s='Type of the validation you want to set for the field.' mod='supercheckout'}"></i>
                                                                                    <p class="help-block">{l s='Validation type will be automatically set as None in case of Selectbox, Radio or Checkboxes.' mod='supercheckout'}</p>
                                                                                </td>
                                                                                <td class="supercheckout_popup_form_field">
                                                                                    <div class="span5">
                                                                                        <select class="dropdn_templates" name="custom_fields[validation_type]">
                                                                                            <option value="0">{l s='None' mod='supercheckout'}</option>
                                                                                            <option value="isInt">isInt</option>
                                                                                            <option value="isName">isName</option>
                                                                                            <option value="isEmail">isEmail</option>
                                                                                            <option value="isDate">isDate</option>
                                                                                            <option value="isUrl">isUrl</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            
                                                                            <tr>
                                                                                <td class="right"><span class="control-label">{l s='Required' mod='supercheckout'}</span>
                                                                                    <i class="icon-question-sign tooltip_color" data-toggle="tooltip" data-placement="top" data-original-title="{l s='Set field as required or not required.' mod='supercheckout'}"></i>
                                                                                </td>
                                                                                <td class="supercheckout_popup_form_field">
                                                                                    <div class="form-group">
                                                                                        <div class="col-lg-9">
                                                                                            <span class="switch prestashop-switch fixed-width-lg">
                                                                                                <input type="radio" name="custom_fields[required]" id="custom_fields[required]_on" value="1">
                                                                                                <label for="custom_fields[required]_on">{l s='Yes' mod='supercheckout'}</label>
                                                                                                <input type="radio" name="custom_fields[required]" id="custom_fields[required]_off" value="0" checked="checked">
                                                                                                <label for="custom_fields[required]_off">{l s='No' mod='supercheckout'}</label>
                                                                                                <a class="slide-button btn"></a>
                                                                                            </span>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            
                                                                            <tr>
                                                                                <td class="right"><span class="control-label">{l s='Active' mod='supercheckout'}</span>
                                                                                    <i class="icon-question-sign tooltip_color" data-toggle="tooltip" data-placement="top" data-original-title="{l s='Set the field as active or inactive.' mod='supercheckout'}"></i>
                                                                                </td>
                                                                                <td class="supercheckout_popup_form_field">
                                                                                    <div class="form-group">
                                                                                        <div class="col-lg-9">
                                                                                            <span class="switch prestashop-switch fixed-width-lg">
                                                                                                <input type="radio" name="custom_fields[active]" id="custom_fields[active]_on" value="1" checked="checked">
                                                                                                <label for="custom_fields[active]_on">{l s='Yes' mod='supercheckout'}</label>
                                                                                                <input type="radio" name="custom_fields[active]" id="custom_fields[active]_off" value="0">
                                                                                                <label for="custom_fields[active]_off">{l s='No' mod='supercheckout'}</label>
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
                                                                <button type="button" onclick="closeModalPopup('modal_add_new_custom_field_form')" class="btn btn-default">{l s='Close' mod='supercheckout'}</button>
                                                                <button type="button" onclick="submitForm()" class="btn btn-primary" id='custom_field_save'>
                                                                    {l s='Save' mod='supercheckout'}
                                                                    <img id='loader_add_form' class='loader_save_button hidden_custom' src='{$module_dir_url nofilter}{*escape not required as contains URL*}/supercheckout/views/img/admin/ajax_loader.gif'/>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- End - Modal Popup Add new Custom Field -->
                                                
                                                <!-- Start -Modal Popup Edit Custom Field -->
                                                <div class="modal fade" id="modal_edit_custom_field_form" tab-index="-1" aria-hidden="true" aria-labelledby="modal-incentive-form">
                                                    <!-- Render edit form here -->
                                                </div>
                                                <!-- End - Modal Popup Edit Custom Field -->
                                                
                                                <div id="tab_custom_fields" class="tab-pane tab-form">
                                                    <div class="block">
                                                        <h4 class='velsof-tab-heading'>{l s='Custom Fields' mod='supercheckout'}</h4>
                                                        <div>
                                                            <table class="table_custom_bordered" id="table_custom_fields_data" style="width: 100%;">
                                                                <thead>
                                                                    <tr>
                                                                        <th>{l s='S. No.' mod='supercheckout'}</th>
                                                                        <th>{l s='Field Label' mod='supercheckout'}</th>
                                                                        <th>{l s='Type' mod='supercheckout'}</th>
                                                                        <th>{l s='Position' mod='supercheckout'}</th>
                                                                        <th>{l s='Required' mod='supercheckout'}</th>
                                                                        <th>{l s='Active' mod='supercheckout'}</th>
                                                                        <th class="center">{l s='Action' mod='supercheckout'}</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="tbody_custom_fields_data">
                                                                    {assign var="counter" value="1"}
                                                                    {foreach from=$custom_fields_details item=array_field}
                                                                        <tr class="pure-table-odd" id="tr_pure_table_{$array_field['id_velsof_supercheckout_custom_fields']}">
                                                                            <td>{$counter}</td>
                                                                            <td class="width_25"><div class="div_250px_ellipsis">{$array_field['field_label']}</div></td>
                                                                            <td>{$array_field['type']}</td>
                                                                            <td>{$array_field['position']}</td>
                                                                            <td>{if $array_field['required'] eq '1'}{l s='Yes' mod='supercheckout'}{else}{l s='No' mod='supercheckout'}{/if}</td>
                                                                            <td>{if $array_field['active'] eq '1'}{l s='Yes' mod='supercheckout'}{else}{l s='No' mod='supercheckout'}{/if}</td>
                                                                            <td class="center" style="padding: 12px;">
                                                                                <a style="margin-top: -26px;" href="javascript://" onclick="displayEditCustomFieldPopup({$array_field['id_velsof_supercheckout_custom_fields']})" type="11" class="velsof-glyphicons2 glyphicons pencil"><i title="{l s='Edit this custom field' mod='supercheckout'}"></i></a>                                                                                                
                                                                                <a style="margin-top: -26px;" href="javascript://" onclick="deleteCustomFieldRow({$array_field['id_velsof_supercheckout_custom_fields']})" type="11" class="velsof-glyphicons2 glyphicons bin"><i title="{l s='Delete this custom field' mod='supercheckout'}"></i></a>
                                                                            </td>
                                                                        </tr>
                                                                        {assign var=counter value=$counter+1}
                                                                    {/foreach}
                                                                    
                                                                    <tr id="tr_custom_fields_add_new">
                                                                        <td colspan="6" class="opacity_0"></td>
                                                                        <td class="left center">
                                                                            <a style="cursor: pointer; text-decoration: none;" href="javascript:void(0)" onclick="addNewCustomFieldPopup()" data-toggle="modal">
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
                                                
                                                <!--------------- End - Custom Fields -------------------->


                                                <!--------------- Start - Cart -------------------->

                                                <div id="tab_cart" class="tab-pane tab-form">
                                                    <div class="block">
                                                        <h4 class='velsof-tab-heading'>{l s='Cart' mod='supercheckout'}</h4>
                                                        <table class="form">
                                                            <tr>
                                                                <td class="name vertical_top_align"><span class="control-label">{l s='Display Cart' mod='supercheckout'}: </span>                                                                
                                                                    <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="bottom" data-original-title="{l s='Display Cart Tooltip' mod='supercheckout'}"></i>
                                                                </td>
                                                                <td class="settings">
                                                                    <input type="hidden" value="0" name="velocity_supercheckout[display_cart]" />
                                                                    {if $velocity_supercheckout['display_cart'] eq 1}
                                                                        {if $IE7 eq true}
                                                                            <div>
                                                                                <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[display_cart]" checked="checked" />
                                                                            </div>
                                                                        {else}
                                                                            <div class="make-switch" data-on="primary" data-off="default">
                                                                                <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[display_cart]" checked="checked" />
                                                                            </div>
                                                                        {/if}                                                                    
                                                                    {else}
                                                                        {if $IE7 eq true}
                                                                            <div>
                                                                                <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[display_cart]" />
                                                                            </div>
                                                                        {else}
                                                                            <div class="make-switch" data-on="primary" data-off="default">
                                                                                <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[display_cart]"/>
                                                                            </div>
                                                                        {/if}
                                                                    {/if}
                                                                </td>
                                                            </tr>
                                                            {* changes started by rishabh jain on 2nd jan 19 to add option to show hide total, total price with tax,excluding tax etc
                                                            <tr>
                                                                <td class="name vertical_top_align"><span class="control-label">{l s='Display Total tax' mod='supercheckout'}: </span>                                                                
                                                                    <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="bottom" data-original-title="{l s='Display total tax field' mod='supercheckout'}"></i>
                                                                </td>
                                                                <td class="settings">
                                                                    <input type="hidden" value="0" name="velocity_supercheckout[display_cart_total_tax]" />
                                                                    {if $velocity_supercheckout['display_cart_total_tax'] eq 1}
                                                                        {if $IE7 eq true}
                                                                            <div>
                                                                                <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[display_cart_total_tax]" checked="checked" />
                                                                            </div>
                                                                        {else}
                                                                            <div class="make-switch" data-on="primary" data-off="default">
                                                                                <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[display_cart_total_tax]" checked="checked" />
                                                                            </div>
                                                                        {/if}                                                                    
                                                                    {else}
                                                                        {if $IE7 eq true}
                                                                            <div>
                                                                                <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[display_cart_total_tax]" />
                                                                            </div>
                                                                        {else}
                                                                            <div class="make-switch" data-on="primary" data-off="default">
                                                                                <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[display_cart_total_tax]"/>
                                                                            </div>
                                                                        {/if}
                                                                    {/if}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="name vertical_top_align"><span class="control-label">{l s='Display Shipping Cost' mod='supercheckout'}: </span>                                                                
                                                                    <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="bottom" data-original-title="{l s='Display Total shipping cost' mod='supercheckout'}"></i>
                                                                </td>
                                                                <td class="settings">
                                                                    <input type="hidden" value="0" name="velocity_supercheckout[display_cart_total_shipping]" />
                                                                    {if $velocity_supercheckout['display_cart_total_shipping'] eq 1}
                                                                        {if $IE7 eq true}
                                                                            <div>
                                                                                <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[display_cart_total_shipping]" checked="checked" />
                                                                            </div>
                                                                        {else}
                                                                            <div class="make-switch" data-on="primary" data-off="default">
                                                                                <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[display_cart_total_shipping]" checked="checked" />
                                                                            </div>
                                                                        {/if}                                                                    
                                                                    {else}
                                                                        {if $IE7 eq true}
                                                                            <div>
                                                                                <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[display_cart_total_shipping]" />
                                                                            </div>
                                                                        {else}
                                                                            <div class="make-switch" data-on="primary" data-off="default">
                                                                                <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[display_cart_total_shipping]"/>
                                                                            </div>
                                                                        {/if}
                                                                    {/if}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="name vertical_top_align"><span class="control-label">{l s='Display Total tax excluded' mod='supercheckout'}: </span>                                                                
                                                                    <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="bottom" data-original-title="{l s='Display Total tax excluded' mod='supercheckout'}"></i>
                                                                </td>
                                                                <td class="settings">
                                                                    <input type="hidden" value="0" name="velocity_supercheckout[display_cart_total_tax_excluded]" />
                                                                    {if $velocity_supercheckout['display_cart_total_tax_excluded'] eq 1}
                                                                        {if $IE7 eq true}
                                                                            <div>
                                                                                <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[display_cart_total_tax_excluded]" checked="checked" />
                                                                            </div>
                                                                        {else}
                                                                            <div class="make-switch" data-on="primary" data-off="default">
                                                                                <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[display_cart_total_tax_excluded]" checked="checked" />
                                                                            </div>
                                                                        {/if}                                                                    
                                                                    {else}
                                                                        {if $IE7 eq true}
                                                                            <div>
                                                                                <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[display_cart_total_tax_excluded]" />
                                                                            </div>
                                                                        {else}
                                                                            <div class="make-switch" data-on="primary" data-off="default">
                                                                                <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[display_cart_total_tax_excluded]"/>
                                                                            </div>
                                                                        {/if}
                                                                    {/if}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="name vertical_top_align"><span class="control-label">{l s='Display Total tax inclusive' mod='supercheckout'}: </span>                                                                
                                                                    <i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="bottom" data-original-title="{l s='Display Cart Tooltip' mod='supercheckout'}"></i>
                                                                </td>
                                                                <td class="settings">
                                                                    <input type="hidden" value="0" name="velocity_supercheckout[display_cart_total_tax_incl]" />
                                                                    {if $velocity_supercheckout['display_cart_total_tax_incl'] eq 1}
                                                                        {if $IE7 eq true}
                                                                            <div>
                                                                                <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[display_cart_total_tax_incl]" checked="checked" />
                                                                            </div>
                                                                        {else}
                                                                            <div class="make-switch" data-on="primary" data-off="default">
                                                                                <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[display_cart_total_tax_incl]" checked="checked" />
                                                                            </div>
                                                                        {/if}                                                                    
                                                                    {else}
                                                                        {if $IE7 eq true}
                                                                            <div>
                                                                                <input class="checkbox" type="checkbox" value="1" name="velocity_supercheckout[display_cart_total_tax_incl]" />
                                                                            </div>
                                                                        {else}
                                                                            <div class="make-switch" data-on="primary" data-off="default">
                                                                                <input class="make-switch" type="checkbox" value="1" name="velocity_supercheckout[display_cart_total_tax_incl]"/>
                                                                            </div>
                                                                        {/if}
                                                                    {/if}
                                                                </td>
                                                            </tr>
                                                            {* changes over *}

                                                        </table>

                                                        <table class="form alternate">
                                                            <thead>
                                                                <tr>
                                                                    <th></th>
                                                                    <th class="left drag-col-2 col-pad-left">{l s='Guest Customer' mod='supercheckout'}</th>
                                                                    <th class="left drag-col-2">{l s='Logged in Customer' mod='supercheckout'}</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="">
                                                                {foreach from=$velocity_supercheckout['cart_options'] key='k' item = 'p_addr'}
                                                                    <tr>
                                                                <input type="hidden" value="{$velocity_supercheckout['cart_options'][$k]['id']}" name="velocity_supercheckout[cart_options][{$k}][id]" />
                                                                <input type="hidden" value="{$velocity_supercheckout['cart_options'][$k]['title']}" name="velocity_supercheckout[cart_options][{$k}][title]" />
                                                                <td class="name"><span>{l s=$velocity_supercheckout['cart_options'][$k]['title'] mod='supercheckout'}:<input class="sort" class="input-sm form-control col-md-12" type="text" value="{if isset($velocity_supercheckout['cart_options'][$k]['sort_order'])}{$velocity_supercheckout['cart_options'][$k]['sort_order']|intval}{/if}" name="velocity_supercheckout[cart_options][{$k}][sort_order]" /></span></td>
                                                                <td class="left drag-col-2 col-pad-left">
                                                                    <div class="widget-body uniformjs" style="padding: 0 !important;">
                                                                        <label class="checkboxinline no-bold">
                                                                            <input type="checkbox" class="checkbox input-checkbox-option" name="velocity_supercheckout[cart_options][{$k}][guest][display]" value="{$velocity_supercheckout['cart_options'][$k]['guest']['display']|intval}" {if $velocity_supercheckout['cart_options'][$k]['guest']['display'] eq 1}checked="checked"{/if} />{l s='Show' mod='supercheckout'}                                                                        
                                                                        </label>
                                                                    </div>
                                                                </td>
                                                                <td class="left drag-col-2">
                                                                    <div class="widget-body uniformjs" style="padding: 0 !important;">
                                                                        <label class="checkboxinline no-bold">
                                                                            <input type="checkbox" class="checkbox input-checkbox-option" name="velocity_supercheckout[cart_options][{$k}][logged][display]" value="{$velocity_supercheckout['cart_options'][$k]['logged']['display']|intval}" {if $velocity_supercheckout['cart_options'][$k]['logged']['display'] eq 1}checked="checked"{/if} />{l s='Show' mod='supercheckout'}                                                                        
                                                                        </label>
                                                                    </div>
                                                                </td>
                                                                </tr>
                                                            {/foreach}
                                                            <tr{if $ps_version eq 15} class="vss_scparent_ver15"{/if}>
                                                                <td class="name"><span>{l s='Product Image Size' mod='supercheckout'}: </span><i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Product Image Size Tooltip' mod='supercheckout'}"></i></td>
                                                                <td colspan="2" class="left drag-col-2 col-pad-left">
                                                                    <div style="width: 45%" class='span1'><input type='text' {if $ps_version eq 15}class="form-control vss-form-control-ver15"{/if} name='velocity_supercheckout[cart_image_size][width]' value='{$velocity_supercheckout['cart_image_size']['width']|intval}' /><span id="cart_product_image_size_width_error" class="supercheckout_error"></span></div>
                                                                    <div class="span0{if $ps_version eq 15} vss-resolution-ver15{/if}">X</div>
                                                                    <div style="width: 45%" class='span1'><input type='text' {if $ps_version eq 15}class="form-control vss-form-control-ver15"{/if} name='velocity_supercheckout[cart_image_size][height]' value='{$velocity_supercheckout['cart_image_size']['height']|intval}' /><span id="cart_product_image_size_height_error" class="supercheckout_error"></span></div>
                                                                </td>
                                                            </tr>
                                                            <tr{if $ps_version eq 15} class="vss_scparent_ver15"{/if}>
                                                                <td class="name"><span>{l s='Quantity update option' mod='supercheckout'}: </span><i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Product Quantity Update option at front end in cart summary.' mod='supercheckout'}"></i></td>
                                                                <td class="left drag-col-2 col-pad-left">
                                                                    <div class="widget-body uniformjs" style="padding: 0 !important;">
                                                                        <label class="radio coupon_type_radio">
                                                                            <input type="radio" class="radio coupon_type_radio" name="velocity_supercheckout[qty_update_option]" value="0"  {if $velocity_supercheckout['qty_update_option'] eq 0} checked="checked" {/if} />{l s='+/- Button' mod='supercheckout'}                                                                        
                                                                        </label>
                                                                        <label class="radio coupon_type_radio">
                                                                            <input type="radio" class="radio coupon_type_radio" name="velocity_supercheckout[qty_update_option]" value="1" {if $velocity_supercheckout['qty_update_option'] eq 1} checked="checked" {/if} />{l s='Update Link' mod='supercheckout'}                                                                        
                                                                        </label>
                                                                    </div>
                                                                </td>
                                                                <td class="left drag-col-2">

                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                        <br><br>
                                                        <h4 class='velsof-tab-heading'>{l s='Order Total' mod='supercheckout'}</h4>
                                                        <table class="form alternate">
                                                            <thead>
                                                                <tr>
                                                                    <th></th>
                                                                    <th class="left drag-col-2 col-pad-left">{l s='Guest Customer' mod='supercheckout'}</th>
                                                                    <th class="left drag-col-2">{l s='Logged in Customer' mod='supercheckout'}</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="">
                                                                <tr>
                                                                    <td class="name"><span>{l s='Product(s) Sub-Total' mod='supercheckout'}: </span><i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Display Sub-Total Tooltip' mod='supercheckout'}"></i></td>
                                                                    <td class="left drag-col-2 col-pad-left">
                                                                        <div class="widget-body uniformjs" style="padding: 0 !important;">
                                                                            <label class="checkboxinline no-bold">
                                                                                <input type="checkbox" class="checkbox input-checkbox-option" name="velocity_supercheckout[order_total_option][product_sub_total][guest][display]" value="{$velocity_supercheckout['order_total_option']['product_sub_total']['guest']['display']|intval}" {if $velocity_supercheckout['order_total_option']['product_sub_total']['guest']['display'] eq 1}checked="checked"{/if} />{l s='Show' mod='supercheckout'}                                                                        
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td class="left drag-col-2">
                                                                        <div class="widget-body uniformjs" style="padding: 0 !important;">
                                                                            <label class="checkboxinline no-bold">
                                                                                <input type="checkbox" class="checkbox input-checkbox-option" name="velocity_supercheckout[order_total_option][product_sub_total][logged][display]" value="{$velocity_supercheckout['order_total_option']['product_sub_total']['logged']['display']|intval}" {if $velocity_supercheckout['order_total_option']['product_sub_total']['logged']['display'] eq 1}checked="checked"{/if} />{l s='Show' mod='supercheckout'}                                                                        
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="name"><span>{l s='Voucher Input' mod='supercheckout'}: </span><i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Display Voucher Input Tooltip' mod='supercheckout'}"></i></td>
                                                                    <td class="left drag-col-2 col-pad-left">
                                                                        <div class="widget-body uniformjs" style="padding: 0 !important;">
                                                                            <label class="checkboxinline no-bold">
                                                                                <input type="checkbox" class="checkbox input-checkbox-option" name="velocity_supercheckout[order_total_option][voucher][guest][display]" value="{$velocity_supercheckout['order_total_option']['voucher']['guest']['display']|intval}" {if $velocity_supercheckout['order_total_option']['voucher']['guest']['display'] eq 1}checked="checked"{/if} />{l s='Show' mod='supercheckout'}                                                                        
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td class="left drag-col-2">
                                                                        <div class="widget-body uniformjs" style="padding: 0 !important;">
                                                                            <label class="checkboxinline no-bold">
                                                                                <input type="checkbox" class="checkbox input-checkbox-option" name="velocity_supercheckout[order_total_option][voucher][logged][display]" value="{$velocity_supercheckout['order_total_option']['voucher']['logged']['display']|intval}" {if $velocity_supercheckout['order_total_option']['voucher']['logged']['display'] eq 1}checked="checked"{/if} />{l s='Show' mod='supercheckout'}                                                                        
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="name"><span>{l s='Shipping Price' mod='supercheckout'}:</span></td>
                                                                    <td class="left drag-col-2 col-pad-left">
                                                                        <div class="widget-body uniformjs" style="padding: 0 !important;">
                                                                            <label class="checkboxinline no-bold">
                                                                                <input type="checkbox" class="checkbox input-checkbox-option" name="velocity_supercheckout[order_total_option][shipping_price][guest][display]" value="{$velocity_supercheckout['order_total_option']['shipping_price']['guest']['display']|intval}" {if $velocity_supercheckout['order_total_option']['shipping_price']['guest']['display'] eq 1}checked="checked"{/if} />{l s='Show' mod='supercheckout'}                                                                        
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td class="left drag-col-2">
                                                                        <div class="widget-body uniformjs" style="padding: 0 !important;">
                                                                            <label class="checkboxinline no-bold">
                                                                                <input type="checkbox" class="checkbox input-checkbox-option" name="velocity_supercheckout[order_total_option][shipping_price][logged][display]" value="{$velocity_supercheckout['order_total_option']['shipping_price']['logged']['display']|intval}" {if $velocity_supercheckout['order_total_option']['shipping_price']['logged']['display'] eq 1}checked="checked"{/if} />{l s='Show' mod='supercheckout'}                                                                        
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                {* changes started by rishabh jain *}
                                                                <tr>
                                                                    <td class="name"><span>{l s='Total tax' mod='supercheckout'}:</span></td>
                                                                    <td class="left drag-col-2 col-pad-left">
                                                                        <div class="widget-body uniformjs" style="padding: 0 !important;">
                                                                            <label class="checkboxinline no-bold">
                                                                                <input type="checkbox" class="checkbox input-checkbox-option" name="velocity_supercheckout[order_total_option][total_tax][guest][display]" value="{$velocity_supercheckout['order_total_option']['total_tax']['guest']['display']|intval}" {if $velocity_supercheckout['order_total_option']['total_tax']['guest']['display'] eq 1}checked="checked"{/if} />{l s='Show' mod='supercheckout'}                                                                        
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td class="left drag-col-2">
                                                                        <div class="widget-body uniformjs" style="padding: 0 !important;">
                                                                            <label class="checkboxinline no-bold">
                                                                                <input type="checkbox" class="checkbox input-checkbox-option" name="velocity_supercheckout[order_total_option][total_tax][logged][display]" value="{$velocity_supercheckout['order_total_option']['total_tax']['logged']['display']|intval}" {if $velocity_supercheckout['order_total_option']['total_tax']['logged']['display'] eq 1}checked="checked"{/if} />{l s='Show' mod='supercheckout'}                                                                        
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                {* changes over *}
                                                                <tr>
                                                                    <td class="name"><span>{l s='Order Total' mod='supercheckout'}:</span></td>
                                                                    <td class="left drag-col-2 col-pad-left">
                                                                        <div class="widget-body uniformjs" style="padding: 0 !important;">
                                                                            <label class="checkboxinline no-bold">
                                                                                <input type="checkbox" class="checkbox input-checkbox-option" name="velocity_supercheckout[order_total_option][total][guest][display]" value="{$velocity_supercheckout['order_total_option']['total']['guest']['display']|intval}" {if $velocity_supercheckout['order_total_option']['total']['guest']['display'] eq 1}checked="checked"{/if} />{l s='Show' mod='supercheckout'}                                                                        
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td class="left drag-col-2">
                                                                        <div class="widget-body uniformjs" style="padding: 0 !important;">
                                                                            <label class="checkboxinline no-bold">
                                                                                <input type="checkbox" class="checkbox input-checkbox-option" name="velocity_supercheckout[order_total_option][total][logged][display]" value="{$velocity_supercheckout['order_total_option']['total']['logged']['display']|intval}" {if $velocity_supercheckout['order_total_option']['total']['logged']['display'] eq 1}checked="checked"{/if} />{l s='Show' mod='supercheckout'}                                                                        
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <br><br>
                                                        <h4 class='velsof-tab-heading'>{l s='Confirm' mod='supercheckout'}</h4>
                                                        <table class="form alternate">
                                                            <thead>
                                                                <tr>
                                                                    <th></th>
                                                                    <th class="left drag-col-2 col-pad-left">{l s='Guest Customer' mod='supercheckout'}</th>
                                                                    <th class="left drag-col-2">{l s='Logged in Customer' mod='supercheckout'}</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="">
                                                                {* changes by rishabh jain started on 4th jan *}
                                                                <tr>
                                                                    <td class="name"><span>{l s='Enable gift message details popup' mod='supercheckout'}:</span></td>
                                                                    <td class="left drag-col-2 col-pad-left">
                                                                        <div class="widget-body uniformjs" style="padding: 0 !important;">
                                                                            <label class="checkboxinline no-bold">
                                                                                <input type="checkbox" class="checkbox input-checkbox-option" name="velocity_supercheckout[confirm][gift_message][guest][display]" value="{$velocity_supercheckout['confirm']['gift_message']['guest']['display']|intval}" {if $velocity_supercheckout['confirm']['gift_message']['guest']['display'] eq 1}checked="checked"{/if} />{l s='Show' mod='supercheckout'}                                                                        
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td class="left drag-col-2">
                                                                        <div class="widget-body uniformjs" style="padding: 0 !important;">
                                                                            <label class="checkboxinline no-bold">
                                                                                <input type="checkbox" class="checkbox input-checkbox-option" name="velocity_supercheckout[confirm][gift_message][logged][display]" value="{$velocity_supercheckout['confirm']['gift_message']['logged']['display']|intval}" {if $velocity_supercheckout['confirm']['gift_message']['logged']['display'] eq 1}checked="checked"{/if} />{l s='Show' mod='supercheckout'}                                                                        
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                </tr>                                                                
                                                                {* changes over *}
                                                                <tr>
                                                                    <td class="name"><span>{l s='Term & Condition' mod='supercheckout'}: </span><i class="icon-question-sign supercheckout-tooltip-color" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Display Term & Condition Tooltip' mod='supercheckout'}"></i></td>
                                                                    <td class="left drag-col-2 col-pad-left">
                                                                        <div class="widget-body uniformjs" style="padding: 0 !important;">
                                                                            <label class="checkboxinline no-bold">
                                                                                <input type="checkbox" class="checkbox input-checkbox-option" name="velocity_supercheckout[confirm][term_condition][guest][display]" value="{$velocity_supercheckout['confirm']['term_condition']['guest']['display']|intval}" {if $velocity_supercheckout['confirm']['term_condition']['guest']['display'] eq 1}checked="checked"{/if} />{l s='Show' mod='supercheckout'}                                                                        
                                                                            </label>
                                                                            <label class="checkboxinline no-bold">
                                                                                <input type="checkbox" class="checkbox input-checkbox-option" name="velocity_supercheckout[confirm][term_condition][guest][require]" value="{$velocity_supercheckout['confirm']['term_condition']['guest']['require']|intval}" {if $velocity_supercheckout['confirm']['term_condition']['guest']['require'] eq 1}checked="checked"{/if} />{l s='Require' mod='supercheckout'}                                                                        
                                                                            </label>
                                                                            <label class="checkboxinline no-bold">
                                                                                <input type="checkbox" class="checkbox input-checkbox-option" name="velocity_supercheckout[confirm][term_condition][guest][checked]" value="{$velocity_supercheckout['confirm']['term_condition']['guest']['checked']|intval}" {if $velocity_supercheckout['confirm']['term_condition']['guest']['checked'] eq 1}checked="checked"{/if} />{l s='Show as Checked' mod='supercheckout'}                                                                        
                                                                            </label>
                                                                            <i class="store_disabled" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='This option will not display, if disable from default prestashop settings' mod='supercheckout'}"><span class="store_disabled_msg">{l s='Warning' mod='supercheckout'} !</span></i>
                                                                        </div>
                                                                    </td>
                                                                    <td class="left drag-col-2">
                                                                        <div class="widget-body uniformjs" style="padding: 0 !important;">
                                                                            <label class="checkboxinline no-bold">
                                                                                <input type="checkbox" class="checkbox input-checkbox-option" name="velocity_supercheckout[confirm][term_condition][logged][display]" value="{$velocity_supercheckout['confirm']['term_condition']['logged']['display']|intval}" {if $velocity_supercheckout['confirm']['term_condition']['logged']['display'] eq 1}checked="checked"{/if} />{l s='Show' mod='supercheckout'}                                                                        
                                                                            </label>
                                                                            <label class="checkboxinline no-bold">
                                                                                <input type="checkbox" class="checkbox input-checkbox-option" name="velocity_supercheckout[confirm][term_condition][logged][require]" value="{$velocity_supercheckout['confirm']['term_condition']['logged']['require']|intval}" {if $velocity_supercheckout['confirm']['term_condition']['logged']['require'] eq 1}checked="checked"{/if} />{l s='Require' mod='supercheckout'}                                                                        
                                                                            </label>
                                                                            <label class="checkboxinline no-bold">
                                                                                <input type="checkbox" class="checkbox input-checkbox-option" name="velocity_supercheckout[confirm][term_condition][logged][checked]" value="{$velocity_supercheckout['confirm']['term_condition']['logged']['checked']|intval}" {if $velocity_supercheckout['confirm']['term_condition']['logged']['checked'] eq 1}checked="checked"{/if} />{l s='Show as Checked' mod='supercheckout'}                                                                        
                                                                            </label>
                                                                            <i class="store_disabled" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='This option will not display, if disable from default prestashop settings' mod='supercheckout'}"><span class="store_disabled_msg">{l s='Warning' mod='supercheckout'} !</span></i>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="name"><span>{l s='Comment Box for Order' mod='supercheckout'}:</span></td>
                                                                    <td class="left drag-col-2 col-pad-left">
                                                                        <div class="widget-body uniformjs" style="padding: 0 !important;">
                                                                            <label class="checkboxinline no-bold">
                                                                                <input type="checkbox" class="checkbox input-checkbox-option" name="velocity_supercheckout[confirm][order_comment_box][guest][display]" value="{$velocity_supercheckout['confirm']['order_comment_box']['guest']['display']|intval}" {if $velocity_supercheckout['confirm']['order_comment_box']['guest']['display'] eq 1}checked="checked"{/if} />{l s='Show' mod='supercheckout'}                                                                        
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                    <td class="left drag-col-2">
                                                                        <div class="widget-body uniformjs" style="padding: 0 !important;">
                                                                            <label class="checkboxinline no-bold">
                                                                                <input type="checkbox" class="checkbox input-checkbox-option" name="velocity_supercheckout[confirm][order_comment_box][logged][display]" value="{$velocity_supercheckout['confirm']['order_comment_box']['logged']['display']|intval}" {if $velocity_supercheckout['confirm']['order_comment_box']['logged']['display'] eq 1}checked="checked"{/if} />{l s='Show' mod='supercheckout'}                                                                        
                                                                            </label>
                                                                        </div>
                                                                    </td>
                                                                </tr>                                                                
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>

                                                <!--------------- End - Cart -------------------->
                                                {* GDPR Change*}
                                                {include file="$gdpr_tpl_dir"}
                                                {* GDPR Change*}
                                                <!--------------- Start - Design -------------------->

                                                <div id="tab_design" class="tab-pane tab-form">
                                                    <div class="block">
                                                        <h4 class='velsof-tab-heading'>{l s='Design' mod='supercheckout'}</h4>                                                            
                                                        <div class="span3">
                                                            <select {if $ps_version eq 15}class="selectpicker vss_sc_ver15"{/if} name="velocity_supercheckout[layout]" onchange='$.cookie("designTab", 1);
                                                                    location.href = "{$action}&velsof_layout=" + $(this).val()'>
                                                                {if $layout eq 1}
                                                                    <option value="1" selected="selected">1-{l s='Columns' mod='supercheckout'}</option>
                                                                {else}
                                                                    <option value="1">1-{l s='Columns' mod='supercheckout'}</option>
                                                                {/if}
                                                                {if $layout eq 2}
                                                                    <option value="2" selected="selected">2-{l s='Columns' mod='supercheckout'}</option>
                                                                {else}
                                                                    <option value="2">2-{l s='Columns' mod='supercheckout'}</option>
                                                                {/if}
                                                                {if $layout eq 3}
                                                                    <option value="3" selected="selected">3-{l s='Columns' mod='supercheckout'}</option>
                                                                {else}
                                                                    <option value="3">3-{l s='Columns' mod='supercheckout'}</option>
                                                                {/if}
                                                            </select>
                                                        </div>
                                                        <table class="form">
                                                            <tbody>
                                                                <tr>
                                                                    {foreach from=$velocity_supercheckout['design']['html'] key='k' item='v'}
                                                                <input id="1_col_h_{$k}"  type="hidden"  class="sort col-data" name="velocity_supercheckout[design][html][{$k}][1_column][column]" value="{$velocity_supercheckout['design']['html'][$k]['1_column']['column']}" />
                                                                <input id="1_row_h_{$k}"  type="hidden"  class="sort row-data" name="velocity_supercheckout[design][html][{$k}][1_column][row]" value="{$velocity_supercheckout['design']['html'][$k]['1_column']['row']}" />
                                                                <input id="1_col_ins_h_{$k}"  type="hidden"  class="sort col-data-inside" name="velocity_supercheckout[design][html][{$k}][1_column][column-inside]" value="{$velocity_supercheckout['design']['html'][$k]['1_column']['column-inside']}" />

                                                                <input id="2_col_h_{$k}"  type="hidden"  class="sort col-data" name="velocity_supercheckout[design][html][{$k}][2_column][column]" value="{$velocity_supercheckout['design']['html'][$k]['2_column']['column']}" />
                                                                <input id="2_row_h_{$k}"  type="hidden"  class="sort row-data" name="velocity_supercheckout[design][html][{$k}][2_column][row]" value="{$velocity_supercheckout['design']['html'][$k]['2_column']['row']}" />
                                                                <input id="2_col_ins_h_{$k}"  type="hidden"  class="sort col-data-inside" name="velocity_supercheckout[design][html][{$k}][2_column][column-inside]" value="{$velocity_supercheckout['design']['html'][$k]['2_column']['column-inside']}" />

                                                                <input id="3_col_h_{$k}"  type="hidden"  class="sort col-data" name="velocity_supercheckout[design][html][{$k}][3_column][column]" value="{$velocity_supercheckout['design']['html'][$k]['3_column']['column']}" />
                                                                <input id="3_row_h_{$k}"  type="hidden"  class="sort row-data" name="velocity_supercheckout[design][html][{$k}][3_column][row]" value="{$velocity_supercheckout['design']['html'][$k]['3_column']['row']}" />
                                                                <input id="3_col_ins_h_{$k}"  type="hidden"  class="sort col-data-inside" name="velocity_supercheckout[design][html][{$k}][3_column][column-inside]" value="{$velocity_supercheckout['design']['html'][$k]['3_column']['column-inside']}" />
                                                            {/foreach}

                                                            <!-- Start - Reserve previous values for all layouts -->
                                                            {foreach from=$velocity_supercheckout['design'] key='tab_name' item='v'}
                                                                {if $tab_name neq 'html'}
                                                                    {foreach from=$velocity_supercheckout['design'][$tab_name] key='col_name' item='v1'}
                                                                        <input   type="hidden"  class="sort col-data" name="velocity_supercheckout[design][{$tab_name}][{$col_name}][column]" value="{$velocity_supercheckout['design'][$tab_name][$col_name]['column']}" />
                                                                        <input   type="hidden"  class="sort row-data" name="velocity_supercheckout[design][{$tab_name}][{$col_name}][row]" value="{$velocity_supercheckout['design'][$tab_name][$col_name]['row']}" />
                                                                        <input   type="hidden"  class="sort col-data-inside" name="velocity_supercheckout[design][{$tab_name}][{$col_name}][column-inside]" value="{$velocity_supercheckout['design'][$tab_name][$col_name]['column-inside']}" />
                                                                    {/foreach}
                                                                {/if}
                                                            {/foreach}                                                                
                                                            <!-- End - Reserve previous values for all layouts -->                                                                                                                                

                                                            <!-- Start - Header and footer Html -->                                                                    
                                                            <input type="hidden" id="modals_bootbox_prompt_html_header_value" name="velocity_supercheckout[html_value][header]" value="{if $velocity_supercheckout['html_value']['header'] neq ''}{html_entity_decode($velocity_supercheckout['html_value']['header'])}{/if}" />
                                                            <input type="hidden" id="modals_bootbox_prompt_html_footer_value" name="velocity_supercheckout[html_value][footer]" value="{if $velocity_supercheckout['html_value']['footer'] neq ''}{html_entity_decode($velocity_supercheckout['html_value']['footer'])}{/if}" />
                                                            <!-- End - Header and footer html -->

                                                            {if $layout eq 1}
                                                                <!-- Start - Reserve previous width of all layouts -->
                                                                {foreach from=$velocity_supercheckout['column_width'] key='tab_name' item='v'}
                                                                    <input type="hidden"  class="column-data-1 col" name="velocity_supercheckout[column_width][{$tab_name}][1]" value="{$velocity_supercheckout['column_width'][$tab_name][1]}" />
                                                                    <input type="hidden"  class="column-data-2 col" name="velocity_supercheckout[column_width][{$tab_name}][2]" value="{$velocity_supercheckout['column_width'][$tab_name][2]}" />
                                                                    <input type="hidden"  class="column-data-2 col" name="velocity_supercheckout[column_width][{$tab_name}][3]" value="{$velocity_supercheckout['column_width'][$tab_name][3]}" />
                                                                    <input type="hidden"  class="column-data-3 col" name="velocity_supercheckout[column_width][{$tab_name}][inside][1]" value="{$velocity_supercheckout['column_width'][$tab_name]['inside'][1]}" />
                                                                    <input type="hidden"  class="column-data-3 col" name="velocity_supercheckout[column_width][{$tab_name}][inside][2]" value="{$velocity_supercheckout['column_width'][$tab_name]['inside'][2]}" />
                                                                {/foreach}
                                                                <!-- End - Reserve previous width of all layouts -->
                                                                <td  colspan="2" style="position:static; width: 960px;">
                                                                    <div class="portlet">
                                                                        <div class="portlet-header">{l s='HTML Header Content' mod='supercheckout'}</div>
                                                                        <div class="portlet-content">
                                                                            <div class="text" style="overflow:visible !important;" >
                                                                                <a data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Edit this HTML content' mod='supercheckout'}" id="modals_bootbox_prompt_html_header" data-toggle="modal" class="glyphicons edit bootbox-design-edit-html" ><i></i></a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <ul id="column-1" class="column column-1" col-data="1" col-inside-data="1" style="width:100%  !important;">
                                                                        <li class="portlet" row-data="{$velocity_supercheckout['design']['payment_address']['1_column']['row']|intval}" >
                                                                            <div class="portlet-header"><i class="icon-small-payment-address"></i>{l s='Payment Address' mod='supercheckout'}</div>
                                                                            <div class="portlet-content">
                                                                                <div class="text">{l s='Payment Address Content' mod='supercheckout'}</div>
                                                                                <div class="text"><i class="icon-drag"></i><i class="icon-drag"></i></div>
                                                                                <input   type="text"  class="sort row-data" name="velocity_supercheckout[design][payment_address][1_column][row]" value="{$velocity_supercheckout['design']['payment_address']['1_column']['row']|intval}" />
                                                                            </div>
                                                                        </li>
                                                                        <li class="portlet" row-data="{$velocity_supercheckout['design']['login']['1_column']['row']|intval}">
                                                                            <div class="portlet-header"><i class="icon-small-payment-address"></i>{l s='Login' mod='supercheckout'}</div>
                                                                            <div class="portlet-content">
                                                                                <div class="text">{l s='Login Content' mod='supercheckout'}</div>
                                                                                <div class="text"><i class="icon-drag"></i><i class="icon-drag"></i></div>
                                                                                <input   type="text"  class="sort row-data" name="velocity_supercheckout[design][login][1_column][row]" value="{$velocity_supercheckout['design']['login']['1_column']['row']|intval}" />
                                                                            </div>
                                                                        </li>
                                                                        <li class="portlet" row-data="{$velocity_supercheckout['design']['shipping_address']['1_column']['row']|intval}">
                                                                            <div class="portlet-header"><i class="icon-small-shipping-address"></i>{l s='Shipping Address' mod='supercheckout'}</div>
                                                                            <div class="portlet-content">
                                                                                <div class="text">{l s='Shipping Address Content' mod='supercheckout'}</div>
                                                                                <div class="text"><i class="icon-drag"></i><i class="icon-drag"></i></div>
                                                                                <input   type="text"  class="sort row-data" name="velocity_supercheckout[design][shipping_address][1_column][row]" value="{$velocity_supercheckout['design']['shipping_address']['1_column']['row']|intval}" />
                                                                            </div>
                                                                        </li>
                                                                        <li class="portlet" row-data="{$velocity_supercheckout['design']['shipping_method']['1_column']['row']|intval}">
                                                                            <div class="portlet-header"><i class="icon-small-shipping-method"></i>{l s='Shipping Method' mod='supercheckout'}</div>
                                                                            <div class="portlet-content">
                                                                                <div class="text">{l s='Shipping Method Content' mod='supercheckout'}</div>
                                                                                <div class="text"><i class="icon-drag"></i><i class="icon-drag"></i></div>
                                                                                <input   type="text"  class="sort row-data" name="velocity_supercheckout[design][shipping_method][1_column][row]" value="{$velocity_supercheckout['design']['shipping_method']['1_column']['row']|intval}" />
                                                                            </div>
                                                                        </li>
                                                                        <li class="portlet" row-data="{$velocity_supercheckout['design']['payment_method']['1_column']['row']|intval}">
                                                                            <div class="portlet-header"><i class="icon-small-payment-method"></i>{l s='Payment Method' mod='supercheckout'}</div>
                                                                            <div class="portlet-content">
                                                                                <div class="text">{l s='Payment Method Content' mod='supercheckout'}</div>
                                                                                <div class="text"><i class="icon-drag"></i><i class="icon-drag"></i></div>
                                                                                <input   type="text"  class="sort row-data" name="velocity_supercheckout[design][payment_method][1_column][row]" value="{$velocity_supercheckout['design']['payment_method']['1_column']['row']|intval}" />
                                                                            </div>
                                                                        </li>
                                                                        <li class="portlet" row-data="{$velocity_supercheckout['design']['cart']['1_column']['row']|intval}">
                                                                            <div class="portlet-header"><i class="icon-small-confirm"></i>{l s='Cart' mod='supercheckout'}</div>
                                                                            <div class="portlet-content">
                                                                                <div class="text">{l s='Cart Content' mod='supercheckout'}</div>
                                                                                <div class="text"><i class="icon-drag"></i><i class="icon-drag"></i></div>
                                                                                <input   type="text"  class="sort row-data" name="velocity_supercheckout[design][cart][1_column][row]" value="{$velocity_supercheckout['design']['cart']['1_column']['row']|intval}" />
                                                                            </div>
                                                                        </li>
                                                                        <li class="portlet"  row-data="{$velocity_supercheckout['design']['confirm']['1_column']['row']|intval}">
                                                                            <div class="portlet-header"><i class="icon-small-confirm"></i>{l s='Confirm' mod='supercheckout'}</div>
                                                                            <div class="portlet-content">
                                                                                <div class="text">{l s='Confirm Content' mod='supercheckout'}</div>
                                                                                <div class="text"><i class="icon-drag"></i><i class="icon-drag"></i></div>
                                                                                <input   type="text"  class="sort row-data" name="velocity_supercheckout[design][confirm][1_column][row]" value="{$velocity_supercheckout['design']['confirm']['1_column']['row']|intval}" />
                                                                            </div>
                                                                        </li>
                                                                        <li class="portlet" row-data="{$velocity_supercheckout['design']['html']['0_0']['1_column']['row']|intval}">
                                                                            <div class="portlet-header"><i class="icon-small-confirm"></i>{l s='Html Content' mod='supercheckout'}</div>
                                                                            <div class="portlet-content">
                                                                                <div class="text">{l s='Extra html content' mod='supercheckout'}</div>
                                                                                <div class="text"><i class="icon-drag"></i><i class="icon-drag"></i></div>
                                                                                <input   type="text"  class="sort row-data" name="velocity_supercheckout[design][html][0_0][1_column][row]" value="{$velocity_supercheckout['design']['html']['0_0']['1_column']['row']|intval}" />
                                                                            </div>
                                                                        </li>
                                                                        {foreach from=$velocity_supercheckout['design']['html'] key='k' item='v'}
                                                                            <li class="portlet" id="portlet_{$k}" row-data="{$velocity_supercheckout['design']['html'][$k]['1_column']['row']|intval}">
                                                                                <div class="portlet-header">{l s='Extra html content' mod='supercheckout'}</div>
                                                                                <div class="portlet-content" id="portlet_content_{$k}">
                                                                                    <div class="text" style="overflow:visible !important;" >
                                                                                        <a data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Add new HTML content' mod='supercheckout'}" id="duplicate_button_{$k}" data="0" class="glyphicons more_windows"  onClick="duplicate_html(this);" ><i></i></a>

                                                                                        <a data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Edit this HTML content' mod='supercheckout'}" id="modals-bootbox-prompt-{$k}" data-toggle="modal" class="glyphicons edit bootbox-design-extra-html"  onClick="dialogExtraHtml(this);"><i></i></a>
                                                                                                {if $k neq "0_0"}
                                                                                            <a data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Remove this HTML content' mod='supercheckout'}" id="delete_button_{$k}" data="{$k}" data-toggle="modal" class="glyphicons remove"  onClick="remove_html(this);" ><i></i></a>
                                                                                                {/if}
                                                                                    </div>

                                                                                    <input id="row_text_{$k}"  type="text"  class="sort row-data" name="velocity_supercheckout[design][html][{$k}][1_column][row]" value="{$velocity_supercheckout['design']['html'][$k]['1_column']['row']|intval}" />
                                                                                </div>
                                                                            </li>
                                                                        {/foreach}
                                                                    </ul>
                                                                    <div class="portlet">
                                                                        <div class="portlet-header">{l s='HTML Footer Content' mod='supercheckout'}</div>
                                                                        <div class="portlet-content">
                                                                            <div class="text" style="overflow:visible !important;" >

                                                                                <a data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Edit this HTML content' mod='supercheckout'}" id="modals_bootbox_prompt_html_footer" data-toggle="modal" class="glyphicons edit bootbox-design-edit-html" ><i></i></a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            {elseif $layout eq 2}
                                                                <td  colspan="2" style="position:static">
                                                                    <div class="portlet">
                                                                        <div class="portlet-header">{l s='HTML Header Content' mod='supercheckout'}</div>
                                                                        <div class="portlet-content">
                                                                            <div class="text" style="overflow:visible !important;" >

                                                                                <a data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Edit this HTML content' mod='supercheckout'}" id="modals_bootbox_prompt_html_header" data-toggle="modal" class="glyphicons edit bootbox-design-edit-html" ><i></i></a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="columns">
                                                                        <input type="hidden"  class="column-data-1 col" name="velocity_supercheckout[column_width][1_column][1]" value="{$velocity_supercheckout['column_width']['1_column'][1]}" />
                                                                        <input type="hidden"  class="column-data-2 col" name="velocity_supercheckout[column_width][1_column][2]" value="{$velocity_supercheckout['column_width']['1_column'][2]}" />
                                                                        <input type="hidden"  class="column-data-2 col" name="velocity_supercheckout[column_width][1_column][3]" value="{$velocity_supercheckout['column_width']['1_column'][3]}" />
                                                                        <input type="hidden"  class="column-data-3 col" name="velocity_supercheckout[column_width][1_column][inside][1]" value="{$velocity_supercheckout['column_width']['1_column']['inside'][1]}" />
                                                                        <input type="hidden"  class="column-data-3 col" name="velocity_supercheckout[column_width][1_column][inside][2]" value="{$velocity_supercheckout['column_width']['1_column']['inside'][2]}" />

                                                                        <input type="hidden"  class="column-data-1 col" name="velocity_supercheckout[column_width][3_column][1]" value="{$velocity_supercheckout['column_width']['3_column'][1]}" />
                                                                        <input type="hidden"  class="column-data-2 col" name="velocity_supercheckout[column_width][3_column][2]" value="{$velocity_supercheckout['column_width']['3_column'][2]}" />
                                                                        <input type="hidden"  class="column-data-2 col" name="velocity_supercheckout[column_width][3_column][3]" value="{$velocity_supercheckout['column_width']['3_column'][3]}" />
                                                                        <input type="hidden"  class="column-data-3 col" name="velocity_supercheckout[column_width][3_column][inside][1]" value="{$velocity_supercheckout['column_width']['3_column']['inside'][1]}" />
                                                                        <input type="hidden"  class="column-data-3 col" name="velocity_supercheckout[column_width][3_column][inside][2]" value="{$velocity_supercheckout['column_width']['3_column']['inside'][2]}" />

                                                                        <input type="hidden"  class="column-data-2 col" name="velocity_supercheckout[column_width][2_column][3]" value="{$velocity_supercheckout['column_width']['2_column'][3]}" />

                                                                        <input type="text" id="column-1-text"  class="column-data-1 col velsof-column-2-input" name="velocity_supercheckout[column_width][2_column][1]" value="{$velocity_supercheckout['column_width']['2_column'][1]}" />
                                                                        <input type="text" id="column-2-text" class="column-data-2 col velsof-column-2-input" name="velocity_supercheckout[column_width][2_column][2]" value="{$velocity_supercheckout['column_width']['2_column'][2]}" />
                                                                    </div>
                                                                    <div id="slider" class="ui-editRangeSlider"></div>
                                                                    <ul id="column-1" class="column column-1 layout_list_height" col-data="1" col-inside-data="1">
                                                                        <li class="portlet" col-data="{$velocity_supercheckout['design']['payment_address']['2_column']['column']|intval}" row-data="{$velocity_supercheckout['design']['payment_address']['2_column']['row']|intval}" col-inside-data="{$velocity_supercheckout['design']['payment_address']['2_column']['column-inside']|intval}">
                                                                            <div class="portlet-header"><i class="icon-small-payment-address"></i>{l s='Payment Address' mod='supercheckout'}</div>
                                                                            <div class="portlet-content">
                                                                                <div class="text">{l s='Payment Address Content' mod='supercheckout'}</div>
                                                                                <div class="text"><i class="icon-drag"></i><i class="icon-drag"></i></div>
                                                                                <input   type="text"  class="sort col-data" name="velocity_velocity_supercheckout[design][payment_address][2_column][column]" value="{$velocity_supercheckout['design']['payment_address']['2_column']['column']|intval}" />
                                                                                <input   type="text"  class="sort row-data" name="velocity_supercheckout[design][payment_address][2_column][row]" value="{$velocity_supercheckout['design']['payment_address']['2_column']['row']|intval}" />
                                                                                <input   type="text"  class="sort col-data-inside" name="velocity_supercheckout[design][payment_address][2_column][column-inside]" value="{$velocity_supercheckout['design']['payment_address']['2_column']['column-inside']|intval}" />
                                                                            </div>
                                                                        </li>
                                                                        <li class="portlet" col-data="{$velocity_supercheckout['design']['login']['2_column']['column']|intval}" row-data="{$velocity_supercheckout['design']['login']['2_column']['row']|intval}" col-inside-data="{$velocity_supercheckout['design']['login']['2_column']['column-inside']|intval}">
                                                                            <div class="portlet-header"><i class="icon-small-payment-address"></i>{l s='Login' mod='supercheckout'}</div>
                                                                            <div class="portlet-content">
                                                                                <div class="text">{l s='Login Content' mod='supercheckout'}</div>
                                                                                <div class="text"><i class="icon-drag"></i><i class="icon-drag"></i></div>
                                                                                <input   type="text"  class="sort col-data" name="velocity_supercheckout[design][login][2_column][column]" value="{$velocity_supercheckout['design']['login']['2_column']['column']|intval}" />
                                                                                <input   type="text"  class="sort row-data" name="velocity_supercheckout[design][login][2_column][row]" value="{$velocity_supercheckout['design']['login']['2_column']['row']|intval}" />
                                                                                <input   type="text"  class="sort col-data-inside" name="velocity_supercheckout[design][login][2_column][column-inside]" value="{$velocity_supercheckout['design']['login']['2_column']['column-inside']|intval}" />
                                                                            </div>
                                                                        </li>
                                                                        <li class="portlet" col-data="{$velocity_supercheckout['design']['shipping_address']['2_column']['column']|intval}" row-data="{$velocity_supercheckout['design']['shipping_address']['2_column']['row']|intval}" col-inside-data="{$velocity_supercheckout['design']['shipping_address']['2_column']['column-inside']|intval}">
                                                                            <div class="portlet-header"><i class="icon-small-shipping-address"></i>{l s='Shipping Address' mod='supercheckout'}</div>
                                                                            <div class="portlet-content">
                                                                                <div class="text">{l s='Shipping Address Content' mod='supercheckout'}</div>
                                                                                <div class="text"><i class="icon-drag"></i><i class="icon-drag"></i></div>
                                                                                <input   type="text"  class="sort col-data" name="velocity_supercheckout[design][shipping_address][2_column][column]" value="{$velocity_supercheckout['design']['shipping_address']['2_column']['column']|intval}" />
                                                                                <input   type="text"  class="sort row-data" name="velocity_supercheckout[design][shipping_address][2_column][row]" value="{$velocity_supercheckout['design']['shipping_address']['2_column']['row']|intval}" />
                                                                                <input   type="text"  class="sort col-data-inside" name="velocity_supercheckout[design][shipping_address][2_column][column-inside]" value="{$velocity_supercheckout['design']['shipping_address']['2_column']['column-inside']|intval}" />
                                                                            </div>
                                                                        </li>
                                                                        <li class="portlet" col-data="{$velocity_supercheckout['design']['shipping_method']['2_column']['column']|intval}" row-data="{$velocity_supercheckout['design']['shipping_method']['2_column']['row']|intval}" col-inside-data="{$velocity_supercheckout['design']['shipping_method']['2_column']['column-inside']|intval}">
                                                                            <div class="portlet-header"><i class="icon-small-shipping-method"></i>{l s='Shipping Method' mod='supercheckout'}</div>
                                                                            <div class="portlet-content">
                                                                                <div class="text">{l s='Shipping Method Content' mod='supercheckout'}</div>
                                                                                <div class="text"><i class="icon-drag"></i><i class="icon-drag"></i></div>
                                                                                <input   type="text"  class="sort col-data" name="velocity_supercheckout[design][shipping_method][2_column][column]" value="{$velocity_supercheckout['design']['shipping_method']['2_column']['column']|intval}" />
                                                                                <input   type="text"  class="sort row-data" name="velocity_supercheckout[design][shipping_method][2_column][row]" value="{$velocity_supercheckout['design']['shipping_method']['2_column']['row']|intval}" />
                                                                                <input   type="text"  class="sort col-data-inside" name="velocity_supercheckout[design][shipping_method][2_column][column-inside]" value="{$velocity_supercheckout['design']['shipping_method']['2_column']['column-inside']|intval}" />
                                                                            </div>
                                                                        </li>
                                                                        <li class="portlet" col-data="{$velocity_supercheckout['design']['payment_method']['2_column']['column']|intval}" row-data="{$velocity_supercheckout['design']['payment_method']['2_column']['row']|intval}" col-inside-data="{$velocity_supercheckout['design']['payment_method']['2_column']['column-inside']|intval}">
                                                                            <div class="portlet-header"><i class="icon-small-payment-method"></i>{l s='Payment Method' mod='supercheckout'}</div>
                                                                            <div class="portlet-content">
                                                                                <div class="text">{l s='Payment Method Content' mod='supercheckout'}</div>
                                                                                <div class="text"><i class="icon-drag"></i><i class="icon-drag"></i></div>
                                                                                <input   type="text"  class="sort col-data" name="velocity_supercheckout[design][payment_method][2_column][column]" value="{$velocity_supercheckout['design']['payment_method']['2_column']['column']|intval}" />
                                                                                <input   type="text"  class="sort row-data" name="velocity_supercheckout[design][payment_method][2_column][row]" value="{$velocity_supercheckout['design']['payment_method']['2_column']['row']|intval}" />
                                                                                <input   type="text"  class="sort col-data-inside" name="velocity_supercheckout[design][payment_method][2_column][column-inside]" value="{$velocity_supercheckout['design']['payment_method']['2_column']['column-inside']|intval}" />
                                                                            </div>
                                                                        </li>
                                                                        <li class="portlet" col-data="{$velocity_supercheckout['design']['cart']['2_column']['column']|intval}" row-data="{$velocity_supercheckout['design']['cart']['2_column']['row']|intval}" col-inside-data="{$velocity_supercheckout['design']['cart']['2_column']['column-inside']|intval}">
                                                                            <div class="portlet-header"><i class="icon-small-confirm"></i>{l s='Cart' mod='supercheckout'}</div>
                                                                            <div class="portlet-content">
                                                                                <div class="text">{l s='Cart Content' mod='supercheckout'}</div>
                                                                                <div class="text"><i class="icon-drag"></i><i class="icon-drag"></i></div>
                                                                                <input   type="text"  class="sort col-data" name="velocity_supercheckout[design][cart][2_column][column]" value="{$velocity_supercheckout['design']['cart']['2_column']['column']|intval}" />
                                                                                <input   type="text"  class="sort row-data" name="velocity_supercheckout[design][cart][2_column][row]" value="{$velocity_supercheckout['design']['cart']['2_column']['row']|intval}" />
                                                                                <input   type="text"  class="sort col-data-inside" name="velocity_supercheckout[design][cart][2_column][column-inside]" value="{$velocity_supercheckout['design']['cart']['2_column']['column-inside']|intval}" />
                                                                            </div>
                                                                        </li>
                                                                        <li class="portlet" col-data="{$velocity_supercheckout['design']['confirm']['2_column']['column']|intval}" row-data="{$velocity_supercheckout['design']['confirm']['2_column']['row']|intval}" col-inside-data="{$velocity_supercheckout['design']['confirm']['2_column']['column-inside']|intval}">
                                                                            <div class="portlet-header"><i class="icon-small-confirm"></i>{l s='Confirm' mod='supercheckout'}</div>
                                                                            <div class="portlet-content">
                                                                                <div class="text">{l s='Confirm Content' mod='supercheckout'}</div>
                                                                                <div class="text"><i class="icon-drag"></i><i class="icon-drag"></i></div>
                                                                                <input   type="text"  class="sort col-data" name="velocity_supercheckout[design][confirm][2_column][column]" value="{$velocity_supercheckout['design']['confirm']['2_column']['column']|intval}" />
                                                                                <input   type="text"  class="sort row-data" name="velocity_supercheckout[design][confirm][2_column][row]" value="{$velocity_supercheckout['design']['confirm']['2_column']['row']|intval}" />
                                                                                <input   type="text"  class="sort col-data-inside" name="velocity_supercheckout[design][confirm][2_column][column-inside]" value="{$velocity_supercheckout['design']['confirm']['2_column']['column-inside']|intval}" />
                                                                            </div>
                                                                        </li>

                                                                        {foreach from=$velocity_supercheckout['design']['html'] key='k' item='v'}                                                                            
                                                                            <li class="portlet" id="portlet_{$k}" col-data="{$velocity_supercheckout['design']['html'][$k]['2_column']['column']|intval}" row-data="{$velocity_supercheckout['design']['html'][$k]['2_column']['row']|intval}" col-inside-data="{$velocity_supercheckout['design']['html'][$k]['2_column']['column-inside']|intval}">
                                                                                <div class="portlet-header">{l s='Extra html content' mod='supercheckout'}</div>
                                                                                <div class="portlet-content" id="portlet_content_{$k}">
                                                                                    <div class="text" style="overflow:visible !important;" >
                                                                                        <a data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Add new HTML content' mod='supercheckout'}" id="duplicate_button_{$k}" data="0" class="glyphicons more_windows"  onClick="duplicate_html(this);" ><i></i></a>

                                                                                        <a data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Edit this HTML content' mod='supercheckout'}" id="modals-bootbox-prompt-{$k}" data-toggle="modal" class="glyphicons edit bootbox-design-extra-html"  onClick="dialogExtraHtml(this);"><i></i></a>
                                                                                                {if $k neq "0_0"}
                                                                                            <a data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Remove this HTML content' mod='supercheckout'}" id="delete_button_{$k}" data="{$k}" data-toggle="modal" class="glyphicons remove"  onClick="remove_html(this);" ><i></i></a>
                                                                                                {/if}
                                                                                    </div>

                                                                                    <input id="col_text_{$k}"   type="text"  class="sort col-data" name="velocity_supercheckout[design][html][{$k}][2_column][column]" value="{$velocity_supercheckout['design']['html'][$k]['2_column']['column']|intval}" />
                                                                                    <input id="row_text_{$k}"  type="text"  class="sort row-data" name="velocity_supercheckout[design][html][{$k}][2_column][row]" value="{$velocity_supercheckout['design']['html'][$k]['2_column']['row']|intval}" />
                                                                                    <input id="col_inside_text_{$k}"  type="text"  class="sort col-data-inside" name="velocity_supercheckout[design][html][{$k}][2_column][column-inside]" value="{$velocity_supercheckout['design']['html'][$k]['2_column']['column-inside']|intval}" />
                                                                                </div>
                                                                            </li>
                                                                        {/foreach}
                                                                    </ul>
                                                                    <ul id="column-2" class="columnmk column-2 layout_list_height" col-data="2" >
                                                                        <ul id="column-2-upper" class="column column-1" col-data="1" col-inside-data="2" style="min-height: 30px !important; width:100% !important; height:auto !important;">

                                                                        </ul>
                                                                        <div class="columns">
                                                                            <input type="text" id="column-1-inside-text"  class="column-data-1 col" name="velocity_supercheckout[column_width][2_column][inside][1]" value="{$velocity_supercheckout['column_width']['2_column']['inside'][1]|intval}" />
                                                                            <input type="text" id="column-2-inside-text"  class="column-data-2 col" name="velocity_supercheckout[column_width][2_column][inside][2]" value="{$velocity_supercheckout['column_width']['2_column']['inside'][2]|intval}" />
                                                                        </div>
                                                                        <div id="slider_inside" class="ui-editRangeSlider" style="clear:both;"></div>

                                                                        <ul id="column-1-inside" class="column column-1" col-inside-data="3" col-data="1" style="min-height: 30px !important; height:auto !important;">

                                                                        </ul>
                                                                        <ul id="column-2-inside" class="column column-2" col-inside-data="3" col-data="2" style="min-height: 30px !important; height:auto !important;"></ul>
                                                                        <hr class="design-separator" size="2">
                                                                        <ul id="column-2-lower" class="column column-1" col-data="1" col-inside-data="4" style="min-height: 30px !important; width:100% !important; height:auto !important;">

                                                                        </ul>        
                                                                    </ul>
                                                                    <div class="portlet">
                                                                        <div class="portlet-header">{l s='HTML Footer Content' mod='supercheckout'}</div>
                                                                        <div class="portlet-content">
                                                                            <div class="text" style="overflow:visible !important;" >

                                                                                <a data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Edit this HTML content' mod='supercheckout'}" id="modals_bootbox_prompt_html_footer" data-toggle="modal" class="glyphicons edit bootbox-design-edit-html" ><i></i></a>
                                                                            </div>
                                                                        </div>
                                                                    </div>                                                                        
                                                                </td>

                                                            {elseif $layout eq 3}
                                                                <td  colspan="2" style="position:static">
                                                                    <div class="portlet">
                                                                        <div class="portlet-header">{l s='HTML Header Content' mod='supercheckout'}</div>
                                                                        <div class="portlet-content">
                                                                            <div class="text" style="overflow:visible !important;" >

                                                                                <a data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Edit this HTML content' mod='supercheckout'}" id="modals_bootbox_prompt_html_header" data-toggle="modal" class="glyphicons edit bootbox-design-edit-html" ><i></i></a>
                                                                            </div>
                                                                        </div>  
                                                                    </div>
                                                                    <div class="columns">
                                                                        <input type="hidden"  class="column-data-1 col" name="velocity_supercheckout[column_width][1_column][1]" value="{$velocity_supercheckout['column_width']['1_column'][1]|intval}" />
                                                                        <input type="hidden"  class="column-data-2 col" name="velocity_supercheckout[column_width][1_column][2]" value="{$velocity_supercheckout['column_width']['1_column'][2]|intval}" />
                                                                        <input type="hidden"  class="column-data-2 col" name="velocity_supercheckout[column_width][1_column][3]" value="{$velocity_supercheckout['column_width']['1_column'][3]|intval}" />
                                                                        <input type="hidden"  class="column-data-3 col" name="velocity_supercheckout[column_width][1_column][inside][1]" value="{$velocity_supercheckout['column_width']['1_column']['inside'][1]|intval}" />
                                                                        <input type="hidden"  class="column-data-3 col" name="velocity_supercheckout[column_width][1_column][inside][2]" value="{$velocity_supercheckout['column_width']['1_column']['inside'][2]|intval}" />

                                                                        <input type="hidden"  class="column-data-1 col" name="velocity_supercheckout[column_width][2_column][1]" value="{$velocity_supercheckout['column_width']['2_column'][1]|intval}" />
                                                                        <input type="hidden"  class="column-data-2 col" name="velocity_supercheckout[column_width][2_column][2]" value="{$velocity_supercheckout['column_width']['2_column'][2]|intval}" />
                                                                        <input type="hidden"  class="column-data-2 col" name="velocity_supercheckout[column_width][2_column][3]" value="{$velocity_supercheckout['column_width']['2_column'][3]|intval}" />
                                                                        <input type="hidden"  class="column-data-3 col" name="velocity_supercheckout[column_width][2_column][inside][1]" value="{$velocity_supercheckout['column_width']['2_column']['inside'][1]|intval}" />
                                                                        <input type="hidden"  class="column-data-3 col" name="velocity_supercheckout[column_width][2_column][inside][2]" value="{$velocity_supercheckout['column_width']['2_column']['inside'][2]|intval}" />

                                                                        <input type="hidden"  class="column-data-3 col" name="velocity_supercheckout[column_width][3_column][inside][1]" value="{$velocity_supercheckout['column_width']['3_column']['inside'][1]|intval}" />
                                                                        <input type="hidden"  class="column-data-3 col" name="velocity_supercheckout[column_width][3_column][inside][2]" value="{$velocity_supercheckout['column_width']['3_column']['inside'][2]|intval}" />
                                                                        <input type="text" id="three-column-1" readonly="readonly" class="column-data-1 col" name="velocity_supercheckout[column_width][3_column][1]" value="{$velocity_supercheckout['column_width']['3_column'][1]|intval}" />
                                                                        <input type="text" id="three-column-2" readonly="readonly" class="column-data-2 col" name="velocity_supercheckout[column_width][3_column][2]" value="{$velocity_supercheckout['column_width']['3_column'][2]|intval}" />
                                                                        <input type="text" id="three-column-3" readonly="readonly" class="column-data-3 col" name="velocity_supercheckout[column_width][3_column][3]" value="{$velocity_supercheckout['column_width']['3_column'][3]|intval}" />
                                                                    </div>
                                                                    <div id="slider" class="ui-editRangeSlider"></div>
                                                                    <ul id="column_1" class="column column-1 layout_list_height" col-data="1">
                                                                        <li class="portlet" col-data="{$velocity_supercheckout['design']['payment_address']['3_column']['column']|intval}" row-data="{$velocity_supercheckout['design']['payment_address']['3_column']['row']|intval}">
                                                                            <div class="portlet-header"><i class="icon-small-payment-address"></i>{l s='Payment Address' mod='supercheckout'}</div>
                                                                            <div class="portlet-content">
                                                                                <div class="text">{l s='Payment Address Content' mod='supercheckout'}</div>
                                                                                <div class="text"><i class="icon-drag"></i><i class="icon-drag"></i></div>
                                                                                <input   type="text"  class="sort col-data" name="velocity_supercheckout[design][payment_address][3_column][column]" value="{$velocity_supercheckout['design']['payment_address']['3_column']['column']|intval}" />
                                                                                <input   type="text"  class="sort row-data" name="velocity_supercheckout[design][payment_address][3_column][row]" value="{$velocity_supercheckout['design']['payment_address']['3_column']['row']|intval}" />
                                                                            </div>
                                                                        </li>
                                                                        <li class="portlet" col-data="{$velocity_supercheckout['design']['login']['3_column']['column']|intval}" row-data="{$velocity_supercheckout['design']['login']['3_column']['row']|intval}">
                                                                            <div class="portlet-header"><i class="icon-small-payment-address"></i>{l s='Login' mod='supercheckout'}</div>
                                                                            <div class="portlet-content">
                                                                                <div class="text">{l s='Login Content' mod='supercheckout'}</div>
                                                                                <div class="text"><i class="icon-drag"></i><i class="icon-drag"></i></div>
                                                                                <input   type="text"  class="sort col-data" name="velocity_supercheckout[design][login][3_column][column]" value="{$velocity_supercheckout['design']['login']['3_column']['column']|intval}" />
                                                                                <input   type="text"  class="sort row-data" name="velocity_supercheckout[design][login][3_column][row]" value="{$velocity_supercheckout['design']['login']['3_column']['row']|intval}" />
                                                                            </div>
                                                                        </li>
                                                                        <li class="portlet" col-data="{$velocity_supercheckout['design']['shipping_address']['3_column']['column']|intval}" row-data="{$velocity_supercheckout['design']['shipping_address']['3_column']['row']|intval}">
                                                                            <div class="portlet-header"><i class="icon-small-shipping-address"></i>{l s='Shipping Address' mod='supercheckout'}</div>
                                                                            <div class="portlet-content">
                                                                                <div class="text">{l s='Shipping Address Content' mod='supercheckout'}</div>
                                                                                <div class="text"><i class="icon-drag"></i><i class="icon-drag"></i></div>
                                                                                <input   type="text"  class="sort col-data" name="velocity_supercheckout[design][shipping_address][3_column][column]" value="{$velocity_supercheckout['design']['shipping_address']['3_column']['column']|intval}" />
                                                                                <input   type="text"  class="sort row-data" name="velocity_supercheckout[design][shipping_address][3_column][row]" value="{$velocity_supercheckout['design']['shipping_address']['3_column']['row']|intval}" />
                                                                            </div>
                                                                        </li>
                                                                        <li class="portlet" col-data="{$velocity_supercheckout['design']['shipping_method']['3_column']['column']|intval}" row-data="{$velocity_supercheckout['design']['shipping_method']['3_column']['row']|intval}">
                                                                            <div class="portlet-header"><i class="icon-small-shipping-method"></i>{l s='Shipping Method' mod='supercheckout'}</div>
                                                                            <div class="portlet-content">
                                                                                <div class="text">{l s='Shipping Method Content' mod='supercheckout'}</div>
                                                                                <div class="text"><i class="icon-drag"></i><i class="icon-drag"></i></div>
                                                                                <input   type="text"  class="sort col-data" name="velocity_supercheckout[design][shipping_method][3_column][column]" value="{$velocity_supercheckout['design']['shipping_method']['3_column']['column']|intval}" />
                                                                                <input   type="text"  class="sort row-data" name="velocity_supercheckout[design][shipping_method][3_column][row]" value="{$velocity_supercheckout['design']['shipping_method']['3_column']['row']|intval}" />
                                                                            </div>
                                                                        </li>
                                                                        <li class="portlet" col-data="{$velocity_supercheckout['design']['payment_method']['3_column']['column']|intval}" row-data="{$velocity_supercheckout['design']['payment_method']['3_column']['row']|intval}">
                                                                            <div class="portlet-header"><i class="icon-small-payment-method"></i>{l s='Payment Method' mod='supercheckout'}</div>
                                                                            <div class="portlet-content">
                                                                                <div class="text">{l s='Payment Method Content' mod='supercheckout'}</div>
                                                                                <div class="text"><i class="icon-drag"></i><i class="icon-drag"></i></div>
                                                                                <input   type="text"  class="sort col-data" name="velocity_supercheckout[design][payment_method][3_column][column]" value="{$velocity_supercheckout['design']['payment_method']['3_column']['column']|intval}" />
                                                                                <input   type="text"  class="sort row-data" name="velocity_supercheckout[design][payment_method][3_column][row]" value="{$velocity_supercheckout['design']['payment_method']['3_column']['row']|intval}" />
                                                                            </div>
                                                                        </li>
                                                                        <li class="portlet" col-data="{$velocity_supercheckout['design']['cart']['3_column']['column']|intval}" row-data="{$velocity_supercheckout['design']['cart']['3_column']['row']|intval}">
                                                                            <div class="portlet-header"><i class="icon-small-confirm"></i>{l s='Cart' mod='supercheckout'}</div>
                                                                            <div class="portlet-content">
                                                                                <div class="text">{l s='Cart Content' mod='supercheckout'}</div>
                                                                                <div class="text"><i class="icon-drag"></i><i class="icon-drag"></i></div>
                                                                                <input   type="text"  class="sort col-data" name="velocity_supercheckout[design][cart][3_column][column]" value="{$velocity_supercheckout['design']['cart']['3_column']['column']|intval}" />
                                                                                <input   type="text"  class="sort row-data" name="velocity_supercheckout[design][cart][3_column][row]" value="{$velocity_supercheckout['design']['cart']['3_column']['row']|intval}" />
                                                                            </div>
                                                                        </li>
                                                                        <li class="portlet" col-data="{$velocity_supercheckout['design']['confirm']['3_column']['column']|intval}" row-data="{$velocity_supercheckout['design']['confirm']['3_column']['row']|intval}">
                                                                            <div class="portlet-header"><i class="icon-small-confirm"></i>{l s='Confirm' mod='supercheckout'}</div>
                                                                            <div class="portlet-content">
                                                                                <div class="text">{l s='Confirm Content' mod='supercheckout'}</div>
                                                                                <div class="text"><i class="icon-drag"></i><i class="icon-drag"></i></div>
                                                                                <input   type="text"  class="sort col-data" name="velocity_supercheckout[design][confirm][3_column][column]" value="{$velocity_supercheckout['design']['confirm']['3_column']['column']|intval}" />
                                                                                <input   type="text"  class="sort row-data" name="velocity_supercheckout[design][confirm][3_column][row]" value="{$velocity_supercheckout['design']['confirm']['3_column']['row']|intval}" />
                                                                            </div>
                                                                        </li>


                                                                        {foreach from=$velocity_supercheckout['design']['html'] key='k' item='v'}
                                                                            <li class="portlet" id="portlet_{$k}" col-data="{$velocity_supercheckout['design']['html'][$k]['3_column']['column']|intval}" row-data="{$velocity_supercheckout['design']['html'][$k]['3_column']['row']|intval}">
                                                                                <div class="portlet-header">{l s='Extra html content' mod='supercheckout'}</div>
                                                                                <div class="portlet-content" id="portlet_content_{$k}">
                                                                                    <div class="text" style="overflow:visible !important;" >
                                                                                        <a data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Add new HTML content' mod='supercheckout'}" id="duplicate_button_{$k}" data="0" class="glyphicons more_windows"  onClick="duplicate_html(this);" ><i></i></a>

                                                                                        <a data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Edit this HTML content' mod='supercheckout'}" id="modals-bootbox-prompt-{$k}" data-toggle="modal" class="glyphicons edit bootbox-design-extra-html"  onClick="dialogExtraHtml(this);"><i></i></a>
                                                                                                {if $k neq "0_0"}
                                                                                            <a data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Remove this HTML content' mod='supercheckout'}" id="delete_button_{$k}" data="{$k}" data-toggle="modal" class="glyphicons remove"  onClick="remove_html(this);" ><i></i></a>
                                                                                                {/if}
                                                                                    </div>

                                                                                    <input id="col_text_{$k}"   type="text"  class="sort col-data" name="velocity_supercheckout[design][html][{$k}][3_column][column]" value="{$velocity_supercheckout['design']['html'][$k]['3_column']['column']|intval}" />
                                                                                    <input id="row_text_{$k}"  type="text"  class="sort row-data" name="velocity_supercheckout[design][html][{$k}][3_column][row]" value="{$velocity_supercheckout['design']['html'][$k]['3_column']['row']|intval}" />
                                                                                </div>
                                                                            </li>                                                    
                                                                        {/foreach}
                                                                    </ul>
                                                                    <ul id="column_2" class="column column-2 layout_list_height" col-data="2"></ul>
                                                                    <ul id="column_3" class="column column-3 layout_list_height" col-data="3"></ul>
                                                                    <div class="portlet">
                                                                        <div class="portlet-header">{l s='HTML Footer Content' mod='supercheckout'}</div>
                                                                        <div class="portlet-content">
                                                                            <div class="text" style="overflow:visible !important;" >

                                                                                <a data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Edit this HTML content' mod='supercheckout'}" id="modals_bootbox_prompt_html_footer" data-toggle="modal" class="glyphicons edit bootbox-design-edit-html" ><i></i></a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            {/if}

                                                            </tr>
                                                            </tbody>
                                                        </table>    
                                                    </div>
                                                </div>

                                                <!--------------- End - Design -------------------->
                                                <input type="hidden" id="modal_value" name="velocity_supercheckout[modal_value]" value="{$velocity_supercheckout['modal_value']}" />
                                                <div id="extra_html_container">
                                                    {foreach from=$velocity_supercheckout['design']['html'] key='k' item='v'}
                                                        <input type="hidden" id="modals_bootbox_prompt_{$k}" name="velocity_supercheckout[design][html][{$k}][value]" value="{html_entity_decode($velocity_supercheckout['design']['html'][{$k}]['value'])}" />
                                                    {/foreach}
                                                </div>
                                            </form>


                                            <!--------------- Start - Frequently Asked Questions -------------------->

                                            <div id="tab_faq" class="tab-pane outsideform">
                                                <div class="block">
                                                    <h4 class='velsof-tab-heading'>{l s='Frequently Asked Questions (Click to expand)' mod='supercheckout'}</h4>
                                                    <br>

                                                    <div class="row faq-row" id="1">
                                                        <div class="span faq-span" id="faq-span1">
                                                            <p style="margin-bottom: 0; margin-right: 5px">
                                                                <span class="question" style="font-weight: bold; font-size: 15px;">{l s='1. Need to change small icons next to //login options, delivery address, delivery method, payment method, confirm your order// heading in front end?' mod='supercheckout'}</span><br><br>
                                                                <span class="answer" id="answer1" style="color: black;">
                                                                    {l s='To change those icons, replace those imaes in following directory on your server.' mod='supercheckout'}<br>
                                                                    /modules/supercheckout/views/img/front/
                                                                </span>
                                                            </p>
                                                        </div>
                                                    </div>


                                                    <div class="row faq-row" id="2">
                                                        <div class="span faq-span" id="faq-span2">
                                                            <p style="margin-bottom: 0; margin-right: 5px">
                                                                <span class="question" style="font-weight: bold; font-size: 15px;">{l s='2. Radio buttons for both Mr and Mrs. is always checked?' mod='supercheckout'}</span><br><br>
                                                            <div class="answer" id="answer2" style="color:black;">{l s=' If both Mr and Mrs. radio buttons are always checked, then add below code in custom css field of our module customizer tab to fix the issue.' mod='supercheckout'}
                                                                <br> <br><pre>
#customer_person_information_table div.radio input {
opacity: 99999;
position: relative !important;
margin: 0px !important;
}
                                                                </pre></div>
                                                            </p>
                                                        </div>
                                                    </div>



                                                    <div class="row faq-row" id="3">
                                                        <div class="span faq-span" id="faq-span3">
                                                            <p style="margin-bottom: 0; margin-right: 5px">
                                                                <span class="question" style="font-weight: bold; font-size: 15px;">{l s='3. Third column is not correctly aligned or full width issue in Desktop?' mod='supercheckout'}</span><br><br>
                                                            <div class="answer" id="answer3" style="color: black;">
                                                                {l s='Most probably your theme template CSS is conflicting with our module. Fix for this issue is very simple. Kindly add following code in Custom CSS field in Customizer tab of our module admin setting.' mod='supercheckout'}<br>
                                                                <br><pre>
#columnleft-3{
width:28% !important;  
}
<br>
OR
<br>
#center_column{
width:100% !important;  
}									
                                                                </pre><br>
                                                                {l s='In case your issue is not solved, try changing this percentage to suit your theme otherwise contact us with admin and FTP login details.' mod='supercheckout'}
                                                            </div>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="row faq-row" id="4">
                                                        <div class="span faq-span" id="faq-span4">
                                                            <p style="margin-bottom: 0; margin-right: 5px">
                                                                <span class="question" style="font-weight: bold; font-size: 15px;">{l s='4. Want to add an extra field in address form?' mod='supercheckout'}</span><br><br>
                                                                <span class="answer" id="answer4" style="color: black;">
                                                                    {l s='By default it is not possible to add custom field in our module, if you wish we can make this custom change for you for additional cost. Kindly contact us with your complete requirements.' mod='supercheckout'}
                                                                </span>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="row faq-row" id="5">
                                                        <div class="span faq-span" id="faq-span5">
                                                            <p style="margin-bottom: 0; margin-right: 5px">
                                                                <span class="question" style="font-weight: bold; font-size: 15px;">{l s='5.  Some third party module is not working?' mod='supercheckout'}</span><br><br>
                                                                <span class="answer" id="answer5" style="color: black;">
                                                                    {l s='Third party modules are only made for default checkout of Prestashop. They may or may not work with our module. In case they are not working with our module, some custom changes need to be made to make them compatible with our module.' mod='supercheckout'}
                                                                </span>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="row faq-row" id="6">
                                                        <div class="span faq-span" id="faq-span6">
                                                            <p style="margin-bottom: 0; margin-right: 5px">
                                                                <span class="question" style="font-weight: bold; font-size: 15px;">{l s='6. Want us to implement some specific feature for additional cost?' mod='supercheckout'}</span><br><br>
                                                                <span class="answer" id="answer6" style="color: black;">
                                                                    {l s='Yes, you can contact us with complete requirements. If changes are feasible, we can implement them for additional cost.' mod='supercheckout'}
                                                                </span>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="row faq-row" id="7">
                                                        <div class="span faq-span" id="faq-span7">
                                                            <p style="margin-bottom: 0; margin-right: 5px">
                                                                <span class="question" style="font-size: 15px;font-weight: bold; font-size: 15px;">{l s='7. Facing any of these issues ?' mod='supercheckout'}
                                                                </span>
                                                            <div class="answer" id="answer7" style="color:black;">
                                                                <br><pre>TECHNICAL ERROR: Request Failed Details:Error thrown: [object Object]Text status: error</pre>
                                                                <pre>500 Internal Server error</pre>
                                                                <pre>Progress Bar stuck on 80% after click on Place order</pre>
                                                                {l s='Reason for these errors are not specific. If you are facing any of these issues, kindly contact us with your admin and FTP details.' mod='supercheckout'}
                                                            </div>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="row faq-row" id="8">
                                                        <div class="span faq-span" id="faq-span8">
                                                            <p style="margin-bottom: 0; margin-right: 5px">
                                                                <span class="question" style="font-weight: bold; font-size: 15px;">{l s='8. Payment method is not displaying additinal cost?' mod='supercheckout'}</span><br><br>
                                                                <span class="answer" id="answer8" style="color: black;">
                                                                    {l s='It is very rare issue and in case you face it, kindly contact us with your admin and FTP login details so that we can fix this issue for you.' mod='supercheckout'}
                                                                </span>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="row faq-row" id="9">
                                                        <div class="span faq-span" id="faq-span9">
                                                            <p style="margin-bottom: 0; margin-right: 5px">
                                                                <span class="question" style="font-weight: bold; font-size: 15px;">{l s='9. Translated text is not reflecting in front-side?' mod='supercheckout'}</span><br><br>
                                                                <span class="answer" id="answer9" style="color: black;">
                                                                    {l s='Kindly try again after clearing your Prestashop cache using Advance Parameter->Performance->Clear cache button. If your issue persists even after that, make sure that your theme directory don\'t contain our module translation file. To check this, go to your theme directory' mod='supercheckout'} 
                                                                    /your_theme_name/modules/ .{l s=' Inside this modules directory, there should no Supercheckout directory, in case it exist just rename it to anything else.' mod='supercheckout'}<br><br>
                                                                    {l s='When you translate text from our module admin panel, our module save translated text in ' mod='supercheckout'} /modules/supercheckout/translations/ directory.
                                                                    {l s='But when there is some translation exist in your theme directory, our module picks text from there and your translated text don\'t reflect in front side.' mod='supercheckout'}<br>
                                                                    <br>
                                                                    {l s='Now question arise, in which case theme directory can have our module translated text file.' mod='supercheckout'}<br>
                                                                    {l s='When you use default translation feature of Prestashop, then it save translated text in theme directory and our module use text from this themes directory rather than using text from module directory.' mod='supercheckout'}
                                                                </span>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="row faq-row" id="10">
                                                        <div class="span faq-span" id="faq-span10">
                                                            <p style="margin-bottom: 0; margin-right: 5px">
                                                                <span class="question" style="font-size: 15px;font-weight: bold; font-size: 15px;">{l s='10. Payment method image is not coming?' mod='supercheckout'}<br><br></span><span class="answer" id="answer10" style="color: black;">
                                                                    {l s='Our module shows payment methods images from their root directory (modules/payment_method_name). If some payment method don\'t have any image in their root directory then no image will be shown.' mod='supercheckout'}<br><br>
                                                                    {l s='To display that payment method image, kindly upload image to the payment module directory. Image name should be same as payment method directory name. You can use any image format eg. jpg, png etc.' mod='supercheckout'} 
                                                                    <br><br>{l s='For example: To display Iupay payment module image, you need to add its image in' mod='supercheckout'} "/modules/iupay/" {l s='directory and image name must be iupay. Image extension can be anything and recommended image resolution is 95x20.' mod='supercheckout'}<br><br> {l s='Don\'t hesistate to contact us if you need any assistance from our side.' mod='supercheckout'}
                                                                </span>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="row faq-row" id="11">
                                                        <div class="span faq-span" id="faq-span11">
                                                            <p style="margin-bottom: 0; margin-right: 5px">
                                                                <span class="question" style="font-size: 15px;font-weight: bold; font-size: 15px;">{l s='11. In front end when you click login or place order button nothing happens?' mod='supercheckout'}<br><br></span><span class="answer" id="answer11" style="color: black;">
                                                                    {l s='To fix this issue, Under Advance parameters->performance->Turn ON //Move Javascript to the end//. Don\'t forget to clear Prestashop cache using Advance Parameter->Performance->Clear cache button.' mod='supercheckout'}<br>
                                                                    <br>{l s='In case your issue persists, contact us with your admin and FTP details.' mod='supercheckout'}
                                                                </span>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="row faq-row" id="12">
                                                        <div class="span faq-span" id="faq-span12">
                                                            <p style="margin-bottom: 0; margin-right: 5px">
                                                            <div class="question" style="font-size: 15px;font-weight: bold; font-size: 15px;">{l s='12. How to translate custom HTML header/footer content?' mod='supercheckout'}<br><br></div><div class="answer" id="answer12" style="color: black;">
                                                                {l s='In order to translate custom HTML header/footer you have to add HTML content (in Custom HTML header or footer field in design tab) for all the languages as follows:' mod='supercheckout'}<br><br>
                                                                <pre>
&lt;div id="LANGISO1_content" style="display: none;"&gt;{l s='Your HTML content for the language' mod='supercheckout'}&lt;/div&gt;

&lt;div id="LANGISO2_content" style="display: none;"&gt;{l s='Your HTML content for the language' mod='supercheckout'}&lt;/div&gt;
	.
	.
	.
&lt;div id="LANGISOn_content" style="display: none;"&gt;{l s='Your HTML content for the language' mod='supercheckout'}&lt;/div&gt;
                                                                </pre>
                                                            </div>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="row faq-row" id="13">
                                                        <div class="span faq-span" id="faq-span13">
                                                            <p style="margin-bottom: 0; margin-right: 5px">
                                                            <div class="question" style="font-size: 15px;font-weight: bold; font-size: 15px;">{l s='13. Payment method not working and is stuck at 80% after click on place order button.' mod='supercheckout'}<br><br></div><div class="answer" id="answer13" style="color: black;">
                                                                {l s='This happens when your payment method has some unique code and need to be compatible with our module, kindly contact us to get it fixed for free.' mod='supercheckout'}<br>
                                                            </div>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="row faq-row" id="14">
                                                        <div class="span faq-span" id="faq-span14">
                                                            <p style="margin-bottom: 0; margin-right: 5px">
                                                            <div class="question" style="font-size: 15px;font-weight: bold; font-size: 15px;">{l s='14.  How to get SEO url like' mod='supercheckout'} http://www.mysite.com/checkout? <br><br></div><div class="answer" id="answer14" style="color: black;">
                                                                {l s='After installing our module, you need to go to Preferences -> SEO & URLs and create a rule over there to get desired SEO URL.' mod='supercheckout'}<br>
                                                            </div>
                                                            </p>
                                                        </div>
                                                    </div>
                                                            
                                                    <div class="row faq-row" id="15">
                                                        <div class="span faq-span" id="faq-span15">
                                                            <p style="margin-bottom: 0; margin-right: 5px">
                                                            <div class="question" style="font-size: 15px;font-weight: bold; font-size: 15px;">{l s='15.  How to Allow Guest Checkout only?' mod='supercheckout'} <br><br></div><div class="answer" id="answer15" style="color: black;">
                                                                {l s='First of all change the default checkout option to Guest Checkout (`Default Option at Checkout` configuration in `General Settings` tab.' mod='supercheckout'}<br>
                                                                {l s='After that add the following CSS code to the `Custom CSS` field in `Customizer` tab.' mod='supercheckout'}<br>
                                                                <br><pre>
#supercheckout-option > div:nth-child(3) { display: none; }
#supercheckout-option > div:nth-child(1) { display: none; }
                                                                </pre>
                                                                {l s='In case your issue is not solved, contact us with admin and FTP login details.' mod='supercheckout'}
                                                            </div>
                                                            </p>
                                                        </div>
                                                    </div>
                                                            
                                                    <div class="row faq-row" id="16">
                                                        <div class="span faq-span" id="faq-span16">
                                                            <p style="margin-bottom: 0; margin-right: 5px">
                                                            <div class="question" style="font-size: 15px;font-weight: bold; font-size: 15px;">{l s='16. DNI/Identification Number number field is not visible in the front, what has been missed?' mod='supercheckout'} <br><br></div><div class="answer" id="answer16" style="color: black;">
                                                                {l s='This happens when the DNI/Identification Number field is not enabled for the default country on the store. You can enable the same at the following path in your back office' mod='supercheckout'}:<br><br>
                                                                <pre>
{l s='International->Locations->Countries->Edit country->Do you need a tax identification number?' mod='supercheckout'}
                                                                </pre>
                                                                {l s='In case your issue is not solved, contact us with admin and FTP login details.' mod='supercheckout'}<br><br>
                                                                <b>{l s='Note' mod='supercheckout'}:</b> {l s='The field will only be required if it is required in the default checkout.' mod='supercheckout'}
                                                            </div>
                                                            </p>
                                                        </div>
                                                    </div>
                                                                
                                                    <div class="row faq-row" id="17">
                                                        <div class="span faq-span" id="faq-span17">
                                                            <p style="margin-bottom: 0; margin-right: 5px">
                                                            <div class="question" style="font-size: 15px;font-weight: bold; font-size: 15px;">{l s='17. VAT Number field is not appearing in the front end?' mod='supercheckout'} <br><br></div><div class="answer" id="answer17" style="color: black;">
                                                                {l s='The VAT Number field is dependent on a module (named `European VAT number`), just install the module and enable `Enable checking of the VAT number with the web service` configuration of the module.' mod='supercheckout'}<br>
                                                                {l s='In case your issue is not solved, contact us with admin and FTP login details.' mod='supercheckout'}<br><br>
                                                                <b>{l s='Note' mod='supercheckout'}:</b> {l s='The field will only be required if it is required in the default checkout.' mod='supercheckout'}
                                                            </div>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>    
                                            </div>
                                            <!--------------- End - Frequently Asked Questions -------------------->
                                        </div>
                                    </div>
                   
                                </div>
                            </div>                    
                        </div>
                    </div> 
                </div>

                <!-- Start - Variables which will not submit and save -->
                <input type="hidden" id="modals_bootbox_prompt_header_html" value="{l s='Edit Your HTML Content Here' mod='supercheckout'}" />
                <!-- Start - Variables which will not submit and save -->
            </div>
                
        </div>

        <!-- Themer -->
        <div id="themer" class="collapse">
            <div class="wrapper">
                <span class="close2">&times; {l s='close' mod='supercheckout'}</span>
                <h4>{l s='Themer' mod='supercheckout'}</h4>
                <ul>
                    <li>{l s='Theme' mod='supercheckout'}: <select id="themer-theme" class="pull-right"></select><div class="clearfix"></div></li>            
                </ul>
                <div id="themer-getcode" class="hide">
                    <hr class="separator" />
                    <button class="btn btn-primary btn-small pull-right btn-icon glyphicons download" id="themer-getcode-less"><i></i>{l s='Get LESS' mod='supercheckout'}</button>
                    <button class="btn btn-inverse btn-small pull-right btn-icon glyphicons download" id="themer-getcode-css"><i></i>{l s='Get CSS' mod='supercheckout'}</button>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
                 
        <!-- Start - Code Modified by Raghu on 22-Aug-2017 for inluding texts to language file. -->
        <div style="display: none;">
            <label>{l s='Title' mod='supercheckout'}</label>
            <label>{l s='DOB' mod='supercheckout'}</label>
            <label>{l s='Sign up for NewsLetter' mod='supercheckout'}</label>
            <label>{l s='Special Offer' mod='supercheckout'}</label>
            <label>{l s='First Name' mod='supercheckout'}</label>
            <label>{l s='Last Name' mod='supercheckout'}</label>
            <label>{l s='Company' mod='supercheckout'}</label>
            <label>{l s='Vat Number' mod='supercheckout'}</label>
            <label>{l s='Address Line 1' mod='supercheckout'}</label>
            <label>{l s='Address Line 2' mod='supercheckout'}</label>
            <label>{l s='Zip/Postal Code' mod='supercheckout'}</label>
            <label>{l s='City' mod='supercheckout'}</label>
            <label>{l s='Country' mod='supercheckout'}</label>
            <label>{l s='State' mod='supercheckout'}</label>
            <label>{l s='Identification Number' mod='supercheckout'}</label>
            <label>{l s='Home Phone' mod='supercheckout'}</label>
            <label>{l s='Mobile Phone' mod='supercheckout'}</label>
            <label>{l s='Address Title' mod='supercheckout'}</label>
            <label>{l s='Other Information' mod='supercheckout'}</label>
            <label>{l s='Image' mod='supercheckout'}</label>
            <label>{l s='Description' mod='supercheckout'}</label>
            <label>{l s='Model' mod='supercheckout'}</label>
            <label>{l s='Quantity' mod='supercheckout'}</label>
            <label>{l s='Price' mod='supercheckout'}</label>
            <label>{l s='Total' mod='supercheckout'}</label>
        </div>
        <!-- End - Code Modified by Raghu on 22-Aug-2017 for inluding texts to language file. -->

        {assign "column_1" ""}
        {assign "column_2" ""}
        {assign "column_3" ""}
        {assign "column_4" ""}
        {assign "column_5" ""}
        {assign "column_5_updated" ""}
        {assign "main_width" 1}
        {if $layout eq 2}
            {$column_1 = $velocity_supercheckout['column_width']['2_column'][1]/$main_width}
            {$column_2 = $velocity_supercheckout['column_width']['2_column'][2]/$main_width}
            {$column_4 = $velocity_supercheckout['column_width']['2_column']['inside'][1]/$main_width}
            {$column_5 = $velocity_supercheckout['column_width']['2_column']['inside'][2]/$main_width}
            {assign var='column_5_updated' value=$column_5 - 1}
        {else if $layout eq 3}
            {$column_1 = $velocity_supercheckout['column_width']['3_column'][1]/$main_width}
            {$column_2 = $velocity_supercheckout['column_width']['3_column'][2]/$main_width}
            {$column_3 = $velocity_supercheckout['column_width']['3_column'][3]/$main_width}
        {/if}

        <style type="text/css">
            {literal}
                .ship2pay-glyphicons i:before{    
                    font-size: 17px;
                    padding: 3px;
                }
                .ship2pay-div{
                    cursor:pointer;
                    padding: 5px;
                    margin: 10px;
                    text-align: center;
                    font-size: 13px;
                    color: white;
                    width: 60%;
                    margin-left: 20%;
                }
                .tickcross-sign{
                    padding-right: 10px;
                    font-weight: bold;
                    font-size: 14px;
                }
                .faq-span{max-height:10px;}
                .faq-row{background: rgba(230, 230, 236, 0.37);
                         border-radius:3px;
                         margin-top:10px;
                         padding: 30px;
                         cursor: pointer;
                         padding-left: 10px;
                         padding-top: 15px;}
                .question{font-family:initial;color:rgb(213, 81, 81) !important;font-size:17px !important;}
                .answer{display:none;font-family:initial;font-size:15px;line-height:20px;letter-spacing:1px;}
                tr.even { background-color: #EDEDED; }
                tr.odd { background-color: white;}
                .column-1, .column-data-1{width:{/literal}{$column_1}{literal}% ;}
                .column-2, .column-data-2{width:{/literal}{$column_2}{literal}%;} 
                .column-3, .column-data-3{width:{/literal}{$column_3}{literal}%;}
                #column-1-inside,#column-1-inside-text {width:{/literal}{$column_4}{literal}%;}
                #column-2-inside,#column-2-inside-text {width:{/literal}{$column_5_updated}{literal}%;}
            {/literal}
            .lang-title td
            {
                padding: 2px 0px;
            }
        </style>

        <script type="text/javascript">
            var ps_ver = '{$ps_version nofilter}{*escape not required as contains html*}';
            {if $layout eq 1}
                {literal}
                    $(".column").sortable({
                        connectWith: ".column",
                        scroll: false,
                        stop: function(event, ui) {
                            $('.column').find("li").each(function(i, el) {

                                $(this).find(".row-data").val($(el).index())

                            });
                        }
                    });

                    $(".column").disableSelection();
                    $('#column-1 > li').tsort({attr: 'row-data'});
                    $('#column-1 > li').each(function() {
                        $(this).appendTo('#column-1');

                    })
                {/literal}
            {else if $layout eq 2}
                {literal}
                    var main_width = 100 / 100;

                    $("#slider").slider({
                        range: false,
                        min: 0,
                        max: 100,
                        design: 1.00,
                        values: [{/literal}{$column_1}{literal}],
                        slide: function(event, ui) {

                            $('#column-1-text').val(Math.round(main_width * (ui.values[ 0 ])))
                                    .attr('width-data', ui.values[ 0 ])
                                    .attr('left-data', 0)
                                    .css({'width': parseInt(ui.values[ 0 ]) + '%'})
                            $('#column-2-text').val(Math.round(main_width * (100 - ui.values[ 0 ])))
                                    .attr('width-data', 100 - ui.values[ 0 ] - 1)
                                    .attr('left-data', parseInt(ui.values[ 0 ]))
                                    .css({'width': parseInt(100 - ui.values[ 0 ] - 1) + '%'})
                            $('#column-1').css({'width': parseInt(ui.values[ 0 ]) + '%'})
                            $('#column-2').css({'width': parseInt(100 - ui.values[ 0 ] - 1) + '%'})
                        }
                    });
                    var main_width_inside = 100 / 100;

                    $("#slider_inside").slider({
                        range: false,
                        min: 0,
                        max: 100,
                        design: 1.00,
                        values: [{/literal}{$column_4}{literal}],
                        slide: function(event, ui) {

                            $('#column-1-inside-text').val(Math.round(main_width_inside * (ui.values[ 0 ])))
                                    .attr('width-data', ui.values[ 0 ])
                                    .attr('left-data', 0)
                                    .css({'width': parseInt(ui.values[ 0 ]) + '%'})
                            $('#column-2-inside-text').val(Math.round(main_width_inside * (100 - ui.values[ 0 ])))
                                    .attr('width-data', 100 - ui.values[ 0 ] - 1)
                                    .attr('left-data', parseInt(ui.values[ 0 ]))
                                    .css({'width': parseInt(100 - ui.values[ 0 ] - 1) + '%'})
                            $('#column-1-inside').css({'width': parseInt(ui.values[ 0 ]) + '%'})
                            $('#column-2-inside').css({'width': parseInt(100 - ui.values[ 0 ] - 1) + '%'})
                        }
                    });
                    $(".column").sortable({
                        connectWith: ".column",
                        scroll: false,
                        stop: function(event, ui) {
                            $('.column').find("li").each(function(i, el) {

                                $(this).find(".row-data").val($(el).index())
                                $(this).find(".col-data").val($(this).parent().attr('col-data'))
                                $(this).find(".col-data-inside").val($(this).parent().attr('col-inside-data'))

                            });
                        }
                    });

                    $(".column").disableSelection();
                    $('.column > li').tsort({attr: 'col-inside-data'});
                    $('.column > li').each(function() {
                        if ($(this).attr('col-inside-data') == "4") {
                            $(this).appendTo('#column-2-lower');
                        }
                        else if ($(this).attr('col-inside-data') == "3") {
                            $(this).appendTo('#column-1-inside');
                        } else if ($(this).attr('col-inside-data') == "2") {
                            $(this).appendTo('#column-2-upper');
                        } else {
                            $(this).appendTo('#column-1');
                        }

                    })
                    $('#column-1 > li').tsort({attr: 'row-data'});
                    $('#column-1 > li').each(function() {
                        $(this).appendTo('#column-' + $(this).attr('col-data'));

                    })
                    $('#column-2-upper > li').tsort({attr: 'row-data'});
                    $('#column-2-upper > li').each(function() {
                        $(this).appendTo('#column-2-upper');

                    })
                    $('#column-2-lower > li').tsort({attr: 'row-data'});
                    $('#column-2-lower > li').each(function() {
                        $(this).appendTo('#column-2-lower');

                    })
                    $('#column-1-inside > li').tsort({attr: 'row-data'});
                    $('#column-1-inside > li').each(function() {
                        $(this).appendTo('#column-' + $(this).attr('col-data') + '-inside');

                    })
                {/literal}
            {else if $layout eq 3}
                {literal}
                    var main_width = 100 / 100;

                    $("#slider").slider({
                        range: true,
                        min: 0,
                        max: 100,
                        step: 1.00,
                        values: [{/literal}{$column_1}{literal}, {/literal}{($column_1+$column_2)}{literal}],
                        slide: function(event, ui) {

                            $('#three-column-1').val(Math.round(main_width * (ui.values[ 0 ])))
                                    .attr('width-data', ui.values[ 0 ])
                                    .attr('left-data', 0)
                                    .css({'width': parseInt(ui.values[ 0 ]) + '%'})
                            $('#three-column-2').val(Math.round(main_width * (ui.values[ 1 ] - ui.values[ 0 ])))
                                    .attr('width-data', ui.values[ 1 ] - ui.values[ 0 ])
                                    .attr('left-data', parseInt(ui.values[ 0 ] + 10))
                                    .css({'width': parseInt(ui.values[ 1 ] - ui.values[ 0 ]) + '%'})
                            $('#three-column-3').val(Math.round(main_width * (100 - ui.values[ 1 ])))
                                    .attr('width-data', 100 - ui.values[ 1 ] - 1)
                                    .attr('left-data', parseInt(ui.values[ 1 ]))
                                    .css({'width': parseInt(100 - ui.values[ 1 ] - 1) + '%'})
                            $('.column-1').css({'width': parseInt(ui.values[ 0 ]) + '%'})
                            $('.column-2').css({'width': parseInt(ui.values[ 1 ] - ui.values[ 0 ]) + '%'})
                            $('.column-3').css({'width': parseInt(100 - ui.values[ 1 ]) + '%'})


                        }
                    });
                    $(".column").sortable({
                        connectWith: ".column",
                        scroll: false,
                        stop: function(event, ui) {
                            $('.column').find("li").each(function(i, el) {

                                $(this).find(".row-data").val($(el).index())
                                $(this).find(".col-data").val($(this).parent().attr('col-data'))

                            });
                        }
                    });

                    $(".column").disableSelection();
                    $('.column > li').tsort({attr: 'row-data'});
                    $('.column > li').each(function() {
                        $(this).appendTo('.column-' + $(this).attr('col-data'));
                    })
                {/literal}
            {/if}

            {literal}

                function duplicate_html(e) {
                    var portlet_id = $(e).parent().parent().attr('id');
                    var col_data = $('#' + portlet_id + ' .col-data').val();
                    var row_data = $('#' + portlet_id + ' .row-data').val();
                    if ("{/literal}{$layout}{literal}" == 2) {
                        var col_data_inside = $('#' + portlet_id + ' .col-data-inside').val();
                    } else {
                        var col_data_inside = 4;
                    }
                    var data = parseInt($('#modal_value').val());
                    data++;
                    $('#modal_value').val(data);
                    string = '<li id="portlet_' + data + '_' + data + '" class="portlet" col-data="" row-data="" col-inside-data="">';
                    string += '<div class="portlet-header">{/literal}{l s='Html Content' mod='supercheckout'}{literal}</div>';
                    string += '<div id="portlet_content_' + data + '_' + data + '" class="portlet-content">';
                    string += '<div class="text" style="overflow:visible !important;" >';
                    string += '<a data-toggle="tooltip"  data-placement="top" data-original-title="{/literal}{l s='Add new HTML content' mod='supercheckout'}{literal}" id="duplicate_button_' + data + '_' + data + '" data="' + (data) + '" class="glyphicons more_windows" onClick="duplicate_html(this);" ><i></i></a>';
                    string += '<a data-toggle="tooltip"  data-placement="top" data-original-title="{/literal}{l s='Edit this HTML content' mod='supercheckout'}{literal}" id="modals-bootbox-prompt-' + data + '_' + data + '" data-toggle="modal" class="glyphicons edit bootbox-design-extra-html" onClick="dialogExtraHtml(this);"><i></i></a>';
                    string += '<a data-toggle="tooltip"  data-placement="top" data-original-title="{/literal}{l s='Remove this HTML content' mod='supercheckout'}{literal}" id="delete_button_' + data + '_' + data + '" data="' + data + '_' + data + '" data-toggle="modal" class="glyphicons remove"  onClick="remove_html(this);" ><i></i></a>';
                    string += '</div>';

                    string += '<input   type="hidden"  class="sort col-data" name="velocity_supercheckout[design][html][' + data + '_' + data + '][2_column][column]" value="' + col_data + '" />';
                    string += '<input   type="hidden"  class="sort row-data" name="velocity_supercheckout[design][html][' + data + '_' + data + '][2_column][row]" value="' + row_data + '" />';
                    string += '<input   type="hidden"  class="sort col-data-inside" name="velocity_supercheckout[design][html][' + data + '_' + data + '][2_column][column-inside]" value="' + col_data_inside + '" />';


                    string += '<input   type="hidden"  class="sort col-data" name="velocity_supercheckout[design][html][' + data + '_' + data + '][1_column][column]" value="' + col_data + '" />';
                    string += '<input   type="hidden"  class="sort row-data" name="velocity_supercheckout[design][html][' + data + '_' + data + '][1_column][row]" value="' + row_data + '" />';
                    string += '<input   type="hidden"  class="sort col-data-inside" name="velocity_supercheckout[design][html][' + data + '_' + data + '][1_column][column-inside]" value="' + col_data_inside + '" />';


                    string += '<input   type="hidden"  class="sort col-data" name="velocity_supercheckout[design][html][' + data + '_' + data + '][3_column][column]" value="' + col_data + '" />';
                    string += '<input   type="hidden"  class="sort row-data" name="velocity_supercheckout[design][html][' + data + '_' + data + '][3_column][row]" value="' + row_data + '" />';
                    string += '<input   type="hidden"  class="sort col-data-inside" name="velocity_supercheckout[design][html][' + data + '_' + data + '][3_column][column-inside]" value="' + col_data_inside + '" />';

                    if ({/literal}{$layout}{literal} == 3) {
                        string += '<input id="col_text_' + data + '_' + data + '"  type="text"  class="sort col-data" name="velocity_supercheckout[design][html][' + data + '_' + data + '][3_column][column]" value="' + col_data + '" />';
                        string += '<input id="row_text_' + data + '_' + data + '"  type="text"  class="sort row-data" name="velocity_supercheckout[design][html][' + data + '_' + data + '][3_column][row]" value="' + row_data + '" />';
                    }
                    if ({/literal}{$layout}{literal} == 2) {
                        string += '<input id="col_text_' + data + '_' + data + '"  type="text"  class="sort col-data" name="velocity_supercheckout[design][html][' + data + '_' + data + '][2_column][column]" value="' + col_data + '" />';
                        string += '<input id="row_text_' + data + '_' + data + '"  type="text"  class="sort row-data" name="velocity_supercheckout[design][html][' + data + '_' + data + '][2_column][row]" value="' + row_data + '" />';
                        string += '<input id="col_inside_text_' + data + '_' + data + '"  type="text"  class="sort col-data-inside" name="velocity_supercheckout[design][html][' + data + '_' + data + '][2_column][row]" value="' + col_data_inside + '" />';
                    }
                    if ({/literal}{$layout}{literal} == 1) {
                        string += '<input id="row_text_' + data + '_' + data + '"  type="text"  class="sort row-data" name="velocity_supercheckout[design][html][' + data + '_' + data + '][2_column][row]" value="' + row_data + '" />';
                    }
                    string += '</div>';
                    string += '</li>';

                    $(e).parent().parent().parent().parent().append(string);

                    $('#extra_html_container').append('<input type="hidden" id="modals_bootbox_prompt_' + data + '_' + data + '" name="velocity_supercheckout[design][html][' + data + '_' + data + '][value]" value="" />')

                }

                if ($.cookie('designTab') == 1) {
                    $('#velsof_supercheckout_container').find('li').removeClass('active');
                    $("#velsof_tab_design").trigger('click');
                    $.cookie('designTab', 0);
                }

                $(document).ready(function() {
                    var clipboard = new Clipboard('.testurlbutton');
                    $('.ship2pay-div').click(function() {
                        var element_id = this.id;

                        if ($('.ship2pay-div input[name=\'' + element_id + '\']').is(":checked"))
                        {
                            $(this).css('background-color', 'rgb(83, 199, 83)'); //green
                            $(this).css('border', '1px solid #257925'); //dark green color border
                            $('.ship2pay-div input[name=\'' + element_id + '\']').prop('checked', false);
                            $(this).children('.tickcross-sign').html('&#10004;');

                        }
                        else
                        {
                            $('.ship2pay-div input[name=\'' + element_id + '\']').prop('checked', true);
                            $(this).css('background-color', 'rgb(224, 69, 69)'); //red
                            $(this).css('border', '1px solid #B13131'); //dark red color border
                            $(this).children('.tickcross-sign').html('&#10060;');
                        }



                    });
                    //added below two lines to show answer of first FAQ
                    $('#faq-span1').css('max-height', 'none');
                    $('#answer1').css('display', 'block')

                    // Carousal in FAQ
                    $('.faq-row').off('click').on('click', function() {
                        var element_id = this.id;
                        var i = 1;
                        for (i = 1; i < 20; i++)
                        {
                            if (i != element_id) {
                                //to hide answer of previously opened FAQ question
                                $('#faq-span' + i).css('max-height', '10px');
                                $('#answer' + i).css('display', 'none');
                            }
                        }
                        //added below to lines to show answer of question, when admin click on it
                        $('#faq-span' + element_id).css('max-height', 'none');
                        $('#answer' + element_id).css('display', 'block');

                    });

                    $('#tab_lang_translator').css('width', $('#tab_general_settings').width() + 'px');
                    if ($('input#supercheckout_test_mode').is(':checked')) {
                        $('#front_module_url').show();
                    }
                    $('#supercheckout_test_mode').change(function() {
                        if ($(this).is(":checked")) {
                            $('#front_module_url').show();
                        }
                        else
                            $('#front_module_url').hide();
                    });
                    if ($('input#supercheckout_google_auto_address').is(':checked')) {
                            $('#google_api_key').show();
                        }
                    $('#supercheckout_google_auto_address').change(function() {
                        if ($(this).is(":checked")) {
                            $('#google_api_key').show();
                        }
                        else
                            $('#google_api_key').hide();
                    });
                });

            {/literal}        
        </script>
