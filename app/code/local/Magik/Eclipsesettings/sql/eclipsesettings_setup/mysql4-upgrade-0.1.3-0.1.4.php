<?php
$installer = $this;
$installer->startSetup();
$installer->endSetup();
try {
//create pages programmatically
//404 page
$cmsPage = array(
    'title' => 'Eclipse 404 No Route2',
    'identifier' => 'eclipse_no_route2',
    'content' => '<div class="content-wrapper">
<div class="std">
<div class="page-not-found">
<h2>404</h2>
<h3><img alt="notfound Store" src="{{skin url="images/signal.png"}}" />Oops! The Page you requested was not found!</h3>
<button class="button btn-home" onclick="location.href=\'{{store url=\'\'}}\'" type="button"><span>Back To Home</span></button></div>
</div>
</div>',
    'is_active' => 1,
    'sort_order' => 0,
    'stores' => array(0),
    'root_template' => 'one_column'
);
Mage::getModel('cms/page')->setData($cmsPage)->save();
//Eclipse Home Slider Banner Block
$staticBlock = array(
    'title' => 'Eclipse RTL Home Slider Banner Block',
    'identifier' => 'eclipse_rtl_home_slider_banner_block',
    'content' => '<div id="rev_slider_4_wrapper" class="rev_slider_wrapper fullwidthbanner-container">
<div id="rev_slider_4" class="rev_slider fullwidthabanner">
<ul>
<li data-transition="random" data-slotamount="7" data-masterspeed="1000"><img src="{{skin url="images/rtl-slide-img1.jpg"}}" alt="slide1" data-bgposition="left top" data-bgfit="cover" data-bgrepeat="no-repeat" />
<div class="tp-caption ExtraLargeTitle sft  tp-resizeme " style="z-index: 2; max-width: auto; max-height: auto; white-space: nowrap;" data-x="45" data-y="30" data-endspeed="500" data-speed="500" data-start="1100" data-easing="Linear.easeNone" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1">Exclusive of designer</div>
<div class="tp-caption LargeTitle sfl  tp-resizeme " style="z-index: 3; max-width: auto; max-height: auto; white-space: nowrap;" data-x="45" data-y="70" data-endspeed="500" data-speed="500" data-start="1300" data-easing="Linear.easeNone" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1">Handbags &amp; Purses</div>
<div class="tp-caption sfb  tp-resizeme " style="z-index: 4; max-width: auto; max-height: auto; white-space: nowrap;" data-x="45" data-y="360" data-endspeed="500" data-speed="500" data-start="1500" data-easing="Linear.easeNone" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1"><a class="view-more" href="#">View More</a> <a class="buy-btn" href="#">Buy Now</a></div>
<div class="tp-caption Title sft  tp-resizeme " style="z-index: 4; max-width: auto; max-height: auto; white-space: nowrap;" data-x="45" data-y="150" data-endspeed="500" data-speed="500" data-start="1500" data-easing="Power2.easeInOut" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1">In augue urna, nunc, tincidunt, augue,<br /> augue facilisis facilisis.</div>
<div class="tp-caption Title sft  tp-resizeme " style="z-index: 4; max-width: auto; max-height: auto; white-space: nowrap; font-size: 11px;" data-x="45" data-y="400" data-endspeed="500" data-speed="500" data-start="1500" data-easing="Power2.easeInOut" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</div>
</li>
<li class="black-text" data-transition="random" data-slotamount="7" data-masterspeed="1000"><img src="{{skin url="images/rtl-slide-img2.jpg"}}" alt="slide2" data-bgposition="left top" data-bgfit="cover" data-bgrepeat="no-repeat" />
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
$staticBlock2 = array(
    'title' => 'Eclipse RTL Home2 Slider Banner Block',
    'identifier' => 'eclipse_rtl_home2_slider_banner_block',
    'content' => '<div id="rev_slider_4_wrapper" class="rev_slider_wrapper fullwidthbanner-container">
<div id="rev_slider_4" class="rev_slider fullwidthabanner">
<ul>
<li class="black-text" data-transition="random" data-slotamount="7" data-masterspeed="1000"><img src="{{skin url="images/rtl-slide-img2-home2.jpg"}}" alt="slide2" data-bgposition="left top" data-bgfit="cover" data-bgrepeat="no-repeat" />
<div class="tp-caption ExtraLargeTitle sft  tp-resizeme " style="z-index: 2; max-width: auto; max-height: auto; white-space: nowrap;" data-x="45" data-y="30" data-endspeed="500" data-speed="500" data-start="1100" data-easing="Linear.easeNone" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1">laptop Sale</div>
<div class="tp-caption LargeTitle sfl  tp-resizeme " style="z-index: 3; max-width: auto; max-height: auto; white-space: nowrap;" data-x="45" data-y="70" data-endspeed="500" data-speed="500" data-start="1300" data-easing="Linear.easeNone" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1">Go Lightly</div>
<div class="tp-caption sfb  tp-resizeme " style="z-index: 4; max-width: auto; max-height: auto; white-space: nowrap;" data-x="45" data-y="360" data-endspeed="500" data-speed="500" data-start="1500" data-easing="Linear.easeNone" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1"><a class="view-more" href="#">View More</a> <a class="buy-btn" href="#">Buy Now</a></div>
<div class="tp-caption Title sft  tp-resizeme " style="z-index: 4; max-width: auto; max-height: auto; white-space: nowrap;" data-x="45" data-y="150" data-endspeed="500" data-speed="500" data-start="1500" data-easing="Power2.easeInOut" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1">In augue urna, nunc, tincidunt, augue,<br /> augue facilisis facilisis.</div>
<div class="tp-caption Title sft  tp-resizeme " style="z-index: 4; max-width: auto; max-height: auto; white-space: nowrap; font-size: 11px;" data-x="45" data-y="400" data-endspeed="500" data-speed="500" data-start="1500" data-easing="Power2.easeInOut" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</div>
</li>
<li data-transition="random" data-slotamount="7" data-masterspeed="1000"><img src="{{skin url="images/rtl-slide-img1-home2.jpg"}}" alt="slide1" data-bgposition="left top" data-bgfit="cover" data-bgrepeat="no-repeat" />
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
Mage::getModel('cms/block')->setData($staticBlock2)->save();
}
catch (Exception $e) {
    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('An error occurred while installing eclipse theme pages and cms blocks.'));
}