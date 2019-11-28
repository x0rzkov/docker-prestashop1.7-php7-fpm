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
{block name='step'}
  {* {[
      'checkout-step' => true,
      '-current' => $step_is_current,
      '-reachable' => $step_is_reachable,
      '-complete' => $step_is_complete,
      'js-current-step' => $step_is_current
    ]|classnames}*}

    <b-card no-body class="mb-1">
      <b-card-header header-tag="header" class="p-1" role="tab">
        <b-btn class="text-left" block href="#" v-b-toggle="'{$identifier}'" {if !$step_is_reachable}disabled{/if} variant="{if $step_is_complete}success{else}light{/if}">
          <span class="step-number">{$position}.</span> {$title}
        </b-btn>
      </b-card-header>

      <b-collapse id="{$identifier}" {if $step_is_current}visible{/if} accordion="checkout" role="tabpanel">
        <b-card-body>
          {block name='step_content'}DUMMY STEP CONTENT{/block}
        </b-card-body>
      </b-collapse>
    </b-card>
{/block}
