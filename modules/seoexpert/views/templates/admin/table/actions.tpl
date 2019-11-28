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

<div class="btn-group-action">
	<div class="btn-group pull-right">
		<a role-id="{$prod.id_rule|intval}" data-role="{$role|escape:'htmlall':'UTF-8'}" data-type="{$type[$count_rule-1]|escape:'htmlall':'UTF-8'}" class="pointer generate btn btn-default" {if $prod.active|intval == 0}disabled="disabled"{/if}>
			<i class="icon-magic"></i> {l s='Apply rule' mod='seoexpert'}
		</a>
		<button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
			<i class="icon-caret-down"></i>&nbsp;
		</button>
		<ul class="dropdown-menu" role="menu">
			<li class="pointer">
				<a role-id="{$prod.id_rule|intval}" data-role="{$role|escape:'htmlall':'UTF-8'}" data-type="{$type[$count_rule-1]|escape:'htmlall':'UTF-8'}" class="pointer edit">
					<i class="icon-pencil"></i> {l s='Edit' mod='seoexpert'}
				</a>
			</li>
			<li class="pointer">
				<a role-id="{$prod.id_rule|intval}" data-role="{$role|escape:'htmlall':'UTF-8'}" data-type="{$type[$count_rule-1]|escape:'htmlall':'UTF-8'}" class="pointer delete">
					<i class="icon-trash"></i> {l s='Delete' mod='seoexpert'}
				</a>
			</li>
		</ul>
	</div>
</div>