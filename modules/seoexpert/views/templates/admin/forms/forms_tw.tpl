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

<div class="col-lg-12">
	<div class="col-lg-8">
		<div class="form-group clear">
			<label for="form-field-1" class="col-sm-4 control-label">
				{l s='Twitter card type' mod='seoexpert'}
			</label>
			<div class="col-lg-8">
				<select id="tw_card_type" name="tw_card_type" name="class" class="selectpicker show-menu-arrow show-tick">
					<option value="" {if !isset($tw_card_type) && empty($tw_card_type)}selected="selected"{/if}>{l s='- None -' mod='seoexpert'}</option>
					<option value="summary" {if isset($tw_card_type) && !empty($tw_card_type) && $tw_card_type == 'summary'}selected="selected"{/if}>{l s='Summary' mod='seoexpert'}</option>
					<option value="summary_large_image" {if isset($tw_card_type) && !empty($tw_card_type) && $tw_card_type == 'summary_large_image'}selected="selected"{/if}>{l s='Summary with large image' mod='seoexpert'}</option>
					<option value="photo" {if isset($tw_card_type) && !empty($tw_card_type) && $tw_card_type == 'photo'}selected="selected"{/if}>{l s='Photo' mod='seoexpert'}</option>
					<option value="gallery" {if isset($tw_card_type) && !empty($tw_card_type) && $tw_card_type == 'gallery'}selected="selected"{/if}>{l s='Gallery' mod='seoexpert'}</option>
					<option value="product" {if isset($tw_card_type) && !empty($tw_card_type) && $tw_card_type == 'product'}selected="selected"{/if}>{l s='Product' mod='seoexpert'}</option>
				</select>
			</div>
		</div>
		<div id="tw_global" class="hide">
			<div class="form-group clear">
				<label for="form-field-1" class="col-sm-4 control-label">
					{l s='Twitter account' mod='seoexpert'}
				</label>
				<div class="col-lg-8">
					<div class="tooltips" data-toggle="tooltip" data-placement="top" data-original-title="{l s='The @username for the website, which will be displayed in the Card\'s footer; must include the @ symbol.' mod='seoexpert'}">
						<input type="text" class="form-control" value="{if isset($tw_username) & !empty($tw_username)}{$tw_username|escape:'htmlall':'UTF-8'}{/if}" id="tw_username" name="tw_username" placeholder="{l s='Site\'s Twitter account' mod='seoexpert'}">
					</div>
					<div class="clear">&nbsp;</div>
				</div>
			</div>
			<div class="form-group clear">
				<label for="form-field-1" class="col-sm-4 control-label">
					{l s='Title' mod='seoexpert'}
				</label>
				<div class="col-lg-8">
					<div class="tooltips" data-toggle="tooltip" data-placement="top" data-original-title="{l s='The page\'s title, which should be concise; it will be truncated at 70 characters by Twitter.' mod='seoexpert'}">
						<input type="text" class="form-control showlist" value="{if isset($tw_title) & !empty($tw_title)}{$tw_title|escape:'htmlall':'UTF-8'}{/if}" id="tw_title" name="tw_title" placeholder="{l s='Title' mod='seoexpert'}">
					</div>
					<div class="clear">&nbsp;</div>
				</div>
			</div>
			<div class="form-group clear">
				<label for="form-field-1" class="col-sm-4 control-label">
					{l s='Description' mod='seoexpert'}
				</label>
				<div class="col-lg-8">
					<div class="tooltips" data-toggle="tooltip" data-placement="top" data-original-title="{l s='A description that concisely summarizes the content of the page, as appropriate for presentation within a Tweet. Do not re-use the title text as the description, or use this field to describe the general services provided by the website. The string will be truncated, by Twitter, at the word to 200 characters.' mod='seoexpert'}">
						<input type="text" class="form-control showlist" value="{if isset($tw_description) & !empty($tw_description)}{$tw_description|escape:'htmlall':'UTF-8'}{/if}" id="tw_description" name="tw_description" placeholder="{l s='Description' mod='seoexpert'}">
					</div>
					<div class="clear">&nbsp;</div>
				</div>
			</div>
			<div class="form-group clear">
				<label for="form-field-1" class="col-sm-4 control-label">
					{l s='Image size' mod='seoexpert'}
				</label>
				<div class="col-lg-8">
					<select id="tw_img_size" name="tw_img_size" name="class" class="selectpicker show-menu-arrow show-tick">
						<option value="" {if !isset($tw_img_size) & empty($tw_img_size)}selected="selected"{/if}>{l s='- None -' mod='seoexpert'}</option>
						{foreach from=$tw_img key=k item=v}
							<option value="{$k|escape:'htmlall':'UTF-8'}" {if isset($tw_img_size) && !empty($tw_img_size) && $tw_img_size == $k}selected="selected"{/if}>{$v|escape:'htmlall':'UTF-8'}</option>
						{/foreach}
					</select>
					<div class="clear">&nbsp;</div>
				</div>
			</div>
		</div>

		<div id="tw_product" class="hide">
			<div class="form-group clear">
				<label for="form-field-1" class="col-sm-4 control-label">
					{l s='Characteristic 1' mod='seoexpert'}
				</label>
				<div class="col-lg-8">
					<select id="tw_data1" name="tw_data[]" name="class" class="selectpicker show-menu-arrow show-tick">
						<option value="" {if !isset($tw_data_1) && empty($tw_data_1)}selected="selected"{/if}>{l s='- None -' mod='seoexpert'}</option>
						<optgroup label="{l s='Price' mod='seoexpert'}">
							<option {if isset($tw_data_1) && !empty($tw_data_1) && $tw_data_1 == '{product_price}'}selected="selected"{/if} value="{literal}{{/literal}product_price{literal}}{/literal}">{l s='Retail price with tax' mod='seoexpert'}</option>
							<option {if isset($tw_data_1) && !empty($tw_data_1) && $tw_data_1 == '{product_price_wt}'}selected="selected"{/if} value="{literal}{{/literal}product_price_wt{literal}}{/literal}">{l s='Pre-tax retail price' mod='seoexpert'}</option>
						</optgroup>
						<optgroup label="{l s='Size' mod='seoexpert'}">
							<option {if isset($tw_data_1) && !empty($tw_data_1) && $tw_data_1 == '{product_width}'}selected="selected"{/if} value="{literal}{{/literal}product_width{literal}}{/literal}">{l s='Width (package)' mod='seoexpert'}</option>
							<option {if isset($tw_data_1) && !empty($tw_data_1) && $tw_data_1 == '{product_length}'}selected="selected"{/if} value="{literal}{{/literal}product_length{literal}}{/literal}">{l s='Length (package)' mod='seoexpert'}</option>
							<option {if isset($tw_data_1) && !empty($tw_data_1) && $tw_data_1 == '{product_depth}'}selected="selected"{/if} value="{literal}{{/literal}product_depth{literal}}{/literal}">{l s='Depth (package)' mod='seoexpert'}</option>
							<option {if isset($tw_data_1) && !empty($tw_data_1) && $tw_data_1 == '{product_weight}'}selected="selected"{/if} value="{literal}{{/literal}product_weight{literal}}{/literal}">{l s='Weight (package)' mod='seoexpert'}</option>
							<option {if isset($tw_data_1) && !empty($tw_data_1) && $tw_data_1 == '{product_volume}'}selected="selected"{/if} value="{literal}{{/literal}product_volume{literal}}{/literal}">{l s='Volume (package)' mod='seoexpert'}</option>
						</optgroup>
						<optgroup label="{l s='Standard' mod='seoexpert'}">
							<option {if isset($tw_data_1) && !empty($tw_data_1) && $tw_data_1 == '{product_condition}'}selected="selected"{/if} value="{literal}{{/literal}product_condition{literal}}{/literal}">{l s='Condition' mod='seoexpert'}</option>
							<option {if isset($tw_data_1) && !empty($tw_data_1) && $tw_data_1 == '{manufacturer_name}'}selected="selected"{/if} value="{literal}{{/literal}manufacturer_name{literal}}{/literal}">{l s='Manufacturer' mod='seoexpert'}</option>
							<option {if isset($tw_data_1) && !empty($tw_data_1) && $tw_data_1 == '{product_reference}'}selected="selected"{/if} value="{literal}{{/literal}product_reference{literal}}{/literal}">{l s='Reference' mod='seoexpert'}</option>
							<option {if isset($tw_data_1) && !empty($tw_data_1) && $tw_data_1 == '{product_ean13}'}selected="selected"{/if} value="{literal}{{/literal}product_ean13{literal}}{/literal}">{l s='EAN13 or JAN' mod='seoexpert'}</option>
							<option {if isset($tw_data_1) && !empty($tw_data_1) && $tw_data_1 == '{product_upc}'}selected="selected"{/if} value="{literal}{{/literal}product_upc{literal}}{/literal}">{l s='UPC' mod='seoexpert'}</option>
							<option {if isset($tw_data_1) && !empty($tw_data_1) && $tw_data_1 == '{product_quantity}'}selected="selected"{/if} value="{literal}{{/literal}product_quantity{literal}}{/literal}">{l s='Quantity' mod='seoexpert'}</option>
						</optgroup>
					</select>
					<div class="clear">&nbsp;</div>
				</div>
			</div>
			<div class="form-group clear">
				<label for="form-field-1" class="col-sm-4 control-label">
					{l s='Characteristic 2' mod='seoexpert'}
				</label>
				<div class="col-lg-8">
					<select id="tw_data2" name="tw_data[]" name="class" class="selectpicker show-menu-arrow show-tick">
						<option value="" {if !isset($tw_data_2) && empty($tw_data_2)}selected="selected"{/if}>{l s='- None -' mod='seoexpert'}</option>
						<optgroup label="{l s='Price' mod='seoexpert'}">
							<option {if isset($tw_data_2) && !empty($tw_data_2) && $tw_data_2 == '{product_price}'}selected="selected"{/if} value="{literal}{{/literal}product_price{literal}}{/literal}">{l s='Retail price with tax' mod='seoexpert'}</option>
							<option {if isset($tw_data_2) && !empty($tw_data_2) && $tw_data_2 == '{product_price_wt}'}selected="selected"{/if} value="{literal}{{/literal}product_price_wt{literal}}{/literal}">{l s='Pre-tax retail price' mod='seoexpert'}</option>
						</optgroup>
						<optgroup label="{l s='Size' mod='seoexpert'}">
							<option {if isset($tw_data_2) && !empty($tw_data_2) && $tw_data_2 == '{product_width}'}selected="selected"{/if} value="{literal}{{/literal}product_width{literal}}{/literal}">{l s='Width (package)' mod='seoexpert'}</option>
							<option {if isset($tw_data_2) && !empty($tw_data_2) && $tw_data_2 == '{product_length}'}selected="selected"{/if} value="{literal}{{/literal}product_length{literal}}{/literal}">{l s='Length (package)' mod='seoexpert'}</option>
							<option {if isset($tw_data_2) && !empty($tw_data_2) && $tw_data_2 == '{product_depth}'}selected="selected"{/if} value="{literal}{{/literal}product_depth{literal}}{/literal}">{l s='Depth (package)' mod='seoexpert'}</option>
							<option {if isset($tw_data_2) && !empty($tw_data_2) && $tw_data_2 == '{product_weight}'}selected="selected"{/if} value="{literal}{{/literal}product_weight{literal}}{/literal}">{l s='Weight (package)' mod='seoexpert'}</option>
							<option {if isset($tw_data_2) && !empty($tw_data_2) && $tw_data_2 == '{product_volume}'}selected="selected"{/if} value="{literal}{{/literal}product_volume{literal}}{/literal}">{l s='Volume (package)' mod='seoexpert'}</option>
						</optgroup>
						<optgroup label="{l s='Standard' mod='seoexpert'}">
							<option {if isset($tw_data_2) && !empty($tw_data_2) && $tw_data_2 == '{product_condition}'}selected="selected"{/if} value="{literal}{{/literal}product_condition{literal}}{/literal}">{l s='Condition' mod='seoexpert'}</option>
							<option {if isset($tw_data_2) && !empty($tw_data_2) && $tw_data_2 == '{manufacturer_name}'}selected="selected"{/if} value="{literal}{{/literal}manufacturer_name{literal}}{/literal}">{l s='Manufacturer' mod='seoexpert'}</option>
							<option {if isset($tw_data_2) && !empty($tw_data_2) && $tw_data_2 == '{product_reference}'}selected="selected"{/if} value="{literal}{{/literal}product_reference{literal}}{/literal}">{l s='Reference' mod='seoexpert'}</option>
							<option {if isset($tw_data_2) && !empty($tw_data_2) && $tw_data_2 == '{product_ean13}'}selected="selected"{/if} value="{literal}{{/literal}product_ean13{literal}}{/literal}">{l s='EAN13 or JAN' mod='seoexpert'}</option>
							<option {if isset($tw_data_2) && !empty($tw_data_2) && $tw_data_2 == '{product_upc}'}selected="selected"{/if} value="{literal}{{/literal}product_upc{literal}}{/literal}">{l s='UPC' mod='seoexpert'}</option>
							<option {if isset($tw_data_2) && !empty($tw_data_2) && $tw_data_2 == '{product_quantity}'}selected="selected"{/if} value="{literal}{{/literal}product_quantity{literal}}{/literal}">{l s='Quantity' mod='seoexpert'}</option>
						</optgroup>
					</select>
					<div class="clear">&nbsp;</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-4 pull-right">
		{include file="./patterns.tpl" social=true}
	</div>
</div>