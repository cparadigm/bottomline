<?php


$installer = $this;
$installer->startSetup();
$installer->endSetup();

$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$setup->addAttribute('catalog_product', 'magikfeatured', array(
        'group'             => 'General',
        'type'              => 'int',
        'backend'           => '',
        'frontend'          => '',
        'label'             => 'Featured Product On Home',
        'input'             => 'boolean',
        'class'             => '',
        'source'            => '',
        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'visible'           => true,
        'required'          => false,
        'user_defined'      => true,
        'default'           => '0',
        'searchable'        => false,
        'filterable'        => false,
        'comparable'        => false,
        'visible_on_front'  => false,
        'unique'            => false,
        'apply_to'          => 'simple,configurable,virtual,bundle,downloadable',
        'is_configurable'   => false
    ));

try {
//create pages and blocks programmatically

//Custom Tab1
$staticBlock = array(
    'title' => 'Custom Tab1',
    'identifier' => 'eclipse_custom_tab1',
    'content' => "<p><strong>Lorem Ipsum</strong><span>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</span></p>",
    'is_active' => 1,
    'stores' => array(0)
);
Mage::getModel('cms/block')->setData($staticBlock)->save();

//Custom Tab2
$staticBlock = array(
    'title' => 'Custom Tab2',
    'identifier' => 'eclipse_custom_tab2',
    'content' => "<p><strong>Lorem Ipsum</strong><span>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</span></p>",
    'is_active' => 1,
    'stores' => array(0)
);
Mage::getModel('cms/block')->setData($staticBlock)->save();

//Empty Category
$staticBlock = array(
    'title' => 'Empty Category',
    'identifier' => 'eclipse_empty_category',
    'content' => "<p>There are no products matching the selection.<br /> This is a static CMS block displayed if category is empty. You can put your own content here.</p>",
    'is_active' => 1,
    'stores' => array(0)
);
Mage::getModel('cms/block')->setData($staticBlock)->save();

//Eclipse Logo Brand block
 $staticBlock = array(
     'title' => 'Eclipse Logo Brand block',
     'identifier' => 'eclipse_logo_brand_block',
     'content' => '<div class="brand-logo"><div class="jcarousel-skin-tango">
<div id="mycarousel3" class="jcarousel-container jcarousel-container-horizontal" style="position: relative; display: block;">
<div class="jcarousel-clip jcarousel-clip-horizontal" style="overflow: hidden; position: relative;">
<ul class="jcarousel-list jcarousel-list-horizontal" style="overflow: hidden; position: relative; top: 0px; margin: 0px; padding: 0px; left: 0px; width: 1940px;">
<li class="jcarousel-item jcarousel-item-horizontal jcarousel-item-1 jcarousel-item-1-horizontal" style="float: left; list-style: none outside none;"><img src="{{skin url="images/b-logo1.png"}}" alt="brand-logo" /></li>
<li class="jcarousel-item jcarousel-item-horizontal jcarousel-item-2 jcarousel-item-2-horizontal" style="float: left; list-style: none outside none;"><img src="{{skin url="images/b-logo2.png"}}" alt="brand-logo" /></li>
<li class="jcarousel-item jcarousel-item-horizontal jcarousel-item-3 jcarousel-item-3-horizontal" style="float: left; list-style: none outside none;"><img src="{{skin url="images/b-logo3.png"}}" alt="brand-logo" /></li>
<li class="jcarousel-item jcarousel-item-horizontal jcarousel-item-4 jcarousel-item-4-horizontal" style="float: left; list-style: none outside none;"><img src="{{skin url="images/b-logo4.png"}}" alt="brand-logo" /></li>
<li class="jcarousel-item jcarousel-item-horizontal jcarousel-item-5 jcarousel-item-5-horizontal" style="float: left; list-style: none outside none;"><img src="{{skin url="images/b-logo5.png"}}" alt="brand-logo" /></li>
<li class="jcarousel-item jcarousel-item-horizontal jcarousel-item-6 jcarousel-item-6-horizontal" style="float: left; list-style: none outside none;"><img src="{{skin url="images/b-logo6.png"}}" alt="brand-logo" /></li>
<li class="jcarousel-item jcarousel-item-horizontal jcarousel-item-7 jcarousel-item-7-horizontal" style="float: left; list-style: none outside none;"><img src="{{skin url="images/b-logo3.png"}}" alt="brand-logo" /></li>
<li class="jcarousel-item jcarousel-item-horizontal jcarousel-item-8 jcarousel-item-8-horizontal" style="float: left; list-style: none outside none;"><img src="{{skin url="images/b-logo2.png"}}" alt="brand-logo" /></li>
<li class="jcarousel-item jcarousel-item-horizontal jcarousel-item-9 jcarousel-item-9-horizontal" style="float: left; list-style: none outside none;"><img src="{{skin url="images/b-logo5.png"}}" alt="brand-logo" /></li>
<li class="jcarousel-item jcarousel-item-horizontal jcarousel-item-10 jcarousel-item-10-horizontal" style="float: left; list-style: none outside none;"><img src="{{skin url="images/b-logo4.png"}}" alt="brand-logo" /></li>
</ul>
</div>
<div class="jcarousel-prev jcarousel-prev-horizontal" style="display: block;">&nbsp;</div>
<div class="jcarousel-next jcarousel-next-horizontal" style="display: block;">&nbsp;</div>
</div>
</div></div>',
     'is_active' => 1,
     'stores' => array(0)
 );
 Mage::getModel('cms/block')->setData($staticBlock)->save();

//Eclipse Store Logo
$staticBlock = array(
    'title' => 'Eclipse Store Logo',
    'identifier' => 'eclipse_logo',
    'content' => '<div><img src="{{skin url="images/logo.png"}}" alt="Eclipse Store" /></div>',
    'is_active' => 1,
    'stores' => array(0)
);
Mage::getModel('cms/block')->setData($staticBlock)->save();


// eclipse navigation block
$staticBlock = array(
    'title' => 'Custom',
    'identifier' => 'eclipse_navigation_block',
    'content' => '<div class="grid12-5">
<div class="custom_img"><img src="{{skin url="images/custom-img1.jpg"}}" alt="custom img1" /></div>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam fringilla augue.</p>
</div>
<div class="grid12-5">
<div class="custom_img"><img src="{{skin url="images/custom-img2.jpg"}}" alt="custom img2" /></div>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam fringilla augue.</p>
</div>
<div class="grid12-5">
<div class="custom_img"><img src="{{skin url="images/custom-img3.jpg"}}" alt="custom img3" /></div>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam fringilla augue.</p>
</div>
<div class="grid12-5">
<div class="custom_img"><img src="{{skin url="images/custom-img4.jpg"}}" alt="custom img4" /></div>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam fringilla augue.</p>
</div>',
    'is_active' => 1,
    'stores' => array(0)
);
Mage::getModel('cms/block')->setData($staticBlock)->save();

// Eclipse Left Navigation Block
$staticBlock = array(
    'title' => 'Custom',
    'identifier' => 'eclipse_left_navigation_block',
    'content' => '<div class="row">
<div class="mega-col col-sm-8 " data-colwidth="8" data-widgets="wid-1">
<div class="mega-col-inner">
<div id="wid-1" class="magik-widget">
<div class="product-block">
<div class="image"><a href="#"><img src="{{skin url="images/custom-img1.jpg"}}" alt="Fauxwaii Shirt - Oldss" width="240" /></a></div>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam fringilla augue.</p>
</div>
</div>
</div>
</div>
<div class="mega-col col-sm-8 " data-colwidth="8" data-widgets="wid-2">
<div class="mega-col-inner">
<div id="wid-2" class="magik-widget">
<div class="widget-product">
<div class="widget-inner">
<div class="product-block">
<div class="image"><a href="#"><img src="{{skin url="images/custom-img2.jpg"}}" alt="Framed-Sleeve Mid" width="240" /></a></div>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam fringilla augue.</p>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<div class="row">
<div class="mega-col col-sm-8 " data-colwidth="8" data-widgets="wid-1">
<div class="mega-col-inner">
<div id="wid-1" class="magik-widget">
<div class="product-block">
<div class="image"><a href="#"><img src="{{skin url="images/custom-img3.jpg"}}" alt="Fauxwaii Shirt - Oldss" width="240" /></a></div>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam fringilla augue.</p>
</div>
</div>
</div>
</div>
<div class="mega-col col-sm-8 " data-colwidth="8" data-widgets="wid-2">
<div class="mega-col-inner">
<div id="wid-2" class="magik-widget">
<div class="widget-product">
<div class="widget-inner">
<div class="product-block">
<div class="image"><a href="#"><img src="{{skin url="images/custom-img4.jpg"}}" alt="Framed-Sleeve Mid" width="240" /></a></div>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam fringilla augue.</p>
</div>
</div>
</div>
</div>
</div>
</div>
</div>',
    'is_active' => 1,
    'stores' => array(0)
);
Mage::getModel('cms/block')->setData($staticBlock)->save();


//Eclipse Home Banner Block
$staticBlock = array(
    'title' => 'Eclipse Home Offer Banner Block',
    'identifier' => 'eclipse_home_offer_banner_block',
    'content' => '<div class="offer-banner-section">
<div class="offer-inner">
<div class="col"><a href="#"><img src="{{skin url="images/offer-banner1.png"}}" alt="offer banner1" /></a></div>
<div class="col"><a href="#"><img src="{{skin url="images/offer-banner2.png"}}" alt="offer banner2" /></a></div>
<div class="col last"><a href="#"><img src="{{skin url="images/offer-banner3.png"}}" alt="offer banner3" /></a></div>
</div>
</div>',
    'is_active' => 1,
    'stores' => array(0)
);
Mage::getModel('cms/block')->setData($staticBlock)->save();

//Eclipse Home Header Block
$staticBlock = array(
    'title' => 'Eclipse Header Block',
    'identifier' => 'eclipse_header_block',
    'content' => '<ul>
<li>
<div class="feature-box">
<div class="icon-truck">&nbsp;</div>
<div class="content">FREE SHIPPING on order over $99</div>
</div>
</li>
<li>
<div class="feature-box">
<div class="icon-support">&nbsp;</div>
<div class="content">Customer Support Service</div>
</div>
</li>
<li>
<div class="feature-box">
<div class="icon-money">&nbsp;</div>
<div class="content">3 days Money Back Guarantee</div>
</div>
</li>
<li class="last">
<div class="feature-box">
<div class="icon-dis">&nbsp;</div>
<div class="content">5% discount on order over $199</div>
</div>
</li>
</ul>',
    'is_active' => 1,
    'stores' => array(0)
);
Mage::getModel('cms/block')->setData($staticBlock)->save();



//Eclipse Footer About Us Block
$staticBlock = array(
    'title' => 'Eclipse Footer About Us',
    'identifier' => 'eclipse_footer_about_us',
    'content' => '<div class="footer-column-1">
<div class="footer-logo"><a title="Logo" href="#"><img src="{{skin url="images/footer-logo.png"}}" alt="footer logo" /></a></div>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus diam arcu.</p>
<div class="payment-accept">
<div><img src="{{skin url="images/payment-1.png"}}" alt="payment1" /> <img src="{{skin url="images/payment-2.png"}}" alt="payment2" /> <img src="{{skin url="images/payment-3.png"}}" alt="payment3" /> <img src="{{skin url="images/payment-4.png"}}" alt="payment4" /></div>
</div>
</div>',
    'is_active' => 1,
    'stores' => array(0)
);
Mage::getModel('cms/block')->setData($staticBlock)->save();


//Eclipse Listing Page Block
$staticBlock = array(
    'title' => 'Eclipse Listing Page Block',
    'identifier' => 'eclipse_listing_page_block',
    'content' => '<div class="block block-banner"><a href="#"><img src="{{skin url="images/block-banner.png"}}" alt="" /></a></div>',
    'is_active' => 1,
    'stores' => array(0)
);
Mage::getModel('cms/block')->setData($staticBlock)->save();


//Eclipse Home Slider Banner Block
$staticBlock = array(
    'title' => 'Eclipse Home Slider Banner Block',
    'identifier' => 'eclipse_home_slider_banner_block',
    'content' => '<div id="rev_slider_4_wrapper" class="rev_slider_wrapper fullwidthbanner-container">
<div id="rev_slider_4" class="rev_slider fullwidthabanner">
<ul>
<li data-transition="random" data-slotamount="7" data-masterspeed="1000"><img src="{{skin url="images/slide-img1.jpg"}}" alt="slide1" data-bgposition="left top" data-bgfit="cover" data-bgrepeat="no-repeat" />
<div class="tp-caption ExtraLargeTitle sft  tp-resizeme " style="z-index: 2; max-width: auto; max-height: auto; white-space: nowrap;" data-x="45" data-y="30" data-endspeed="500" data-speed="500" data-start="1100" data-easing="Linear.easeNone" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1">Exclusive of designer</div>
<div class="tp-caption LargeTitle sfl  tp-resizeme " style="z-index: 3; max-width: auto; max-height: auto; white-space: nowrap;" data-x="45" data-y="70" data-endspeed="500" data-speed="500" data-start="1300" data-easing="Linear.easeNone" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1">Handbags &amp; Purses</div>
<div class="tp-caption sfb  tp-resizeme " style="z-index: 4; max-width: auto; max-height: auto; white-space: nowrap;" data-x="45" data-y="360" data-endspeed="500" data-speed="500" data-start="1500" data-easing="Linear.easeNone" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1"><a class="view-more" href="#">View More</a> <a class="buy-btn" href="#">Buy Now</a></div>
<div class="tp-caption Title sft  tp-resizeme " style="z-index: 4; max-width: auto; max-height: auto; white-space: nowrap;" data-x="45" data-y="150" data-endspeed="500" data-speed="500" data-start="1500" data-easing="Power2.easeInOut" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1">In augue urna, nunc, tincidunt, augue,<br /> augue facilisis facilisis.</div>
<div class="tp-caption Title sft  tp-resizeme " style="z-index: 4; max-width: auto; max-height: auto; white-space: nowrap; font-size: 11px;" data-x="45" data-y="400" data-endspeed="500" data-speed="500" data-start="1500" data-easing="Power2.easeInOut" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</div>
</li>
<li class="black-text" data-transition="random" data-slotamount="7" data-masterspeed="1000"><img src="{{skin url="images/slide-img2.jpg"}}" alt="slide2" data-bgposition="left top" data-bgfit="cover" data-bgrepeat="no-repeat" />
<div class="tp-caption ExtraLargeTitle sft  tp-resizeme " style="z-index: 2; max-width: auto; max-height: auto; white-space: nowrap;" data-x="45" data-y="30" data-endspeed="500" data-speed="500" data-start="1100" data-easing="Linear.easeNone" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1">laptop Sale</div>
<div class="tp-caption LargeTitle sfl  tp-resizeme " style="z-index: 3; max-width: auto; max-height: auto; white-space: nowrap;" data-x="45" data-y="70" data-endspeed="500" data-speed="500" data-start="1300" data-easing="Linear.easeNone" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1">Go Lightly</div>
<div class="tp-caption sfb  tp-resizeme " style="z-index: 4; max-width: auto; max-height: auto; white-space: nowrap;" data-x="45" data-y="360" data-endspeed="500" data-speed="500" data-start="1500" data-easing="Linear.easeNone" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1"><a class="view-more" href="#">View More</a> <a class="buy-btn" href="#">Buy Now</a></div>
<div class="tp-caption Title sft  tp-resizeme " style="z-index: 4; max-width: auto; max-height: auto; white-space: nowrap;" data-x="45" data-y="150" data-endspeed="500" data-speed="500" data-start="1500" data-easing="Power2.easeInOut" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1">In augue urna, nunc, tincidunt, augue,<br /> augue facilisis facilisis.</div>
<div class="tp-caption Title sft  tp-resizeme " style="z-index: 4; max-width: auto; max-height: auto; white-space: nowrap; font-size: 11px;" data-x="45" data-y="400" data-endspeed="500" data-speed="500" data-start="1500" data-easing="Power2.easeInOut" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</div>
</li>
</ul>
<div class="tp-bannertimer">&nbsp;</div>
</div>
</div>',
    'is_active' => 1,
    'stores' => array(0)
);
Mage::getModel('cms/block')->setData($staticBlock)->save();

//Eclipse Home2 Slider Banner Block
$staticBlock = array(
    'title' => 'Eclipse Home2 Slider Banner Block',
    'identifier' => 'eclipse_home2_slider_banner_block',
    'content' => '<div id="rev_slider_4_wrapper" class="rev_slider_wrapper fullwidthbanner-container">
<div id="rev_slider_4" class="rev_slider fullwidthabanner">
<ul>
<li class="black-text" data-transition="random" data-slotamount="7" data-masterspeed="1000"><img src="{{skin url="images/slide-img2-home2.jpg"}}" alt="slide2" data-bgposition="left top" data-bgfit="cover" data-bgrepeat="no-repeat" />
<div class="tp-caption ExtraLargeTitle sft  tp-resizeme " style="z-index: 2; max-width: auto; max-height: auto; white-space: nowrap;" data-x="45" data-y="30" data-endspeed="500" data-speed="500" data-start="1100" data-easing="Linear.easeNone" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1">laptop Sale</div>
<div class="tp-caption LargeTitle sfl  tp-resizeme " style="z-index: 3; max-width: auto; max-height: auto; white-space: nowrap;" data-x="45" data-y="70" data-endspeed="500" data-speed="500" data-start="1300" data-easing="Linear.easeNone" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1">Go Lightly</div>
<div class="tp-caption sfb  tp-resizeme " style="z-index: 4; max-width: auto; max-height: auto; white-space: nowrap;" data-x="45" data-y="360" data-endspeed="500" data-speed="500" data-start="1500" data-easing="Linear.easeNone" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1"><a class="view-more" href="#">View More</a> <a class="buy-btn" href="#">Buy Now</a></div>
<div class="tp-caption Title sft  tp-resizeme " style="z-index: 4; max-width: auto; max-height: auto; white-space: nowrap;" data-x="45" data-y="150" data-endspeed="500" data-speed="500" data-start="1500" data-easing="Power2.easeInOut" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1">In augue urna, nunc, tincidunt, augue,<br /> augue facilisis facilisis.</div>
<div class="tp-caption Title sft  tp-resizeme " style="z-index: 4; max-width: auto; max-height: auto; white-space: nowrap; font-size: 11px;" data-x="45" data-y="400" data-endspeed="500" data-speed="500" data-start="1500" data-easing="Power2.easeInOut" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</div>
</li>
<li data-transition="random" data-slotamount="7" data-masterspeed="1000"><img src="{{skin url="images/slide-img1-home2.jpg"}}" alt="slide1" data-bgposition="left top" data-bgfit="cover" data-bgrepeat="no-repeat" />
<div class="tp-caption ExtraLargeTitle sft  tp-resizeme " style="z-index: 2; max-width: auto; max-height: auto; white-space: nowrap;" data-x="45" data-y="30" data-endspeed="500" data-speed="500" data-start="1100" data-easing="Linear.easeNone" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1">Exclusive of designer</div>
<div class="tp-caption LargeTitle sfl  tp-resizeme " style="z-index: 3; max-width: auto; max-height: auto; white-space: nowrap;" data-x="45" data-y="70" data-endspeed="500" data-speed="500" data-start="1300" data-easing="Linear.easeNone" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1">Handbags &amp; Purses</div>
<div class="tp-caption sfb  tp-resizeme " style="z-index: 4; max-width: auto; max-height: auto; white-space: nowrap;" data-x="45" data-y="360" data-endspeed="500" data-speed="500" data-start="1500" data-easing="Linear.easeNone" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1"><a class="view-more" href="#">View More</a> <a class="buy-btn" href="#">Buy Now</a></div>
<div class="tp-caption Title sft  tp-resizeme " style="z-index: 4; max-width: auto; max-height: auto; white-space: nowrap;" data-x="45" data-y="150" data-endspeed="500" data-speed="500" data-start="1500" data-easing="Power2.easeInOut" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1">In augue urna, nunc, tincidunt, augue,<br /> augue facilisis facilisis.</div>
<div class="tp-caption Title sft  tp-resizeme " style="z-index: 4; max-width: auto; max-height: auto; white-space: nowrap; font-size: 11px;" data-x="45" data-y="400" data-endspeed="500" data-speed="500" data-start="1500" data-easing="Power2.easeInOut" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</div>
</li>
</ul>
<div class="tp-bannertimer">&nbsp;</div>
</div>
</div>',
    'is_active' => 1,
    'stores' => array(0)
);
Mage::getModel('cms/block')->setData($staticBlock)->save();


//Eclipse Footer Information Links Block
$staticBlock = array(
    'title' => 'Eclipse Footer Information Links Block',
    'identifier' => 'eclipse_footer_information_links_block',
    'content' => '<div class="footer-column">
<h4>Shopping Guide</h4>
<ul class="links">
<li class="first"><a title="How to buy" href="{{store_url=blog}}">Blog</a></li>
<li><a title="FAQs" href="#">FAQs</a></li>
<li><a title="Payment" href="#">Payment</a></li>
<li><a title="Shipment" href="#">Shipment</a></li>
<li><a title="Where is my order?" href="#">Where is my order?</a></li>
<li class="last"><a title="Return policy" href="#">Return policy</a></li>
</ul>
</div>
<div class="footer-column">
<h4>Style Advisor</h4>
<ul class="links">
<li class="first"><a title="Your Account" href="{{store_url=customer/account/}}">Your Account</a></li>
<li><a title="Information" href="#">Information</a></li>
<li><a title="Addresses" href="#">Addresses</a></li>
<li><a title="Addresses" href="#">Discount</a></li>
<li><a title="Orders History" href="#">Orders History</a></li>
<li class="last"><a title=" Additional Information" href="#"> Additional Information</a></li>
</ul>
</div>
<div class="footer-column">
<h4>Information</h4>
<ul class="links">
<li class="first"><a title="Site Map" href="{{store_url=catalog/seo_sitemap/category/}}">Site Map</a></li>
<li><a title="Search Terms" href="{{store_url=catalogsearch/term/popular/}}">Search Terms</a></li>
<li><a title="Advanced Search" href="{{store_url=catalogsearch/advanced/}}">Advanced Search</a></li>
<li><a title="History" href="# ">History</a></li>
<li><a title="Suppliers" href="#">Suppliers</a></li>
<li class=" last"><a class="link-rss" title="Our stores" href="#">Our stores</a></li>
</ul>
</div>',
    'is_active' => 1,
    'stores' => array(0)
);
Mage::getModel('cms/block')->setData($staticBlock)->save();

//Eclipse Home Latest Blog Block
$staticBlock = array(
    'title' => 'Eclipse Home Latest Blog Block',
    'identifier' => 'eclipse_home_latest_blog_block',
    'content' => '<div class="blog_fix latest-news">
<div class="inner-content">{{block type="blogmate/index" name="blog_home" template="blogmate/right/home_right.phtml"}}</div>
</div>',
    'is_active' => 1,
    'stores' => array(0)
);
Mage::getModel('cms/block')->setData($staticBlock)->save();

//Eclipse Blog Banner Text Block
$staticBlock = array(
    'title' => 'Eclipse Blog Banner Text Block',
    'identifier' => 'eclipse_blog_banner_text_block',
    'content' => '<div class="text-widget widget widget__sidebar">
<h3 class="widget-title">Text Widget</h3>
<div class="widget-content">Mauris at blandit erat. Nam vel tortor non quam scelerisque cursus. Praesent nunc vitae magna pellentesque auctor. Quisque id lectus.<br /> <br /> Massa, eget eleifend tellus. Proin nec ante leo ssim nunc sit amet velit malesuada pharetra. Nulla neque sapien, sollicitudin non ornare quis, malesuada.</div>
</div>',
    'is_active' => 1,
    'stores' => array(0)
);
Mage::getModel('cms/block')->setData($staticBlock)->save();

//Eclipse Blog Banner Ad Block
$staticBlock = array(
    'title' => 'Eclipse Blog Banner Ad Block',
    'identifier' => 'eclipse_blog_banner_ad_block',
    'content' => '<div class="ad-spots widget widget__sidebar">
<h3 class="widget-title">Ad Spots</h3>
<div class="widget-content"><a title="" href="#" target="_self"><img src="{{skin url="images/offer-banner1.jpg"}}" alt="offer banner" /></a></div>
</div>',
    'is_active' => 1,
    'stores' => array(0)
);
Mage::getModel('cms/block')->setData($staticBlock)->save();


//Eclipse Banner Block
$staticBlock = array(
    'title' => 'Eclipse Banner Block',
    'identifier' => 'eclipse_banner_block',
    'content' => '<div class="promo-banner-section">
<div class="col"><img src="{{skin url="images/jewelry-banner.jpg"}}" alt="promo-banner3" /></div>
</div>',
    'is_active' => 1,
    'stores' => array(0)
);
Mage::getModel('cms/block')->setData($staticBlock)->save();

//Eclipse RHS Banner Block
$staticBlock = array(
    'title' => 'Eclipse RHS Banner Block',
    'identifier' => 'eclipse_rhs_banner_block',
    'content' => '<div class="RHS-banner">
<div class="add"><a href="#"><img src="{{skin url="images/rhs-banner.png"}}" alt="banner-img"  /></a></div>
</div>',
    'is_active' => 1,
    'stores' => array(0)
);
Mage::getModel('cms/block')->setData($staticBlock)->save();

//Eclipse_LHS_Banner_Block
$staticBlock = array(
    'title' => 'Eclipse_LHS_Banner_Block',
    'identifier' => 'eclipse_lhs_banner_block',
    'content' => '<div class="RHS-banner">
<div class="add"><a href="#"><img src="{{skin url="images/RHS-b1.png"}}" alt="banner-img" /></a></div>
<div class="add"><a href="#"><img src="{{skin url="images/RHS-b2.png"}}" alt="banner-img" /></a></div>
</div>',
    'is_active' => 1,
    'stores' => array(0)
);
Mage::getModel('cms/block')->setData($staticBlock)->save();

//Eclipse Recommended Product Block
$staticBlock = array(
    'title' => 'Eclipse Recommended Product Block',
    'identifier' => 'eclipse_recommended_product_block',
    'content' => '<div>{{block type="catalog/product_list" name="homerecommended" as="homerecommended" num_products="12" template="catalog/product/recommended.phtml" }}</div>',
    'is_active' => 1,
    'stores' => array(0)
);
Mage::getModel('cms/block')->setData($staticBlock)->save();

//Eclipse Home 1 Tab Dropdown Block
$staticBlock = array(
    'title' => 'Eclipse Home1 Tab Dropdown Block',
    'identifier' => 'eclipse_home1_tab_dropdown_block',
    'content' => '<ul class="level1" style="display: none;">
<li class="level1 parent"><a href="http://demo.magikthemes.com/index.php/eclipseblue/"><span>Home Version 1</span></a></li>
<li class="level1 parent"><a href="http://demo.magikthemes.com/index.php/eclipsebluehome2/"><span>Home Version 2</span></a></li>
<li class="level1 parent"><a href="http://demo.magikthemes.com/index.php/eclipsegreen/"><span>Green Color</span></a></li>
<li class="level1 parent"><a href="http://demo.magikthemes.com/index.php/eclipsered/"><span>Red Color</span></a></li>
<li class="level1 parent"><a href="http://demo.magikthemes.com/index.php/eclipselavender/"><span>Lavender Color</span></a></li>
<li class="level1 parent"><a href="http://demo.magikthemes.com/index.php/eclipseemerald/"><span>Emerald Color</span></a></li>
</ul>',
    'is_active' => 1,
    'stores' => array(0)
);
Mage::getModel('cms/block')->setData($staticBlock)->save();

//Eclipse Home 2 Tab Dropdown Block
$staticBlock = array(
    'title' => 'Eclipse Home2 Tab Dropdown Block',
    'identifier' => 'eclipse_home2_tab_dropdown_block',
    'content' => '<ul class="level1" style="display: none;">
<li class="level1 parent"><a href="http://demo.magikthemes.com/index.php/eclipseblue/"><span>Home Version 1</span></a></li>
<li class="level1 parent"><a href="http://demo.magikthemes.com/index.php/eclipsebluehome2/"><span>Home Version 2</span></a></li>
<li class="level1 parent"><a href="http://demo.magikthemes.com/index.php/eclipsegreenhome2/"><span>Green Color</span></a></li>
<li class="level1 parent"><a href="http://demo.magikthemes.com/index.php/eclipseredhome2/"><span>Red Color</span></a></li>
<li class="level1 parent"><a href="http://demo.magikthemes.com/index.php/eclipselavenderhome2/"><span>Lavender Color</span></a></li>
<li class="level1 parent"><a href="http://demo.magikthemes.com/index.php/eclipseemeraldhome2/"><span>Emerald Color</span></a></li>
</ul>',
    'is_active' => 1,
    'stores' => array(0)
);
Mage::getModel('cms/block')->setData($staticBlock)->save();

//Eclipse Footer Contact Us
$staticBlock = array(
    'title' => 'Eclipse Footer Contact Us',
    'identifier' => 'eclipse_footer_contact_us',
    'content' => '<div class="footer-column-last">
<h4>Contact us</h4>
<address><em class="add-icon">&nbsp;</em>123 Main Street, Anytown, <br /> &nbsp;CA 12345 USA</address>
<div class="phone-footer"><em class="phone-icon">&nbsp;</em> +1 800 123 1234</div>
<div class="email-footer"><em class="email-icon">&nbsp;</em> <a href="#">support@magikcommerce.com</a></div>
</div>',
    'is_active' => 1,
    'stores' => array(0)
);
Mage::getModel('cms/block')->setData($staticBlock)->save();

//Eclipse Top Menu Left Position Static Block
$staticBlock = array(
    'title' => 'Eclipse Top Menu Single Image Static Block',
    'identifier' => 'eclipse_top_menu_single_image_static_block',
    'content' => '<div class="static-img-block"><a href="#"><img src="{{skin url="images/nav-img.jpg"}}" alt="nav image"></a></div>',
    'is_active' => 1,
    'stores' => array(0)
);
Mage::getModel('cms/block')->setData($staticBlock)->save();

//Eclipse Top Menu Right Position Static Block
$staticBlock = array(
    'title' => 'Eclipse Top Menu Image With Text Static Block',
    'identifier' => 'eclipse_top_menu_image_with_text_static_block',
    'content' => '<div class="static-img-block"><a href="#"><img src="{{skin url="images/nav-img1.jpg"}}" alt="nav image" /></a></div>
<h3 class="heading">Responsive Magento Theme</h3>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam fringilla augue nec est tristique auctor.</p>
<p><a class="btn-button-st" title="Shop collection now" href="#">Shop collection now</a></p>',
    'is_active' => 1,
    'stores' => array(0)
);
Mage::getModel('cms/block')->setData($staticBlock)->save();

}
catch (Exception $e) {
    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('An error occurred while installing Eclipse theme pages and cms blocks.'));
}