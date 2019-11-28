{*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{if $elements}
  <div id="block-reassurance" class="bg-white text-dark border border-top-0 border-right-0 border-left-0">
    <div class="container">
      <ul class="row list-unstyled mb-0">
        {foreach from=$elements item=element}
          <li class="col-12 col-sm d-flex align-items-center justify-content-center py-2">
            <img src="{$element.image}" alt="{$element.text|escape:'quotes'}" class="mr-2"/> <span>{$element.text}</span>
          </li>
        {/foreach}
      </ul>
    </div>
  </div>
{/if}
