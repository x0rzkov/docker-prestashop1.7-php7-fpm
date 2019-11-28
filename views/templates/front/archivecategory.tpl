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

{capture name=path}<a href="{smartblog::GetSmartBlogLink('smartblog')|escape:'htmlall':'UTF-8'}">{l s='All Blog News' mod='smartblog'}</a>
     {if $title_category != ''}
    <span class="navigation-pipe">{$navigationPipe|escape:'htmlall':'UTF-8'}</span>{$title_category|escape:'htmlall':'UTF-8'}{/if}{/capture}
    {if $postcategory == ''}
             <p class="error">{l s='No Post in Archive' mod='smartblog'}</p>
    {else}   
    <div id="smartblogcat" class="block">
{foreach from=$postcategory item=post}
    {include file="./category_loop.tpl" postcategory=$postcategory}
{/foreach}
    </div>
 {/if}
 {if isset($smartcustomcss)}
    <style>
        {$smartcustomcss|escape:'htmlall':'UTF-8'}
    </style>
{/if}

