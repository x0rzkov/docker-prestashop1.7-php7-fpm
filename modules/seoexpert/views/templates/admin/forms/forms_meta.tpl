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

<form id="form_add" name="form_metas">
	<div id="wizard" class="swMain">
		{*<div class="alert alert-info">{$shop_name|escape:'htmlall':'UTF-8'}</div> *}
		<ul class="anchor">
			<li>
				<a href="#step-1"  class="selected" >
					<div class="stepNumber">1</div>
					<span class="stepDesc">{l s='General' mod='seoexpert'}</span>
				</a>
			</li>
			<li>
				<a href="#step-2">
					<div class="stepNumber">2</div>
					<span class="stepDesc">{l s='Categories' mod='seoexpert'}</span>
				</a>
			</li>
			<li>
				<a href="#step-3">
					<div class="stepNumber">3</div>
					<span class="stepDesc">{l s='Setup metatags' mod='seoexpert'}</span>
				</a>
			</li>
			<li>
				<a href="#step-4">
					<div class="stepNumber">4</div>
					<span class="stepDesc">{l s='Facebook metatags' mod='seoexpert'}</span>
				</a>
			</li>
			<li>
				<a href="#step-5">
					<div class="stepNumber">5</div>
					<span class="stepDesc">{l s='Twitter metatags' mod='seoexpert'}</span>
				</a>
			</li>
		</ul>

		<hr class="clear"/>
		<div class="clearfix"></div>
		<div id="step-1">
			<div class="alert alert-info">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				{l s='Names the setting you create so that you can identify it at any time. We advise you to choose a setting name that reminds you of the category selected.' mod='seoexpert'} <a target="_blank" href="{$module_dir|escape:'htmlall':'UTF-8'}{$guide_link|escape:'htmlall':'UTF-8'}">{l s='(C.f. documentation).' mod='seoexpert'}</a>
			</div>
			<div class="form-group clear">
				<label for="form-field-1" class="col-sm-4 control-label">
					{l s='Rule name' mod='seoexpert'}
				</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" value="{if isset($rule_name) & !empty($rule_name)}{$rule_name|escape:'htmlall':'UTF-8'}{/if}" id="rule_name" name="rule_name" placeholder="{l s='Rule name' mod='seoexpert'}" />
					<div class="clear">&nbsp;</div>
				</div>
			</div>
			<div class="form-group clear">
				<label for="form-field-1" class="col-sm-4 control-label">
					{if $object|intval > 0}
					{l s='Rule lang' mod='seoexpert'}
					{else}
					{l s='Select you lang' mod='seoexpert'}
					{/if}
				</label>
				<div class="col-sm-6">
					{if $object|intval > 0}
						{foreach $lang_select as $lang}
							{if $lang.id === $rule_lang}
							{$lang.title|escape:'htmlall':'UTF-8'} ({$lang.subtitle|escape:'htmlall':'UTF-8'})
							<input type="hidden" id="select_lang" name="select_lang" value="{$lang.id|intval}" />
							{/if}
						{/foreach}
					{else}
					<select id="select_lang" name="select_lang" class="selectpicker show-menu-arrow show-tick" data-show-subtext="true">
						{foreach $lang_select as $lang}
							<option value="{$lang.id|intval}" {if ($lang.id === $default_lang) || ($lang.id === $rule_lang)}selected="selected"{/if} {if !empty($lang.subtitle)}data-subtext="{$lang.subtitle|escape:'htmlall':'UTF-8'}"{/if} data-icon="icon-flag">{$lang.title|escape:'htmlall':'UTF-8'}</option>
						{/foreach}
					</select>
					{/if}
					<div class="clear">&nbsp;</div>
				</div>
			</div>
		</div>
		<div id="step-2">
			<div class="alert alert-info">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				{l s='This step allows you to select which product categories you would like to apply the Meta tag setting for.' mod='seoexpert'} <a target="_blank" href="{$module_dir|escape:'htmlall':'UTF-8'}{$guide_link|escape:'htmlall':'UTF-8'}">{l s='(C.f. documentation).' mod='seoexpert'}</a>
			</div>

			<div class="form-group clear">
				<label for="form-field-1" class="col-sm-4 control-label">
					{l s='Category' mod='seoexpert'}
				</label>
				<div class="col-sm-6">
					<div class="form-group clear">
						<div id="radios-0" class="radio">
							<label for="form-field-5">
								<input type="radio" name="categorys_id" value="0">
								&nbsp;{l s='All category' mod='seoexpert'}
							</label>
						</div>
						{if $blockCategTree != false}
						<div id="radios-1" class="radio">
							<label for="form-field-5">
								<input type="radio" name="categorys_id" value="-1">
								&nbsp;{l s='Select a category' mod='seoexpert'}
							</label>
						</div>
						{/if}
					</div>
					{if $blockCategTree != false}
					<div id="catree" class="hide">
						<div>
							<div id="button_tree" class="btn-group pull-left">
								<button id="expandall"type="button" class="btn btn-xs btn-default"> {l s='Expand All' mod='seoexpert'}</button>
								<button id="collapseall"type="button" class="btn btn-xs btn-default"> {l s='Collapse All' mod='seoexpert'}</button>
								<button id="checkall" type="button" class="btn btn-xs btn-default"> {l s='Check All' mod='seoexpert'}</button>
								<button id="uncheckall" type="button" class="btn btn-xs btn-default"> {l s='Uncheck All' mod='seoexpert'}</button>
							</div>
						</div>

						<div id="jstree" class="clear">
							<ul>
								<li id="category_{$blockCategTree.id|intval}" class="jstree-open">
									{foreach from=$default_category item=def}
										{if $def['id_obj'] == $blockCategTree.id}
											<a class="jstree-clicked">{$blockCategTree.name|escape:'htmlall':'UTF-8'}</a>
										{else}
											{$blockCategTree.name|escape:'htmlall':'UTF-8'}
										{/if}
									{/foreach}
									<ul>
										{foreach from=$blockCategTree.children item=child name=blockCategTree}
											{if $smarty.foreach.blockCategTree.last}
												{include file="$branche_tpl_path" node=$child last='true'}
											{else}
												{include file="$branche_tpl_path" node=$child}
											{/if}
										{/foreach}
									</ul>
								</li>
							</ul>
						</div>
						<input type="hidden" name="category_id" id="category_id" value="" />
					</div>
					{/if}
				</div>
			</div>
		</div>
		<div id="step-3">
			<div class="alert alert-info">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				{l s='You can choose the composition of the meta tags you plan to apply to your product pages.' mod='seoexpert'} <a target="_blank" href="{$module_dir|escape:'htmlall':'UTF-8'}{$guide_link|escape:'htmlall':'UTF-8'}">{l s='(C.f. documentation).' mod='seoexpert'}</a>
			</div>
			<div class="col-lg-8">
				<div class="form-group clear">
					<label for="form-field-1" class="col-sm-4 control-label">
						{l s='Meta title tag' mod='seoexpert'}
					</label>
					<div class="col-lg-8">
						<div class="tooltips" data-toggle="tooltip" data-placement="top" data-original-title="{l s='It is made up of the blue text that appears on a page of Google results.' mod='seoexpert'}">
							<input type="text" class="form-control showlist" value="{if isset($meta_title) & !empty($meta_title)}{$meta_title|escape:'htmlall':'UTF-8'}{/if}" id="meta_title" name="meta_title" placeholder="{l s='Meta title tag' mod='seoexpert'}" />
						</div>
						<div class="clear">&nbsp;</div>
					</div>
				</div>
				<div class="form-group clear">
					<label for="form-field-1" class="col-sm-4 control-label">
						{l s='Meta description tag' mod='seoexpert'}
					</label>
					<div class="col-lg-8">
						<div class="tooltips" data-toggle="tooltip" data-placement="top" data-original-title="{l s='This is the gray text that appears after performing a Google search. It is used to guide internet users and is what encourages them to click on your website.' mod='seoexpert'}">
							<input type="text" class="form-control showlist" value="{if isset($meta_description) & !empty($meta_description)}{$meta_description|escape:'htmlall':'UTF-8'}{/if}" id="meta_description" name="meta_description" placeholder="{l s='Meta description tag' mod='seoexpert'}" />
						</div>
						<div class="clear">&nbsp;</div>
					</div>
				</div>
				<div class="form-group clear">
					<label for="form-field-1" class="col-sm-4 control-label">
						{l s='Meta keyword tag' mod='seoexpert'}
					</label>
					<div class="col-lg-8">
						<div class="tooltips" data-toggle="tooltip " data-placement="top" data-original-title="{l s='Keywords are used to garner the most pertinent results in relation to the performed search.' mod='seoexpert'}">
							<input type="text" class="form-control showlist" value="{if isset($meta_keywords) & !empty($meta_keywords)}{$meta_keywords|escape:'htmlall':'UTF-8'}{/if}" id="meta_keywords" name="meta_keywords" placeholder="{l s='Meta keyword tag' mod='seoexpert'}">
						</div>
						<div class="clear">&nbsp;</div>
					</div>
				</div>
			</div>
			<div class="col-lg-4 pull-right">
				{include file="./patterns.tpl" social=false}
			</div>
		</div>

		<div id="step-4">
			<div class="alert alert-info">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				{l s='Configure Facebook meta tags, which will help boost traffic to your website. Admin ID and Application ID are not mandatory fields. If you do not have any Facebook page, you can skip this step.' mod='seoexpert'} <a target="_blank" href="{$module_dir|escape:'htmlall':'UTF-8'}{$guide_link|escape:'htmlall':'UTF-8'}">{l s='(C.f. documentation).' mod='seoexpert'}</a>
			</div>
			{include file="./forms_fb.tpl"}
		</div>

		<div id="step-5">
			<div class="alert alert-info">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				{l s='Configure Twitter meta tags, which describe the content you share. If you do not have any Twitter account, you can skip this step.' mod='seoexpert'} <a target="_blank" href="{$module_dir|escape:'htmlall':'UTF-8'}{$guide_link|escape:'htmlall':'UTF-8'}">{l s='(C.f. documentation).' mod='seoexpert'}</a>
			</div>
			{include file="./forms_tw.tpl"}
		</div>
	</div>
</form>