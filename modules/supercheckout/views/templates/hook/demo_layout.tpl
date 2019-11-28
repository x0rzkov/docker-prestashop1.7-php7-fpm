<div class="kb-supercheckout-demo-blk">
    <h3 class="kb-super-demo-heading">{l s='One Page Supercheckout Demo' mod='supercheckout'}</h3>
    <p>{l s='Click below to view the supercheckout demo in different layouts' mod='supercheckout'}</p>
    <div class="kb-super-demo-content col-lg-12">
        <div class="col-lg-4 col-md-4 col-sm-4">
            <a class="btn btn-warning kb-demo-btn" href="{$one_column_link}{*Variable contains url, escape not required*}">{l s='DEMO 1: One Column' mod='supercheckout'}</a>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4">
            <a class="btn btn-warning kb-demo-btn" href="{$two_column_link}{*Variable contains url, escape not required*}">{l s='DEMO 2: Two Column' mod='supercheckout'}</a>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4">
            <a class="btn btn-warning kb-demo-btn" href="{$three_column_link}{*Variable contains url, escape not required*}">{l s='DEMO 3: Three Column' mod='supercheckout'}</a>
        </div>
    </div>
</div>
<div style="clear: both;"></div>
<style>
    .kb-demo-btn {
        background-color: #f77219 !important;
        border-color: #f26100 !important;
        font-weight : bold;
        white-space: normal;
    }
    .kb-demo-btn:hover {
        background-color: #f88c43 !important;
        border-color: #f77219 !important;
    }
    .kb-supercheckout-demo-blk {
        -moz-box-shadow: rgb(240, 173, 78) 0px 2px 15px;
        -webkit-box-shadow: rgb(240, 173, 78) 0px 2px 15px;
        box-shadow: rgb(240, 173, 78) 0px 2px 15px;
        border-radius: 4px;
        text-align: center;
        border: 1px solid #f77219;
        clear: both;
        background: #fff;
        margin-bottom: 1.5rem;
        padding: 4.125rem;
        padding-top: 30px;

    }
    .kb-super-demo-heading {
        font-weight: 600;
        color: #000;
        text-align: center;
    }
    .kb-super-demo-content {
        text-align: center;
        display: inline-block;
    }
    @media(max-width:992px)
    {
        .kb-super-demo-content.col-lg-12 {
            width: 80%;
            margin: 0 auto;
        }
    }
    @media(max-width:767px)
    {
        .kb-super-demo-content.col-lg-12 {
            width: 100%;
            margin: 0 auto;
        }
        .kb-super-demo-content .col-lg-4.col-md-4.col-sm-4 {
            width: 100%;
            margin-bottom:10px;

            display:inline-block;
            padding:0;
        }
        .kb-demo-btn{
            width:100%;
        }
    }
    @media(max-width:600px)
    {
        .kb-super-demo-content.col-lg-12 {
            padding:0;
        }
    }
    @media(max-width:370px)
    {
        .kb-super-demo-content .col-lg-4.col-md-4.col-sm-4 {
            width: 100%;
            margin-bottom:5px;
        }
        .kb-super-demo-content .col-lg-4.col-md-4.col-sm-4 a {
            max-width: 100%;
        }
    }
</style>

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
* @copyright 2016 Knowband
*}