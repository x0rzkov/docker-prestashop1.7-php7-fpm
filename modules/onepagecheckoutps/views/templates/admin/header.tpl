{*
* We offer the best and most useful modules PrestaShop and modifications for your online store.
*
* We are experts and professionals in PrestaShop
*
* @category  PrestaShop
* @category  Module
* @author    PresTeamShop.com <support@presteamshop.com>
* @copyright 2011-2019 PresTeamShop
* @license   see file: LICENSE.txt
*}

{foreach from=$paramsBack.JS_FILES item="file"}
    <script type="text/javascript" src="{$file|escape:'htmlall':'UTF-8'}"></script>
{/foreach}
{foreach from=$paramsBack.CSS_FILES item="file"}
    <link type="text/css" rel="stylesheet" href="{$file|escape:'htmlall':'UTF-8'}"/>
{/foreach}

<script type="text/javascript">
    {*removeIf(addons)*}
    var url_contact_presteam = 'http://www.presteamshop.com/en/contact-us';
    var url_opinions_presteam = 'https://www.presteamshop.com/en/modules-prestashop/one-page-checkout-prestashop.html?ifb=1';
    {*endRemoveIf(addons)*}
    var url_contact_addons = 'https://addons.prestashop.com/en/write-to-developper?id_product=8503';
    var url_opinions_addons = 'http://addons.prestashop.com/ratings.php';
    var iso_lang_backoffice_shop = '{$paramsBack.ISO_LANG_BACKOFFICE_SHOP|escape:'htmlall':'UTF-8'}';

    var remote_addr = '{$paramsBack.remote_addr|escape:'htmlall':'UTF-8'}';

    var module_dir = "{$paramsBack.MODULE_DIR|escape:'htmlall':'UTF-8'}";
    var module_img = "{$paramsBack.MODULE_IMG|escape:'htmlall':'UTF-8'}";
    var pts_static_token = '{$paramsBack.OPC_STATIC_TOKEN|escape:'htmlall':'UTF-8'}';
    var class_name = 'App{$paramsBack.MODULE_PREFIX|escape:'htmlall':'UTF-8'}';

    //status codes
    var ERROR_CODE = {$paramsBack.ERROR_CODE|intval};
    var SUCCESS_CODE = {$paramsBack.SUCCESS_CODE|intval};

    var onepagecheckoutps_dir = '{$paramsBack.MODULE_DIR|escape:'htmlall':'UTF-8'}';
    var onepagecheckoutps_img = '{$paramsBack.MODULE_IMG|escape:'htmlall':'UTF-8'}';
    var GLOBALS_JS = {$paramsBack.GLOBALS_JS|escape:'quotes':'UTF-8'};
    var id_language_default = Number({$paramsBack.DEFAULT_LENGUAGE|intval});
    var iso_lang_backoffice_shop = '{$paramsBack.iso_lang_backoffice_shop|escape:'htmlall':'UTF-8'}';

    //languages
    var id_language = {$paramsBack.DEFAULT_LENGUAGE|intval};
    var languages = [];
    var languages_iso = [];
    var languages_name = [];

    {foreach from=$paramsBack.LANGUAGES item=language name=f_languages}
        languages.push({$language.id_lang|intval});
        languages_iso.push('{$language.iso_code|escape:'htmlall':'UTF-8'}');
        languages_name.push('{$language.name|escape:'htmlall':'UTF-8'}');
    {/foreach}
    var static_token = '{$paramsBack.STATIC_TOKEN|escape:'htmlall':'UTF-8'}';

    var actions_controller_url = '{$paramsBack.ACTIONS_CONTROLLER_URL|escape:'quotes':'UTF-8'}';
</script>

<script type="text/javascript">
    var Msg = {ldelim}
        update_ship_to_pay: {ldelim}
            off: "{l s='Updating association...' mod='onepagecheckoutps' js=1}",
            on: "{l s='Update' mod='onepagecheckoutps' js=1}"
        {rdelim},
        change: "{l s='Change' mod='onepagecheckoutps' js=1}",
        only_gif: "{l s='Only gif images are allowed.' mod='onepagecheckoutps' js=1}",
        select_file: "{l s='You must select one file.' mod='onepagecheckoutps' js=1}",
        edit_field: "{l s='Edit field.' mod='onepagecheckoutps' js=1}",
        new_field: "{l s='New field.' mod='onepagecheckoutps' js=1}",
        confirm_remove_field: "{l s='Are you sure to want remove this field?' mod='onepagecheckoutps' js=1}",
        cannot_remove_field: "{l s='Only custom fields can be removed' mod='onepagecheckoutps' js=1}",
        manage_field_options: "{l s='Manage field options' mod='onepagecheckoutps' js=1}",
        add_IP: "{l s='Add IP' mod='onepagecheckoutps' js=1}",
        required_default_country: "{l s='The default value of this field can not be empty, you must enter the ID of a country' mod='onepagecheckoutps' js=1}",
        chart_title: "{l s='Connections number per social network' mod='onepagecheckoutps' js=1}"
    {rdelim};
</script>

{if $paramsBack.ERRORS}
    {foreach from=$paramsBack.ERRORS item='warning'}
        <div class="alert alert-danger">
            {$warning|escape:'quotes'}
        </div>
    {/foreach}
{/if}
{if $paramsBack.WARNINGS}
    {foreach from=$paramsBack.WARNINGS item='warning'}
        <div class="alert alert-warning">
            {$warning|escape:'quotes'}
        </div>
    {/foreach}
{/if}