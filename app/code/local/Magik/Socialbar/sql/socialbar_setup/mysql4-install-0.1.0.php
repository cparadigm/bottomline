<?php
$installer = $this;
$installer->startSetup();
$installer->run("


DROP TABLE IF EXISTS {$this->getTable('magik_socialbar')};
CREATE TABLE IF NOT EXISTS {$this->getTable('magik_socialbar')} (id int not null auto_increment, name varchar(200),show_socialsites varchar(255), social_block_code text, show_pagelocation varchar(150), 
			      show_category varchar(255),store_id varchar(100), primary key(id));


DROP TABLE IF EXISTS {$this->getTable('magik_socialsites')};
CREATE TABLE IF NOT EXISTS {$this->getTable('magik_socialsites')} (id int not null auto_increment, name varchar(255) NOT NULL, favicon varchar(255) NOT NULL,
  url text NOT NULL,
  primary key(id));

INSERT INTO {$this->getTable('magik_socialsites')} VALUES (1,'Delicious','delicious.png','http://delicious.com/post?url=PERMALINK&amp;title=TITLE&amp;notes=EXCERPT'),
(2,'Digg','digg.png','http://digg.com/submit?phase=2&amp;url=PERMALINK&amp;title=TITLE&amp;bodytext=EXCERPT'),
(3,'Facebook','facebook.png','http://www.facebook.com/share.php?u=PERMALINK&amp;t=TITLE'),
(4,'LinkedIn','linkedin.png','http://www.linkedin.com/shareArticle?mini=true&amp;url=PERMALINK&amp;title=TITLE&amp;source=BLOGNAME&amp;summary=EXCERPT'),
(5,'Reddit','reddit.png','http://reddit.com/submit?url=PERMALINK&amp;title=TITLE'),(16,'RSS','rss.png','FEEDLINK'),
(6,'StumbleUpon','stumbleupon.png','http://www.stumbleupon.com/submit?url=PERMALINK&amp;title=TITLE'),
(7,'Tumblr','tumblr.png','http://www.tumblr.com/share?v=3&amp;u=PERMALINK&amp;t=TITLE&amp;s=EXCERPT'),
(8,'Twitter','twitter.png','http://twitter.com/home?status=TITLE%20-%20PERMALINK'),
('9','Pinterest','PinExt.png','http://pinterest.com/pin/create/link/?url=PERMALINK&amp;media=Productmedia&amp;description=DESCRIPTION'),
('10','Google Plus','googleplus.png','https://plus.google.com/share?url=PERMALINK');
");


$installer->endSetup();

$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$setup->addAttribute('catalog_product', 'product_socialbar', array(
        'group'             => 'Magik Socialbar',
        'type'              => 'int',
        'backend'           => '',
        'frontend'          => '',
        'label'             => 'Disable Social Bar for this product',
        'input'             => 'boolean',
        'class'             => '',
        'source'            => '',
        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'visible'           => true,
        'required'          => false,
        'user_defined'      => false,
        'default'           => '0',
        'searchable'        => false,
        'filterable'        => false,
        'comparable'        => false, 
        'visible_on_front'  => false,
        'unique'            => false,        
        'is_configurable'   => false
    ));	 



