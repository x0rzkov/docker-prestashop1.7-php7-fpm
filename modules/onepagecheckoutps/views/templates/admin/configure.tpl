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

{*removeIf(addons)*}
{if !Module::getInstanceByName('onepagecheckoutps')->core->checkModulePTS()}
<div class="row">
    <div class="col-xs-12">
        <div class="col-xs-12" style="background-color: #ffffff; padding: 35px 0;">
            <div id="pts_register_product" class="clearfix pull-left col-xs-12 col-xs-offset-0 col-sm-10 col-sm-offset-1 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
                <form id="frm_register_product" name="frm_register_product" method="post" class="form-horizontal" role="form">
                    <div class="row form-validate">
                        <div class="input-group">
                            <input type="text" class="form-control" id="txt_license_number" name="license_number" placeholder="{l s='Validate your license' mod='onepagecheckoutps'}">
                            <span class="input-group-btn">
                                <button class="btn btn-primary btn-sm" id="btn_validate_license" name="validate_license">
                                    {l s='Validate' mod='onepagecheckoutps'}
                                </button>
                            </span>
                        </div>
                        <br />
                        <div class="col-xs-12 alert-info" style="padding: 5px 10px;">
                            <label style="line-height: 20px; font-size: 13px; padding-top: 5px;">{l s='Get your license' mod='onepagecheckoutps'}&nbsp;<a target="_blank" href="https://www.presteamshop.com/{if $paramsBack.ISO_LANG_BACKOFFICE_SHOP eq 'es'}es/mis-modulos{else}en/my-modules{/if}">{l s='Here' mod='onepagecheckoutps'}&nbsp;<i class="fa-pts fa-pts-external-link"></i></a></label>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{/if}
{*endRemoveIf(addons)*}
<div id="pts_content" class="pts bootstrap nopadding clear clearfix">
    <div class="pts-overlay" style="display: block;"></div>
    {*removeIf(addons)*}
    {if Module::getInstanceByName('onepagecheckoutps')->core->checkModulePTS()}
    {*endRemoveIf(addons)*}
        <div class="row">
            {if isset($show_saved_message) and $show_saved_message}
                <div id="message_configuration_saved">
                    <br class="clearfix"/>
                    <div class="clearfix col-xs-12">
                        <div class="alert alert-success">
                            {l s='Configuration was saved successful' mod='onepagecheckoutps'}
                        </div>
                    </div>
                </div>
            {/if}
            <div class="clear row-fluid clearfix col-xs-12">
                <div class="col-xs-1 pts-menu-xs visible-xs visible-sm pts-menu">
                    <div class="pts-menu-xs-container"></div>
                </div>
                {include file=$paramsBack.MODULE_TPL|cat:'views/templates/admin/helper/menu.tpl'}
                <div class="col-xs-11 col-md-9 col-lg-10 pts-content">
                    <div class="panel pts-panel nopadding">
                        <div class="panel-heading main-head">
                            <span class="pull-right bold">{l s='Version' mod='onepagecheckoutps'}&nbsp;{$paramsBack.VERSION|escape:'htmlall':'UTF-8'}</span>
                            <span class="pts-content-current-tab">&nbsp;</span>
                        </div>
                        <div class="panel-body">
                            <!-- Tab panes -->
                            <div class="tab-content">
                                {if isset($ANOTHER_MODULES) and file_exists($paramsBack.MODULE_TPL|cat:'views/templates/admin/helper/another_modules.tpl')}
                                    <div class="tab-pane{if (isset($CURRENT_FORM) && $CURRENT_FORM eq 'another_modules')} active{/if}" id="tab-another_modules">
                                        {include file=$paramsBack.MODULE_TPL|cat:'views/templates/admin/helper/another_modules.tpl' modules=$ANOTHER_MODULES}
                                    </div>
                                {/if}
                                {if isset($ADDONS) and file_exists($paramsBack.MODULE_TPL|cat:'views/templates/admin/helper/another_modules.tpl')}
                                    <div class="tab-pane{if (isset($CURRENT_FORM) && $CURRENT_FORM eq 'addons')} active{/if}" id="tab-addons">
                                        {include file=$paramsBack.MODULE_TPL|cat:'views/templates/admin/helper/another_modules.tpl' modules=$ADDONS}
                                    </div>
                                {/if}
                                {if isset($paramsBack.HELPER_FORM)}
                                    {if isset($paramsBack.HELPER_FORM.forms) and is_array($paramsBack.HELPER_FORM.forms) and count($paramsBack.HELPER_FORM.forms)}
                                        {foreach from=$paramsBack.HELPER_FORM.forms key='key' item='form' name='forms'}
                                            {if isset($form.modal) and $form.modal}{assign var='modal' value=1}{else}{assign var='modal' value=0}{/if}
                                            <div class="tab-pane {if (isset($CURRENT_FORM) && $CURRENT_FORM eq $form.tab) || (not isset($CURRENT_FORM) && $smarty.foreach.forms.first)}active{/if}" id="tab-{$form.tab|escape:'htmlall':'UTF-8'}">
                                                <form action="{$paramsBack.ACTION_URL|escape:'htmlall':'UTF-8'}" {if isset($form.method) and $form.method neq 'ajax'}method="{$form.method|escape:'htmlall':'UTF-8'}"{/if}
                                                      class="form form-horizontal clearfix {if isset($form.class)}{$form.class|escape:'htmlall':'UTF-8'}{/if}"
                                                      {if isset($form.id)}id="{$form.id|escape:'htmlall':'UTF-8'}"{/if}
                                                      autocomplete="off">
                                                    <div class="col-xs-12 {if not $modal}col-md-8{/if} content-form pts-content">
                                                        {foreach from=$form.options item='option'}
                                                            <div class="form-group clearfix clear {if isset($option.hide_on) and $option.hide_on}hidden{/if}"
                                                                {if isset($option.data_hide)}data-hide="{$option.data_hide|escape:'htmlall':'UTF-8'}"{/if}
                                                                id="container-{$option.name|escape:'htmlall':'UTF-8'}">
                                                                <div class="row">
                                                                    {if isset($option.label)}
                                                                        <div class="col-xs-{if $modal}3{else}{if $option.type eq $paramsBack.GLOBALS->type_control->checkbox}9 pts-nowrap{else}12{/if} col-sm-6 col-md-5 nopadding-xs{/if}"
                                                                             title="{$option.label|escape:'quotes':'UTF-8'}">
                                                                            <label class="pts-label-tooltip col-xs-12 nopadding control-label">
                                                                                {$option.label|escape:'quotes':'UTF-8'}
                                                                                {if isset($option.tooltip)}
                                                                                    {include file='./helper/tooltip.tpl' option=$option}
                                                                                {/if}
                                                                            </label>
                                                                        </div>
                                                                    {/if}
                                                                    {include file=$paramsBack.MODULE_TPL|cat:'views/templates/admin/helper/form.tpl' option=$option global=$paramsBack.GLOBALS modal=$modal}
                                                                    <div class="clear clearfix"></div>
                                                                </div>
                                                            </div>
                                                        {/foreach}
                                                    </div>
                                                    <div class="col-xs-12 nopadding clear clearfix">
                                                        <hr />
                                                        {include file=$paramsBack.MODULE_TPL|cat:'views/templates/admin/helper/action.tpl' form=$form key=$key modal=$modal}
                                                    </div>
                                                </form>
                                                {if isset($form.list) and is_array($form.list) and count($form.list)}
                                                    {if isset($form.list.headers) and is_array($form.list.headers) and count($form.list.headers)}
                                                        {if $form.tab eq 'required_fields'}
                                                            <div class="clearfix">
                                                                <div class="col-xs-12 col-sm-6 col-md-5 col-lg-3 nopadding-xs">
                                                                    <div class="pull-left col-xs-12 nopadding">
                                                                        <span id="btn-manage_field_options" class="btn btn-default btn-block">
                                                                            <i class="fa-pts fa-pts-list nohover"></i>
                                                                            {l s='Manage field options' mod='onepagecheckoutps'}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xs-12 col-sm-6 col-md-5 col-lg-3 nopadding-xs pull-right">
                                                                    <div class="pull-right pull-left-xs col-xs-12 nopadding">
                                                                        <span id="btn-new_register" class="btn btn-success btn-block">
                                                                            <i class="fa-pts fa-pts-edit nohover"></i>
                                                                            {l s='New custom field' mod='onepagecheckoutps'}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {* Modal options *}
                                                            <form class="form form-horizontal clearfix hidden" id="form_manage_field_options">
                                                                <div class="col-xs-12 nopadding">
                                                                    <div class="row">
                                                                        <div class="col-xs-6">
                                                                            <span>{l s='Object' mod='onepagecheckoutps'}</span>
                                                                        </div>
                                                                        <div class="col-xs-6">
                                                                            <span>{l s='Field' mod='onepagecheckoutps'}</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-xs-6">
                                                                            <select id="lst-manage-object" class="form-control" autocomplete="false"></select>
                                                                        </div>
                                                                        <div class="col-xs-6">
                                                                            <select id="lst-manage-field" class="form-control" disabled autocomplete="false">
                                                                                <option value="">--</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">&nbsp;</div>
                                                                    <div class="col-xs-12 nopadding">
                                                                        <div class="hidden" id="aux_clone_translatable_input">
                                                                            {include file=$paramsBack.MODULE_TPL|cat:'views/templates/admin/helper/input_text_lang.tpl'
                                                                            languages=$paramsBack.LANGUAGES input_name='' input_value=''}
                                                                        </div>
                                                                        <div class="clearfix">
                                                                            <span class="btn btn-success pull-right disabled" id="btn-add_field_option">{l s='Add' mod='onepagecheckoutps'}</span>
                                                                        </div>
                                                                        <table id="table-field-options">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th class="{*col-xs-5 nopadding*}">{l s='Value' mod='onepagecheckoutps'}</th>
                                                                                    <th class="">{l s='Description' mod='onepagecheckoutps'}</th>
                                                                                    <th class="">{l s='Action' mod='onepagecheckoutps'}</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody></tbody>
                                                                        </table>
                                                                    </div>
                                                                    <div class="row">&nbsp;</div>
                                                                    <div class="row">
                                                                        <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
                                                                            <span id="btn-update_field_options" class="btn btn-primary btn-block disabled">
                                                                                <i class="fa-pts fa-pts-save nohover"></i>
                                                                                {l s='Save' mod='onepagecheckoutps'}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                            {*/Modal options *}
                                                        {/if}
                                                        <div class="row">&nbsp;</div>
                                                        <div class="table-responsive">
                                                            <div class="pts-overlay"></div>
                                                            <table class="table table-bordered" id="{$form.list.table|escape:'htmlall':'UTF-8'}">
                                                                <thead>
                                                                    <tr>
                                                                        {foreach from=$form.list.headers item='header_text' key='header'}
                                                                            <th {if $header eq 'actions'}class="col-sm-2 col-md-1 text_center"{/if}>{$header_text|escape:'htmlall':'UTF-8'}</th>
                                                                        {/foreach}
                                                                    </tr>
                                                                </thead>
                                                                <tbody></tbody>
                                                            </table>
                                                        </div>
                                                    {/if}
                                                {/if}
                                            </div>
                                        {/foreach}
                                    {/if}
                                {/if}
                                <div class="tab-pane{if (isset($CURRENT_FORM) && $CURRENT_FORM eq 'ship_pay')} active{/if}" id="tab-ship_pay">
                                    <div class="row">
                                        <div class="clearfix col-xs-12 nopadding-xs">
                                            <div class="alert alert-info">
                                                <b>{l s='Choose the payment methods that are available according to the delivery method. (If you have no restrictions, not select anything to show all payments)' mod='onepagecheckoutps'}</b>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row clearfix">&nbsp;</div>
                                    <div id="ship-pay-container" class="row">
                                        {foreach from=$paramsBack.CARRIERS item='carrier'}
                                            <div class="col-xs-12 col-sm-6 col-md-4 clearfix nopadding-xs">
                                                <div class="panel clearfix panel-primary">
                                                    <div class="panel-heading">
                                                        <span class="panel-title">
                                                            <i class='fa-pts fa-pts-truck fa-pts-1x nohover'></i>&nbsp;{$carrier.name}
                                                        </span>
                                                    </div>
                                                    <div class="panel-body nopadding">
                                                        <div id="carrier_{$carrier.id_reference}" data-id-reference="{$carrier.id_reference}" class="carrier_container">
                                                            {foreach from=$paramsBack.PAYMENTS item='payment'}
                                                                <label class="checkbox-inline">
                                                                    <input type="checkbox" id="payment_{$payment.id_module}_{$carrier.id_reference}" data-id-module="{$payment.id_module}" data-name-payment-module="{$payment.name}">&nbsp;{$payment.name}
                                                                </label>
                                                            {/foreach}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        {/foreach}
                                    </div>
                                    <button type="button" class="btn btn-primary pull-right has-action" id="btn-update_ship_pay">
                                        <i class="fa-pts fa-pts-save nohover"></i>
                                        {l s='Save' mod='onepagecheckoutps'}
                                    </button>
                                </div>
                                <div class="tab-pane{if (isset($CURRENT_FORM) && $CURRENT_FORM eq 'fields_position')} active{/if}" id="tab-fields_position">
                                    <div class="row" id="fields-position">
                                        {foreach from=$paramsBack.FIELDS_POSITION item='rows' key='group_name'}
                                            <div class="col-xs-12 col-md-4">
                                                <div class="row">
                                                    <label>
                                                        {if $group_name eq 'customer'}
                                                            {l s='Customer' mod='onepagecheckoutps'}
                                                        {elseif $group_name eq 'delivery'}
                                                            {l s='Delivery' mod='onepagecheckoutps'}
                                                        {elseif $group_name eq 'invoice'}
                                                            {l s='Invoice' mod='onepagecheckoutps'}
                                                        {/if}
                                                        &nbsp;
                                                        <span class="label label-{if $group_name eq 'customer'}primary{elseif $group_name eq 'delivery'}success{elseif $group_name eq 'invoice'}warning{/if}">&nbsp;</span>
                                                    </label>
                                                </div>
                                                <div class="row">
                                                    <ol class="nested_fields_position list-group col-xs-12" data-group='{$group_name|escape:'htmlall':'UTF-8'}'>
                                                        {foreach from=$rows item='row'}
                                                            <li class="list-group-item li-row">
                                                                <ol class="list-group ol-row">
                                                                    {foreach from=$row item='field'}
                                                                        <li data-field="{$field->id|intval}">
                                                                            <label class="label label-{if $field->object eq 'customer'}primary{elseif $field->object eq 'delivery'}success{elseif $field->object eq 'invoice'}warning{/if}">{$field->description|escape:'htmlall':'UTF-8'}</label>
                                                                        </li>
                                                                    {/foreach}
                                                                </ol>
                                                            </li>
                                                        {/foreach}
                                                    </ol>
                                                </div>
                                            </div>
                                        {/foreach}
                                    </div>
                                </div>
{*                                   Payment methods*}
                                <div class="tab-pane{if (isset($CURRENT_FORM) && $CURRENT_FORM eq 'pay_methods')} active{/if}" id="tab-pay_methods">
                                    {assign var='filename_default' value=$paramsBack.MODULE_IMG|cat:'payments/default.png'}

                                    <div class="col-xs-12 clearfix">&nbsp;</div>
                                    <div class="col-xs-12">
                                        <div class="alert alert-info">
                                            {l s='Here you can configure the images, titles and descriptions of the methods of payment. The recommended images size is 86x49 pixels.' mod='onepagecheckoutps'}
                                        </div>
                                    </div>

                                    <div id="payment-images-container" class="row">
                                        {foreach from=$paramsBack.PAYMENTS item='payment'}
                                            <div class="col-xs-12 col-md-6">
                                                <form autocomplete="off">
                                                    <div class="panel panel-primary">
                                                        <div class="panel-heading">
                                                            <span class="panel-title">
                                                                <i class='fa-pts fa-pts-credit-card fa-pts-1x nohover'></i>&nbsp;{$payment.name|escape:'htmlall':'UTF-8'}
                                                            </span>
                                                        </div>
                                                        <div class="panel-body nopadding">
                                                            <div id="payment_{$payment.name|escape:'htmlall':'UTF-8'}" class="payment_container">
                                                                <div class="col-xs-12">
                                                                    <form class="form-horizontal" role="form">
                                                                        <div class="form-group row">
                                                                            <label class="col-xs-12 col-md-3 control-label">{l s='Title' mod='onepagecheckoutps'}</label>
                                                                            <div class="col-xs-12 col-md-9">
                                                                                {if isset($payment.data.title)}
                                                                                    {assign var='input_value' value=$payment.data.title}{else}{assign var='input_value' value=[]}
                                                                                {/if}
                                                                                {include languages=$paramsBack.LANGUAGES input_name='txt-image_payment_title-'|cat:$payment.name
                                                                                file=$paramsBack.MODULE_TPL|cat:'views/templates/admin/helper/input_text_lang.tpl' input_value=$input_value}
                                                                            </div>
                                                                            <div class="clear clearfix"></div>
                                                                        </div>
                                                                        <div class="form-group row">
                                                                            <label class="col-xs-12 col-md-3 control-label">{l s='Description' mod='onepagecheckoutps'}</label>
                                                                            <div class="col-xs-12 col-md-9">
                                                                                {if isset($payment.data.description)}
                                                                                    {assign var='input_value' value=$payment.data.description}{else}{assign var='input_value' value=[]}
                                                                                {/if}
                                                                                {include languages=$paramsBack.LANGUAGES input_name='ta-image_payment_description-'|cat:$payment.name
                                                                                file=$paramsBack.MODULE_TPL|cat:'views/templates/admin/helper/textarea_lang.tpl' input_value=$input_value}
                                                                            </div>
                                                                            <div class="clear clearfix"></div>
                                                                        </div>
                                                                        {*<div class="form-group row">
                                                                            <label class="pts-label-tooltip col-xs-12 col-md-3 control-label">
                                                                                {l s='Force display' mod='onepagecheckoutps'}
                                                                                <span data-toggle="button popover" data-container="#payment_{$payment.name|escape:'htmlall':'UTF-8'}" class="btn-popover pts-tooltip" type="button" id="tooltip-information-payment_{$payment.name|escape:'htmlall':'UTF-8'}" data-original-title="" title="">
                                                                                    <i class="fa-pts fa-pts-exclamation-triangle nohover"></i>
                                                                                </span>
                                                                                <div class="tooltip-content " id="tooltip-information-payment_{$payment.name|escape:'htmlall':'UTF-8'}-content">
                                                                                    {l s='Just enable this option if the payment method is not shown by any problems of incompatibility.' mod='onepagecheckoutps'}
                                                                                </div>
                                                                            </label>
                                                                            <div class="col-xs-12 col-md-9 simple-switch">
                                                                                <label class="pull-right-xs switch">
                                                                                    <input type="checkbox" class="switch-input" data-switch="force_display"
                                                                                       name="force_display" id="chk-force_display-{$payment.name|escape:'htmlall':'UTF-8'}"
                                                                                       {if $payment.force_display == 1}checked{/if} autocomplete="off">
                                                                                    <span class="switch-label" data-on="{l s='Yes' mod='onepagecheckoutps'}" data-off="{l s='No' mod='onepagecheckoutps'}"></span>
                                                                                    <span class="switch-handle"></span>
                                                                                </label>
                                                                            </div>
                                                                            <div class="clear clearfix"></div>
                                                                        </div>*}
                                                                        <div class="form-group row container-test-ip">
                                                                            <label class="pts-label-tooltip col-xs-12 col-md-3 control-label">
                                                                                {l s='Test mode' mod='onepagecheckoutps'}
                                                                            </label>
                                                                            <div class="col-xs-12 col-md-9 simple-switch">
                                                                                <div class="input-group">
                                                                                    <div class="input-group-addon">
                                                                                        <input name="test_mode" id="chk-test_mode-{$payment.name|escape:'htmlall':'UTF-8'}" type="checkbox" {if $payment.test_mode == 1}checked{/if}>
                                                                                    </div>
                                                                                    <div class="input-group-addon">
                                                                                        {l s='IP\'s' mod='onepagecheckoutps'}
                                                                                    </div>
                                                                                    <input type="text" class="form-control" {if $payment.test_mode == 0}disabled="true"{/if} value="{$payment.test_ip|escape:'htmlall':'UTF-8'}" name="test_ip" id="txt-test_ip-{$payment.name|escape:'htmlall':'UTF-8'}">
                                                                                    <div class="{if $payment.test_mode == 0}disabled{/if} input-group-addon btn btn-primary btn-payment-add-ip">
                                                                                        <i class='fa-pts fa-pts-plus'></i> {l s='Add' mod='onepagecheckoutps'}
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="clear clearfix"></div>
                                                                        </div>
                                                                        <div class="form-group row">
                                                                            <div class="hidden-xs hidden-sm col-md-3">&nbsp;</div>
                                                                            <div class="col-xs-12 col-md-9 pts-image-change-container">
                                                                                {assign var='filename' value=$paramsBack.MODULE_PATH_ABSOLUTE|cat:'/views/img/payments/'|cat:$payment.name_image}
                                                                                {assign var='path_image' value=$paramsBack.MODULE_IMG|cat:'payments/'|cat:$payment.name_image}

                                                                                <img class="img-thumbnail" id="image_payment_{$payment.id_module}" height="49" width="86" src="{if file_exists($filename)}{$path_image}{/if}?{$smarty.now}" />
                                                                                <br />
                                                                                <a href="#" id="remove-image-handler-{$payment.id_module}" class="pts-remove-image-handler col-xs-12 nopadding {if $payment.name_image eq 'no-image.png'}hidden{/if}" data-id-module="{$payment.id_module}" data-name-module="{$payment.name}">
                                                                                    <i class="fa-pts fa-pts-remove"></i>
                                                                                    {l s='Remove image' mod='onepagecheckoutps'}</span>
                                                                                </a>
                                                                                <a href="#" id="change-image-handler-{$payment.id_module}" class="pts-change-image-handler col-xs-12 nopadding" data-id-module="{$payment.id_module}" data-name-module="{$payment.name}">
                                                                                    <i class="fa-pts fa-pts-refresh"></i>
                                                                                    {l s='Upload' mod='onepagecheckoutps'}&nbsp;<span class="pts-change-image-name">{l s='image' mod='onepagecheckoutps'}</span>
                                                                                </a>

                                                                                <input id="file-image_payment-{$payment.id_module}" data-name-module="{$payment.name}" type="file" class="hidden">
                                                                            </div>
                                                                            <div class="clear clearfix"></div>
                                                                        </div>
                                                                        <div class="form-group row">
                                                                            <div class="col-xs-12 col-md-6 col-lg-4 col-md-push-3">
                                                                                <button id="btn-save_image_payment-{$payment.name}" data-id-module="{$payment.id_module}" data-name-module="{$payment.name}" class="btn btn-primary btn-block save-image-payment has-action">
                                                                                    <i class="fa-pts fa-pts-save nohover"></i>
                                                                                    {l s='Save' mod='onepagecheckoutps'}
                                                                                </button>
                                                                            </div>
                                                                            <div class="clear clearfix"></div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        {/foreach}
                                    </div>
                                </div>
{*                                SOCIAL LOGIN FORMS*}
                                {foreach from=$paramsBack.SOCIAL_LOGIN item='social_network' key='name_social_network'}
                                    <div class="tab-pane{if (isset($CURRENT_FORM) && $CURRENT_FORM eq 'social_login_'|cat:$name_social_network)} active{/if}" id="tab-social_login_{$name_social_network|escape:'htmlall':'UTF-8'}">
                                        <div class="row">
                                            <div class="clearfix col-xs-12 nopadding">
                                                <a data-social-modal="how-to-{$name_social_network|escape:'htmlall':'UTF-8'}" role="button" class="btn btn-info handler-modal-social-login">
                                                    <i class="fa-pts fa-pts-question-circle nohover"></i>
                                                    {l s='How to I get this info?' mod='onepagecheckoutps'}
                                                </a>

                                                <!-- Modal -->
                                                <div id="how-to-{$name_social_network|escape:'htmlall':'UTF-8'}" class="hidden" tabindex="-1" role="dialog">
                                                    <div class="row clearfix">&nbsp;</div>
                                                    {include file=$paramsBack.MODULE_TPL|cat:'views/templates/admin/social/'|cat:{$name_social_network|escape:'htmlall':'UTF-8'}|cat:'.tpl' paramsBack=$paramsBack}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row clearfix">&nbsp;</div>
                                        <div id="social_login_{$name_social_network|escape:'htmlall':'UTF-8'}-container" class="row">
                                            <div class="form-group clearfix clear">
                                                <div class="col-xs-12 col-sm-2 col-md-2 nopadding-xs" title="{l s='Enable' mod='onepagecheckoutps'} {$social_network->name_network|escape:'quotes':'UTF-8'}">
                                                    <label class="pts-label-tooltip col-xs-12 nopadding control-label">
                                                        {l s='Enable' mod='onepagecheckoutps'}&nbsp;{$social_network->name_network|escape:'quotes':'UTF-8'}
                                                    </label>
                                                </div>
                                                {include file=$paramsBack.MODULE_TPL|cat:'views/templates/admin/helper/form.tpl' global=$paramsBack.GLOBALS
                                                option=['name' => 'social_login_enable','prefix' => 'chk' , 'type' => $paramsBack.GLOBALS->type_control->checkbox, label_on => {l s='Yes' mod='onepagecheckoutps'}, label_off => {l s='No' mod='onepagecheckoutps'}, 'check_on' => $social_network->enable|intval]}
                                            </div>
                                            <div class="form-group clearfix clear">
                                                <div class="col-xs-12 col-sm-6 col-md-5 nopadding-xs" title="{l s='API Key' mod='onepagecheckoutps'} {$social_network->name_network|escape:'quotes':'UTF-8'}">
                                                    <label class="pts-label-tooltip col-xs-12 nopadding control-label">
                                                        {l s='API Key' mod='onepagecheckoutps'}&nbsp;{$social_network->name_network|escape:'quotes':'UTF-8'}
                                                    </label>
                                                </div>
                                                {capture assign='id_lang'}{l s='API Key' mod='onepagecheckoutps'}{/capture}
                                                {include file=$paramsBack.MODULE_TPL|cat:'views/templates/admin/helper/form.tpl' global=$paramsBack.GLOBALS
                                                option=['name' => 'social_login_id','prefix' => 'txt','label' => $id_lang,'type' => $paramsBack.GLOBALS->type_control->textbox,'value' => $social_network->client_id]}
                                            </div>
                                            <div class="form-group clearfix clear">
                                                <div class="col-xs-12 col-sm-6 col-md-5 nopadding-xs" title="{l s='Secret Key' mod='onepagecheckoutps'} {$social_network->name_network|escape:'quotes':'UTF-8'}">
                                                    <label class="pts-label-tooltip col-xs-12 nopadding control-label">
                                                        {l s='Secret Key' mod='onepagecheckoutps'}&nbsp;{$social_network->name_network|escape:'quotes':'UTF-8'}
                                                    </label>
                                                </div>
                                                {capture assign='id_lang'}{l s='Secret Key' mod='onepagecheckoutps'}{/capture}
                                                {include file=$paramsBack.MODULE_TPL|cat:'views/templates/admin/helper/form.tpl' global=$paramsBack.GLOBALS
                                                option=['name' => 'social_login_secret','prefix' => 'txt','label' => $id_lang, 'type' => $paramsBack.GLOBALS->type_control->textbox,'value' => $social_network->client_secret]}
                                            </div>
                                        </div>

                                        <div class="col-xs-12 nopadding">
                                            <hr />
                                            {capture assign='save_lang'}{l s='Save' mod='onepagecheckoutps'}{/capture}
                                            {include file=$paramsBack.MODULE_TPL|cat:'views/templates/admin/helper/action.tpl'
                                                form=['method' => 'ajax', 'tab' => 'social_login_'|cat:$name_social_network,
                                                    'actions' => ['save' => ['label' => $save_lang, 'class' => 'save-social_login', 'icon' => 'save']]]}
                                        </div>
                                    </div>
                                {/foreach}

                                <div class="tab-pane{if (isset($CURRENT_FORM) && $CURRENT_FORM eq 'statistics')} active{/if}" id="tab-statistics">
                                    <div class="row">
                                        {if is_array($paramsBack.SOCIAL_DATA) && count($paramsBack.SOCIAL_DATA) > 0}
                                            <div class="col-md-3 col-md-offset-4">
                                                <canvas id="statistics"></canvas>
                                            </div>
                                        {else}
                                            <div class="alert alert-info text-center">
                                                {l s='There is no data to show, there are no connections through social networks or you have not configured the login through these.' mod='onepagecheckoutps'}
                                            </div>
                                        {/if}
                                    </div>
                                </div>
{*                                END SOCIAL LOGIN FORMS*}

                                <div class="tab-pane{if (isset($CURRENT_FORM) && $CURRENT_FORM eq 'information')} active{/if}" id="tab-information">
                                    <div class="alert alert-info">
                                        {l s='Configure the following CRON task to eliminate the empty addresses created by the module in your store' mod='onepagecheckoutps'}:
                                        <a href="{$paramsBack.CRON_LINK|escape:'quotes':'UTF-8'}" target="_blank">{$paramsBack.CRON_LINK|escape:'quotes':'UTF-8'}</a>
                                    </div>
                                </div>

                                {*global tabs*}
                                {include file=$paramsBack.MODULE_TPL|cat:'views/templates/admin/global_tabs.tpl' tabs=$paramsBack.HELPER_FORM.tabs}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {*removeIf(addons)*}
    {/if}
    {*endRemoveIf(addons)*}
    {include file=$paramsBack.MODULE_TPL|cat:'views/templates/admin/helper/credits.tpl'}
</div>
<div class="col-xs-12">&nbsp;</div>