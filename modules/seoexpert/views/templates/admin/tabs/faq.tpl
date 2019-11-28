{*
* 2007-2017 PrestaShop
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2017 PrestaShop SA
* @license   http://addons.prestashop.com/en/content/12-terms-and-conditions-of-use
* International Registered Trademark & Property of PrestaShop SA
*}

<div class="panel col-lg-12 right-panel">
    <h3>
        <i class="fa fa-question-circle"></i> {l s='Help for the seoexpert module' mod='seoexpert'} <small>{$module_display|escape:'htmlall':'UTF-8'}</small>
    </h3>
    <div class="helpContentParent">
        

    <div class="helpContentRight-sub">
        <b>{l s='Need help ?' mod='seoexpert'}</b><br>
        {l s='Find here the documentation of this module' mod='seoexpert'}
        <a class="btn btn-primary" href="{$module_dir|escape:'htmlall':'UTF-8'}{$guide_link|escape:'htmlall':'UTF-8'}" target="_blank" style="margin-left:20px;">
            <i class="fa fa-book"></i> {l s='Documentation' mod='seoexpert'}</a>
        </a>
        <br><br>
        <div class="tab-pane panel" id="faq">
            <div class="panel-heading"><i class="icon-question"></i> {l s='FAQ' mod='seoexpert'}</div>
            {foreach from=$apifaq item=categorie name='faq'}
                <span class="faq-h1">{$categorie->title|escape:'htmlall':'UTF-8'}</span>
                <ul>
                    {foreach from=$categorie->blocks item=QandA}
                        {if !empty($QandA->question)}
                            <li>
                                <span class="faq-h2"><i class="icon-info-circle"></i> {$QandA->question|escape:'htmlall':'UTF-8'}</span>
                                <p class="faq-text hide">
                                    {$QandA->answer|escape:'htmlall':'UTF-8'|replace:"\n":"<br />"}
                                </p>
                            </li>
                        {/if}
                    {/foreach}
                </ul>
                {if !$smarty.foreach.faq.last}<hr/>{/if}
            {/foreach}
        </div>
        <br />
        {l s='You couldn\'t find any answer to your question ?' mod='seoexpert'}
        <b><a href="https://addons.prestashop.com/contact-form.php" target="_blank">{l s='Contact us on PrestaShop Addons' mod='seoexpert'}</a></b>
    </div>
</div>

</div>

