{*
* We offer the best and most useful modules PrestaShop and modifications for your online store.
*
* We are experts and professionals in PrestaShop
*
* @author    PresTeamShop.com <support@presteamshop.com>
* @copyright 2011-2019 PresTeamShop
* @license   see file: LICENSE.txt
* @category  PrestaShop
* @category  Module
*}

{if isset($overrides)}
    <div class="alert alert-danger">
        <p>{$message_override_text|escape:'htmlall':'UTF-8'}</p>
        <p>{$message_override_files|escape:'htmlall':'UTF-8'}</p>
        <p>&nbsp;</p>
        <ul>
            {foreach from=$overrides item=override}
                <li>{$override|escape:'quotes':'UTF-8'}</li>
            {/foreach}
        </ul>
    </div>
{else}
    {literal}
        <script>
            $(function() {
                $('#btn_update_version_module').on('click', function (){
                    $.ajax({
                        type: 'POST',
                        url: {/literal}'{$url_call|escape:'quotes':'UTF-8'}'{literal},
                        data: {
                            is_ajax: true,
                            action: 'updateVersion',
                            token: {/literal}'{$token|escape:'htmlall':'UTF-8'}'{literal},
                            dataType: 'html'
                        },
                        beforeSend: function () {
                            $('#btn_update_version_module').attr('disabled', true).addClass('disabled');
                        },
                        success: function (data) {
                            if (data == 'OK') {
                                location.reload();
                            }
                        }
                    });
                });
            });
        </script>
    {/literal}

    <div class="bootstrap panel">
        <div class="alert alert-warning">
            {$message_updated|escape:'htmlall':'UTF-8'} <b>{$module_version|escape:'htmlall':'UTF-8'}</b> {$message_module|escape:'htmlall':'UTF-8'} <b>{$module_name|escape:'htmlall':'UTF-8'}</b>.
            <br/><br/>
            {$message_click|escape:'htmlall':'UTF-8'}: <input id="btn_update_version_module" type="button" class="btn btn-primary btn-xs" value="{$message_now|escape:'htmlall':'UTF-8'}" />
        </div>
    </div>
{/if}