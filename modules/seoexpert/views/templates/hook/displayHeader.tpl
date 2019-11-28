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

<!-- Open Graph -->
{if isset($fb_admins) && !empty($fb_admins)}
<meta property="fb:admins" content="{$fb_admins|escape:'htmlall':'UTF-8'}" />{/if}
{if isset($fb_pageid) && !empty($fb_pageid)}<meta property="fb:page_id" content="{$fb_pageid|escape:'htmlall':'UTF-8'}" />
{/if}
{if isset($fb_admins) && !empty($fb_appid)}
<meta property="fb:app_id" content="{$fb_appid|escape:'htmlall':'UTF-8'}" />
{/if}
{if isset($fb_title) && !empty($fb_title)}
<meta property="og:title" content="{$fb_title|escape:'htmlall':'UTF-8'}" />
{/if}
{if isset($fb_desc) && !empty($fb_desc)}
<meta property="og:description" content="{$fb_desc|escape:'htmlall':'UTF-8'}" />
{/if}
{if isset($fb_image)}
{if is_array($fb_image)}
{foreach from=$fb_image key=k item=image}
<meta property="og:image" content="{$image|escape:'htmlall':'UTF-8'}" />
{/foreach}
{else}
<meta property="og:image" content="{$fb_image|escape:'htmlall':'UTF-8'}" />{/if}
{/if}
{if isset($fb_type) && !empty($fb_type)}
<meta property="og:type" content="{$fb_type|escape:'htmlall':'UTF-8'}" />
{/if}

<!-- Twitter Cards -->
<meta name="twitter:domain" content="{$domain|escape:'htmlall':'UTF-8'}" />
{if isset($tw_card_type) && !empty($tw_card_type)}<meta name="twitter:card" content="{$tw_card_type|escape:'htmlall':'UTF-8'}">{/if}
{if isset($tw_username) && !empty($tw_username)}<meta name="twitter:site" content="{$tw_username|escape:'htmlall':'UTF-8'}">
<meta name="twitter:creator" content="{$tw_username|escape:'htmlall':'UTF-8'}">{/if}
{if isset($tw_title) && !empty($tw_title)}<meta name="twitter:title" content="{$tw_title|escape:'htmlall':'UTF-8'}">{/if}
{if isset($tw_card_type) && !empty($tw_card_type) && $tw_card_type != 'photo'}
{if isset($tw_description) && !empty($tw_description)}<meta name="twitter:description" content="{$tw_description|escape:'htmlall':'UTF-8'}">{/if}
{/if}
{if isset($tw_img_size) && !empty($tw_img_size) && isset($tw_card_type) && !empty($tw_card_type)}
{if $tw_card_type == 'summary_large_image'}
<meta name="twitter:image:src" content="{$tw_img_size|escape:'htmlall':'UTF-8'}">
{elseif ($tw_card_type == 'summary' || $tw_card_type == 'photo' || $tw_card_type == 'product')}
<meta name="twitter:image" content="{$tw_img_size|escape:'htmlall':'UTF-8'}">
{elseif $tw_card_type == 'gallery'}
{foreach from=$tw_img_size key=k item=image}
<meta name="twitter:image{$k|intval}:src" content="{$image|escape:'htmlall':'UTF-8'}">
{/foreach}
{/if}
{if $tw_card_type == 'photo'}
{if isset($tw_img_height) && !empty($tw_img_height)}<meta name="twitter:image:width" content="{$tw_img_width|intval}">{/if}
{if isset($tw_img_height) && !empty($tw_img_height)}<meta name="twitter:image:height" content="{$tw_img_height|intval}">{/if}
{/if}
{if $tw_card_type == 'product'}
{if isset($tw_data_1) && !empty($tw_data_1)}<meta name="twitter:label1" content="{$tw_data_1.label|escape:'htmlall':'UTF-8'}">
<meta name="twitter:data1" content="{$tw_data_1.value|escape:'htmlall':'UTF-8'}">
{/if}
{if isset($tw_data_1) && !empty($tw_data_1)}<meta name="twitter:label2" content="{$tw_data_2.label|escape:'htmlall':'UTF-8'}">
<meta name="twitter:data2" content="{$tw_data_2.value|escape:'htmlall':'UTF-8'}">
{/if}
{/if}
{/if}
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta http-equiv="cleartype" content="on" />
<meta http-equiv="x-dns-prefetch-control" value="on" />
<meta name="HandheldFriendly" content="true" />
<meta name="MobileOptimized" content="640" />
<meta name="apple-mobile-web-app-capable" content="yes" />
{if isset($meta_title)}
<meta name="apple-mobile-web-app-title" content="{$meta_title|escape:'html':'UTF-8'}">
{/if}
<link rel="dns-prefetch" href="//www.google-analytics.com" />
<link rel="dns-prefetch" href="//twitter.com" />
<link rel="dns-prefetch" href="//facebook.com" />
<link rel="dns-prefetch" href="//apis.google.com" />
<link rel="dns-prefetch" href="//fonts.googleapis.com" />
<link rel="dns-prefetch" href="//ssl.gstatic.com" />
<link rel="dns-prefetch" href="//{$domain|escape:'htmlall':'UTF-8'}" />

<link rel="preconnect" href="//www.google-analytics.com" crossorigin />
<link rel="preconnect" href="//twitter.com" crossorigin />
<link rel="preconnect" href="//facebook.com" crossorigin />
<link rel="preconnect" href="//apis.google.com" crossorigin />
<link rel="preconnect" href="//fonts.googleapis.com" crossorigin />
<link rel="preconnect" href="//ssl.gstatic.com" crossorigin />
<link rel="preconnect" href="//{$domain|escape:'htmlall':'UTF-8'}" crossorigin />
