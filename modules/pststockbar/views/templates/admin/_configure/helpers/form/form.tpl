{**
* NOTICE OF LICENSE
*
* This file is licenced under the Software License Agreement.
* With the purchase or the installation of the software in your application
* you accept the licence agreement.
*
* @author    Presta.Site
* @copyright 2018 Presta.Site
* @license   LICENSE.txt
*}

{extends file="helpers/form/form.tpl"}
{block name="defaultForm"}
    {if isset($fields[0].form.id_form_wrp) && $fields[0].form.id_form_wrp}
    {if isset($fields[0].form.expand_text) && $fields[0].form.expand_text}
		<button type="button" class="expand-ffifw button btn btn-primary {if $psv == 1.5}ps15{/if}">{$fields[0].form.expand_text|escape:'html':'UTF-8'}</button>
		<span id="pstg_add_success">{l s='Successfully added' mod='pststockbar'}</span>
    {/if}
<div id="{$fields[0].form.id_form_wrp|escape:'html':'UTF-8'}" class="{if isset($fields[0].form.collapsed) && $fields[0].form.collapsed}expandable-ffifw{/if}">
	<div class="ffifw-errors" style="display: none;"></div>
    {/if}
    {if $psv == 1.5}
	<div class="ps15">
        {/if}
        {$smarty.block.parent}
        {/block}

        {if $psv == 1.5}
            {block name="label"}
				<div class="{if isset($input.form_group_class)} {$input.form_group_class|escape:'html':'UTF-8'}{/if}">
                {$smarty.block.parent}
            {/block}
            {block name="field"}
                {$smarty.block.parent}
				</div>
            {/block}
        {/if}

        {block name="field"}
            {if $input.type == 'html' && $psv == 1.5}
				<div class="html_content15">
                    {if isset($input.html_content)}{$input.html_content nofilter}{/if}
				</div>
            {else}
                {$smarty.block.parent}
            {/if}
        {/block}

        {block name="input"}
            {if $input.type == 'textarea' && $psv == 1.5}
            {if isset($input.lang) AND $input.lang}
				<div class="translatable">
                    {foreach $languages as $language}
						<div class="lang_{$language.id_lang|intval}" id="{$input.name|escape:'html':'UTF-8'}_{$language.id_lang|intval}" style="display:{if $language.id_lang == $defaultFormLanguage}block{else}none{/if}; float: left;">
							<textarea cols="{$input.cols|intval}" rows="{$input.rows|intval}" name="{$input.name|escape:'html':'UTF-8'}_{$language.id_lang|intval}" id="ta_{$input.name|escape:'html':'UTF-8'}_{$language.id_lang|intval}"
									  class="{if isset($input.autoload_rte) && $input.autoload_rte}rte autoload_rte {if isset($input.class)}{$input.class|escape:'html':'UTF-8'}{/if}{/if} {if isset($input.class) && $input.class}{$input.class|escape:'html':'UTF-8'}{/if}"
							>{$fields_value[$input.name][$language.id_lang]|escape:'htmlall':'UTF-8'}</textarea>
						</div>
                    {/foreach}
				</div>
            {else}
				<textarea name="{$input.name|escape:'html':'UTF-8'}" id="{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}{else}{$input.name|escape:'html':'UTF-8'}{/if}" cols="{$input.cols|intval}" rows="{$input.rows|intval}" {if isset($input.autoload_rte) && $input.autoload_rte}class="rte autoload_rte {if isset($input.class)}{$input.class|escape:'html':'UTF-8'}{/if}"{/if}>{$fields_value[$input.name]|escape:'htmlall':'UTF-8'}</textarea>
            {/if}
            {elseif $input.type == 'file' && $psv == 1.5}
            {if isset($input.lang) AND $input.lang}
				<div class="translatable">
                    {foreach $languages as $language}
						<div class="lang_{$language.id_lang|intval}" id="{$input.name|escape:'html':'UTF-8'}_{$language.id_lang|intval}" style="display:{if $language.id_lang == $defaultFormLanguage}block{else}none{/if}; float: left;">
							<input type="file" name="{$input.name|escape:'quotes':'UTF-8'}[{$language.id_lang|intval}]" {if isset($input.id)}id="{$input.id|escape:'quotes':'UTF-8'}"{/if} />
                            {if !empty($input.hint)}<span class="hint" name="help_box">{$input.hint|escape:'html':'UTF-8'}<span class="hint-pointer">&nbsp;</span></span>{/if}
							<div class="form-group pbc-img-wrp">
                                {if isset($input['files']) && isset($input['files'][$language.id_lang]) && isset($input['files'][$language.id_lang]['image']) && $input['files'][$language.id_lang]['image']}
									<div id="{$input.name|escape:'quotes':'UTF-8'}-{$language.id_lang|intval}-images-thumbnails">
                                        <img src="{$input['files'][$language.id_lang]['image']|escape:'html':'UTF-8'}" alt="#">
                                        <a href="{$input['files'][$language.id_lang]['delete_url']|escape:'quotes':'UTF-8'}" class="pbc-delete-img button btn btn-default">{l s='Delete' mod='pststockbar'}</a>
									</div>
                                {/if}
							</div>
						</div>
                    {/foreach}
				</div>
            {else}
                {$smarty.block.parent}
            {/if}
            {elseif $input.type == 'file' && $psv >= 1.6}
            {if isset($input.lang) AND $input.lang}
            {foreach from=$languages item=language}
            {if $languages|count > 1}
                    <div class="translatable-field lang-{$language.id_lang|intval}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
                    {/if}
					<div class="form-group">
						<div class="col-lg-6">
							<input type="file" name="{$input.name|escape:'quotes':'UTF-8'}[{$language.id_lang|intval}]" {if isset($input.id)}id="{$input.id|escape:'quotes':'UTF-8'}"{/if} />
							<div class="form-group pbc-img-wrp">
                                {if isset($input['files']) && isset($input['files'][$language.id_lang]) && isset($input['files'][$language.id_lang]['image']) && $input['files'][$language.id_lang]['image']}
									<div id="{$input.name|escape:'quotes':'UTF-8'}-{$language.id_lang|intval}-images-thumbnails">
                                            <img src="{$input['files'][$language.id_lang]['image']|escape:'html':'UTF-8'}" alt="#">
                                            <a href="{$input['files'][$language.id_lang]['delete_url']|escape:'quotes':'UTF-8'}" class="pbc-delete-img button btn btn-default">{l s='Delete' mod='pststockbar'}</a>
									</div>
                                {/if}
							</div>
						</div>
                        {if $languages|count > 1}
							<div class="col-lg-2">
								<button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
                                        {$language.iso_code|escape:'html':'UTF-8'}
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu">
                                    {foreach from=$languages item=lang}
                                            <li><a href="javascript:hideOtherLanguage({$lang.id_lang|intval});" tabindex="-1">{$lang.name|escape:'html':'UTF-8'}</a></li>
                                    {/foreach}
								</ul>
							</div>
                        {/if}
					</div>
                    {if $languages|count > 1}
				</div>
            {/if}
				<script>
                    $(document).ready(function(){
                            $('#{$input.name|escape:'html':'UTF-8'}_{$language.id_lang|intval}-selectbutton').click(function(e){
                                $('#{$input.name|escape:'html':'UTF-8'}_{$language.id_lang|intval}').trigger('click');
                        });
                            $('#{$input.name|escape:'html':'UTF-8'}_{$language.id_lang|intval}').change(function(e){
                            var val = $(this).val();
                            var file = val.split(/[\\/]/);
                                $('#{$input.name|escape:'html':'UTF-8'}_{$language.id_lang|intval}-name').val(file[file.length-1]);
                        });
                    });
				</script>
            {/foreach}
            {if isset($input.desc) && !empty($input.desc)}
				<p class="help-block">
                        {$input.desc|escape:'html':'UTF-8'}
				</p>
            {/if}
            {else}
                {$smarty.block.parent}
            {/if}
            {elseif $input.type == 'colors'}
            {foreach from=$input.colors_data item=elem key='i'}
				<div class="pstg_color_wrp pbc_color_wrp color-theme-{$elem.theme|escape:'quotes':'UTF-8'} {if $current_theme != $elem.theme}hidden{/if}">
                    {$elem.name|escape:'html':'UTF-8'}<br>
					<input type="text"
                           {if isset($input.class)}class="{$input.class|escape:'html':'UTF-8'}"
                           {else}class="pspc-color pbcColorPickerInput"{/if}
						   name="{$input.name|escape:'html':'UTF-8'}[{$i|escape:'html':'UTF-8'}]"
                           id="psb_{$input.name|escape:'html':'UTF-8'}_{$i|escape:'html':'UTF-8'}"
						   value="{$fields_value[$input.name][$i]|escape:'html':'UTF-8'}" />
				</div>
            {/foreach}
            {elseif $input.type == 'theme'}
            {strip}
				<div class="row">
					<div class="col-lg-{if isset($input.col)}{$input.col|intval}{else}12{/if}{if !isset($input.label)} col-lg-offset-3{/if} psb-themes-wrp themes-wrp-{$psvd|escape:'html':'UTF-8'}">
						<div class="row">
                            {foreach $input.values as $value}
								<div class="col-lg-4 col-md-4 col-xs-6 theme-item {if isset($input.class)}{$input.class|escape:'html':'UTF-8'}{/if}">
                                    {strip}
										<label>
											<input type="radio"	name="{$input.name|escape:'html':'UTF-8'}" id="theme-{$value.label|escape:'html':'UTF-8'}" value="{$value.value|escape:'html':'UTF-8'}" data-theme="{rtrim($value.value, '.css')|escape:'quotes':'UTF-8'}" {if $fields_value[$input.name] == $value.value} checked="checked"{/if}{if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}/>
											<img class="theme-img" src="{$value.img|escape:'html':'UTF-8'}" alt="{$value.label|escape:'html':'UTF-8'}">
										</label>
                                    {/strip}
								</div>
                                {if isset($value.p) && $value.p}<p class="help-block">{$value.p|escape:'html':'UTF-8'}</p>{/if}
                            {/foreach}
						</div>
					</div>
				</div>
            {/strip}
            {elseif $input.type == 'stock_levels'}
                {include file="./_stock_levels.tpl"}
            {else}
                {$smarty.block.parent}
            {/if}
        {/block}

        {block name="after"}
        {$smarty.block.parent}
        {if $psv == 1.5}
	</div>
    {/if}
    {if isset($fields[0].form.id_form_wrp)}
	<span class="close-ffifw">&times;</span>
</div>
    {/if}
{if isset($fields[0].form.after_content)}
    {$fields[0].form.after_content nofilter} {* html content *}
{/if}
{/block}
