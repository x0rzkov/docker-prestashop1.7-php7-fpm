{*
* DISCLAIMER
*
*  @author    SolverCircle

*  @copyright 2007-2016 SolverCircle
*  @license   http://opensource.org/licenses/LGPL-2.1
*  International Registered Trademark & Property of SolverCircle
*}
{extends file=$layout}
{block name='content'}
<link rel="stylesheet" type="text/css" href="{$ps_base_url|escape:'htmlall':'UTF-8'}/modules/vendor/views/css/style.css" />
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet">
<style>
.banner-container {
    background: rgba(0, 0, 0, 0) url("{$store['store_banner_image']|escape:'htmlall':'UTF-8'}") no-repeat scroll center center / 100% 325px;
    height: 415px;
    padding-top: 50px;
    text-align: center;
    width: 100%;
}
.fa-stack {
    display: inline-block;
    height: 2em;
    line-height: 2em;
    position: relative;
    vertical-align: middle;
    width: 1em;
}
.banner-stripe {
    height: 150px;
    margin-top: 45px;
    text-align: center;
    width: 100%;
}
.seller-details-stripe-lower {
    height: 150px;
    margin-top: 15px;
    padding: 0 100px;
}
.detail-container-inner {
    background-color: #{$vendor_color_7};
    box-shadow: 0 0 1px 1px #e8e8e8;
    height: 100%;
	opacity:0.8;
	border-bottom:solid 8px #{$vendor_color_8};
}
.profile-pic {
    float: left;
    height: 100%;
    padding: 15px 10px 15px 15px;
    text-align: center;
    width: 15%;
}
.img-div > img {
    box-shadow: 0 0 0 1px #a0a0a0;
	width:100px;
	height:100px;
}
.upper-detail {
    height: 35px;
}
.lower-detail {
    height: 55px;
}
.details {
    float: left;
    padding: 15px;
    text-align: left;
    width: 40%;
}
.contact-seller {
    float: right;
    height: 100%;
    padding: 10px 15px;
    text-align: right;
    width: 40%;
}
.upper-contact-seller {
    color: #194397;
    float: left;
    height: 50px;
    width: 100%;
}
.seller-mobile {
    float: left;
    font-size: 16px;
	color:#{$vendor_color_2} !important;
}
.seller-email {
    float: right;
    font-size: 16px;
	color:#{$vendor_color_3} !important;
}
.lower-contact-seller {
    color:#{$vendor_color_4} !important;
    float: right;
    font-size: 16px;
    font-weight: bold;
    height: 55px;
    width: 75%;
}
.cennect-text {
    float: left;
}
.connect-icons {
    float: right;
    letter-spacing: 6px;
}
.company-logo {
    height: 100px;
    opacity: 1;
    overflow: hidden;
    padding-top: 15px;
}
.company-logo img {
    height: 80px;
    width: 80px;
}

.mp-list-group {
    border: 1px solid #cacecf;
    box-shadow: 0 0 0 1px #eee;
    color: #a4a547;
    font-size: 16px;
    list-style: outside none none;
    padding: 15px 0;
    text-align: left;
    width: 100%;
	background:#{$vendor_color_9} !important;
}
.mp-list-group-item {
    margin-bottom: 1px;
    padding: 9px 25px;
}
.mp-active {
    background-color: #{$vendor_color_10} !important;
    color: #000;
    cursor: pointer;
}
mp-list-group-item {
    margin-bottom: 1px;
    padding: 9px 25px;
}
.fa-star {
    color: #d8c602;
}
.fa {
    font-size: 20px;
}
.mp-tab-content-product {
	padding: 0px 15px 15px 15px;
}
.mp-tab-content {
    border: 1px solid #cacecf;
    box-shadow: 0 0 0 1px #eee;
    display: none;
    min-height: 265px;
    overflow: auto;
    padding: 15px;
	background-color: #{$vendor_color_11} !important;
}
.mp-tab-active {
    display: block !important;
}
.mp-tabs {
    display:none;
	margin-bottom:15px;
}
.left-panel {
    margin-top: 20px;
    padding-left: 0;
}
.right-panel {
    margin-top: 20px;
    padding-right: 0;
}
.mp-list-group-item a
{
	cursor:pointer;
	color:#{$vendor_color_5} !important;
}
.mp-active a{
	color:#{$vendor_color_6} !important;
}


.review-container-left-panel {
    float: left;
    height: 80px;
    padding: 5px;
    width: 23%;
}
.reviewer-name {
    color: #000;
    font-size: 16px;
    margin-bottom: 5px;
}
.review-date {
    color: #000;
    font-size: 14px;
}
.review-border {
    border: 1px solid #afabac;
    float: left;
    height: 70px;
    margin-right: 20px;
    margin-top: 11px;
}
.review-container-right-panel {
    color: #000;
    float: left;
    font-size: 14px;
    text-align: justify;
    width: 74%;
	margin-top:8px;
}
.review-container-right-panel {
    color: #000;
    font-size: 14px;
    text-align: justify;
}
.review-rating {
	margin-bottom:15px;
}
.new_line_comments {
	 clear:both;
}
.review-container {
    border-bottom: 1px dashed #999;
    display: block;
    height: auto;
    overflow: auto;
    padding: 30px 5px;
}
.upper-detail h4 {
    color: #{$vendor_color_4} !important;
    font-size: 20px;
	text-decoration:underline;
}
.lower-detail > h4 {
    color: #{$vendor_color_4} !important;
	font-size: 13px;
}
.reviewed-product-name a{
    color: #{$vendor_color_4} !important;
    font-weight: bold;
	font-size:18px;
}
.fa-facebook {
    color: #39579b;
}
.fa-google-plus {
    color: #d34836;
}
.fa-twitter {
    color: #00aced;
}
.seller-location h4{
	color:#{$vendor_color_1} !important;
}
.filter_title{
	font-size:15px;
	font-weight:bolder;
}
.vendor_location {
	clear:both;
	text-align:left;
	padding-top:10px;
	color:#{$vendor_color_4} !important;
}
.vendor_main_container {
	 margin-top:50px;
}
.vendor-seller-mobile-view{
	display:none;
}
.vendor-seller-email-view  {
	display:none;
}

@media screen and (max-width: 480px) {
	.mp-list-group{
		 font-size:14px !important;
	}
	.review-container-left-panel {
		 float:none !important;
		 width:100% !important;
		 height:auto !important;
	}
	.review-border {
		display:none !important;
	}
	.banner-container{
		background: rgba(0, 0, 0, 0) url("{$store['store_banner_image']|escape:'htmlall':'UTF-8'}") no-repeat !important;
		height:auto !important;
		background-size: 100% auto !important;
	}
	.banner-stripe {
		display:none !important;
	}
	.seller-details-stripe-lower{
		padding:0 !important;
		margin-top:40px;
	}
	.detail-container-inner{
		height:170px;
	}
	.profile-pic{
		 width:30% !important;
	}
	.details{
		 width:70% !important;
	}
	.upper-detail{
		 height:auto !important;
	}
	.contact-seller{
		float:none !important;
	}
	.vendor-seller-mobile-view{
		display:block !important;
		font-size: 12px;
		font-weight:bold !important;
		color:#{$vendor_color_2} !important;
	}
	.contact-seller{
		display:none !important;
	}
	.lower-detail > h4{
		 font-size:12px !important;
		 font-weight:bold !important;
	}
	.vendor-seller-email-view {
		display:block !important;
		font-size: 11px;
		font-weight:bold !important;
		color:#{$vendor_color_2} !important;
	}
	.vendor-seller-email-view .fa{
		font-size:14px !important;
	}
	.vendor-seller-mobile-view .fa{
		font-size:14px !important;
	}
}

@media screen and (max-width: 1000px) {
	.banner-stripe{
		display:none !important;
	}
	.banner-container{
		background: rgba(0, 0, 0, 0) url("{$store['store_banner_image']|escape:'htmlall':'UTF-8'}") no-repeat !important;
		height:auto !important;
		background-size: 100% auto !important;
	}
	.mp-list-group{
		 font-size:14px !important;
	}
	.seller-details-stripe-lower{
		margin-top: 70px !important;
	}
	.seller-mobile{
		 font-size:12px !important;
		 float:right !important;
	}
	.seller-email{
		 font-size:12px !important;
		 float:right !important;
	}
	.upper-contact-seller{
		height:90px !important;
	}
	.cennect-text{
		display:none;
	}
	.mp-list-group-item{
		font-size:13px !important;
	}
	.mp-list-group-item .fa {
		 font-size:15px !important;
	}
	.review-container-right-panel{
		 width:70% !important;
	}
}
#products {
    color: #878787;
}
.products .product-miniature {
    display: flex !important;
    flex-wrap: wrap !important;
    justify-content: flex-start !important;
}
#tab-store, #tab-profile {
	margin-left:10px;
}
</style>
<div>
<div class="banner-container">
  <div class="banner-stripe">
    <!--<div class="company-logo"> <img src="{$store['store_grid_image']|escape:'htmlall':'UTF-8'}"> </div>-->
    <!--<div class="seller-location">
      <h4> We're from <span data-toggle="tooltip" title="" data-original-title="We're from"></h4>
    </div>-->
  </div>
  <!-- banner stripe -->
  <div class="seller-details-stripe-lower">
    <div class="detail-container-inner">
      <div class="profile-pic">
        <div class="img-div"> <img class="img-responsive" src="{$store['store_grid_image']|escape:'htmlall':'UTF-8'}" /> </div>
      </div>
      <!-- profile-pic -->
      <div class="details">
        <div class="upper-detail">
          <h4>{$store['store_name']|escape:'htmlall':'UTF-8'}</h4>
          <div class="vendor_location"><i class="fa fa-home"></i></span> <b>{$store['name']|escape:'htmlall':'UTF-8'}</b> </div>
        </div>
        <div class="lower-detail">
          <!-- <br/> -->
        </div>
        <div class="vendor-seller-mobile-view"> <i class="fa fa-phone"></i> {$store['telephone']|escape:'htmlall':'UTF-8'} </div>
        <div class="vendor-seller-email-view"> <i class="fa fa-envelope-o"></i> {$store['email']|escape:'htmlall':'UTF-8'} </div>
      </div>
      <!-- details -->
      <div class="contact-seller">
        <div class="upper-contact-seller">
          <div class="seller-mobile"> <i class="fa fa-phone"></i> {$store['telephone']|escape:'htmlall':'UTF-8'} </div>
          <div class="seller-email"> <i class="fa fa-envelope-o"></i> {$store['email']|escape:'htmlall':'UTF-8'} </div>
          <!--<div class="vendor_location"><i class="fa fa-home"></i></span> <b>{$store['name']|escape:'htmlall':'UTF-8'}</b> </div>-->
        </div>
        <div class="lower-contact-seller">
          <div class="cennect-text"> Connect us with: </div>
          <div class="connect-icons"> <span> <a target="_blank" href="{$store['facebook_link']}"> <img src="{$ps_base_url|escape:'htmlall':'UTF-8'}/modules/vendor/views/img/fb.png" /> </a> </span> <span> <a target="_blank" href="{$store['twitter_link']}"> <img src="{$ps_base_url|escape:'htmlall':'UTF-8'}/modules/vendor/views/img/tw.png" /> </a> </span> <span> <a target="_blank" href="{$store['google_plus_link']}"> <img src="{$ps_base_url|escape:'htmlall':'UTF-8'}/modules/vendor/views/img/g+.png" /> </a> </span> </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="vendor_main_container">
<div class="col-xs-12 col-sm-4 col-md-3">
  <ul class="mp-list-group" id="main-tab">
    <li class="mp-list-group-item  mp-active"> <a data-tab="#tab-collection"><i class="fa fa-tags"></i> Collection ({count($products)}) </a> </li>
    <li class="mp-list-group-item"> <a data-tab="#tab-profile"><i class="fa fa-user"></i> Profile </a> </li>
    <li class="mp-list-group-item"> <a data-tab="#tab-store"><i class="fa fa-info-circle"></i> About Store </a> </li>
  </ul>
</div>
<div id="content-wrapper" class="left-column col-xs-12 col-sm-8 col-md-9">
  <section id="main" class="row">
    <div id="tab-profile" class="mp-tab-content mp-tabs">
      <p>{$store_content nofilter}</p>
    </div>
    <!-- tab-profile -->
    <div id="tab-store" class="mp-tab-content mp-tabs">
      <p>{$store_content_about_us nofilter}</p>
    </div>
    <!-- tab-store -->
    <div id="tab-collection" class="mp-tabs mp-tab-active">
      <section id="products">
        <div id="js-product-list">
          <div class="products"> {foreach from=$products item=product}
            <article data-id-product="{$product.id_product}" class="product-miniature js-product-miniature">
              <div class="thumbnail-container"> <a class="thumbnail product-thumbnail" href="{$product.link}"> <img data-full-size-image-url="{$product.image}" alt="" src="{$product.image}"> </a>
                <div class="product-description">
                  <h1 itemprop="name" class="h3 product-title"><a href="{$product.link}">{$product.name}</a></h1>
                  <div class="product-price-and-shipping"> <span class="price" itemprop="price">{$product.price}</span> </div>
                </div>
                <div class="highlighted-informations hidden-sm-down"> <a data-link-action="quickview" class="quick-view"> <i class="fa fa-search"></i> Quick view </a> </div>
              </div>
            </article>
            {/foreach} </div>
        </div>
      </section>
    </div>
  </section>
</div>
{/block} 