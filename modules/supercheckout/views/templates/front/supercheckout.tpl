{extends file='checkout/checkout.tpl'}
{block name='content'}
    {capture name=path}
        {l s='SuperCheckout' mod='supercheckout'}
    {/capture}
    {if !isset($empty)}

        <script>
            {if (isset($mobileLoginActive) && ($mobileLoginActive eq 1))}
            var kbmobile_front_url = '{$kbmobile_front_url nofilter}';{*escape not required as contains url*}
            var login_by_mobile = "{$mobile_login_setting['login_by_mobile']}";
            var login_by_otp = "{$mobile_login_setting['login_by_otp']}";
            var kb_input_error_msg = "{l s='Kindly input valid values in all required fields' mod='supercheckout'}";
            {/if}
            var cartRefreshURL = '{$cartRefreshURL}';
            var supercheckoutLayout = {$settings['layout']|intval};
            var hideShippingMethod = {$settings['hide_ship_pay']};
            var supercheckout_image_path = "{$module_image_path nofilter}"; {*escape not required as contains url*}
            var empty_cart_warning = "{l s='Your cart is empty' mod='supercheckout'}";
            var google_auto_fill_warning = "{l s='There is some issue with Google Auto Address feature. Please contact support.' mod='supercheckout'}";
            var notification_title = "{l s='Notification' mod='supercheckout'}";
            var warning = "{$warning}";
            var product_remove_success = "{$product_remove_success}";
            var product_qty_update_success = "{$product_qty_update_success}";
            var freeShippingTranslation = "{l s='Free Shipping' mod='supercheckout'}";
            var noShippingRequired = "{l s='No Delivery Method Required' mod='supercheckout'}";
            var ShippingRequired = "{l s='Delivery Method Required' mod='supercheckout'}";
            var paymentRequired = "{l s='Payment Method Required' mod='supercheckout'}";
            var updateSameQty = "{l s='No change found in quantity' mod='supercheckout'}";
            var scInvalidQty = "{l s='Invalid Quantity' mod='supercheckout'}";
            var scOtherError = "{l s='Technical Error Occured. Please contact to support.' mod='supercheckout'}";
            var commentInvalid = "{l s='Message is in invalid format' mod='supercheckout'}";
            var tosRequire = "{l s='Please acccept our terms & conditions before confirming your order' mod='supercheckout'}";
            var requestToLogin = "{l s='Please login first' mod='supercheckout'}";
            var ajaxRequestFailedMsg = "{l s='TECHNICAL ERROR- Request Failed' mod='supercheckout'}";
            var validationfailedMsg = "{l s='Please provide required Information' mod='supercheckout'}";
            var totalVoucherText = "{l s='Total Vouchers' mod='supercheckout'}";
            var tax_incl_text = "{l s='(Tax incl.)' mod='supercheckout'}";
            var tax_excl_text = "{l s='(Tax excl.)' mod='supercheckout'}";
            var update_text = "{l s='Update' mod='supercheckout'}";
            var kb_input_error_msg = "{l s='Kindly input valid values in all required fields' mod='supercheckout'}";
            var idAddress_delivery = {$id_address_delivery|intval};
            var scp_use_taxes = 1;
            var nanvalidqty = "{l s='Please enter a valid quanity.' mod='supercheckout'}";

            var scp_guest_tracking_url = "{$link->getPageLink("guest-tracking", true)|addslashes nofilter}{*escape not required as contains url*}";
            var scp_history_url = "{$link->getPageLink("history", true)|addslashes nofilter}{*escape not required as contains url*}";
            var payment_content_id = 'center_column';
            var scp_required_tos = {$settings['confirm']['term_condition'][$user_type]['require']|intval};
            var show_delivery_add_for_virtualcart = false;
            {if $show_delivery_add_for_virtualcart eq true}
            show_delivery_add_for_virtualcart = true;
            {/if}
            var is_virtual_cart = false;
            {if $is_virtual_cart eq true}
            is_virtual_cart = true;
            {/if}
            var cart_update_url = "{$link->getPageLink('cart')|escape:'quotes'}";
            var enable_save_address_front = "{$settings['enable_save_address']}";
            var orderOpcUrl = "{$link->getPageLink("order-opc", true)|escape:'quotes'}";
            var button_background = "{$settings['customizer']['button_color']}";
            var required_error = "{l s='Required Field' mod='supercheckout'}";
            var invalid_email = "{l s='Email is invalid' mod='supercheckout'}";
            var pwd_error = "{l s='(Five characters minimum)' mod='supercheckout'}";
            var invalid_city = "{l s='Special Characters !<>;?=+@#°{}_$% are not allowed' mod='supercheckout'}";
            var invalid_address = "{l s='Special Characters !<>?=+@{}_$% are not allowed' mod='supercheckout'}";
            var invalid_title = "{l s='Special Characters <>={} are not allowed' mod='supercheckout'}";
            var invalid_number = "{l s='Only +.-() and numbers are allowed' mod='supercheckout'}";
            var invalid_other_info = "{l s='Special Characters <>{} are not allowed' mod='supercheckout'}";
            var invalid_dob = "{l s='Invalid Date of Birth' mod='supercheckout'}";
            var invalid_country_msg = "{l s='Invalid Country' mod='supercheckout'}";
            var invalid_state_msg = "{l s='Invalid State' mod='supercheckout'}";
            var invalid_name = "{l s='Name is invalid' mod='supercheckout'}";
            var number_error = "{l s='Numbers not allowed' mod='supercheckout'}";
            var toc_error = "{l s='Please acccept our terms & conditions before confirming your order' mod='supercheckout'}";
            var zipcode_error = "{l s='Some Products in your cart can not be delivered to the selected address.Kindly remove the same or change your address.' mod='supercheckout'}";
            var order_place_confirmation = "{l s='All the information provided by you are correct?' mod='supercheckout'}";
            var splchar_error = "{l s='Special Characters !<>,;?=+()@#°{}_$%: are not allowed' mod='supercheckout'}";
            var inline_validation = {$settings['inline_validation']['enable']|intval};
            var street_number_warning = "{l s='Street Number in address is missing, are you sure you don\'t have one?' mod='supercheckout'}";

            /*
             * Added by Anshul Mittal
             */
            var save_update_address = "{l s='You might forgot to update the address. Please save the address first or cancel it.' mod='supercheckout'}";
            var upload_file_text = "{l s='Please upload a file.' mod='supercheckout'}";
            var valid_format_file_text = "{l s='Please upload a file with a valid format.' mod='supercheckout'}";


            {if isset($settings['qty_update_option']) && $settings['qty_update_option'] eq 0 }
            var update_qty_button = 1;
            {else}
            var update_qty_button = 0;
            {/if}
            {*Below code added by Nimish for google auto address feature*}
            {if $settings['google_auto_address']['enable'] eq 1}
                {if isset($settings['google_auto_address']['api_key']) && $settings['google_auto_address']['api_key'] neq ''}
            var google_auto_address_api_key = "{$settings['google_auto_address']['api_key']|escape:'htmlall':'UTF-8'}";
                {/if}
            {/if}
            {*Above code added by Nimish for google auto address feature*}
            {if isset($guest_information) && $guest_information != ''}
            var guest_information = JSON.parse('{$guest_information nofilter}'); {*escape not required as contains JS*}
            {/if}
            {urldecode($settings['custom_js']) nofilter}  {*escape not required as contains JS*}
        </script>
        {assign var='login_boxes_width' value=50|intval}
        {if $settings['fb_login']['enable'] || $settings['fb_login']['enable']}
            {$login_boxes_width = 33}
        {/if}
        <style>
            {literal}    
                .supercheckout_top_boxes{width:{/literal}{$login_boxes_width|intval}{literal}%;}
            {/literal}
            {urldecode($settings['custom_css']) nofilter}{*Variable contains css content, escape not required*}

            #supercheckout-fieldset .orangebutton {
                background-color:#{$settings['customizer']['button_color']};
                {if $settings['customizer']['button_color'] == 'F77219'}
                    background: linear-gradient(to bottom, #F77219 1%, #FEC6A7 3%, #F77219 7%, #F75B16 100%) repeat scroll 0 0 rgba(0, 0, 0, 0) !important;
                {else}
                    background : #{$settings['customizer']['button_color']};
                {/if}

                border: 1px solid #{$settings['customizer']['button_border_color']} !important;
                color: #{$settings['customizer']['button_text_color']} !important;
                border-bottom:3px solid #{$settings['customizer']['border_bottom_color']} !important;
            }
            #supercheckout-fieldset .orangebutton:hover {
                background-color:#{$settings['customizer']['button_color']};
                {if $settings['customizer']['button_color'] == 'F77219'}
                    background: linear-gradient(to bottom, #F28941 1%, #FEC6A7 3%, #F28941 7%, #F75B16 100%) repeat scroll 0 0 rgba(0, 0, 0, 0) !important;
                {/if}

                border: 1px solid #{$settings['customizer']['button_border_color']} !important;
                border-bottom:3px solid #{$settings['customizer']['border_bottom_color']} !important;
            }
            #supercheckout-fieldset .orangebuttonsmall {
                background-color:#{$settings['customizer']['button_color']};
                {if $settings['customizer']['button_color'] == 'F77219'}
                    background: linear-gradient(to bottom, #F77219 1%, #FEC6A7 3%, #F77219 7%, #F75B16 100%) repeat scroll 0 0 rgba(0, 0, 0, 0) !important;
                {else}
                    background : #{$settings['customizer']['button_color']};
                {/if}

                border: 1px solid #{$settings['customizer']['button_border_color']} !important;
                color: #{$settings['customizer']['button_text_color']} !important;
            }
            #supercheckout-fieldset .orangebuttonsmall:hover {
                background-color:#{$settings['customizer']['button_color']};
                {if $settings['customizer']['button_color'] == 'F77219'}
                    background: linear-gradient(to bottom, #F28941 1%, #FEC6A7 3%, #F28941 7%, #F75B16 100%) repeat scroll 0 0 rgba(0, 0, 0, 0) !important;
                {/if}

                border: 1px solid #{$settings['customizer']['button_border_color']} !important;
            }
            #shipping_address_table td {
                vertical-align: top;
            }
            #payment_address_table td {
                vertical-align: top;
            }
            #supercheckout-fieldset .orangebuttonapply {
                background-color:#{$settings['customizer']['button_color']}; 
                {if $settings['customizer']['button_color'] == 'F77219'}
                    background: linear-gradient(to bottom, #F77219 1%, #FEC6A7 3%, #F77219 7%, #F75B16 100%) repeat scroll 0 0 rgba(0, 0, 0, 0) !important;
                {else}
                    background : #{$settings['customizer']['button_color']};
                {/if}

                border: 1px solid #{$settings['customizer']['button_border_color']} !important;
                color: #{$settings['customizer']['button_text_color']} !important;
            }
            #supercheckout-fieldset .orangebuttonapply:hover {
                background-color:#{$settings['customizer']['button_color']};
                {if $settings['customizer']['button_color'] == 'F77219'}
                    background: linear-gradient(to bottom, #F28941 1%, #FEC6A7 3%, #F28941 7%, #F75B16 100%) repeat scroll 0 0 rgba(0, 0, 0, 0) !important;
                {/if}

                border: 1px solid #{$settings['customizer']['button_border_color']} !important;
            }

            .divkbmobilelogin .orangebuttonapply {
                background-color:#{$settings['customizer']['button_color']}; 
                {if $settings['customizer']['button_color'] == 'F77219'}
                    background: linear-gradient(to bottom, #F77219 1%, #FEC6A7 3%, #F77219 7%, #F75B16 100%) repeat scroll 0 0 rgba(0, 0, 0, 0) !important;
                {else}
                    background : #{$settings['customizer']['button_color']};
                {/if}

                border: 1px solid #{$settings['customizer']['button_border_color']} !important;
                color: #{$settings['customizer']['button_text_color']} !important;
            }
            {* changes by rishabh jain *}
            .divKbgiftMessage .orangebuttonapply {
                background-color:#{$settings['customizer']['button_color']}; 
                {if $settings['customizer']['button_color'] == 'F77219'}
                    background: linear-gradient(to bottom, #F77219 1%, #FEC6A7 3%, #F77219 7%, #F75B16 100%) repeat scroll 0 0 rgba(0, 0, 0, 0) !important;
                {else}
                    background : #{$settings['customizer']['button_color']};
                {/if}

                border: 1px solid #{$settings['customizer']['button_border_color']} !important;
                color: #{$settings['customizer']['button_text_color']} !important;
            }
            {* changes over *}
            .divkbmobilelogin .orangebuttonapply:hover {
                background-color:#{$settings['customizer']['button_color']};
                {if $settings['customizer']['button_color'] == 'F77219'}
                    background: linear-gradient(to bottom, #F28941 1%, #FEC6A7 3%, #F28941 7%, #F75B16 100%) repeat scroll 0 0 rgba(0, 0, 0, 0) !important;
                {/if}

                border: 1px solid #{$settings['customizer']['button_border_color']} !important;
            }
            {* changes by rishabh jain *}
            .divKbgiftMessage .orangebuttonapply:hover {
                background-color:#{$settings['customizer']['button_color']};
                {if $settings['customizer']['button_color'] == 'F77219'}
                    background: linear-gradient(to bottom, #F28941 1%, #FEC6A7 3%, #F28941 7%, #F75B16 100%) repeat scroll 0 0 rgba(0, 0, 0, 0) !important;
                {/if}

                border: 1px solid #{$settings['customizer']['button_border_color']} !important;
            }
            {* changes over *}


            {*Added by Anshul to change the progressive loader*}

           #supercheckout-fieldset .kb_edit_btn {
            background-color:#{$settings['customizer']['update_address_button_color']}; 
                border: 1px solid #{$settings['customizer']['update_address_button_color']} !important;
                color: #{$settings['customizer']['button_text_color']} !important;
            padding:10px;
            letter-spacing: 1px;
            }
            #supercheckout-fieldset .kb_delete_btn {
            background-color:#{$settings['customizer']['remove_address_button_color']}; 
                border: 1px solid #{$settings['customizer']['remove_address_button_color']} !important;
                color: #{$settings['customizer']['button_text_color']} !important;
            letter-spacing: 1px;
            padding:10px;
            }
            
            .opc-container input[type=checkbox]:checked + label:before {
                background-color:#{$settings['customizer']['theme_common_color']}; 
    border-color: #{$settings['customizer']['theme_common_color']};
    color: #{$settings['customizer']['button_text_color']};
            }
            
            .opc-container .custom-panel h2:after {
    position: absolute;
    content: '';
    border-left: 3px solid #{$settings['customizer']['theme_common_color']};
    left: 0;
    top: 0;
    height: 100%;
}
            
            .opc-container a, .opc-container a:hover, .opc-container a:focus {
                 color: #{$settings['customizer']['product_name_color']};
            }
            
            button.cart_quantity_down.qty-btn.increase_button.quantity-left-minus.btn.btn-primary.btn-number,
            button.cart_quantity_down.qty-btn.decrease_button.quantity-right-plus.btn.btn-primary.btn-number {
                background-color: #{$settings['customizer']['theme_common_color']};
            }
            
            .myAccountLi a, .myAccountLi a:focus, .myAccountLi a:hover {
                background-color: #{$settings['customizer']['my_account_button_color']};
                color: #{$settings['customizer']['button_text_color']} !important;
            }
            .accountLogoutLi a, .accountLogoutLi a:focus, .accountLogoutLi a:hover {
                background-color: #{$settings['customizer']['logout_button_color']};
                color: #{$settings['customizer']['button_text_color']} !important;
            }
            
            #velsof_supercheckout_form .progress-bar-striped, .progress-striped .progress-bar{
                background-image: linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent);
                -webkit-background-size: 40px 40px;
                background-size: 40px 40px;    
                height: 100%;
                background-color: #{$settings['customizer']['progressbar_button_color']};
            }
            #velsof_supercheckout_form .progress .progress-bar:after{ border-left-color: #{$settings['customizer']['progressbar_button_color']}; }
            .progress-bar {
                color: #{$settings['customizer']['button_text_color']} !important;
            }




            #velsof_supercheckout_form .kb_velsof_sc_overlay{
                position: absolute;
                width: 100%;
                height: 100%;
                background-color: white;
                opacity: 0.5;
                top: 0;
                bottom: 0;
                left: 0;
                right: 0;
                margin: auto;
                z-index:1;
            }


            {* #velsof_supercheckout_form .kb_lds-ripple {
            width: 50px;
            height: 80px;
            text-align: center;
            font-size: 10px;
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            margin: auto;
            z-index: 1;
            }

            #velsof_supercheckout_form .kb_lds-ripple > div {
            background-color: #696969;
            height: 100%;
            width: 3px;
            display: inline-block;

            -webkit-animation: sk-stretchdelay 1.2s infinite ease-in-out;
            animation: sk-stretchdelay 1.2s infinite ease-in-out;
            }

            #velsof_supercheckout_form .kb_lds-ripple .rect2 {
            -webkit-animation-delay: -1.1s;
            animation-delay: -1.1s;
            }

            #velsof_supercheckout_form
            .kb_lds-ripple .rect3 {
            -webkit-animation-delay: -1.0s;
            animation-delay: -1.0s;

            }

            #velsof_supercheckout_form
            .kb_lds-ripple .rect4 {
            -webkit-animation-delay: -0.9s;
            animation-delay: -0.9s;
            }


            #velsof_supercheckout_form
            .kb_lds-ripple .rect5 {
            -webkit-animation-delay: -0.8s;
            animation-delay: -0.8s;

            }

            @-webkit-keyframes sk-stretchdelay {
            0%, 40%, 100% { -webkit-transform: scaleY(0.4) }  
            20% { -webkit-transform: scaleY(1.0) }
            }

            @keyframes sk-stretchdelay {
            0%, 40%, 100% { 
            transform: scaleY(0.4);
            -webkit-transform: scaleY(0.4);
            } 20% { 
            transform: scaleY(1.0);
            -webkit-transform: scaleY(1.0);
            }
            }*}
            {*Added by Anshul to change the progressive loader*}


        </style>
        {if (isset($mobileLoginActive) && ($mobileLoginActive eq 1))}
            <style>
                /* For Firefox */
                input[type='number'] {
                    -moz-appearance:textfield;
                }
                /* Webkit browsers like Safari and Chrome */
                input[type=number]::-webkit-inner-spin-button,
                input[type=number]::-webkit-outer-spin-button {
                    -webkit-appearance: none;
                    margin: 0;
                }
                .kb-help-block{
                    margin-top: 5px;
                }
            </style>
        {/if}
        {*{if isset($HOOK_EXTRACARRIER)}*}
        {*{$HOOK_EXTRACARRIER}{*Variable contains html content, escape not required*}
        {*{/if}*}
        <a style="display:none;" href="javascript:void(0)" id="bancasella_process_payment" ></a>
        {capture name=path}<span class="navigation_page">{l s='Your shopping cart' mod='supercheckout'}</span>{/capture}
        <div id="fb-root"></div>
        <div id="supercheckout-empty-page-content" class="supercheckout-empty-page-content" style="display:block">
            {if isset($velsof_errors) && count($velsof_errors) > 0}

                <div class="permanent-warning">
                    {foreach $velsof_errors as $err}
                        {$err}<br>
                    {/foreach}</div>
                {/if}
        </div>
        {hook h='displayPaymentTop'}
        {hook h="displayContentWrapperTop"}
        <form id="velsof_supercheckout_form" class="container opc-container" action="{$supercheckout_url}" method="POST" onsubmit=" return isPressedEnter(this)" enctype="multipart/form-data">
            {*Added by Anshul to change the progressive loader*}

            <div class="kb_velsof_sc_overlay" style="display: none;"></div>
            <div class="pay-loader kb_lds-ripple">
                <div class="lds-hourglass"></div>
            </div>
            {* <div class="kb_lds-ripple" style='display:none;'>
            <div class="rect1"></div>
            <div class="rect2"></div>
            <div class="rect3"></div>
            <div class="rect4"></div>
            <div class="rect5"></div>
            </div>*}
            {*Added by Anshul to change the progressive loader*}
            <input type='hidden' name='{$plugin_name}PlaceOrder' value='1' />
            {if isset($settings['html_value']['header']) && $settings['html_value']['header'] neq ''}
                <div id="supercheckout_html_content_header">        
                    {$settings['html_value']['header'] nofilter}{*Variable contains html content, escape not required*}
                </div>
            {/if}
            <div id="submission_progress_overlay" class="submit_progress_disable"></div>
            {*Added by Anshul to change the progressive loader*}
            <div class="container" id="supercheckout_order_progress_bar" style="display:none;">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="progress-title">{l s='Loading' mod='supercheckout'}....</h3>
                        <div class="progress">
                            <div class="progress-bar progress-bar-danger progress-bar-striped active" style="width:20%;">
                                <div class="progress-value" id="supercheckout_order_progress_status_text">20%</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {*Added by Anshul to change the progressive loader*}
            <div class="panel panel-default">
                {if isset($kb_free_shipping_percent)}
                <div class="">
                    <div class="freeShipping"> {*Need to change later*}
                        {if $hidden_amount == 0}
                            <h3>{l s='Congratulations!! You have reached the minumum amount limit to get Free Shipping.' mod='supercheckout'} </h3>
                        {else}
                            <h3>{l s='Almost there, Add ' mod='supercheckout'} {$kb_free_shipping_amount} {l s=' more to get Free Shipping' mod='supercheckout'}</h3>
                        {/if}
                        <div class="progress">
                            <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar"
                                 aria-valuenow="{$kb_free_shipping_percent}" aria-valuemin="0" aria-valuemax="100" style="width:{$kb_free_shipping_percent}%;flex:{$kb_free_shipping_percent}%;max-width:{$kb_free_shipping_percent}%;">
                                {$kb_free_shipping_percent}% {l s='Complete (success) ' mod='supercheckout'} 
                            </div>
                        </div>
                    </div>
                </div>
                {/if}
                <fieldset class="group-select" id="supercheckout-fieldset">
                    {if !$logged} 
                        {if isset($mobileLoginActive) && ($mobileLoginActive eq 1)}
                            <div id="divkbmobilelogin" style="display:none" title="{l s='Login/Register With Mobile Number' mod='supercheckout'}" class="supercheckout-threecolumns divkbmobilelogin">
                                {*                <div class="velsof_sc_overlay" style="display: none;"></div>*}
                                <div id="supercheckout-option" style="display:{if (($mobile_login_setting['login_by_otp'] eq 1) or ($mobile_login_setting['login_by_mobile'] eq 1)) and ($mobile_login_setting['show_mobile_on_registration'] eq 1)}block{else}none{/if}">
                                    {if ($mobile_login_setting['login_by_otp'] eq 1) or ($mobile_login_setting['login_by_mobile'] eq 1)}
                                        <div class="supercheckout-extra-wrap">
                                            <input type="radio" name="mobile_option" value="0" id="mobile_login_checkout" checked="checked" >
                                            <label for="mobile_login_checkout">{l s='Login into shop' mod='supercheckout'}</label>
                                            <br>
                                        </div>
                                    {/if}
                                    {if ($mobile_login_setting['show_mobile_on_registration'] eq 1)}
                                        <div class="supercheckout-extra-wrap">
                                            <input type="radio" name="mobile_option" value="1" id="mobile_register_checkout" {if ($mobile_login_setting['login_by_otp'] neq 1) and ($mobile_login_setting['login_by_mobile'] neq 1)} checked="checked" {/if}>
                                            <label for="mobile_register_checkout">{l s='Register new account' mod='supercheckout'}</label>
                                            <br>
                                        </div>
                                    {/if}

                                </div>

                                <div class="supercheckout-extra-wrap">
                                    {l s='First Name' mod='supercheckout'}<span class="supercheckout-required">*</span><br>
                                    <input type="text" id="mobile_firstname" name="supercheckout_mobile_firstname" value="" class="supercheckout-large-field form-control">
                                </div>
                                <div class="supercheckout-extra-wrap">
                                    {l s='Last Name' mod='supercheckout'}<span class="supercheckout-required">*</span><br>
                                    <input type="text" id="mobile_lastname" name="supercheckout_mobile_lastname" value="" class="supercheckout-large-field form-control">
                                </div>
                                <div class="supercheckout-extra-wrap">
                                    {l s='Email' mod='supercheckout'}<span class="supercheckout-required">*</span><br>
                                    <input type="text" id="mobile_email" name="supercheckout_mobile_email" value="" class="supercheckout-large-field form-control">
                                </div>
                                <div class="supercheckout-extra-wrap">
                                    {l s='Country' mod='supercheckout'}<span class="supercheckout-required">*</span><br>
                                    <select name="mobile_country" class="supercheckout-large-field form-control">
                                        {*                        <option value="">--</option>*}
                                        {if is_array($total_active_country) && !empty($total_active_country)}
                                            {foreach $total_active_country as $country_details}
                                                <option value="{$country_details['id_country']}" {*{if isset($kb_country_id) &&  ($country_details['id_country'] eq $kb_country_id)}selected{/if}*}>{$country_details['country']} (+{$country_details['prefix']}) </option>
                                            {/foreach}
                                        {/if}
                                    </select>
                                </div>
                                <div class="supercheckout-extra-wrap">
                                    {l s='Mobile Number' mod='supercheckout'}<span class="supercheckout-required">*</span><br>
                                    <input type="number" id="mobile_number" name="supercheckout_mobile_number" style="width:auto;" value="" class="supercheckout-large-field form-control">
                                    <span class="mobile_send_otp">
                                        <input id="mobile_send_otp" type="button" onclick="sendOtp();" class="orangebuttonapply" value="{l s='Send OTP' mod='supercheckout'}">
                                    </span>
                                </div>    
                                <div id="supercheckout-option" {if ($mobile_login_setting['login_by_otp'] eq 1) and ($mobile_login_setting['login_by_mobile'] eq 1)} style="display:-webkit-box;" {else} style="display:none;" {/if}>
                                    <div class="supercheckout-extra-wrap" >
                                        <input type="radio" name="password_option" value="0" id="login_password" {if ($mobile_login_setting['login_by_mobile'] eq 1)}checked="checked"{/if}>
                                        <label for="login_password">{l s='Password' mod='supercheckout'}</label>
                                        <br>
                                    </div>
                                    <div class="supercheckout-extra-wrap">
                                        <input type="radio" name="password_option" value="1" id="login_otp" {if ($mobile_login_setting['login_by_mobile'] neq 1)}checked="checked"{/if}>
                                        <label for="login_otp">{l s='OTP' mod='supercheckout'}</label>
                                        <br>
                                    </div>                 
                                </div>
                                <div class="supercheckout-extra-wrap">
                                    {l s='OTP' mod='supercheckout'}<span class="supercheckout-required">*</span><br>
                                    <input type="number" id="mobile_otp" name="supercheckout_mobile_otp" value="" style="width:auto;" class="supercheckout-large-field form-control">
                                    <span class="mobile_verify_otp">
                                        <input id="mobile_verify_otp" type="button" onclick="verfyOtp();" class="orangebuttonapply" value="{l s='Verify OTP' mod='supercheckout'}">
                                    </span>

                                </div>
                                <div class="supercheckout-extra-wrap">
                                    {l s='Password' mod='supercheckout'}<span class="supercheckout-required">*</span><br>
                                    <input type="password" id="mobile_Password" name="supercheckout_mobile_Password" value="" class="supercheckout-large-field form-control">
                                </div>
                                <div class="supercheckout-extra-wrap">
                                    <input id="mobile_login" type="button" onclick="MobileLogin();" class="orangebuttonapply form-control" value="{l s='LOGIN' mod='supercheckout'}">
                                </div>
                                <div class="supercheckout-extra-wrap">
                                    <input id="mobile_register" type="button" onclick="MobileRegister();" class="orangebuttonapply form-control" value="{l s='REGISTER' mod='supercheckout'}">
                                </div>
                            </div>
                        {/if} 
                    {/if}
           
                    <div class="supercheckout-threecolumns supercheckout-container supercheckout-skin-generic panel-body" id="supercheckout-columnleft">
                        <div class="row">
                            {assign var='layout_name' value='1_column'}
                            {assign var='multiplier' value=1}
                            {assign var='multiplier_3' value=0.98}
                            {assign var='multiplier_2' value=0.99}
                            {if $settings['layout'] eq 3}
                                {$layout_name='3_column'}
                                {$multiplier=$multiplier_3}
                            {else if $settings['layout'] eq 2}
                                {$layout_name='2_column'}
                                {$multiplier=$multiplier_2}
                            {/if}
                            
                            {if isset($is_mobile)}
                                {assign var='layout_name' value='1_column'}
                                {assign var='multiplier' value=1}
                            {/if}
                            
                            <div class="col-lg-4 col-md-4 col-sm-12 kb_mobile_sc_respo" {if $layout_name == '1_column'} style="width:100%;flex: 100%;max-width: 100%;" {else if $layout_name == '2_column' || $layout_name == '3_column'} style="width:{$settings['column_width'][$layout_name][1]*$multiplier}%;flex:{$settings['column_width'][$layout_name][1]*$multiplier}%;max-width:{$settings['column_width'][$layout_name][1]*$multiplier}%;" {/if}>
                                <div class="supercheckout-column-left columnleftsort checkout-section section-login custom-panel" id="columnleft-1" style="width:{100*$multiplier}%;flex:{100*$multiplier}%;max-width:{100*$multiplier}%;"> {*Need to change later*}
                                    <div  class="supercheckout-blocks" data-column="{$settings['design']['login'][$layout_name]['column']|intval}" data-row="{$settings['design']['login'][$layout_name]['row']|intval}" data-column-inside="{$settings['design']['login'][$layout_name]['column-inside']|intval}"  >
                                        {if $logged}
                                            <h2 class="title section-title">{l s='Welcome' mod='supercheckout'} {$customer_name} <i class="fas fa-user-lock"></i></h2>
                                            {else}
                                            <h2 class="title section-title">{l s='Login Options' mod='supercheckout'} <i class="fas fa-user-lock"></i></h2>
                                            {/if}
                                        <div id="checkoutLogin" class="section-body">
                                            <div class="supercheckout-checkout-content"></div>
                                            {include file='module:supercheckout/views/templates/front/login.tpl'}
                                            <br>
                                        </div>     
                                    </div>
                                    <div class="supercheckout-blocks" data-column="{$settings['design']['shipping_address'][$layout_name]['column']|intval}" data-row="{$settings['design']['shipping_address'][$layout_name]['row']|intval}" data-column-inside="{$settings['design']['shipping_address'][$layout_name]['column-inside']|intval}">
                                        {if $show_delivery_add_for_virtualcart eq true}
                                            <div id="checkoutShippingAddress" style="display:none;">
                                                {*                            <div class="velsof_sc_overlay"></div>*}
                                            </div> 
                                        {else}
                                            <div id="checkoutShippingAddress">
                                                {*                            <div class="velsof_sc_overlay"></div>*}
                                                {include file='module:supercheckout/views/templates/front/shipping_address.tpl'}
                                                <br>
                                            </div>
                                        {/if}
                                    </div>
                                    <div  class="supercheckout-blocks"  data-column="{$settings['design']['payment_address'][$layout_name]['column']|intval}" data-row="{$settings['design']['payment_address'][$layout_name]['row']|intval}" data-column-inside="{$settings['design']['payment_address'][$layout_name]['column-inside']|intval}">
                                        <div id="checkoutBillingAddress" style="display:{if $settings['use_delivery_for_payment_add'][$user_type]}none{else}block{/if};">
                                            {*                        <div class="velsof_sc_overlay"></div>*}
                                            {include file='module:supercheckout/views/templates/front/payment_address.tpl'}
                                        </div>
                                        <br/>
                                        {if isset($settings['enable_save_address']) && $settings['enable_save_address'] eq 1}
                                            <div id="supercheckout_save_address_button" style="display:none;">
                                                <!-- Code Modified by Raghu on 22-Aug-2017 for fixing 'Save Button Translation' issue -->
                                                <input type="button" value="{l s='Save' mod='supercheckout'}" id="supercheckout_save_address" class="button-save-address orangebuttonsmall">
                                            </div>
                                        {/if}
                                    </div>            
                                    <div style="{if $settings['shipping_method']['enable'] eq 0}display:none;{/if}"  class="supercheckout-blocks" data-column="{$settings['design']['shipping_method'][$layout_name]['column']|intval}" data-row="{$settings['design']['shipping_method'][$layout_name]['row']|intval}" data-column-inside="{$settings['design']['shipping_method'][$layout_name]['column-inside']|intval}" >
                                        <div class="opc_shipping_method">
                                            <h2 class="supercheckout-numbers supercheckout-numbers-4 title section-title">{l s='Delivery Method' mod='supercheckout'} <i class="fas fa-shipping-fast"></i></h2>

                                            <div id="shipping_method_update_warning" class="supercheckout-checkout-content"></div>
                                            {if isset($settings['hide_ship_pay']) && $settings['hide_ship_pay'] eq 1 && !$customer.addresses}
                                                <span class="permanent-warning" style="display: inline-block;"> {l s='Save your address first in order to check actual shipping methods & cost' mod='supercheckout'} </span>

                                            {else}
                                                <div id="shipping-method">
                                                    {*Here Delivery Methods will be rendered*}
                                                </div>
                                                <br>
                                            {/if}
                                        </div>
                                    </div>

                                    <div style="{if $settings['payment_method']['enable'] eq 0}display:none;{/if}" class="supercheckout-blocks" data-column="{$settings['design']['payment_method'][$layout_name]['column']|intval}" data-row="{$settings['design']['payment_method'][$layout_name]['row']|intval}" data-column-inside="{$settings['design']['payment_method'][$layout_name]['column-inside']|intval}">
                                        <div class="opc_payment_method">
                                            <h2 class="supercheckout-numbers supercheckout-numbers-4 title section-title">{l s='Payment Method' mod='supercheckout'} <i class="far fa-credit-card"></i></h2>

                                            <div id="payment_method_update_warning" class="supercheckout-checkout-content"></div>
                                            {if isset($settings['hide_ship_pay']) && $settings['hide_ship_pay'] eq 1 && !$customer.addresses}
                                                <span class="permanent-warning" style="display: inline-block;">{l s='Please choose your shipping address first in order to check the payment methods.' mod='supercheckout'}</span>
                                            {else}  
                                                <div id="payment-method">
                                                    {*include file='./payment_methods.tpl'*}
                                                </div>
                                                <br>
                                            {/if}
                                        </div>
                                    </div>
                                    <div class="supercheckout-blocks confirmCheckoutBack" data-column="{$settings['design']['cart'][$layout_name]['column']|intval}" data-row="{$settings['design']['cart'][$layout_name]['row']|intval}" data-column-inside="{$settings['design']['cart'][$layout_name]['column-inside']|intval}" style="display:{if $settings['display_cart'] eq 1}block{else}none{/if};">
                                          
                                        <div class="opc_shoppingcart custom-panel">
                                            <h2>{l s='Shopping Cart' mod='supercheckout'} <i class="fas fa-shopping-basket"></i></h2>

                                            <div id="cart_update_warning" class="supercheckout-checkout-content"></div>
                                            <div id="confirmCheckout">
                                                {*Here Cart Summary will be rendered*}
                                            </div>
                                        </div>

                                    </div>
                                    <div class="supercheckout-blocks opc_confirmcart custom-panel columnleftsort" data-column="{$settings['design']['confirm'][$layout_name]['column']|intval}" data-row="{$settings['design']['confirm'][$layout_name]['row']|intval}" data-column-inside="{$settings['design']['confirm'][$layout_name]['column-inside']|intval}">
                                        <!--h2>Confirm Your Order</h2-->
                                        <div class="supercheckout-checkout-content"> </div>
                                        <div class="form-group" id="supercheckout-comments" style="display:{if $logged}{if $settings['confirm']['order_comment_box']['logged']['display'] eq 1}block{else}none{/if}{else}{if $settings['confirm']['order_comment_box']['guest']['display'] eq 1}block{else}none{/if}{/if};">
                                            <textarea id="supercheckout-comment_order" class="form-control" name="comment" placeholder="{l s='Add Comments About Your Order' mod='supercheckout'}"></textarea>
                                        </div>

                                        <div id='order-shipping-extra'>
                                            {include file='module:supercheckout/views/templates/front/order_extra_fields.tpl'}
                                        </div>

                                        <div id="placeorderButton" style="text-align: center;margin-bottom: 10px;">
                                            {* changes by rishabh jain *}
                                            <div id="buttonWithProgres">
                                                <div  id="supercheckout_continue_shopping" class="orangebutton btn btn-success" style="display:none;">
                                                    <a href="{$index_page_link}" style = "color: #{$settings['customizer']['button_text_color']} !important;text-decoration: none !important;">
                                                        {l s='Continue Shoppping' mod='supercheckout'}
                                                    </a>
                                                </div>
                                            </div>
                                            {* changes over *}
                                            <div id="buttonWithProgres">
                                                <div  id="supercheckout_confirm_order" class="orangebutton btn btn-success" >
                                                    {l s='Place Order' mod='supercheckout'}
                                                    <div id="progressbar" style="text-align:center;margin-top: 0px;"></div>
                                                </div>
                                                <div id="" style="display:none;">
                                                    <input type="button"  id="supercheckout_confirm_order_kb" value="test button" class="orangebutton" >
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="supercheckout_submission" value="" />
                                    </div>



                                    {foreach from=$settings['design']['html'] item='html'}
                                        <div  class="supercheckout-blocks" data-column="{$html[$layout_name]['column']|intval}" data-row="{$html[$layout_name]['row']|intval}" data-column-inside="{$html[$layout_name]['column-inside']|intval}">
                                            {$html['value'] nofilter}{*Variable contains html content, escape not required*}
                                        </div>
                                    {/foreach}
                                </div>
                            </div>
                            <div class="{if $layout_name == '2_column' || $layout_name == '1_column'}col-lg-8 col-md-8 col-sm-12 {else}parent-three-column{/if} custom-panel kb_mobile_sc_respo" {if $layout_name == '2_column'} style="width:{$settings['column_width'][$layout_name][2]*$multiplier}%;flex:{$settings['column_width'][$layout_name][2]*$multiplier}%;max-width:{$settings['column_width'][$layout_name][2]*$multiplier}%;"{/if}>
                       {if $layout_name != '3_column'}         <div class="row"> {/if}
                                    <div class="supercheckout-column-middle columnleftsort col-lg-12 col-md-12 col-sm-12 colxs-12 kb_mobile_sc_respo" id="columnleft-2"  style="{if $layout_name == '3_column'}width:{$settings['column_width'][$layout_name][2]*$multiplier}% !important;flex:{$settings['column_width'][$layout_name][2]*$multiplier}% !important;max-width:{$settings['column_width'][$layout_name][2]*$multiplier}% !important;float:left !important;{else}width:{100*$multiplier}%;margin-right:0px;{/if}"> {*Need to change later*}
                                        <div class="supercheckout-column-left columnleftsort col-lg-6 col-md-6 col-sm-6" id="column-2-upper" style="width:100%;flex:100%; max-width:100%;height:auto;padding-left: 0px;"> 
                                        </div>
                                        <div class="supercheckout-column-left columnleftsort col-lg-6 col-md-6 col-sm-6 kb_mobile_sc_respo" id="column-1-inside" style="width:{$settings['column_width'][$layout_name]['inside'][1]*1}%;flex:{$settings['column_width'][$layout_name]['inside'][1]*1}%;max-width:{$settings['column_width'][$layout_name]['inside'][1]*1}%;{if $layout_name == '2_column'}padding-left: 0px;{/if}"> 
                                        </div>
                                        <div class="supercheckout-column-left columnleftsort col-lg-6 col-md-6 col-sm-6 kb_mobile_sc_respo" id="column-2-inside"  style="width:{$settings['column_width'][$layout_name]['inside'][2]*1}%;flex:{$settings['column_width'][$layout_name]['inside'][2]*1}%;max-width:{$settings['column_width'][$layout_name]['inside'][2]*1}%;{if $layout_name == '2_column'}padding-right: 0px;{/if}">

                                        </div>
                                        <div class="supercheckout-column-left columnleftsort col-lg-6 col-md-6 col-sm-6" id="column-2-lower"  style="width:100%;flex:100%;max-width:100%;height:auto;padding-left: 0px;">

                                        </div>
                                    </div>
                                    <div class="supercheckout-column-right columnleftsort col-lg-12 col-md-12 col-sm-12 colxs-12 kb_mobile_sc_respo" id="columnleft-3" style="{if $layout_name == '3_column'}width:{$settings['column_width'][$layout_name][3]*$multiplier}% !important;flex:{$settings['column_width'][$layout_name][3]*$multiplier}% !important;max-width:{$settings['column_width'][$layout_name][3]*$multiplier}% !important;float:left !important;{else}width:{100*$multiplier}%;flex:{100*$multiplier}%;max-width:{100*$multiplier}%{/if}">
                                    </div>
                            {if $layout_name != '3_column'}      </div>{/if}

                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="module_url" value="{$supercheckout_url|escape:'quotes'}" />    
                    <input type="hidden" id="addon_url" value="{$addon_url|escape:'quotes'}" />  
                    <input type="hidden" id="analytic_url" value="{$analytic_url|escape:'quotes'}" />    
                </fieldset>
            </div>
        </form>
        {* changes by rishabh jain *}
        <!-- The Modal -->
        <div id="myModal_supercheckout" class="modal_supercheckout" onclick='hideEnlargedImage()'>
            <!-- The Close Button -->
            <span class="close_supercheckout" onclick='hideEnlargedImage()'>&times;</span>
            <!-- Modal Content (The Image) -->
            <img class="modal-content_supercheckout" id="img01_supercheckout">
            <!-- Modal Caption (Image Text) -->
            <div id="caption_supercheckout"></div>
        </div>
        {* changes over *}
        <div class="modal fade in" id="velsof_payment_dialog" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content" id="velsof_dialog_content">
                    <div class="velsof_payment_dialog_overlay"></div>
                    <div class="modal-body" id="velsof_payment_container">
                        <div class="pay-modal-heading velsof_title_section">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <div class="payment-icon">
                                <img src="{$module_image_path nofilter}debit-card.png" class="img-responsive"/> {* Variable contains URL, can't escape. *}
                            </div>
                            <div class="payment-heading">
                                <h4>{l s='checkout' mod='supercheckout'}</h4>
                            </div>
                        </div>
                        <div class="payment-body velsof_content_section">
                            <p>{l s='Payment Information' mod='supercheckout'}</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-dismiss="modal">{l s='Make Payment' mod='supercheckout'}</button>
                    </div>
                </div>

            </div>
        </div>
        {if isset($settings['html_value']['footer']) && $settings['html_value']['footer'] neq ''}
            <div id="supercheckout_html_content_footer">        
                {$settings['html_value']['footer'] nofilter}{*Variable contains html content, escape not required*}
            </div>
        {/if}

        <script>
            var default_country = {$default_country};
            var js_countries = '{json_encode($countries)|addslashes nofilter}{*escape not required as contains html*}';
            var countries = JSON.parse(js_countries);
            delete js_countries;
            var allowed_countries = [];
            for (var country in countries) {
                if (country == default_country) {
                    allowed_countries.push(countries[country].iso_code);
                }
            }
            var page_lang_code = "{$iso_code}";

            {if $settings['mailchimp']['enable'] eq 1 && isset($settings['mailchimp']['default']) && $settings['mailchimp']['default'] eq 1}
            var supercheckout_subscribe_mailchimp = true;
            {/if}
            {if $settings['SendinBlue']['enable'] eq 1 && isset($settings['SendinBlue']['default']) && $settings['SendinBlue']['default'] eq 1}
            var supercheckout_subscribe_sendinblue = true;
            {/if}
            {if $settings['klaviyo']['enable'] eq 1 && isset($settings['klaviyo']['default']) && $settings['klaviyo']['default'] eq 1}
            var supercheckout_subscribe_klaviyo = true;
            {/if}
        </script>
        {*Below script starting from if statement added by Nimish for google auto address feature*}
        {if $settings['google_auto_address']['enable'] eq 1}
            <script>
                window.onload = function (e) {
                    var script = document.createElement('script');
                    script.src = "https://maps.googleapis.com/maps/api/js?key=" + google_auto_address_api_key + "&libraries=places&callback=initAutocomplete";
                    document.getElementsByTagName('script')[0].parentNode.appendChild(script);
                }

            </script>  
            {literal}
                <script>
                    var placeSearch;
                    var autocomplete = {};
                    var autocompleteLsr = {};
                    // Create the autocomplete object, restricting the search to geographical
                    // location types.     
                    var autocompletesWraps = [{name: ['shipping_address_address1', 'shipping_address_address2'], form: 'shipping-new'}, {name: ['payment_address_address1', 'payment_address_address2'], form: 'payment-new'}];
                    var shipping_address_address1_field = {street_number: 'short_name', route: 'long_name', locality: 'long_name', administrative_area_level_1: 'short_name', country: 'short_name', postal_code: 'short_name'};
                    var shipping_address_address2_field = {street_number: 'short_name', route: 'long_name', locality: 'long_name', administrative_area_level_1: 'short_name', country: 'short_name', postal_code: 'short_name'};
                    var payment_address_address1_field = {street_number: 'short_name', route: 'long_name', locality: 'long_name', administrative_area_level_1: 'short_name', country: 'short_name', postal_code: 'short_name'};
                    var payment_address_address2_field = {street_number: 'short_name', route: 'long_name', locality: 'long_name', administrative_area_level_1: 'short_name', country: 'short_name', postal_code: 'short_name'};

                    window.gm_authFailure = function () {
                        $('#supercheckout-empty-page-content').html('<div class="permanent-warning">' + google_auto_fill_warning + '</div>');
                        $("#shipping_address_address1").prop('disabled', false);
                        $("#shipping_address_address1").removeAttr('placeholder');
                        $("#shipping_address_address1").css('background-image', 'none');

                        $("#shipping_address_address2").prop('disabled', false);
                        $("#shipping_address_address2").removeAttr('placeholder');
                        $("#shipping_address_address2").css('background-image', 'none');

                        $("#payment_address_address1").prop('disabled', false);
                        $("#payment_address_address1").removeAttr('placeholder');
                        $("#payment_address_address1").css('background-image', 'none');

                        $("#payment_address_address2").prop('disabled', false);
                        $("#payment_address_address2").removeAttr('placeholder');
                        $("#payment_address_address2").css('background-image', 'none');

                        // Start Code Added by Priyanshu on 07-April-2018
                        $.each(autocompletesWraps, function (index, wraps) {
                            $.each(wraps.name, function (index, name) {
                                google.maps.event.removeListener(autocompleteLsr[name]);
                                google.maps.event.clearInstanceListeners($('#' + name + '.autocomplete')[0]);
                            });
                        });
                        // End code
                    }

                    function initAutocomplete() {
                        $.each(autocompletesWraps, function (index, wraps) {
                            $.each(wraps.name, function (index, name) {
                                if ($('#' + name).length == 0) {
                                    return;
                                }
                                autocomplete[name] = new google.maps.places.Autocomplete($('#' + name + '.autocomplete')[0], {types: ['geocode'], componentRestrictions: {'country': allowed_countries}});
                                autocompleteLsr[name] = google.maps.event.addListener(autocomplete[name], 'place_changed', function () {

                                    var place = autocomplete[name].getPlace();
                                    var field = eval(name + '_field');

                                    for (var component in field) {
                                        $('#' + wraps.form + ' .' + component).val('');
                                        $('#' + wraps.form + ' .' + component).attr('disabled', false);
                                    }

                                    for (var i = 0; i < place.address_components.length; i++) {
                                        var addressType = place.address_components[i].types[0];
                                        if (addressType == 'administrative_area_level_1') {
                                            var val = place.address_components[i][field[addressType]];
                                            $('#' + wraps.form + ' .administrative_area_level_1').val(val);
                                        }
                                    }

                                    for (var i = 0; i < place.address_components.length; i++) {
                                        var addressType = place.address_components[i].types[0];
                                        if (typeof field[addressType] !== 'undefined') {
                                            var val = place.address_components[i][field[addressType]];
                                            $('#' + wraps.form + ' .' + addressType).val(val);
                                            if (addressType == 'postal_code') {
                                                if (wraps.form == 'payment-new') {
                                                    $('input[name="payment_address[postcode]"]').trigger("blur");
                                                } else if (wraps.form == 'shipping-new') {
                                                    $('input[name="shipping_address[postcode]"]').trigger("blur");
                                                }
                                            } else if (addressType == 'street_number') {
                                                if (wraps.form == 'payment-new') {
                                                    $('input[name="payment_address[address1]"]').trigger("blur");
                                                } else if (wraps.form == 'shipping-new') {
                                                    $('input[name="shipping_address[address1]"]').trigger("blur");
                                                }
                                            } else if (addressType == 'route') {
                                                if (wraps.form == 'payment-new') {
                                                    $('input[name="payment_address[address2]"]').trigger("blur");
                                                } else if (wraps.form == 'shipping-new') {
                                                    $('input[name="shipping_address[address2]"]').trigger("blur");
                                                }
                                            } else if (addressType == 'locality') {
                                                if (wraps.form == 'payment-new') {
                                                    $('input[name="payment_address[city]"]').trigger("blur");
                                                } else if (wraps.form == 'shipping-new') {
                                                    $('input[name="shipping_address[city]"]').trigger("blur");
                                                }
                                            } else if (addressType == 'country') {
                                                $('#' + wraps.form + ' option[id=' + val + ']').attr('selected', 'selected');
                                                if (wraps.form == 'payment-new') {
                                                    $('select[name="payment_address[id_country]"]').trigger("change");
                                                } else if (wraps.form == 'shipping-new') {
                                                    $('select[name="shipping_address[id_country]"]').trigger("change");
                                                }

                                                var state_fetched = $('#' + wraps.form + ' .administrative_area_level_1').val();
                                                var reg = /^\b[a-zA-Z0-9_]+\b$/;
                                                if (reg.test(state_fetched)) {
                                                    if (wraps.form == 'payment-new') {
                                                        var countryid = $('select[name="payment_address[id_country]"]').val();
                                                    } else if (wraps.form == 'shipping-new') {
                                                        var countryid = $('select[name="shipping_address[id_country]"]').val();
                                                    }
                                                    var selected_state_id = '';
                                                    for (state in countries[countryid]["states"]) {
                                                        if (countries[countryid]["states"][state]['iso_code'] == state_fetched) {
                                                            selected_state_id = countries[countryid]["states"][state]['id_state'];
                                                            break;
                                                        }
                                                    }
                                                    //$('#'+wraps.form+' option[id='+selected_state_id+']').attr('selected','selected');
                                                    if (wraps.form == 'payment-new') {
                                                        $('select[name="payment_address[id_state]"]').val(selected_state_id);
                                                    } else if (wraps.form == 'shipping-new') {
                                                        $('select[name="shipping_address[id_state]"]').val(selected_state_id);
                                                    }
                                                    if (wraps.form == 'payment-new') {
                                                        $('select[name="payment_address[id_state]"]').trigger("change");
                                                    } else if (wraps.form == 'shipping-new') {
                                                        $('select[name="shipping_address[id_state]"]').trigger("change");
                                                    }
                                                }
                                            }
                                        }
                                    }
                                });
                            });
                        });
                    }

                    // Bias the autocomplete object to the user's geographical location,
                    // as supplied by the browser's 'navigator.geolocation' object.
                    function geolocate() {
                        $.each(autocompletesWraps, function (index, wraps) {
                            $.each(wraps.name, function (index, name) {
                                if (navigator.geolocation) {
                                    navigator.geolocation.getCurrentPosition(function (position) {
                                        var geolocation = {
                                            lat: position.coords.latitude,
                                            lng: position.coords.longitude
                                        };
                                        var circle = new google.maps.Circle({
                                            center: geolocation,
                                            radius: position.coords.accuracy
                                        });
                                        autocomplete[name].setBounds(circle.getBounds());
                                    });
                                }
                            });
                        });
                    }
                </script>
            {/literal}
            {*Code for google auto address feature ends*}
        {/if}
    {else}
        <div class="supercheckout-empty-page-content" style="display:block">
            <div class="permanent-warning">{l s='Your shopping cart is empty.' mod='supercheckout'}</div>
        </div>
        <script>
            var cart_empty = {$empty};
        </script>
    {/if}    
{/block}
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
* @copyright 2016 Knowband
*}
