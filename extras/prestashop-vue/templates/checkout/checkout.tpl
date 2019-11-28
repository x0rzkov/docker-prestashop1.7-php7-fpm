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
<!doctype html>
<html lang="{$language.iso_code}">

  <head>
    {block name='head'}
      {include file='_partials/head.tpl'}
    {/block}
  </head>

  <body id="{$page.page_name}" class="{$page.body_classes|classnames} bg-light">
    <div id="app" class="container">
      {block name='header'}
        {include file='checkout/_partials/header.tpl'}
      {/block}

      {block name='notifications'}
        {include file='_partials/notifications.tpl'}
      {/block}

      {block name='content'}
        <section id="content">
          <div class="row">
            <div class="col-7">
              {block name='cart_summary'}
                <div role="tablist">
                  {render file='checkout/checkout-process.tpl' ui=$checkout_process}
                </div>
              {/block}
            </div>
            <div class="col-5">
              <div class="bg-white border rounded border p-3">
                {block name='cart_summary'}
                  {include file='checkout/_partials/cart-summary.tpl' cart=$cart}
                {/block}
              </div>
            </div>
          </div>
        </section>
      {/block}
    </div>

    {block name='footer'}
      {include file='checkout/_partials/footer.tpl'}
    {/block}

    {block name='vue-templates'}
      {include file="vue-templates/_partials/product.tpl"}
      {include file="vue-templates/_partials/product-small-list.tpl"}
      {include file="vue-templates/checkout/cart-detailed-product.tpl"}
    {/block}

    {block name='javascript_bottom'}
      {include file="_partials/javascript.tpl" javascript=$javascript.bottom}
    {/block}
  </body>

</html>
