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
    </table>
</div>
<div class="row">
    <div class="col-lg-5">
        {if $bulk_actions && $has_bulk_actions}
        <script type="application/javascript">
            var msg_error_select_product = '{l s='You must select at least one product' j=1 mod='dmuadminrecherche'}';
        </script>
        <div class="btn-group bulk-actions dropup">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                {l s='Bulk actions' mod='dmuadminrecherche'} <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li>
                    <a href="#" onclick="checkDelBoxes($(this).closest('form').get(0), '{$list_id|escape:'html':'UTF-8'}Box[]', true);return false;">
                        <i class="icon-check-sign"></i>&nbsp;{l s='Select all' mod='dmuadminrecherche'}
                    </a>
                </li>
                <li>
                    <a href="#" onclick="checkDelBoxes($(this).closest('form').get(0), '{$list_id|escape:'html':'UTF-8'}Box[]', false);return false;">
                        <i class="icon-check-empty"></i>&nbsp;{l s='Unselect all' mod='dmuadminrecherche'}
                    </a>
                </li>
                <li class="divider"></li>
                {foreach $bulk_actions as $key => $params}
                    <li{if $params.text == 'divider'} class="divider"{/if}>
                        {if $params.text != 'divider'}
                        <a href="javascript:;" onclick="{if !empty($params.fancy)}show_price_impact('{$params.fancy|escape:'html':'UTF-8'}'){else}{if isset($params.confirm)}if (confirm('{$params.confirm|escape:'html':'UTF-8'}')){/if}sendBulk{if isset($popin_combinations)}Comb{/if}ActionAjax('{$key|escape:'html':'UTF-8'}'{if isset($popin_combinations)},{$id_product|intval}{/if}){/if};">
                            {if isset($params.icon)}<i class="{$params.icon|escape:'html':'UTF-8'}"></i>{/if}&nbsp;{$params.text|escape:'html':'UTF-8'}
                        </a>
                        {/if}
                    </li>
                {/foreach}
            </ul>
            <div id="popin_fancy"></div>
        </div>
        {/if}
    </div>
    {if !isset($popin_combinations)}
    <div class="col-lg-7">
        {* Choose number of results per page *}
        <div class="pagination row">
            <div class="col-lg-12">
                <label class="control-label col-lg-3">{l s='Display' mod='dmuadminrecherche'}</label>
                <div class="col-lg-2"><input type="text" name="{$list_id|escape:'html':'UTF-8'}_pagination" id="{$list_id|escape:'html':'UTF-8'}_pagination" value="{$dmu_pagination|intval}"></div>
                <div class="control-label col-lg-4" style="text-align: left;">/ {$list_total|intval} {l s='result(s)' mod='dmuadminrecherche'}</div>
            </div>
        </div>
        <script type="text/javascript">
            $('.pagination-items-page').on('click',function(e){
                e.preventDefault();
                $('#'+$(this).data("list-id")+'-pagination-items-page').val($(this).data("items")).closest("form").submit();
            });
        </script>
        {if $list_total > $dmu_pagination}
            {assign total_pages max(1, ceil($list_total / $dmu_pagination))}
        <ul class="pagination pull-right">
            <li {if $page <= 1}class="disabled"{/if}>
                <a href="javascript:void(0);"{if $page > 1} class="pagination-link"{/if} data-page="1" data-list-id="{$list_id|escape:'html':'UTF-8'}">
                    <i class="icon-double-angle-left"></i>
                </a>
            </li>
            <li {if $page <= 1}class="disabled"{/if}>
                <a href="javascript:void(0);"{if $page > 1} class="pagination-link"{/if} data-page="{$page|intval - 1}" data-list-id="{$list_id|escape:'html':'UTF-8'}">
                    <i class="icon-angle-left"></i>
                </a>
            </li>
            {assign p 0}
            {while $p++ < $total_pages}
                {if $p < $page-2}
                    <li class="disabled">
                        <a href="javascript:void(0);">&hellip;</a>
                    </li>
                    {assign p $page-3}
                {elseif $p > $page+2}
                    <li class="disabled">
                        <a href="javascript:void(0);">&hellip;</a>
                    </li>
                    {assign p $total_pages}
                {else}
                    <li {if $p == $page}{assign select_pagination $p} class="active"{/if}>
                        <a href="javascript:void(0);" class="pagination-link" data-page="{$p|intval}" data-list-id="{$list_id|escape:'html':'UTF-8'}">{$p|intval}</a>
                    </li>
                {/if}
            {/while}
            <li {if $page >= $total_pages}class="disabled"{/if}>
                <a href="javascript:void(0);"{if $page < $total_pages} class="pagination-link"{/if} data-page="{$page|intval + 1}" data-list-id="{$list_id|escape:'html':'UTF-8'}">
                    <i class="icon-angle-right"></i>
                </a>
            </li>
            <li {if $page >= $total_pages}class="disabled"{/if}>
                <a href="javascript:void(0);"{if $page < $total_pages} class="pagination-link"{/if} data-page="{$total_pages|intval}" data-list-id="{$list_id|escape:'html':'UTF-8'}">
                    <i class="icon-double-angle-right"></i>
                </a>
            </li>
        </ul>
            <input type="hidden" name="select_pagination" id="select_pagination" value="{$select_pagination|intval}">
        {/if}
    </div>
    {/if}
</div>
{block name="footer"}{/block}
    </div>

{hook h='displayAdminListAfter'}
{if isset($name_controller)}
    {capture name=hookName assign=hookName}display{$name_controller|ucfirst|escape:'html':'UTF-8'}ListAfter{/capture}
    {hook h=$hookName}
{elseif isset($smarty.get.controller)}
    {capture name=hookName assign=hookName}display{$smarty.get.controller|ucfirst|escape:'html':'UTF-8'}ListAfter{/capture}
    {hook h=$hookName}
{/if}

{block name="endForm"}
</form>
{/block}

{block name="after"}
    {if !$return_ajax}
        <div class="popin_action" id="popin_action"></div>
    {include file='./bulk_price.tpl'}
    {/if}
{/block}
