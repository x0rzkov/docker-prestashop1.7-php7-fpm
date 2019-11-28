{if $empty_gift_message eq 0}
    <div id="order-detail-content" style="margin-left: -14px;" class="table_block table-responsive">

                <table>
    <tr>
				<td width="10" style="padding:7px 0">&nbsp;</td>
				<td style="padding:7px 0">
					<font size="2" face="Open-sans, sans-serif" color="#555454">
						<p data-html-only="1" style="border-bottom:1px solid #D6D4D4;margin:3px 0 7px;text-transform:uppercase;font-weight:500;font-size:18px;padding-bottom:10px">
							{l s='Gift message details' mod='supercheckout'}</p>
						<span style="color:#777">
							<span style="color:#333"><strong>{l s='Sender' mod='supercheckout'}:</strong></span> {$sender} <br /><br />
							<span style="color:#333"><strong>{l s='Receiver' mod='supercheckout'}:</strong></span> {$receiver} <br /><br />
							<span style="color:#333"><strong>{l s='Message' mod='supercheckout'}:</strong></span> {$gift_msg}
						</span>
					</font>
				</td>
				<td width="10" style="padding:7px 0">&nbsp;</td>
		
    </tr>
                </table>
    </div>
{/if}
{*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
* We offer the best and most useful modules PrestaShop and modifications for your online store.
*
* @category  PrestaShop Module
* @author    velsof.com <support@velsof.com>
* @copyright 2017 Velocity Software Solutions Pvt Ltd
* @license   see file: LICENSE.txt
*
* Description
*
* Product Update Block Page
*}
