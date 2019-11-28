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
	{if isset($maxchar) && $maxchar}
				<div class="input-group">
					<span id="{if isset($input_id)}{$input_id|escape:'html':'UTF-8'}_{$product->id|intval}_{$language.id_lang|intval}{else}{$input_name|escape:'html':'UTF-8'}_{$product->id|intval}_{$language.id_lang|intval}{/if}_counter" class="input-group-addon">
						<span class="text-count-down">{$maxchar|intval}</span>
					</span>
	{/if}
					<textarea id="{$input_name|escape:'html':'UTF-8'}_{$product->id|intval}_{$language.id_lang|intval}" name="{$input_name|escape:'html':'UTF-8'}_{$product->id|intval}_{$language.id_lang|intval}" class="{if isset($class)}{$class|escape:'html':'UTF-8'}{else}textarea-autosize{/if}"{if isset($maxlength) && $maxlength} maxlength="{$maxlength|intval}"{/if}{if isset($maxchar) && $maxchar} data-maxchar="{$maxchar|intval}"{/if}>{if isset($input_value[$language.id_lang])}{$input_value[$language.id_lang]|escape:'html':'UTF-8'}{/if}</textarea>
					<span class="counter" data-max="{if isset($max)}{$max|intval}{/if}{if isset($maxlength)}{$maxlength|intval}{/if}{if !isset($max) && !isset($maxlength)}none{/if}"></span>
			{if isset($maxchar) && $maxchar}
				</div>
			{/if}
	{if $languages|count > 1}
			</div>
			<div class="col-lg-2">
				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
					{$language.iso_code|escape:'html':'UTF-8'}
					<span class="caret"></span>
				</button>
				<ul class="dropdown-menu">
					{foreach from=$languages item=language}
					<li><a href="javascript:hideOtherLanguage({$language.id_lang|intval});">{$language.name|escape:'html':'UTF-8'}</a></li>
					{/foreach}
				</ul>
			</div>
		</div>
	{/if}
{/foreach}

<script type="text/javascript">
	{if isset($maxchar) && $maxchar}
	$(document).ready(function(){
		{foreach from=$languages item=language}
			countDown($("#{$input_name|escape:'html':'UTF-8'}_{$product->id|intval}_{$language.id_lang|intval}"), $("#{$input_name|escape:'html':'UTF-8'}_{$product->id|intval}_{$language.id_lang|intval}_counter"));
		{/foreach}
	});
	{/if}
	$(".textarea-autosize").off().autosize();
</script>
