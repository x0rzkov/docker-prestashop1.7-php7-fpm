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
{extends file=$layout}

{block name='head_seo' prepend}
  <link rel="canonical" href="{$product.canonical_url}">
{/block}

{block name='head' append}
  <meta property="og:type" content="product">
  <meta property="og:url" content="{$urls.current_url}">
  <meta property="og:title" content="{$page.meta.title}">
  <meta property="og:site_name" content="{$shop.name}">
  <meta property="og:description" content="{$page.meta.description}">
  <meta property="og:image" content="{$product.cover.large.url}">
  <meta property="product:pretax_price:amount" content="{$product.price_tax_exc}">
  <meta property="product:pretax_price:currency" content="{$currency.iso_code}">
  <meta property="product:price:amount" content="{$product.price_amount}">
  <meta property="product:price:currency" content="{$currency.iso_code}">
  {if isset($product.weight) && ($product.weight != 0)}
    <meta property="product:weight:value" content="{$product.weight}">
    <meta property="product:weight:units" content="{$product.weight_unit}">
  {/if}
{/block}

{block name='content'}
  <section id="main" itemscope itemtype="https://schema.org/Product">
    <meta itemprop="url" content="{$product.url}">

    {block name='product_activation'}
      {include file='catalog/_partials/product-activation.tpl'}
    {/block}

    {block name='page_header_container'}
      <header class="page-header">
        {block name='page_header'}
        {/block}
      </header>
    {/block}

    {block name='page_content_container'}
      <section id="content" class="page-content" data-module-name="productPageData" data-module-data="{$product|@json_encode}">
        {block name='page_content'}
          <div class="row mb-3">
            <div class="col-12 col-md-6 col-lg-7">
              {block name='product_cover_thumbnails'}
                {include file='catalog/_partials/product-cover-thumbnails.tpl'}
              {/block}
            </div>
            <div class="col-12 col-md-6 col-lg-5">
              <div class="product-information">
                <div class="d-flex">
                  {block name='product_availability'}
                    {if $product.show_availability && $product.availability_message !== ''}
                      <p id="product-availability" class="mr-2">
                        <span class="badge badge-success">
                          {$product.availability_message}
                        </span>
                      </p>

                      <span class="mr-2">|</span>
                    {/if}
                  {/block}

                  {block name='product_reference'}
                    {if $product.reference}
                      <p id="product-reference">
                        <label class="mb-0">
                          {l s='Reference: ' d='Shop.Theme.Catalog'}
                        </label>
                        <span itemprop="sku" class="badge badge-info">
                          {$product.reference}
                        </span>
                      </p>
                    {/if}
                  {/block}
                </div>

                {block name="product-name"}
                  <h1 class="h4" itemprop="name">
                    {block name='page_title'}
                      {$product.name}
                    {/block}
                  </h1>
                {/block}

                {block name='product_condition'}
                  {if $product.condition}
                    <p id="product-condition">
                      <label>{l s='Condition' d='Shop.Theme.Catalog'}</label>
                      <link itemprop="itemCondition" href="{$product.condition.schema_url}"/>
                      <span>{$product.condition.label}</span>
                    </p>
                  {/if}
                {/block}

                {block name='product_description_short'}
                  <div id="product-description-short" itemprop="description">
                    {$product.description_short nofilter}
                  </div>
                {/block}

                {block name='product_flags'}
                  <div class="product-flags list-inline">
                    {foreach from=$product.flags item=flag name=flag}
                      <span class="badge badge-info {$flag.type} mr-1">
                        {$flag.label}
                      </span>
                    {/foreach}
                  </div>
                {/block}

                {block name='product_quantities'}
                  <p id="product-quantities" class="h6" v-if="modules.productPageData.show_quantities">
                    {literal}{{modules.productPageData.quantity}}{/literal}
                    {literal}{{modules.productPageData.quantity_label}}{/literal}
                  </p>
                {/block}

                {block name='product_availability_date'}
                  {if $product.availability_date}
                    <p id="product-availability-date">
                      <label>{l s='Availability date:' d='Shop.Theme.Catalog'} </label>
                      <span>{$product.availability_date}</span>
                    </p>
                  {/if}
                {/block}

                {block name='product_out_of_stock'}
                  <div class="product-out-of-stock">
                    {hook h='actionProductOutOfStock' product=$product}
                  </div>
                {/block}
              </div>

              <div class="product-actions">
                {block name='product_buy'}
                  <form action="{$urls.pages.cart}" method="post" id="add-to-cart-or-refresh">
                    <input type="hidden" name="token" value="{$static_token}">
                    <input type="hidden" name="id_product" value="{$product.id}" id="product_page_product_id">
                    <input type="hidden" name="id_customization" value="{$product.id_customization}" id="product_customization_id">

                    {block name='product_prices'}
                      {include file='catalog/_partials/product-prices.tpl'}
                    {/block}

                    {block name='product_variants'}
                      {include file='catalog/_partials/product-variants.tpl'}
                    {/block}


                    {block name='product_add_to_cart'}
                      {include file='catalog/_partials/product-add-to-cart.tpl'}
                    {/block}

                    {* {block name='hook_product_buttons'}
                      {hook h='displayProductButtons' product=$product}
                    {/block} *}

                    {block name='product_additional_info'}
                      {include file='catalog/_partials/product-additional-info.tpl'}
                    {/block}

                    {block name='product_refresh'}
                      <input class="custom-product-refresh ps-hidden-by-js"
                             name="refresh" type="submit"
                             value="{l s='Refresh' d='Shop.Theme.Actions'}">
                    {/block}
                  </form>
                {/block}
              </div>

              {block name='product_discounts'}
                {include file='catalog/_partials/product-discounts.tpl'}
              {/block}

            </div>
          </div>

          <b-card no-body class="mb-3">
            <b-tabs card>
              <b-tab title="{l s='Description' d='Shop.Theme.Catalog'}" active>
                {block name='product_description'}
                  {$product.description nofilter}
                {/block}
              </b-tab>
              {block name='product_features'}
                {if $product.features}
                  <b-tab title="{l s='Data sheet' d='Shop.Theme.Catalog'}">
                    <section class="product-features">
                      <h3 class="sr-only">{l s='Data sheet' d='Shop.Theme.Catalog'}</h3>
                      <table class="table table-sm table-bordered">
                        <tbody>
                          {foreach from=$product.features item=feature}
                            <tr>
                              <td>{$feature.name}</td>
                              <td>{$feature.value}</td>
                            </tr>
                          {/foreach}
                        </tbody>
                      </table>
                    </section>
                  </b-tab>
                {/if}
              {/block}
              {block name='product_attachments'}
                {if $product.attachments}
                  <b-tab title="{l s='Attachments' d='Shop.Theme.Catalog'}">
                    <section class="product-attachments">
                      <h3 class="sr-only">{l s='Download' d='Shop.Theme.Actions'}</h3>
                      {foreach from=$product.attachments item=attachment}
                        <div class="attachment">
                          <h5 class="mb-2">
                            <a href="{url entity='attachment' params=['id_attachment' => $attachment.id_attachment]}">
                              {$attachment.name}
                            </a>
                          </h5>
                          {if $attachment.description !== ''}
                            <p>{$attachment.description}</p>
                          {/if}
                          <a class="btn btn-outline-dark" href="{url entity='attachment' params=['id_attachment' => $attachment.id_attachment]}">
                            {l s='Download' d='Shop.Theme.Actions'} ({$attachment.file_size_formatted})
                          </a>
                        </div>
                      {/foreach}
                    </section>
                  </b-tab>
                {/if}
              {/block}
              {if $product.is_customizable && count($product.customizations.fields)}
                {block name='product_customization'}
                  <b-tab title="{l s='Customization' d='Shop.Theme.Catalog'}">
                    {include file='catalog/_partials/product-customization.tpl' customizations=$product.customizations}
                  </b-tab>
                {/block}
              {/if}
              {foreach from=$product.extraContent item=extra key=extraKey}
                <b-tab title="{$extra.title}">
                  <div class="{$extra.attr.class}" id="extra-{$extraKey}">
                    {$extra.content nofilter}
                  </div>
                </b-tab>
              {/foreach}

            </b-tabs>
          </b-card>



          {block name='product_pack'}
            {if $packItems}
              <section class="product-pack mb-3" data-module-name="packProduct" data-module-data="{$packItems|@json_encode}">
                <b-card header="{l s='Pack content' d='Shop.Theme.Catalog'}" body-class="p-2" header-tag="h5">
                  <carousel class="products" :per-page-custom="[[768, 3], [1024, 4], [2560, 4]]"
                    :pagination-padding="5"
                    pagination-active-color="var(--primary)"
                    pagination-color="var(--secondary)"
                    navigation-next-label=""
                    navigation-prev-label="">
                    <slide v-for="product in modules.packProduct" :key="product.id">
                      <div is="product-miniature" :product='product'></div>
                    </slide>
                </carousel>
                </b-card>
              </section>
            {/if}
          {/block}

          {block name='product_accessories'}
            {if $accessories}
              <section class="product-accessories mb-3" data-module-name="accessoriesProduct" data-module-data="{$accessories|@json_encode}">
                <b-card header="{l s='Accessories' d='Shop.Theme.Catalog'}" body-class="p-2" header-tag="h5">
                  <carousel class="products" :per-page-custom="[[768, 3], [1024, 4], [2560, 4]]"
                    :pagination-padding="5"
                    pagination-active-color="var(--primary)"
                    pagination-color="var(--secondary)"
                    navigation-next-label=""
                    navigation-prev-label=""
                    >
                    <slide v-for="product in modules.accessoriesProduct" :key="product.id">
                      <div is="product-miniature" :product='product'></div>
                    </slide>
                  </carousel>
                </b-card>
              </section>
            {/if}
          {/block}

          {block name='product_footer'}
            {hook h='displayFooterProduct' product=$product category=$category}
          {/block}

        {/block}
      </section>
    {/block}

    {block name='page_footer_container'}
      <footer class="page-footer">
        {block name='page_footer'}
          <!-- Footer content -->
        {/block}
      </footer>
    {/block}

  </section>

{/block}
