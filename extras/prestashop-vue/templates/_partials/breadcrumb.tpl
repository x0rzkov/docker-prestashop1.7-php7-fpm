{**
 * 2007-2017 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
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
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2017 PrestaShop SA
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
<nav aria-label="breadcrumb" role="navigation" data-depth="{$breadcrumb.count}" v-if="!page.body_classes['page-index'] && !page.body_classes['page-cart'] && !page.body_classes['page-customer-account'] && !page.body_classes['page-order-confirmation']">
  <ol class="breadcrumb border bg-white" itemscope itemtype="http://schema.org/BreadcrumbList">
    {foreach from=$breadcrumb.links item=path name=breadcrumb}
      {block name='breadcrumb_item'}
        <li class="breadcrumb-item {if !$smarty.foreach.breadcrumb.first && $smarty.foreach.breadcrumb.last}active{/if}" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
          {if $smarty.foreach.breadcrumb.last}
            <span itemprop="name">{$path.title}</span>
          {else}
            <a itemprop="item" href="{$path.url}">
              <span itemprop="name">{$path.title}</span>
            </a>
          {/if}
          <meta itemprop="position" content="{$smarty.foreach.breadcrumb.iteration}">
        </li>
      {/block}
    {/foreach}
  </ol>
</nav>