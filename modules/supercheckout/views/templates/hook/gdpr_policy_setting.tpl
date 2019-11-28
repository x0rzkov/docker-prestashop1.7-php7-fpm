{if isset($supercheckout_gdpr_setting) && !empty($supercheckout_gdpr_setting)}
    <div id="supercheckout-policy">
        {foreach from=$supercheckout_gdpr_setting item='gdpr_setting'}
            <div class="checkbox">
               
                    <input id="kb_super_policy_{$gdpr_setting['policy_id']|intval}" class="" type="checkbox" name="kb_super_policy[{$gdpr_setting['policy_id']|intval}]" value="1"/>
                     <label>
                    {* Start - Code Modified by Priyanshu on 14-June-2019  to fix the issue of Privacy Policy URL field *}
                    {if $gdpr_setting['url'] neq '' || $gdpr_setting['url'] neq 0}
                        <a href="{$link->getCMSLink($gdpr_setting['url'], null, true, $current_lang_id)}" target="_blank" class="{if $gdpr_setting['is_manadatory'] eq 1} required_policy {/if}" style="" rel="nofollow">
                            {$gdpr_setting['description']} 
                        </a>
                    {* End - Code Modified by Priyanshu on 14-June-2019  to fix the issue of Privacy Policy URL field *}
                        {if $gdpr_setting['is_manadatory']}
                            <span style="display:inline;" class="supercheckout-required">*</span>
                        {/if}
                    {else}
                        {$gdpr_setting['description']}
                        {if $gdpr_setting['is_manadatory']}
                            <span style="display:inline;" class="supercheckout-required">*</span>
                        {/if}
                    {/if}

                </label>
            </div>
        {/foreach}
    </div>
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