{if $logged}
    <div class="myaccount rectangle-list text-center">
        <ul class="accountOptions">
            <li class="myAccountLi">
                <a href="{$my_account_url}"><i class="fa fa-user"></i> {l s='My Account' mod='supercheckout'}</a>
            </li>
            <li  class="accountLogoutLi">
                <a href="{$sc_logout_url}"><i class="fa fa-sign-out"></i> {l s='Logout' mod='supercheckout'}</a>
            </li>
        </ul>

        <!-- Start - Code to insert custom fields in registration form block -->
        <div class="div_custom_fields">
            {foreach from=$array_fields item=field}
                {if $field['position'] eq 'customer_registration_block'}
                    <div class="supercheckout-blocks form-group">
                                    {if $field['type'] eq "textbox"}
                                        <label class="cursor_help" title="{$field['field_help_text']}">{$field['field_label']}{if $field['required'] eq "1"}<span style="display:inline;" class="supercheckout-required">*</span>{/if}</label>
                                        <input type="text" name="custom_fields[field_{$field['id_velsof_supercheckout_custom_fields']}]" value="{$field['default_value']}" class="supercheckout-large-field width_100 form-control">
                                        <span id="error_field_{$field['id_velsof_supercheckout_custom_fields']}" class="errorsmall_custom hidden_custom"></span>
                                    {/if}

                                    {if $field['type'] eq "textarea"}
                                        <label class="cursor_help" title="{$field['field_help_text']}">{$field['field_label']}{if $field['required'] eq "1"}<span style="display:inline;" class="supercheckout-required">*</span>{/if}</label>
                                        <textarea name="custom_fields[field_{$field['id_velsof_supercheckout_custom_fields']}]" class="supercheckout-large-field width_100 form-control" style="width: 100%; height: 100px;">{$field['default_value']}</textarea>
                                        <span id="error_field_{$field['id_velsof_supercheckout_custom_fields']}" class="errorsmall_custom hidden_custom"></span>
                                    {/if}

                                    {if $field['type'] eq "selectbox"}
                                        <label class="cursor_help" title="{$field['field_help_text']}">{$field['field_label']}{if $field['required'] eq "1"}<span style="display:inline;" class="supercheckout-required">*</span>{/if}</label>
                                        <select name="custom_fields[field_{$field['id_velsof_supercheckout_custom_fields']}]" class="supercheckout-large-field width_100 form-control">
                                            {foreach from=$field['options'] item=field_options}
                                                <option {if $field_options['default_value'] eq $field_options['option_value']}selected{/if} value="{$field_options['option_value']}">{$field_options['option_label']}</option>
                                            {/foreach}
                                        </select>
                                        <span id="error_field_{$field['id_velsof_supercheckout_custom_fields']}" class="errorsmall_custom hidden_custom"></span>
                                    {/if}

                                    {if $field['type'] eq "radio"}
                                        <label class="cursor_help" title="{$field['field_help_text']}">{$field['field_label']}{if $field['required'] eq "1"}<span style="display:inline;" class="supercheckout-required">*</span>{/if}</label>
                                        {assign var=radio_counter value=1}
                                        {foreach from=$field['options'] item=field_options}
                                            <div class="supercheckout-extra-wrap">
                                                <div class="radio" id="uniform-field_{$field['id_velsof_supercheckout_custom_fields']}"><span>
                                                        <input type="radio" name="custom_fields[field_{$field['id_velsof_supercheckout_custom_fields']}]" value="{$field_options['option_value']}" {if $field_options['default_value'] eq $field_options['option_value']}checked{/if}>
                                                        <label for="field_{$field['id_velsof_supercheckout_custom_fields']}">{$field_options['option_label']}</label>
                                                    </span></div>
                                                
                                            </div>
                                            {assign var=radio_counter value=$radio_counter+1}
                                        {/foreach}
                                        <span id="error_field_{$field['id_velsof_supercheckout_custom_fields']}" class="errorsmall_custom hidden_custom"></span>
                                    {/if}

                                    {if $field['type'] eq "checkbox"}
                                        <label class="cursor_help" title="{$field['field_help_text']}">{$field['field_label']}{if $field['required'] eq "1"}<span style="display:inline;" class="supercheckout-required">*</span>{/if}</label>
                                        {foreach from=$field['options'] item=field_options}
                                            <div class="input-box input-field_{$field['id_velsof_supercheckout_custom_fields']}">
                                                <div class="checker checkbox" id="uniform-field_{$field['id_velsof_supercheckout_custom_fields']}">
                                                    <span class="checked">
                                                        <input {if $field_options['default_value'] eq $field_options['option_value']}checked{/if} type="checkbox" name="custom_fields[field_{$field['id_velsof_supercheckout_custom_fields']}][]" value="{$field_options['option_value']}">
                                                        <label for="field_{$field['id_velsof_supercheckout_custom_fields']}"><b>{$field_options['option_label']}</b></label>
                                                    </span>
                                                </div>
                                                
                                            </div>
                                        {/foreach}
                                        <span id="error_field_{$field['id_velsof_supercheckout_custom_fields']}" class="errorsmall_custom hidden_custom"></span>
                                    {/if}

                                    {* Start: Code added by Anshul for date field *}
                                    {if $field['type'] eq "date"}                         
                                        <label class="cursor_help" title="{$field['field_help_text']}">{$field['field_label']}{if $field['required'] eq "1"}<span style="display:inline;" class="supercheckout-required">*</span>{/if}</label>
                                        <input style="position: relative;" type="text" id="" name="custom_fields[field_{$field['id_velsof_supercheckout_custom_fields']}]" value="{$field['default_value']}" class="supercheckout-large-field width_100 kb_sc_custom_field_date form-control" readonly="true">
                                        <span id="error_field_{$field['id_velsof_supercheckout_custom_fields']}" class="errorsmall_custom hidden_custom"></span>
                                        {if isset($field['validation_type']) && $field['validation_type'] == 'isDate'}
                                            <span style="color:#999999">
                                                {l s='Date format is Y-m-d' mod='supercheckout'}
                                            </span>
                                        {/if}
                                    {/if}
                                    {* Code added by Anshul for date field *}

                                    {* Start: Code added by Anshul for file field *}
                                    {if $field['type'] eq "file"}                         
                                        <label class="cursor_help" title="{$field['field_help_text']}">{$field['field_label']}{if $field['required'] eq "1"}<span style="display:inline;" class="supercheckout-required">*</span>{/if}</label>
                                        <input type="file" data-buttonText="{l s='Choose file' mod='supercheckout'}" id="kb_sc_custom_field_file_{$field['id_velsof_supercheckout_custom_fields']}" name="custom_fields[field_{$field['id_velsof_supercheckout_custom_fields']}]" value="{$field['default_value']}" class="supercheckout-large-field width_100 kbfiletype form-control">
                                        <span id="error_field_{$field['id_velsof_supercheckout_custom_fields']}" class="errorsmall_custom hidden_custom"></span>
                                        {if isset($field['validation_type']) && $field['validation_type'] == 'isFile'}
                                            <span style="color:#999999">
                                                {l s='Supported file formats are PDF, JPEG, PNG, DOCX, CSV & GIF.' mod='supercheckout'}
                                            </span>
                                        {/if}
                                    {/if}
                                    {* Code added by Anshul for file field *}
                                </div>
                {/if}
            {/foreach}
        </div>
        <!-- End - Code to insert custom fields in registration form block -->

    </div>
{else}
    {if isset($mobileLoginActive) && ($mobileLoginActive eq 1)}
        <div class="form-group" style="text-align: center;">
            <a href="javascript:void(0)" onclick="showMobileLoginPopup()">{l s='Login/Register with mobile' mod='supercheckout'}</a>
        </div>
    {/if}
    <div id="supercheckout-option" class="form-group login-options text-center" style="display:block">
        <div class="radio-inline">
            {if $settings['checkout_option'] eq 0}
                <input type="radio" name="checkout_option" value="0" id="logged_checkout" checked>
            {else}
                <input type="radio" name="checkout_option" value="0" id="logged_checkout">
            {/if}
            <label for="logged_checkout" >{l s='Login' mod='supercheckout'}</label>
        </div>
        {if $settings['enable_guest_checkout'] eq 1 && $guest_enable_by_system}
            <div class="radio-inline">
                {if $settings['checkout_option'] eq 1 || ($settings['enable_guest_checkout'] eq 0 && $settings['checkout_option'] eq 1)}
                    <input type="radio" name="checkout_option" value="1" id="guest_checkout" checked>
                {else}
                    <input type="radio" name="checkout_option" value="1" id="guest_checkout">
                {/if}
                <label for="guest_checkout">{l s='Guest' mod='supercheckout'}</label>
            </div>
        {/if}
        <div class="radio-inline">
            {if $settings['checkout_option'] eq 2 || ($settings['enable_guest_checkout'] eq 0 && $settings['checkout_option'] eq 1)}
                <input type="radio" name="checkout_option" value="2" id="register_checkout" checked>
            {else}
                <input type="radio" name="checkout_option" value="2" id="register_checkout">
            {/if}
            <label for="register_checkout">{l s='Register' mod='supercheckout'}</label>
        </div>
    </div>

    <div class="login-form">
        <div class="form-group supercheckout-extra-wrap">
            <!--label class="control-label" for="input-email">E-Mail</label-->
            <input type="text" name="supercheckout_email" value="" placeholder="{l s='E-Mail' mod='supercheckout'}" id="email" class="form-control supercheckout-large-field">
        </div>
        <div id="supercheckout-login-box" class="form-group" style="display:{if $settings['checkout_option'] eq 0}block{else}none{/if};">
            <div id="supercheckout-login-password-box" class="supercheckout-extra-wrap">
                <input type="password" name="supercheckout_password" value="" placeholder="{l s='Password' mod='supercheckout'}" onkeydown="checkAction(event)" id="password" class="form-control margin-b15 supercheckout-large-field">
            </div>
            <div id="forgotpasswordlink"><a href="{$forgotten_link}" class="label-link pull-right">{l s='Forgotten Password' mod='supercheckout'}</a></div>
            <input type="hidden" name="SubmitLogin" value="SubmitLogin" />
            <!---->
            <p class="loginBtn"><input type="button" value="{l s='Login' mod='supercheckout'}" id="button-login" data-loading-text="Loading..." class="btn btn-primary orangebuttonsmall"><img src="{$module_image_path}loading12.gif" style="display:none;"/></p>
        </div>

    </div>

    <!-- INSERT INTO #REGISTRATION FORM -->    
    <!-- Start - Code to insert custom fields in registration form block -->
     <div class="div_custom_fields">
                        {foreach from=$array_fields item=field}
                            {if $field['position'] eq 'customer_registration_block'}
                                <div class="supercheckout-blocks form-group">
                                    {if $field['type'] eq "textbox"}
                                        <label class="cursor_help" title="{$field['field_help_text']}">{$field['field_label']}{if $field['required'] eq "1"}<span style="display:inline;" class="supercheckout-required">*</span>{/if}</label>
                                        <input type="text" name="custom_fields[field_{$field['id_velsof_supercheckout_custom_fields']}]" value="{$field['default_value']}" class="supercheckout-large-field width_100 form-control">
                                        <span id="error_field_{$field['id_velsof_supercheckout_custom_fields']}" class="errorsmall_custom hidden_custom"></span>
                                    {/if}

                                    {if $field['type'] eq "textarea"}
                                        <label class="cursor_help" title="{$field['field_help_text']}">{$field['field_label']}{if $field['required'] eq "1"}<span style="display:inline;" class="supercheckout-required">*</span>{/if}</label>
                                        <textarea name="custom_fields[field_{$field['id_velsof_supercheckout_custom_fields']}]" class="supercheckout-large-field width_100 form-control" style="width: 100%; height: 100px;">{$field['default_value']}</textarea>
                                        <span id="error_field_{$field['id_velsof_supercheckout_custom_fields']}" class="errorsmall_custom hidden_custom"></span>
                                    {/if}

                                    {if $field['type'] eq "selectbox"}
                                        <label class="cursor_help" title="{$field['field_help_text']}">{$field['field_label']}{if $field['required'] eq "1"}<span style="display:inline;" class="supercheckout-required">*</span>{/if}</label>
                                        <select name="custom_fields[field_{$field['id_velsof_supercheckout_custom_fields']}]" class="supercheckout-large-field width_100 form-control">
                                            {foreach from=$field['options'] item=field_options}
                                                <option {if $field_options['default_value'] eq $field_options['option_value']}selected{/if} value="{$field_options['option_value']}">{$field_options['option_label']}</option>
                                            {/foreach}
                                        </select>
                                        <span id="error_field_{$field['id_velsof_supercheckout_custom_fields']}" class="errorsmall_custom hidden_custom"></span>
                                    {/if}

                                    {if $field['type'] eq "radio"}
                                        <label class="cursor_help" title="{$field['field_help_text']}">{$field['field_label']}{if $field['required'] eq "1"}<span style="display:inline;" class="supercheckout-required">*</span>{/if}</label>
                                        {assign var=radio_counter value=1}
                                        {foreach from=$field['options'] item=field_options}
                                            <div class="supercheckout-extra-wrap">
                                                <div class="radio" id="uniform-field_{$field['id_velsof_supercheckout_custom_fields']}"><span>
                                                        <input type="radio" name="custom_fields[field_{$field['id_velsof_supercheckout_custom_fields']}]" value="{$field_options['option_value']}" {if $field_options['default_value'] eq $field_options['option_value']}checked{/if}>
                                                        <label for="field_{$field['id_velsof_supercheckout_custom_fields']}">{$field_options['option_label']}</label>
                                                    </span></div>
                                                
                                            </div>
                                            {assign var=radio_counter value=$radio_counter+1}
                                        {/foreach}
                                        <span id="error_field_{$field['id_velsof_supercheckout_custom_fields']}" class="errorsmall_custom hidden_custom"></span>
                                    {/if}

                                    {if $field['type'] eq "checkbox"}
                                        <label class="cursor_help" title="{$field['field_help_text']}">{$field['field_label']}{if $field['required'] eq "1"}<span style="display:inline;" class="supercheckout-required">*</span>{/if}</label>
                                        {foreach from=$field['options'] item=field_options}
                                            <div class="input-box input-field_{$field['id_velsof_supercheckout_custom_fields']}">
                                                <div class="checker checkbox" id="uniform-field_{$field['id_velsof_supercheckout_custom_fields']}">
                                                    <span class="checked">
                                                        <input {if $field_options['default_value'] eq $field_options['option_value']}checked{/if} type="checkbox" name="custom_fields[field_{$field['id_velsof_supercheckout_custom_fields']}][]" value="{$field_options['option_value']}">
                                                        <label for="field_{$field['id_velsof_supercheckout_custom_fields']}"><b>{$field_options['option_label']}</b></label>
                                                    </span>
                                                </div>
                                                
                                            </div>
                                        {/foreach}
                                        <span id="error_field_{$field['id_velsof_supercheckout_custom_fields']}" class="errorsmall_custom hidden_custom"></span>
                                    {/if}

                                    {* Start: Code added by Anshul for date field *}
                                    {if $field['type'] eq "date"}                         
                                        <label class="cursor_help" title="{$field['field_help_text']}">{$field['field_label']}{if $field['required'] eq "1"}<span style="display:inline;" class="supercheckout-required">*</span>{/if}</label>
                                        <input style="position: relative;" type="text" id="" name="custom_fields[field_{$field['id_velsof_supercheckout_custom_fields']}]" value="{$field['default_value']}" class="supercheckout-large-field width_100 kb_sc_custom_field_date form-control" readonly="true">
                                        <span id="error_field_{$field['id_velsof_supercheckout_custom_fields']}" class="errorsmall_custom hidden_custom"></span>
                                        {if isset($field['validation_type']) && $field['validation_type'] == 'isDate'}
                                            <span style="color:#999999">
                                                {l s='Date format is Y-m-d' mod='supercheckout'}
                                            </span>
                                        {/if}
                                    {/if}
                                    {* Code added by Anshul for date field *}

                                    {* Start: Code added by Anshul for file field *}
                                    {if $field['type'] eq "file"}                         
                                        <label class="cursor_help" title="{$field['field_help_text']}">{$field['field_label']}{if $field['required'] eq "1"}<span style="display:inline;" class="supercheckout-required">*</span>{/if}</label>
                                        <input type="file" data-buttonText="{l s='Choose file' mod='supercheckout'}" id="kb_sc_custom_field_file_{$field['id_velsof_supercheckout_custom_fields']}" name="custom_fields[field_{$field['id_velsof_supercheckout_custom_fields']}]" value="{$field['default_value']}" class="supercheckout-large-field width_100 kbfiletype form-control">
                                        <span id="error_field_{$field['id_velsof_supercheckout_custom_fields']}" class="errorsmall_custom hidden_custom"></span>
                                        {if isset($field['validation_type']) && $field['validation_type'] == 'isFile'}
                                            <span style="color:#999999">
                                                {l s='Supported file formats are PDF, JPEG, PNG, DOCX, CSV & GIF.' mod='supercheckout'}
                                            </span>
                                        {/if}
                                    {/if}
                                    {* Code added by Anshul for file field *}
                                </div>
                            {/if}
                        {/foreach}
                    </div>
    <!-- End - Code to insert custom fields in registration form block -->


    <div class="register-section" id="supercheckout-new-customer-form" style="display:{if $settings['checkout_option'] neq 0}block{else}none{/if};">
        <table id="customer_person_information_table" class="supercheckout-form" style="margin-bottom:0 !important;width:100%;">
            <tr id="new_customer_password" class="sort_data register_type"  data-percentage="0" style="display:{if $settings['checkout_option'] eq 2}block{else}none{/if};" >
                <td style="display: block;">
                    <div class="form-group ">
                        <input type="password" name="customer_personal[password]" value="" placeholder="{l s='Password' mod='supercheckout'}" id="password" class="form-control margin-b15">		
                    </div>
                </td>
            </tr>
            {assign var="counter" value="0"}
            {foreach from=$settings['customer_personal'] key='cus_per_info' item='cus_info_field'}
                {if $settings['customer_personal'][$cus_per_info][$user_type]['display'] eq 1}
                    {assign var=counter value=$counter+1}
                    <tr class="sort_data"  data-percentage="{$settings['customer_personal'][$cus_per_info]['sort_order']|intval}" >
                        <td>
                            {if $cus_per_info eq 'id_gender'}
                                <div>{l s={$settings['customer_personal'][$cus_per_info]['title']} mod='supercheckout'}:<span style="display:{if $settings['customer_personal'][$cus_per_info][$user_type]['require'] eq 1}inline{else}none{/if};" class="supercheckout-required">*</span></div>
                                <div class="supercheckout_personal_id_gender inline-fields supercheckout-large-field">
                                    <div class="">                                                        
                                        {foreach from=$genders key=k item=gender}
                                            <div class="radio-inline">
                                                <div class="radio"><input type="radio" name="customer_personal[id_gender]" value="{$gender->id|intval}" id="customer_gender_{$gender->id|intval}" checked="checked"/>
                                                    <label for="customer_gender_{$gender->id|intval}">{$gender->name|escape:'htmlall':'UTF-8'}</label>
                                                </div>
                                            </div>
                                        {/foreach}
                                    </div>
                                </div>
                            {else if $cus_per_info eq 'dob'}
                                <div style="margin-right: 18px;">{l s={$settings['customer_personal'][$cus_per_info]['title']} mod='supercheckout'}:<span style="display:{if $settings['customer_personal'][$cus_per_info][$user_type]['require'] eq 1}inline{else}none{/if};" class="supercheckout-required">*</span></div>                                                    
                                <div class="supercheckout_personal_dob inline-fields supercheckout-large-field">
                                    <div class="" style="display: inline-block;width: 100%;">
                                        <div class="" style="display: inline-block;">
                                            <select name="customer_personal[dob_days]">
                                                <option value="">--</option>
                                                {foreach from=$days item='day'}
                                                    <option value="{$day|intval}">{$day|intval}</option>
                                                {/foreach}
                                            </select>
                                        </div>
                                            <div class="" style="display: inline-block;">
                                            <select name="customer_personal[dob_months]">
                                                <option value="">--</option>
                                                {foreach from=$months key=month_value item=month_name}
                                                    <option value="{$month_value}">{$month_name}</option>
                                                {/foreach}
                                            </select>
                                        </div>
                                            <div class="" style="display: inline-block;">
                                            <select name="customer_personal[dob_years]">
                                                <option value="">--</option>
                                                {foreach from=$years item='year'}
                                                    <option value="{$year}">{$year}</option>
                                                {/foreach}
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            {/if}
                        </td>
                    </tr>
                {/if}
            {/foreach}
            {foreach from=$settings['customer_subscription'] key='cus_subs_info' item='cus_info_field'}
                {if $settings['customer_subscription'][$cus_subs_info]['guest']['display'] eq 1}
                    <tr class="sort_data"  data-percentage="{$settings['customer_subscription'][$cus_subs_info]['sort_order'] + {$counter}|intval}" >
                        <td>
                            <div class="input-box" >
                                <input type="checkbox" class="supercheckout_offers_option" name="customer_personal[{$cus_subs_info}]" id="customer_personal_{$cus_subs_info}"  {if $settings['customer_subscription'][$cus_subs_info]['guest']['checked'] eq 1}checked="checked"{/if} >
                                <label for="customer_personal_{$cus_subs_info}">{l s=$settings['customer_subscription'][$cus_subs_info]['title'] mod='supercheckout'}</label>
                            </div>
                        </td>
                    </tr>
                {/if}
            {/foreach}
        </table>
    </div>
    <div id="social_login_block" style="display: none;">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">	
        {if $settings['fb_login']['enable'] neq 1 && $settings['google_login']['enable'] neq 1}
                <div class="social-head">
                    <span>{l s='OR' mod='supercheckout'}</span>

                </div>
                <h4 class="vss_socialloginizer_buttons" id="ivss_socialloginizer_buttons">{l s='Sign in with' mod='supercheckout'}</h4>
                    {if isset($show_on_supercheckout) && $show_on_supercheckout['status'] == 1}
                        {hook h='customLoginizerShortCodeHook' id_layout="{$show_on_supercheckout['layout']}"}
                        {*{else if isset($show_on_supercheckout) && $show_on_supercheckout == 'large_buttons'}
                        {hook h='customLoginizerShortCodeHook' size='large'}*}
                    {/if}
                {/if}
                {if $settings['fb_login']['enable'] eq 1 || $settings['google_login']['enable'] eq 1}
            <div class="social-login">
                <div class="social-head">
                    <span>{l s='OR' mod='supercheckout'}</span>

                </div>
                <h4>{l s='Sign in with' mod='supercheckout'}</h4>
                {if $settings['fb_login']['enable'] eq 1}
                    {if $settings['social_login_popup']['enable'] eq 1}
                        <a onclick="return !window.open(this.href, 'popup', 'width=450,height=300,left=500,top=500')" target="_blank" href="{$supercheckout_url|escape:'quotes'}&myfbLogin" class="fbButton btn azm-social azm-btn azm-pill azm-facebook" id="fb-auth" ><i class="fab fa-facebook-f"></i>  Facebook</a>
                    {else}
                        <a href="{$supercheckout_url}&myfbLogin" class="fbButton btn azm-social azm-btn azm-pill azm-facebook" id="fb-auth" ><i class="fab fa-facebook-f"></i> Facebook</a>
                    {/if}
                {/if}
                {if $settings['google_login']['enable'] eq 1}
                    {if $settings['social_login_popup']['enable'] eq 1}
                        <a onclick="return !window.open(this.href, 'popup', 'width=500,height=500,left=500,top=500')" target="_blank" href="{$supercheckout_url|escape:'quotes'}&myGoogleLogin" class="googleButton btn azm-social azm-btn azm-pill azm-google-plus" ><i class="fab fa-google-plus-g"></i> Google+</a>
                    {else}
                        <a href="{$supercheckout_url}&myGoogleLogin" class="googleButton btn azm-social azm-btn azm-pill azm-google-plus" ><i class="fab fa-google-plus-g"></i> Google+</a>
                    {/if}
                {/if}
                <div class="supercheckout-clear"></div>
            </div>
        </div>

        <div class="socialNetwork">

        {/if}
    </div>
{/if}
<div style="display: none;">
    <label>{l s='Title' mod='supercheckout'}</label>
    <label>{l s='DOB' mod='supercheckout'}</label>
    <label>{l s='Sign up for NewsLetter' mod='supercheckout'}</label>
    <label>{l s='Special Offer' mod='supercheckout'}</label>
</div>
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