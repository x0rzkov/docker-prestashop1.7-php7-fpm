
<div class="supercheckout-checkout-content"></div>
{*<ul>
<li>
<p class="supercheckout-numbers supercheckout-numbers-ship">{l s='Delivery Address' mod='supercheckout'}</p>
</li>
</ul>*}

        <h2>{l s='Delivery Address' mod='supercheckout'} <i class="fas fa-file-invoice"></i></h2>
  
{if !isset($guest_information)}
    {if $customer.addresses}
        <ul>
            <li>
                <div class="supercheckout-extra-wrap radio">
                    <input type="radio" name="shipping_address_value" value="0" id="shipping-address-existing" checked="checked" />
                    <label for="shipping-address-existing">{l s='Use Existing Address' mod='supercheckout'}</label>
                </div>
                <div id="shipping-existing" class="styled-select">
                    <select name="shipping_address_id" class="form-control" style="">
                        {foreach from=$customer.addresses item='shipping_addr'}                                
                            <option value="{$shipping_addr['id']}" {if $shipping_addr['id'] == $id_address_delivery} selected="selected"{/if}>{$shipping_addr['alias']}</option>
                        {/foreach}
                    </select>
                    <!-- Start: Code added by Anshul for adding update & remove address -->
                    <div id="kb_delivery_update_block" class="actionAddress">
                        <span id="delivery_address_edit" class="kb_edit_btn btn btn-warning" onclick="updateAddressForm('delivery');"><i class="fa fa-edit"></i> {l s='Update Address' mod='supercheckout'}</span>
                        <span id="delivery_address_delete" class="kb_delete_btn btn btn-danger" onclick="deleteAddressForm('delivery');"><i class="fa fa-trash"></i> {l s='Remove Address' mod='supercheckout'}</span>
                    </div>
                    <!-- End: Code added by Anshul for adding update & remove address -->
                    <div id="delivery_address_detail" class="supercheckout_address_detail card userAddressData"><div class="card-body">

                        </div>
                    </div>
            </li>
            <li>

                <div class="supercheckout-extra-wrap radio">
                    <p>
                        <input type="radio" name="shipping_address_value" value="1" id="shipping-address-new" />
                        <label for="shipping-address-new">{l s='Use New Address' mod='supercheckout'}</label>
                    </p>
                </div>
            </li>
        </ul>
    {/if}
{/if}
<div class="opc_shipping_address custom-panel">

    <div id="shipping-new" style="display: {if isset($guest_information)}block{else if $customer.addresses}none{else}block{/if};">
        {*            <div class="supercheckout-form billing-form" id="shipping_address_table" >*}  {*Need to change later*}
        {assign var='display_row' value=''}
        {assign var='google_region_type' value=''}
        {foreach from=$settings['shipping_address'] key='p_address_key' item='p_address_field'}
            {$display_row = ''}
            {$google_region_type = ''}
            {if $settings['shipping_address'][$p_address_key][$user_type]['display'] eq 1 || (isset($settings['shipping_address'][$p_address_key]['conditional']) && $settings['shipping_address'][$p_address_key]['conditional'] eq 1)}
                {if $p_address_key eq 'dni' && !$need_dni}
                    {$display_row = 'display:none;'}
                {else if $p_address_key eq 'dni' && $settings['shipping_address'][$p_address_key][$user_type]['display'] == 0}
                    {$display_row = 'display:none;'}
                {/if}
                {if $p_address_key eq 'vat_number' && $settings['shipping_address'][$p_address_key][$user_type]['display'] == 0}
                    {$display_row = 'display:none;'}
                {/if}
                {if ($p_address_key eq 'postcode' || $p_address_key eq 'id_country' || $p_address_key eq 'id_state' || $p_address_key eq 'alias') && !$settings['shipping_address'][$p_address_key][$user_type]['require'] && !$settings['shipping_address'][$p_address_key][$user_type]['display']}
                    {$display_row = 'display:none;'}
                {/if}
                {if $p_address_key eq 'postcode'}
                    {$google_region_type='postal_code'}
                {else if $p_address_key eq 'address1'}
                    {$google_region_type='street_number'}
                {else if $p_address_key eq 'address2'}
                    {$google_region_type='route'}
                {else if $p_address_key eq 'city'}
                    {$google_region_type='locality'}
                {else if $p_address_key eq 'id_country'}
                    {$google_region_type='country'}
                {else if $p_address_key eq 'id_state'}
                    {$google_region_type='administrative_area_level_1'}
                {/if}
                {if $p_address_key eq 'id_state'}
                    <script>var show_shipping_state = {$settings['shipping_address'][$p_address_key][$user_type]['display']};</script>
                {/if}
                {if $p_address_key eq 'postcode'}
                    <script>var show_shipping_postcode = {$settings['shipping_address'][$p_address_key][$user_type]['display']};</script>

                {/if}


                {if $settings['shipping_address'][$p_address_key]['html_format'] == 1}
                    {if $p_address_key eq 'postcode'}
                        <div class="row sort_data" id="shipping_post_code" data-percentage="{$settings['shipping_address'][$p_address_key]['sort_order']}" style="{$display_row}">
                        {else}
                            <div class="row sort_data" data-percentage="{$settings['shipping_address'][$p_address_key]['sort_order']}" style="{$display_row}">
                            {/if}
                            <div class="col-sm-6">
                                <div class="form-group ">
                                    <label class="control-label" for="shipping_address[{$p_address_key}]">{l s={$settings['shipping_address'][$p_address_key]['title']} mod='supercheckout'}: 
                                        {if $p_address_key eq 'vat_number'}
                                            <span style="display:{if $need_vat}inline{else}none{/if};" class="supercheckout-required">*</span>
                                        {else}
                                            <span style="display:{if $settings['shipping_address'][$p_address_key][$user_type]['require'] eq 1}inline{else}none{/if};" class="supercheckout-required">*</span>
                                        {/if}
                                    </label>
                                    {if $p_address_key eq 'id_country' || $p_address_key eq 'id_state'}
                                        <input type='hidden' class="{$google_region_type|escape:'htmlall':'UTF-8'}"/>
                                        <select name="shipping_address[{$p_address_key}]" class="supercheckout-large-field form-control" {if $p_address_key eq 'id_country'} onchange="restrictAutofillbyCountry(this)" {/if}>
                                            {if $p_address_key eq 'id_country'}
                                                <option value="0">--</option>
                                                {foreach from=$countries item='country'}
                                                    <option value="{$country['id_country']}"  id='{$country['iso_code']}' {if $country['id_country'] == $default_country} selected="selected"{/if}>{$country['name']}</option>                                        
                                                {/foreach}
                                            {else}
                                                <option value="0">{l s='Select State' mod='supercheckout'}</option>
                                            {/if}                            
                                        </select>
                                    {else if  $p_address_key eq 'other'}
                                        <input type="text" name="shipping_address[{$p_address_key}]" value="" class="supercheckout-large-field form-control">
                                    {else}

                                        {if $settings['google_auto_address']['enable'] eq 1}
                                            <input autocomplete="off" type="text"  {if $p_address_key eq 'address1'|| $p_address_key eq 'address2'} placeholder="{l s='Enter a location' mod='supercheckout'}" id='shipping_address_{$p_address_key|escape:'htmlall':'UTF-8'}'onFocus="geolocate()"{/if} name="shipping_address[{$p_address_key|escape:'htmlall':'UTF-8'}]" value="" class="form-control supercheckout-large-field {$google_region_type|escape:'htmlall':'UTF-8'} {if $p_address_key eq 'address1'|| $p_address_key eq 'address2'}autocomplete{/if}" />
                                        {else}
                                            <input autocomplete="off" type="text" name="shipping_address[{$p_address_key|escape:'htmlall':'UTF-8'}]" value="" class="supercheckout-large-field form-control" />
                                        {/if}
                                    {/if}
                                </div>
                            </div>

                        {elseif $settings['shipping_address'][$p_address_key]['html_format'] == 2}
                            {*{if $p_address_key eq 'postcode'}
                            <tr class="sort_data" id="shipping_post_code" data-percentage="{$settings['shipping_address'][$p_address_key]['sort_order']}" style="{$display_row}" >
                            {else}
                            <tr class="sort_data" data-percentage="{$settings['shipping_address'][$p_address_key]['sort_order']}" style="{$display_row}" >
                            {/if}*}
                            <div class="col-sm-6">
                                <div class="form-group ">
                                    <label class="control-label" for="shipping_address[{$p_address_key}]">{l s={$settings['shipping_address'][$p_address_key]['title']} mod='supercheckout'}: 
                                        {if $p_address_key eq 'vat_number'}
                                            <span style="display:{if $need_vat}inline{else}none{/if};" class="supercheckout-required">*</span>
                                        {else}
                                            <span style="display:{if $settings['shipping_address'][$p_address_key][$user_type]['require'] eq 1}inline{else}none{/if};" class="supercheckout-required">*</span>
                                        {/if}
                                    </label>
                                    {if $p_address_key eq 'id_country' || $p_address_key eq 'id_state'}
                                        <input type='hidden' class="{$google_region_type|escape:'htmlall':'UTF-8'}"/>
                                        <select name="shipping_address[{$p_address_key}]" class="supercheckout-large-field form-control" {if $p_address_key eq 'id_country'} onchange="restrictAutofillbyCountry(this)" {/if}>
                                            {if $p_address_key eq 'id_country'}
                                                <option value="0">--</option>
                                                {foreach from=$countries item='country'}
                                                    <option value="{$country['id_country']}"  id='{$country['iso_code']}' {if $country['id_country'] == $default_country} selected="selected"{/if}>{$country['name']}</option>                                        
                                                {/foreach}
                                            {else}
                                                <option value="0">{l s='Select State' mod='supercheckout'}</option>
                                            {/if}                            
                                        </select>
                                    {else if  $p_address_key eq 'other'}
                                        <input type="text" name="shipping_address[{$p_address_key}]" value="" class="supercheckout-large-field form-control">
                                    {else}

                                        {if $settings['google_auto_address']['enable'] eq 1}
                                            <input autocomplete="off" type="text"  {if $p_address_key eq 'address1'|| $p_address_key eq 'address2'} placeholder="{l s='Enter a location' mod='supercheckout'}" id='shipping_address_{$p_address_key|escape:'htmlall':'UTF-8'}'onFocus="geolocate()"{/if} name="shipping_address[{$p_address_key|escape:'htmlall':'UTF-8'}]" value="" class="form-control supercheckout-large-field {$google_region_type|escape:'htmlall':'UTF-8'} {if $p_address_key eq 'address1'|| $p_address_key eq 'address2'}autocomplete{/if}" />
                                        {else}
                                            <input autocomplete="off" type="text" name="shipping_address[{$p_address_key|escape:'htmlall':'UTF-8'}]" value="" class="supercheckout-large-field form-control" />
                                        {/if}
                                    {/if}
                                </div>
                            </div>
                        </div>


                    {else}
                        {if $p_address_key eq 'postcode'}
                            <div class="sort_data" id="shipping_post_code" data-percentage="{$settings['shipping_address'][$p_address_key]['sort_order']}" style="{$display_row}">
                            {else}
                                <div class="sort_data" data-percentage="{$settings['shipping_address'][$p_address_key]['sort_order']}" style="{$display_row}">
                                {/if}
                                <div class="form-group ">
                                    <label class="control-label" for="shipping_address[{$p_address_key}]">{l s={$settings['shipping_address'][$p_address_key]['title']} mod='supercheckout'}: 
                                        {if $p_address_key eq 'vat_number'}
                                            <span style="display:{if $need_vat}inline{else}none{/if};" class="supercheckout-required">*</span>
                                        {else}
                                            <span style="display:{if $settings['shipping_address'][$p_address_key][$user_type]['require'] eq 1}inline{else}none{/if};" class="supercheckout-required">*</span>
                                        {/if}
                                    </label>
                                    {if $p_address_key eq 'id_country' || $p_address_key eq 'id_state'}
                                        <input type='hidden' class="{$google_region_type|escape:'htmlall':'UTF-8'}"/>
                                        <select name="shipping_address[{$p_address_key}]" class="supercheckout-large-field form-control" {if $p_address_key eq 'id_country'} onchange="restrictAutofillbyCountry(this)" {/if}>
                                            {if $p_address_key eq 'id_country'}
                                                <option value="0">--</option>
                                                {foreach from=$countries item='country'}
                                                    <option value="{$country['id_country']}"  id='{$country['iso_code']}' {if $country['id_country'] == $default_country} selected="selected"{/if}>{$country['name']}</option>                                        
                                                {/foreach}
                                            {else}
                                                <option value="0">{l s='Select State' mod='supercheckout'}</option>
                                            {/if}                            
                                        </select>
                                    {else if  $p_address_key eq 'other'}
                                        <input type="text" name="shipping_address[{$p_address_key}]" value="" class="supercheckout-large-field form-control">
                                    {else}

                                        {if $settings['google_auto_address']['enable'] eq 1}
                                            <input autocomplete="off" type="text"  {if $p_address_key eq 'address1'|| $p_address_key eq 'address2'} placeholder="{l s='Enter a location' mod='supercheckout'}" id='shipping_address_{$p_address_key|escape:'htmlall':'UTF-8'}'onFocus="geolocate()"{/if} name="shipping_address[{$p_address_key|escape:'htmlall':'UTF-8'}]" value="" class="form-control supercheckout-large-field {$google_region_type|escape:'htmlall':'UTF-8'} {if $p_address_key eq 'address1'|| $p_address_key eq 'address2'}autocomplete{/if}" />
                                        {else}
                                            <input autocomplete="off" type="text" name="shipping_address[{$p_address_key|escape:'htmlall':'UTF-8'}]" value="" class="supercheckout-large-field form-control" />
                                        {/if}
                                    {/if}
                                </div>
                            </div>
                        {/if}
                    {/if}
                {/foreach}    
            </div>
            {*                </div>*}
        </div>
        <!-- INSERT INTO #SHIPPING ADDRESS FORM -->
        <!-- Start - Code to insert custom fields in shipping address form block -->
          <div class="div_custom_fields">
                        {foreach from=$array_fields item=field}
                            {if $field['position'] eq 'shipping_address_form'}
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
        <!-- End - Code to insert custom fields in shipping address form block -->

        <ul>
            <li>
                <div class="input-box input-different-shipping" {if !$settings['show_use_delivery_for_payment_add'][$user_type]} style="display:none;" {/if}>
                    <input type="checkbox" name="use_for_invoice" id="use_for_invoice" 
                           {if $settings['use_delivery_for_payment_add'][$user_type]}checked="checked"{/if}
                           >
                    <label for="use_for_invoice"><b>{l s='Same invoice address' mod='supercheckout'}</b></label>
                </div>
            </li>
        </ul>

        <div style="display: none;">
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