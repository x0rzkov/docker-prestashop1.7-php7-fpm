{if isset($orders_consent) && !empty($orders_consent) && ($orders_consent|@count gt 0)}
    {if $orders_consent|@count gt 0}
        <table class="pure-table">
            <thead>
                <tr style="background-color:#f2f2f2">
                    <th style="width: 3%;">{l s='S. No.' mod='supercheckout'}</th>
                    <th style="width: 7%;">{l s='Order Ref.' mod='supercheckout'}</th>
                    <th style="width: 12%;">{l s='Customer' mod='supercheckout'}</th>
                    <th style="width: 15%;">{l s='Email' mod='supercheckout'}</th>
                    <th style="">{l s='Accepted Consent' mod='supercheckout'}</th>
                    <th style="width: 10%;">{l s='Date' mod='supercheckout'}</th>
                </tr>
            </thead>
            <tbody id="supercheckout_customer_consent_tbody">
                {$i = 0}
                {foreach $orders_consent as $data}
                    <tr class="pure-table-{if $i%2 == 0}even{else}odd{/if}">
                        <td>{$i+1|escape:'htmlall':'UTF-8'}</td>
                        <td><a href="{$order_controller|escape:'htmlall':'UTF-8'}&id_order={$data['id_order']|escape:'htmlall':'UTF-8'}&vieworder" target="_blank">{$data['reference']|escape:'htmlall':'UTF-8'}</a></td>
                        <td><a href="{$customer_controller|escape:'htmlall':'UTF-8'}&id_customer={$data['id_customer']|escape:'htmlall':'UTF-8'}&viewcustomer" target="_blank">{$data['customer']|escape:'htmlall':'UTF-8'}</a></td>
                        <td>{$data['email']|escape:'htmlall':'UTF-8'}</td>
                        <td style="padding-left:50px;">
                            {if $data['consent']|@count gt 0}
                                <ul>
                                    {foreach $data['consent'] as $con}
                                        <li>{$con|escape:'htmlall':'UTF-8'}</li>
                                    {/foreach}
                                </ul>
                            {else}
                                {l s='No accepted consent data found for this order' mod='supercheckout'}
                            {/if} 
                        </td>
                        <td>{$data['date']|escape:'htmlall':'UTF-8'}</td>
                    </tr>
                    {$i = $i + 1}
                {/foreach}
            </tbody>
        </table>
    {/if}
{else}
    <div class="rm_no_data"><span>{l s='No data found.' mod='supercheckout'}</span></div>
{/if}

{*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer tohttp://www.prestashop.com for more information.
* We offer the best and most useful modules PrestaShop and modifications for your online store.
*
* @category  PrestaShop Module
* @author    knowband.com <support@knowband.com>
* @copyright 2015 Knowband
*}