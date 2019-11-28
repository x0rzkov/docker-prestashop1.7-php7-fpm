{*
 * We offer the best and most useful modules PrestaShop and modifications for your online store.
 *
 * We are experts and professionals in PrestaShop
 *
 * @category  PrestaShop
 * @category  Module
 * @author    PresTeamShop.com <support@presteamshop.com>
 * @copyright 2011-2019 PresTeamShop
 * @license   see file: LICENSE.txt
*}

{if $field.type == 'hidden'}
    {block name='form_field_item_hidden'}
        <input type="hidden" name="{$field.name}" value="{$field.value}">
    {/block}
{else}
    <div class="row {if !empty($field.errors)}has-error{/if}">
        <div id="field_customer_{$field.name}" class="form-group col-xs-12 col-12">
            {if $field.type === 'checkbox'}
                {block name='form_field_item_checkbox'}
                    <label>
                        <input name="{$field.name}" class="not_unifrom not_uniform" type="checkbox" value="1" {if $field.value}checked="checked"{/if} {if $field.required}required{/if}>
                        {$field.label nofilter}
                    </label>
                {/block}
            {else}
                <label class="col-md-4 form-control-label{if $field.required} required{/if}">
                    {$field.label}
                </label>

                <div class="col-md-5{if ($field.type === 'radio-buttons')} form-control-valign{/if}">
                    {if $field.type === 'select'}
                        {block name='form_field_item_select'}
                            <select class="form-control form-control-select" name="{$field.name}" {if $field.required}required{/if}>
                                <option value disabled selected>{l s='-- please choose --' d='Shop.Forms.Labels'}</option>
                                    {foreach from=$field.availableValues item="label" key="value"}
                                        <option value="{$value}" {if $value eq $field.value} selected {/if}>{$label}</option>
                                    {/foreach}
                            </select>
                        {/block}
                    {elseif $field.type === 'countrySelect'}
                        {block name='form_field_item_country'}
                            <select class="form-control form-control-select js-country" name="{$field.name}" {if $field.required}required{/if}>
                                <option value disabled selected>{l s='-- please choose --' d='Shop.Forms.Labels'}</option>
                                {foreach from=$field.availableValues item="label" key="value"}
                                    <option value="{$value}" {if $value eq $field.value} selected {/if}>{$label}</option>
                                {/foreach}
                            </select>
                        {/block}
                    {elseif $field.type === 'radio-buttons'}
                        {block name='form_field_item_radio'}
                            {foreach from=$field.availableValues item="label" key="value"}
                                <label class="radio-inline">
                                    <span class="custom-radio">
                                        <input name="{$field.name}" type="radio" value="{$value}" {if $field.required}required{/if} {if $value eq $field.value} checked {/if}>
                                        <span></span>
                                    </span>
                                    {$label}
                                </label>
                            {/foreach}
                        {/block}
                    {elseif $field.type === 'date'}
                        {block name='form_field_item_date'}
                            <input name="{$field.name}" class="form-control" type="date" value="{$field.value}" placeholder="{if isset($field.availableValues.placeholder)}{$field.availableValues.placeholder}{/if}">
                            {if isset($field.availableValues.comment)}
                                <span class="form-control-comment">
                                    {$field.availableValues.comment}
                                </span>
                            {/if}
                        {/block}
                    {elseif $field.type === 'birthday'}
                        {block name='form_field_item_birthday'}
                            <div class="js-parent-focus">
                              {html_select_date
                              field_order=DMY
                              time={$field.value}
                              field_array={$field.name}
                              prefix=false
                              reverse_years=true
                              field_separator='<br>'
                              day_extra='class="form-control form-control-select"'
                              month_extra='class="form-control form-control-select"'
                              year_extra='class="form-control form-control-select"'
                              day_empty={l s='-- day --' d='Shop.Forms.Labels'}
                              month_empty={l s='-- month --' d='Shop.Forms.Labels'}
                              year_empty={l s='-- year --' d='Shop.Forms.Labels'}
                              start_year={'Y'|date}-100 end_year={'Y'|date}
                              }
                            </div>
                        {/block}
                    {elseif $field.type === 'password'}
                        {block name='form_field_item_password'}
                          <div class="input-group js-parent-focus">
                                <input
                                  class="form-control js-child-focus js-visible-password"
                                  name="{$field.name}"
                                  type="password"
                                  value=""
                                  pattern=".{literal}{{/literal}5,{literal}}{/literal}"
                                  {if $field.required}required{/if}
                                >
                                <span class="input-group-btn">
                                    <button
                                      class="btn"
                                      type="button"
                                      data-action="show-password"
                                      data-text-show="{l s='Show' d='Shop.Theme.Actions'}"
                                      data-text-hide="{l s='Hide' d='Shop.Theme.Actions'}"
                                    >
                                      {l s='Show' d='Shop.Theme.Actions'}
                                    </button>
                                </span>
                          </div>
                        {/block}
                    {else}
                      {block name='form_field_item_other'}
                          <input
                            class="form-control"
                            name="{$field.name}"
                            type="{$field.type}"
                            value="{$field.value}"
                            {if isset($field.availableValues.placeholder)}placeholder="{$field.availableValues.placeholder}"{/if}
                            {if $field.maxLength}maxlength="{$field.maxLength}"{/if}
                            {if $field.required}required{/if}
                          >
                          {if isset($field.availableValues.comment)}
                              <span class="form-control-comment">
                                {$field.availableValues.comment}
                              </span>
                          {/if}
                      {/block}
                    {/if}

                    {block name='form_field_errors'}
                      {include file='_partials/form-errors.tpl' errors=$field.errors}
                    {/block}
                </div>
            {/if}

            {if (!$field.required && !in_array($field.type, ['radio-buttons', 'checkbox']))}
                <div class="col-md-3 form-control-comment">
                    {block name='form_field_comment'}
                       {l s='Optional' d='Shop.Forms.Labels'}
                    {/block}
                </div>
            {/if}
        </div>
    </div>
{/if}
