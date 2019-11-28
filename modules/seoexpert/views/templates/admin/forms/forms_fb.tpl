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
	{*
	<ul class="nav nav-tabs">
		<li class="active"><a href="#general" data-toggle="tab">{l s='General' mod='seoexpert'}</a></li>
		<li><a href="#video" data-toggle="tab">{l s='Video' mod='seoexpert'}</a></li>
		<li><a href="#localization" data-toggle="tab">{l s='Localization' mod='seoexpert'}</a></li>
	</ul>
	*}

	<div class="col-lg-8">
		{*<div class="tab-content">*}
			<!-- TABS General -->
			<div class="tab-pane active" id="general">
				<div class="form-group clear">
					<label for="form-field-1" class="col-sm-4 control-label">
						{l s='Admin IDs' mod='seoexpert'}
					</label>
					<div class="col-lg-8">
						<div class="tooltips" data-toggle="tooltip" data-placement="top" data-original-title="{l s='A comma-separated list of Facebook user IDs of people who are considered administrators or moderators of this page' mod='seoexpert'}">
							<input type="text" class="form-control" value="{if isset($fb_admins) & !empty($fb_admins)}{$fb_admins|escape:'htmlall':'UTF-8'}{/if}" id="fb_admins" name="fb_admins" placeholder="{l s='Facebook Admins' mod='seoexpert'}">
						</div>
						<div class="clear">&nbsp;</div>
					</div>
				</div>
				<div class="form-group clear">
					<label for="form-field-1" class="col-sm-4 control-label">
						{l s='Application ID' mod='seoexpert'}
					</label>
					<div class="col-lg-8">
						<div class="tooltips" data-toggle="tooltip" data-placement="top" data-original-title="{l s='A comma-separated list of Facebook Platform Application IDs applicable for this site' mod='seoexpert'}">
							<input type="text" class="form-control" value="{if isset($fb_appid) & !empty($fb_appid)}{$fb_appid|escape:'htmlall':'UTF-8'}{/if}" id="fb_appid" name="fb_appid" placeholder="{l s='Facebook Application ID' mod='seoexpert'}">
						</div>
						<div class="clear">&nbsp;</div>
					</div>
				</div>
				<div class="form-group clear">
					<label for="form-field-1" class="col-sm-4 control-label">
						{l s='Title' mod='seoexpert'}
					</label>
					<div class="col-lg-8">
						<div class="tooltips" data-toggle="tooltip" data-placement="top" data-original-title="{l s='The title of your object as it should appear within the graph, e.g., The Rock' mod='seoexpert'}">
							<input type="text" class="form-control showlist" value="{if isset($fb_title) & !empty($fb_title)}{$fb_title|escape:'htmlall':'UTF-8'}{/if}" id="fb_title" name="fb_title" placeholder="{l s='title' mod='seoexpert'}">
						</div>
						<div class="clear">&nbsp;</div>
					</div>
				</div>
				<div class="form-group clear">
					<label for="form-field-1" class="col-sm-4 control-label">
						{l s='Description' mod='seoexpert'}
					</label>
					<div class="col-lg-8">
						<div class="tooltips" data-toggle="tooltip" data-placement="top" data-original-title="{l s='A one to two sentence description of your page' mod='seoexpert'}">
							<input type="text" class="form-control showlist" value="{if isset($fb_desc) & !empty($fb_desc)}{$fb_desc|escape:'htmlall':'UTF-8'}{/if}" id="fb_desc" name="fb_desc" placeholder="{l s='description' mod='seoexpert'}">
						</div>
						<div class="clear">&nbsp;</div>
					</div>
				</div>
				<div class="form-group clear">
					<label for="form-field-1" class="col-sm-4 control-label">
						{l s='Type' mod='seoexpert'}
					</label>
					<div class="col-lg-8">
						<select id="fb_type" name="fb_type" class="selectpicker show-menu-arrow show-tick" data-live-search="true">
							<option value="" {if !isset($fb_type) & empty($fb_type)}selected="selected"{/if}>{l s='- None -' mod='seoexpert'}</option>
							<optgroup label="Activities">
								<option value="activity" {if isset($fb_type) && !empty($fb_type) && $fb_type == 'activity'}selected="selected"{/if}>Activity</option>
								<option value="sport" {if isset($fb_type) & !empty($fb_type) && $fb_type == 'sport'}selected="selected"{/if}>Sport</option>
							</optgroup>
							<optgroup label="Businesses">
								<option value="bar" {if isset($fb_type) & !empty($fb_type) && $fb_type == 'bar'}selected="selected"{/if}>Bar</option>
								<option value="company" {if isset($fb_type) & !empty($fb_type) && $fb_type == 'company'}selected="selected"{/if}>Company</option>
								<option value="cafe" {if isset($fb_type) & !empty($fb_type) && $fb_type == 'cafe'}selected="selected"{/if}>Cafe</option>
								<option value="hotel" {if isset($fb_type) & !empty($fb_type) && $fb_type == 'hotel'}selected="selected"{/if}>Hotel</option>
								<option value="restaurant" {if isset($fb_type) & !empty($fb_type) && $fb_type == 'restaurant'}selected="selected"{/if}>Restaurant</option>
							</optgroup>
							<optgroup label="Groups">
								<option value="cause" {if isset($fb_type) & !empty($fb_type) && $fb_type == 'cause'}selected="selected"{/if}>Cause</option>
								<option value="sports_league" {if isset($fb_type) & !empty($fb_type) && $fb_type == 'sports_league'}selected="selected"{/if}>Sports league</option>
								<option value="sports_team" {if isset($fb_type) & !empty($fb_type) && $fb_type == 'sports_team'}selected="selected"{/if}>Sports team</option>
							</optgroup>
							<optgroup label="Organizations">
								<option value="band" {if isset($fb_type) & !empty($fb_type) && $fb_type == 'band'}selected="selected"{/if}>Band</option>
								<option value="government" {if isset($fb_type) & !empty($fb_type) && $fb_type == 'government'}selected="selected"{/if}>Government</option>
								<option value="non_profit" {if isset($fb_type) & !empty($fb_type) && $fb_type == 'non_profit'}selected="selected"{/if}>Non-profit</option>
								<option value="school" {if isset($fb_type) & !empty($fb_type) && $fb_type == 'school'}selected="selected"{/if}>School</option>
								<option value="university" {if isset($fb_type) & !empty($fb_type) && $fb_type == 'university'}selected="selected"{/if}>University</option>
							</optgroup>
							<optgroup label="People">
								<option value="actor" {if isset($fb_type) & !empty($fb_type) && $fb_type == 'actor'}selected="selected"{/if}>Actor</option>
								<option value="athlete" {if isset($fb_type) & !empty($fb_type) && $fb_type == 'athlete'}selected="selected"{/if}>Athlete</option>
								<option value="author" {if isset($fb_type) & !empty($fb_type) && $fb_type == 'author'}selected="selected"{/if}>Author</option>
								<option value="director" {if isset($fb_type) & !empty($fb_type) && $fb_type == 'director'}selected="selected"{/if}>Director</option>
								<option value="musician" {if isset($fb_type) & !empty($fb_type) && $fb_type == 'musician'}selected="selected"{/if}>Musician</option>
								<option value="politician" {if isset($fb_type) & !empty($fb_type) && $fb_type == 'politician'}selected="selected"{/if}>Politician</option>
								<option value="profile" {if isset($fb_type) & !empty($fb_type) && $fb_type == 'profile'}selected="selected"{/if}>Profile</option>
								<option value="public_figure" {if isset($fb_type) & !empty($fb_type) && $fb_type == 'public_figure'}selected="selected"{/if}>Public figure</option>
							</optgroup>
							<optgroup label="Places">
								<option value="city" {if isset($fb_type) & !empty($fb_type) && $fb_type == 'city'}selected="selected"{/if}>City</option>
								<option value="country" {if isset($fb_type) & !empty($fb_type) && $fb_type == 'country'}selected="selected"{/if}>Country</option>
								<option value="landmark" {if isset($fb_type) & !empty($fb_type) && $fb_type == 'landmark'}selected="selected"{/if}>Landmark</option>
								<option value="state_province" {if isset($fb_type) & !empty($fb_type) && $fb_type == 'state_province'}selected="selected"{/if}>State or province</option>
							</optgroup>
							<optgroup label="Products and Entertainment">
								<option value="album" {if isset($fb_type) & !empty($fb_type) && $fb_type == 'album'}selected="selected"{/if}>Album</option>
								<option value="book" {if isset($fb_type) & !empty($fb_type) && $fb_type == 'book'}selected="selected"{/if}>Book</option>
								<option value="drink" {if isset($fb_type) & !empty($fb_type) && $fb_type == 'drink'}selected="selected"{/if}>Drink</option>
								<option value="food" {if isset($fb_type) & !empty($fb_type) && $fb_type == 'food'}selected="selected"{/if}>Food</option>
								<option value="game" {if isset($fb_type) & !empty($fb_type) && $fb_type == 'game'}selected="selected"{/if}>Game</option>
								<option value="movie" {if isset($fb_type) & !empty($fb_type) && $fb_type == 'movie'}selected="selected"{/if}>Movie</option>
								<option value="product" {if isset($fb_type) & !empty($fb_type) && $fb_type == 'product'}selected="selected"{/if}>Product</option>
								<option value="song" {if isset($fb_type) & !empty($fb_type) && $fb_type == 'song'}selected="selected"{/if}>Song</option>
								<option value="tv_show" {if isset($fb_type) & !empty($fb_type) && $fb_type == 'tv_show'}selected="selected"{/if}>TV show</option>
							</optgroup>
							<optgroup label="Websites">
								<option value="blog" {if isset($fb_type) & !empty($fb_type) && $fb_type == 'blog'}selected="selected"{/if}>Blog</option>
								<option value="website" {if isset($fb_type) & !empty($fb_type) && $fb_type == 'website'}selected="selected"{/if}>Website</option>
								<option value="article" {if isset($fb_type) & !empty($fb_type) && $fb_type == 'article'}selected="selected"{/if}>Article</option>
							</optgroup>
						</select>
					</div>
				</div>
				<div class="form-group clear">
					<label for="form-field-1" class="col-sm-4 control-label">
						{l s='Image(s)' mod='seoexpert'}
					</label>
					<div class="col-lg-8">
						<div class="form-group clear">
							<div class="radio">
								<label for="form-field-5">
									<input type="radio" name="fb_image" value="0" {if isset($fb_image) && $fb_image == 0}checked="checked"{/if}>
									{l s='All images' mod='seoexpert'}
								</label>
							</div>

							<div class="radio">
								<label for="form-field-5">
									<input type="radio" name="fb_image" value="1" {if isset($fb_image) && $fb_image == 1}checked="checked"{/if}>
									{l s='Cover image' mod='seoexpert'}
								</label>
							</div>
						</div>
					</div>
				</div>
			</div>

			{*
			<!-- TABS Videos -->
			<div class="tab-pane" id="video">
				<div class="form-group clear">
					<label for="form-field-1" class="col-sm-4 control-label">
						{l s='Video Type' mod='seoexpert'}
					</label>
					<div class="col-lg-8">
						<div class="tooltips" data-toggle="tooltip" data-placement="top" data-original-title="{l s='The type of the video file' mod='seoexpert'}">
							<select id="fb_video_type" name="fb_video_type" name="class" class="selectpicker show-menu-arrow show-tick">
								<option value="" selected="selected">{l s='- None -' mod='seoexpert'}</option>
								<option value="application/x-shockwave-flash">{l s='Flash - playable directly from the feed' mod='seoexpert'}</option>
								<option value="text/html">{l s='Separate HTML page' mod='seoexpert'}</option>
							</select>
						</div>
						<div class="clear">&nbsp;</div>
					</div>
				</div>
				<div class="form-group clear">
					<label for="form-field-1" class="col-sm-4 control-label">
						{l s='URL' mod='seoexpert'}
					</label>
					<div class="col-lg-8">
						<div class="tooltips" data-toggle="tooltip" data-placement="top" data-original-title="{l s='A URL to a video file that complements this object' mod='seoexpert'}">
							<input type="text" class="form-control" value="" id="fb_video_url" name="fb_video_url" placeholder="{l s='Video (URL)' mod='seoexpert'}">
						</div>
						<div class="clear">&nbsp;</div>
					</div>
				</div>
				<div class="form-group clear">
					<label for="form-field-1" class="col-sm-4 control-label">
						{l s='Width' mod='seoexpert'}
					</label>
					<div class="col-lg-8">
						<div class="tooltips" data-toggle="tooltip" data-placement="top" data-original-title="{l s='The width of the video' mod='seoexpert'}">
							<input type="text" class="form-control" value="" id="fb_video_with" name="fb_video_with" placeholder="{l s='Video Width' mod='seoexpert'}">
						</div>
						<div class="clear">&nbsp;</div>
					</div>
				</div>
				<div class="form-group clear">
					<label for="form-field-1" class="col-sm-4 control-label">
						{l s='Height' mod='seoexpert'}
					</label>
					<div class="col-lg-8">
						<div class="tooltips" data-toggle="tooltip" data-placement="top" data-original-title="{l s='The height of the video' mod='seoexpert'}">
							<input type="text" class="form-control" value="" id="fb_video_height" name="fb_video_height" placeholder="{l s='Video Height' mod='seoexpert'}">
						</div>
						<div class="clear">&nbsp;</div>
					</div>
				</div>
			</div>

			<!-- TABS Localization -->
			<div class="tab-pane" id="localization">
				<div class="form-group clear">
					<label for="form-field-1" class="col-sm-4 control-label">
						{l s='Latitude' mod='seoexpert'}
					</label>
					<div class="col-lg-8">
						<div class="tooltips" data-toggle="tooltip" data-placement="top" data-original-title="{l s='To find the position, go on Google Maps, search you location, make a right click on the position and click on « more information ».' mod='seoexpert'}">
							<input type="text" class="form-control" value="" id="fb_lat" name="fb_lat" placeholder="{l s='Latitude' mod='seoexpert'}">
						</div>
						<div class="clear">&nbsp;</div>
					</div>
				</div>
				<div class="form-group clear">
					<label for="form-field-1" class="col-sm-4 control-label">
						{l s='Longitude' mod='seoexpert'}
					</label>
					<div class="col-lg-8">
						<div class="tooltips" data-toggle="tooltip" data-placement="top" data-original-title="{l s='To find the position, go on Google Maps, search you location, make a right click on the position and click on « more information ».' mod='seoexpert'}">
							<input type="text" class="form-control" value="" id="fb_long" name="fb_long" placeholder="{l s='Longitude' mod='seoexpert'}">
						</div>
						<div class="clear">&nbsp;</div>
					</div>
				</div>
				<div class="form-group clear">
					<label for="form-field-1" class="col-sm-4 control-label">
						{l s='Street Address' mod='seoexpert'}
					</label>
					<div class="col-lg-8">
						<div class="tooltips" data-toggle="tooltip" data-placement="top" data-original-title="{l s='A definir' mod='seoexpert'}">
							<input type="text" class="form-control" value="" id="fb_street" name="fb_street" placeholder="{l s='Street Address' mod='seoexpert'}">
						</div>
						<div class="clear">&nbsp;</div>
					</div>
				</div>
				<div class="form-group clear">
					<label for="form-field-1" class="col-sm-4 control-label">
						{l s='Locality' mod='seoexpert'}
					</label>
					<div class="col-lg-8">
						<div class="tooltips" data-toggle="tooltip" data-placement="top" data-original-title="{l s='A definir' mod='seoexpert'}">
							<input type="text" class="form-control" value="" id="fb_local" name="fb_local" placeholder="{l s='Locality' mod='seoexpert'}">
						</div>
						<div class="clear">&nbsp;</div>
					</div>
				</div>
				<div class="form-group clear">
					<label for="form-field-1" class="col-sm-4 control-label">
						{l s='Region' mod='seoexpert'}
					</label>
					<div class="col-lg-8">
						<div class="tooltips" data-toggle="tooltip" data-placement="top" data-original-title="{l s='A definir' mod='seoexpert'}">
							<input type="text" class="form-control" value="" id="fb_region" name="fb_region" placeholder="{l s='Region' mod='seoexpert'}">
						</div>
						<div class="clear">&nbsp;</div>
					</div>
				</div>
				<div class="form-group clear">
					<label for="form-field-1" class="col-sm-4 control-label">
						{l s='Postal Code' mod='seoexpert'}
					</label>
					<div class="col-lg-8">
						<div class="tooltips" data-toggle="tooltip" data-placement="top" data-original-title="{l s='A definir' mod='seoexpert'}">
							<input type="text" class="form-control" value="" id="fb_zipcode" name="fb_zipcode" placeholder="{l s='Postal Code' mod='seoexpert'}">
						</div>
						<div class="clear">&nbsp;</div>
					</div>
				</div>
				<div class="form-group clear">
					<label for="form-field-1" class="col-sm-4 control-label">
						{l s='Country Name' mod='seoexpert'}
					</label>
					<div class="col-lg-8">
						<div class="tooltips" data-toggle="tooltip" data-placement="top" data-original-title="{l s='A definir' mod='seoexpert'}">
							<input type="text" class="form-control" value="" id="fb_country" name="fb_country" placeholder="{l s='Country Name' mod='seoexpert'}">
						</div>
						<div class="clear">&nbsp;</div>
					</div>
				</div>
				<div class="form-group clear">
					<label for="form-field-1" class="col-sm-4 control-label">
						{l s='Email' mod='seoexpert'}
					</label>
					<div class="col-lg-8">
						<div class="tooltips" data-toggle="tooltip" data-placement="top" data-original-title="{l s='A definir' mod='seoexpert'}">
							<input type="text" class="form-control" value="" id="fb_email" name="fb_email" placeholder="{l s='Email' mod='seoexpert'}">
						</div>
						<div class="clear">&nbsp;</div>
					</div>
				</div>
				<div class="form-group clear">
					<label for="form-field-1" class="col-sm-4 control-label">
						{l s='Phone Number' mod='seoexpert'}
					</label>
					<div class="col-lg-8">
						<div class="tooltips" data-toggle="tooltip" data-placement="top" data-original-title="{l s='A definir' mod='seoexpert'}">
							<input type="text" class="form-control" value="" id="fb_phone" name="fb_phone" placeholder="{l s='Phone Number' mod='seoexpert'}">
						</div>
						<div class="clear">&nbsp;</div>
					</div>
				</div>
				<div class="form-group clear">
					<label for="form-field-1" class="col-sm-4 control-label">
						{l s='Fax Number' mod='seoexpert'}
					</label>
					<div class="col-lg-8">
						<div class="tooltips" data-toggle="tooltip" data-placement="top" data-original-title="{l s='A definir' mod='seoexpert'}">
							<input type="text" class="form-control" value="" id="fb_fax" name="fb_fax" placeholder="{l s='Fax Number' mod='seoexpert'}">
						</div>
						<div class="clear">&nbsp;</div>
					</div>
				</div>
				<div class="form-group clear">
					<label for="form-field-1" class="col-sm-4 control-label">
						{l s='Locale' mod='seoexpert'}
					</label>
					<div class="col-lg-8">
						<div class="tooltips" data-toggle="tooltip" data-placement="top" data-original-title="{l s='The locale these tags are marked up in. Of the format language_TERRITORY. Default is en_US' mod='seoexpert'}">
							<input type="text" class="form-control" value="" id="fb_local" name="fb_local" placeholder="{l s='Locale' mod='seoexpert'}">
						</div>
						<div class="clear">&nbsp;</div>
					</div>
				</div>
			</div>
		</div>
		*}
	</div>
	<div class="col-lg-4 pull-right">
		{include file="./patterns.tpl" social=true}
	</div>
</div>