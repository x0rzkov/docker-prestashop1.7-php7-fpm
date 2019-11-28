{*
* 2007-2017 PrestaShop
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2017 PrestaShop SA
* @license   http://addons.prestashop.com/en/content/12-terms-and-conditions-of-use
* International Registered Trademark & Property of PrestaShop SA
*}

<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;" class="table table-striped table-bordered table-hover">
{foreach $history as $data}
<tr>
	<td><b>{$data.field|escape:'htmlall':'UTF-8'}</b></td>
	<td>
		{if $data.field == 'fb_image'}
			{if $data.pattern == 0}
				{l s='All images' mod='seoexpert'}
			{else}
				{l s='Cover image' mod='seoexpert'}
			{/if}
		{else}
			{$data.pattern|escape:'htmlall':'UTF-8'}
		{/if}
	</td>
</tr>
{/foreach}
</table>