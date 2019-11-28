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

{if $type|escape:'htmlall':'UTF-8' == 'plus'}
<i class="icon-plus"></i>
{elseif $type|escape:'htmlall':'UTF-8' == 'flag'}
<img src="{$lang_img|escape:'htmlall':'UTF-8'}" alt="" title="" with="16" height="11" />
{/if}