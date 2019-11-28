{**
* NOTICE OF LICENSE
*
* This source file is subject to a commercial license from SARL DREAM ME UP
* Use, copy, modification or distribution of this source file without written
* license agreement from the SARL DREAM ME UP is strictly forbidden.
*
*   .--.
*   |   |.--..-. .--, .--.--.   .--.--. .-.   .  . .,-.
*   |   ;|  (.-'(   | |  |  |   |  |  |(.-'   |  | |   )
*   '--' '   `--'`-'`-'  '  `-  '  '  `-`--'  `--`-|`-'
*        w w w . d r e a m - m e - u p . f r       '
*
* @author    Dream me up <prestashop@dream-me-up.fr>
* @copyright 2007 - 2016 Dream me up
* @license   All Rights Reserved
*}

{foreach from=$languages item=language}
	{if $languages|count > 1}
	<div class="translatable-field row lang-{$language.id_lang|intval}">
		<div class="col-lg-9">
	{/if}
		{if isset($maxchar)}
		<div class="input-group">
			<span id="{$input_name|escape:'html':'UTF-8'}_{$product->id|intval}_{$language.id_lang|intval}_counter" class="input-group-addon">
				<span class="text-count-down">{$maxchar|intval}</span>
			</span>
			{/if}
			<input type="text"
			id="{$input_name|escape:'html':'UTF-8'}_{$product->id|intval}_{$language.id_lang|intval}"
			class="form-control {if isset($input_class)}{$input_class|escape:'html':'UTF-8'} {/if}"
			name="{$input_name|escape:'html':'UTF-8'}_{$product->id|intval}_{$language.id_lang|intval}"
			value="{$input_value[$language.id_lang]|escape:'html':'UTF-8'|default:''}"
			{if $input_name == 'link_rewrite_seo'}onkeyup="if (isArrowKey(event)) return ;this.value = str2url($('#link_rewrite_seo_' + {$product->id|intval} + '_' + {$language.id_lang|intval}).val(), 'UTF-8');"
			onblur="this.value = str2url($('#link_rewrite_seo_' + {$product->id|intval} + '_' + {$language.id_lang|intval}).val(), 'UTF-8');"{/if}
			{if isset($required)} required="required"{/if}
			{if isset($maxchar)} data-maxchar="{$maxchar|intval}"{/if}
			{if isset($maxlength)} maxlength="{$maxlength|intval}"{/if} />
			{if isset($maxchar)}
		</div>
		{/if}
	{if $languages|count > 1}
		</div>
		<div class="col-lg-2">
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" tabindex="-1">
				{$language.iso_code|escape:'html':'UTF-8'}
				<span class="caret"></span>
			</button>
			<ul class="dropdown-menu">
				{foreach from=$languages item=language}
				<li>
					<a href="javascript:hideOtherLanguage({$language.id_lang|intval});">{$language.name|escape:'html':'UTF-8'}</a>
				</li>
				{/foreach}
			</ul>
		</div>
	</div>
	{/if}
{/foreach}
{if isset($maxchar)}
<script type="text/javascript">
$(document).ready(function(){
{foreach from=$languages item=language}
	countDown($("#{$input_name|escape:'html':'UTF-8'}_{$product->id|intval}_{$language.id_lang|intval}"), $("#{$input_name|escape:'html':'UTF-8'}_{$product->id|intval}_{$language.id_lang|intval}_counter"));
{/foreach}
});
</script>
{/if}
