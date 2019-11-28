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

{if 'translate'|array_key_exists:$tabs}
    <div id="tab-translate" class="tab-pane">
        <div class="row">
            <div class="col-md-12 nopadding">
                <div class="form-inline">
                    <div class="form-group">
                        <span>{l s='Choose a language' mod='onepagecheckoutps'}</span>
                        <select class="form-control" id="lst-id_lang">
                            {foreach $paramsBack.LANGUAGES as $language}
                                <option value="{$language.iso_code|escape:'htmlall':'UTF-8'}" {if $paramsBack.id_lang == $language.id_lang} selected="selected" {/if}>
                                    {$language.name|escape:'htmlall':'UTF-8'}
                                </option>
                            {/foreach}
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-default" id="btn-save-translation" data-action ="save">
                            <i class="fa-pts fa-pts-floppy-o nohover"></i> {l s='Save' mod='onepagecheckoutps'}
                        </button>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-default" id="btn-save-download-translation" data-action="save_download">
                            <i class="fa-pts fa-pts-download nohover"></i> {l s='Save and Download' mod='onepagecheckoutps'}
                        </button>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-default" id="btn-share-translation">
                            <i class="fa-pts fa-pts-share nohover"></i> {l s='Share us your translation' mod='onepagecheckoutps'}
                        </button>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-default" id="btn-expand-all">
                            <i class="fa-pts fa-pts-expand nohover"></i> {l s='Expand all' mod='onepagecheckoutps'}
                        </button>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-default" id="btn-collapse-all">
                            <i class="fa-pts fa-pts-compress nohover"></i> {l s='Collapse all' mod='onepagecheckoutps'}
                        </button>
                    </div>
                </div>
            </div>
            <div class="clear clearfix">&nbsp;</div>
            <div class="col-md-12 nopadding">
                <div class="alert alert-warning">
                    {l s='Some expressions use the syntax' mod='onepagecheckoutps'}: %s. {l s='Do not replace or modify this.' mod='onepagecheckoutps'}.
                </div>
            </div>
            <div class="col-md-12 nopadding">
                <h4 class="title_manage_settings text-primary">
                    {l s='Management settings' mod='onepagecheckoutps'}
                </h4>
            </div>
            <div class="col-md-12 overlay-translate hidden text-center">
                <img src="{$paramsBack.MODULE_IMG|escape:'htmlall':'UTF-8'}/pts/loader.gif">
            </div>
            <div class="col-md-12 nopadding" id="content_translations">
                <div class="panel-group">
                    {foreach $paramsBack.TRANSLATIONS as $key => $value}
                        {if $key !== 'translate_language'}
                            <div class="panel content_translations" data-file="{$key|escape:'htmlall':'UTF-8'}">
                                <div class="panel-heading" style="white-space: normal; padding: 0px;">
                                    <h4 class="panel-title clearfix" style="text-transform: none; font-weight: bold;">
                                        <a class="accordion-toggle collapsed" data-toggle="collapse" href="#collapse_{$key|escape:'htmlall':'UTF-8'}">
                                            <span>{l s='File' mod='onepagecheckoutps'}: {$key|escape:'htmlall':'UTF-8'}</span>
                                            <span><i class="indicator pull-right fa-pts {if isset($value.empty_elements)} fa-pts-minus {else} fa-pts-plus {/if}"></i></span>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse_{$key|escape:'htmlall':'UTF-8'}" class="panel-collapse collapse {if isset($value.empty_elements)} in {/if}">
                                    <div class="panel-body">
                                        <div class="content_text-translation table-responsive">
                                            <table class="table">
                                                {foreach $value as $key_label => $label_translate}
                                                    {if $key_label !== 'empty_elements'}
                                                        <tr>
                                                            <td>
                                                                <label for="{$key_label|escape:'htmlall':'UTF-8'}" class="control-label col-sm-12">
                                                                    {$label_translate['en']|escape:'htmlall':'UTF-8'}
                                                                </label>
                                                            </td>
                                                            <td>=</td>
                                                            <td class="input_content_translation" width="60%">
                                                                <input type="hidden" value="{$key|escape:'htmlall':'UTF-8'}" name="{$key_label|escape:'htmlall':'UTF-8'}">
                                                                <input type="text" class="form-control {if empty($label_translate['lang_selected'])} input-error-translate {/if}" value="{$label_translate['lang_selected']|escape:'htmlall':'UTF-8'}" name="{$key_label|escape:'htmlall':'UTF-8'}">
                                                            </td>
                                                        </tr>
                                                    {/if}
                                                {/foreach}
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-footer">
                                    <button class="btn btn-default pull-right" name="btn-save-translation-{$key|escape:'htmlall':'UTF-8'}" type="button" data-action="save">
                                        <i class="process-icon-save"></i> {l s='Save' mod='onepagecheckoutps'}
                                    </button>
                                </div>
                            </div>
                        {/if}
                    {/foreach}
                </div>
            </div>
        </div>
    </div>
{/if}

{if 'code_editors'|array_key_exists:$tabs}
    <div class="tab-pane" id="tab-code_editors">
        <div class="col-md-12">
            {foreach $paramsBack.CODE_EDITORS as $key => $row}
                <div class="col-md-12 nopadding">
                    <h4>
                        {$key|escape:'htmlall':'UTF-8'}
                    </h4>
                    <div class="col-md-12">
                        {foreach $row as $value}
                            <form action="{$paramsBack.ACTION_URL|escape:'htmlall':'UTF-8'}" class="form-horizontal form_code_editors">
                                <h4>{$value.filename|escape:'htmlall':'UTF-8'}.{if $key === 'css'}css{else}js{/if}</h4>
                                <div class="form-group">
                                    {strip}<textarea name="txt-{$key|escape:'htmlall':'UTF-8'}-{$value.filename|escape:'htmlall':'UTF-8'}" class="linedtextarea form-control" rows="20" cols="60">
                                        {$value.content|escape:'htmlall':'UTF-8':false:true}
                                    </textarea>{/strip}
                                </div>
                                <div class="form-group">
                                    <button type="button" class="btn btn-default pull-right btn-save-code-editors" data-filepath="{$value.filepath|escape:'htmlall':'UTF-8'}" data-type="{$key|escape:'htmlall':'UTF-8'}" data-name="{$value.filename|escape:'htmlall':'UTF-8'}">
                                        {l s='Save' mod='onepagecheckoutps'}
                                    </button>
                                </div>
                            </form>
                        {/foreach}
                    </div>
                </div>
            {/foreach}
        </div>
    </div>
{/if}

{if 'suggestions'|array_key_exists:$tabs}
    <div id="tab-suggestions" class="tab-pane">
        <div class="row">
            <div class="alert alert-info center-block clearfix">
                <div class="col-sm-12">
                    <div class="col-sm-3 col-md-2">
                        <img src="{$paramsBack.MODULE_IMG|escape:'htmlall':'UTF-8'}/pts/star.png" class="img-responsive">
                    </div>
                    <div class="col-sm-9 col-md-10 text-left content-text-suggestions">
                        {l s='Share with us your suggestions, functionalities and opinions' mod='onepagecheckoutps'}
                        <a id="suggestions-opinions">{l s='Here' mod='onepagecheckoutps'}</a>
                    </div>
                </div>
            </div>
            <div class="alert alert-success center-block clearfix">
                <div class="col-sm-12">
                    <div class="col-sm-3 col-md-2">
                        <img src="{$paramsBack.MODULE_IMG|escape:'htmlall':'UTF-8'}/pts/support.png" class="img-responsive">
                    </div>
                    <div class="col-sm-9 col-md-10 text-left content-text-suggestions">
                        {l s='Do you have any questions or problems regarding our module?' mod='onepagecheckoutps'}
                        <a id="suggestions-contact">{l s='Contact us' mod='onepagecheckoutps'}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
{/if}