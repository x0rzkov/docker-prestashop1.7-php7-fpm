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

{math assign='num_col' equation='12/a' a=$cant_fields}

<div id="field_{if $field->object neq ''}{$field->object}_{/if}{$field->name}"
     class="form-group col-xs-{$num_col} col-{$num_col} {if $field->required}required{/if} {if $cant_fields == 1}clear clearfix{/if}">
    {if $field->type_control eq $OPC_GLOBALS->type_control->textbox}
        <label for="{$field->name_control}">
            {$field->description}:
            <sup>{if $field->required}*{/if}</sup>
        </label>
        <input
            id="{$field->id_control}"
            name="{$field->name_control}"
            type="{if $OPC_GLOBALS->type->{$field->type} eq 'password' or $field->name == 'conf_passwd'}password{elseif $OPC_GLOBALS->type->{$field->type} eq 'email'}email{else}text{/if}"
            class="{$field->classes|escape:'htmlall':'UTF-8'} form-control input-sm not_unifrom not_uniform {if $field->is_custom}custom_field{/if} {if $field->capitalize}capitalize{/if}"
            data-field-name="{$field->name}"
            data-validation="{$field->type|escape:'htmlall':'UTF-8'}{if $field->size neq 0 and $OPC_GLOBALS->type->{$field->type} eq 'string'},length{/if} {if $CONFIGS.OPC_VALIDATE_DNI && $field->name eq 'dni'}isValidIdByCountry{/if}"
            data-default-value="{$field->default_value}"
            data-required="{$field->required|intval}"
            {if $field->name == 'address' && $CONFIGS.OPC_AUTOCOMPLETE_GOOGLE_ADDRESS}autocomplete="off"{/if}
            {if !$field->required}data-validation-optional="true"{/if}
            {if isset($field->error_message) && $field->error_message neq ''}data-validation-error-msg="{$field->error_message}"{/if}
            {if in_array($OPC_GLOBALS->type->{$field->type}, ['string', 'integer', 'text'])}data-validation-length="max{$field->size|intval}" maxlength="{$field->size|intval}"{/if}
            {*if $field->size neq 0}maxlength="{$field->size}"{/if*}
            {if !empty($field->value)}value="{$field->value|escape:'htmlall':'UTF-8'}"{/if}
        />
        {if $field->label neq ''}
            <em>{$field->label nofilter}</em>
        {/if}
    {elseif $field->type_control eq $OPC_GLOBALS->type_control->select}
        <label for="{$field->name_control}">
            {$field->description}:
            <sup>{if $field->required}*{/if}</sup>
        </label>
        <select
            id="{$field->id_control}"
            name="{$field->name_control}"
            class="{$field->classes} form-control input-sm not_unifrom not_uniform {if $field->is_custom}custom_field{/if}"
            data-field-name="{$field->name}"
            data-default-value="{$field->default_value}"
            data-required="{$field->required|intval}"
            {if $field->required}data-validation="required"{/if}
            {if isset($field->error_message) && $field->error_message neq ''}data-validation-error-msg="{$field->error_message}"{/if}>
            {if isset($field->options.empty_option) && $field->options.empty_option}
                <option value="" data-text="" {if $field->default_value eq '' or (!isset($field->options.data) and $field->options.data|count)}selected{/if}>
                    {if $field->name_control eq 'delivery_id' or $field->name_control eq 'invoice_id'}
                        {l s='Create a new address' mod='onepagecheckoutps'}....
                    {else}
                        --
                    {/if}
                </option>
            {/if}
            {if isset($field->options.data)}
                {foreach from=$field->options.data item='item' name='f_options'}
                    <option
                        value="{$item[$field->options.value]}"
                        data-text="{$item[$field->options.description]}"
                        {if $field->name == 'id_country'}data-iso-code="{$item['iso_code']}"{/if}
                        {if $field->default_value eq $item[$field->options.value]}selected{/if}>
                            {$item[$field->options.description]}
                    </option>
                {/foreach}
            {/if}
        </select>
        {if $field->label neq ''}
            <em>{$field->label nofilter}</em>
        {/if}
    {elseif $field->type_control eq $OPC_GLOBALS->type_control->checkbox}
        <label for="{$field->name_control}">
            <input
                id="{$field->id_control}"
                name="{$field->name_control}"
                type="checkbox"
                class="{$field->classes} not_unifrom not_uniform {if $field->is_custom}custom_field{/if}"
                {if $field->default_value}checked{/if}
                data-field-name="{$field->name}"
                data-default-value="{$field->default_value}"
                data-required="{$field->required|intval}"
                {if !$field->required}data-validation-optional="true"{else}data-validation="required"{/if}
                {if isset($field->error_message) && $field->error_message neq ''}data-validation-error-msg="{$field->error_message}"{/if}
            />
            {$field->description}
            <sup>{if $field->required}*{/if}</sup>
        </label>
        {if $field->label neq ''}
            <em>{$field->label nofilter}</em>
        {/if}
    {elseif $field->type_control eq $OPC_GLOBALS->type_control->radio}
        <label>
            {$field->description}:
            <sup>{if $field->required}*{/if}</sup>
        </label>
        <div class="row">
            {foreach from=$field->options.data item='item' name='f_options'}
                {math assign='num_col_option' equation='12/a' a=$smarty.foreach.f_options.total}
                <div class="col-xs-{$num_col_option} col-{$num_col_option}">
                    <label for="{$field->name_control}">
                        <input
                            id="{$field->id_control}_{$item[$field->options.value]}"
                            name="{$field->name}"
                            type="radio"
                            class="{$field->classes} not_unifrom not_uniform {if $field->is_custom}custom_field{/if}"
                            value="{$item[$field->options.value]}"
                            {if $field->default_value eq $item[$field->options.value]}checked{/if}
                            data-field-name="{$field->name}"
                            data-required="{$field->required|intval}"
                        />
                        {$item[$field->options.description]}
                    </label>
                </div>
            {/foreach}
        </div>
        {if $field->label neq ''}
            <em>{$field->label nofilter}</em>
        {/if}
    {elseif $field->type_control eq $OPC_GLOBALS->type_control->textarea}
        <label for="{$field->name_control}">
            {$field->description}:
            <sup>{if $field->required}*{/if}</sup>
        </label>
        <textarea
            id="{$field->id_control}"
            name="{$field->name_control}"
            class="{$field->classes} form-control input-sm not_unifrom not_uniform {if $field->is_custom}custom_field{/if}"
            data-field-name="{$field->name}"
            data-validation="{$field->type}{if $field->size neq 0},length{/if}"
            data-default-value="{$field->default_value}"
            data-required="{$field->required|intval}"
            {if !$field->required}data-validation-optional="true"{/if}
            {if isset($field->error_message) && $field->error_message neq ''}data-validation-error-msg="{$field->error_message}"{/if}
            {if in_array($OPC_GLOBALS->type->{$field->type}, ['string', 'integer', 'text'])}data-validation-length="max{$field->size|intval}"{/if}
            ></textarea>
        {if $field->label neq ''}
            <em>{$field->label nofilter}</em>
        {/if}
    {/if}
</div>

{*if $CONFIGS.OPC_AUTO_ADDRESS_GEONAMES and $field->name == 'address1'}
    <div class="form-group col-xs-12 col-12 clear clearfix inner-addon left-addon" id="field_{$field->object}_search_by_postcode">
        <label for="search_by_postcode">
            {l s='Autocomplete address by postcode' mod='onepagecheckoutps'}:
        </label>
        <input type="text" id="{$field->object}_search_by_postcode" name="search_by_postcode" data-validation-length="max10" class="form-control input-sm not_unifrom not_uniform" autocomplete="off" placeholder="{l s='Example: 08930' mod='onepagecheckoutps'}" style='font-style: italic'/>
    </div>
{/if*}