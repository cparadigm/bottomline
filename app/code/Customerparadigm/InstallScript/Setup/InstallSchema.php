<?php

namespace Customerparadigm\InstallScript\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;

use Magento\Store\Model\WebsiteFactory;
use Magento\Store\Model\ResourceModel\Website;

class InstallSchema implements InstallSchemaInterface {
	
	/**
     * @var WebsiteFactory
     */
    private $websiteFactory;
	
	/**
     * @var Website
     */
    private $websiteResourceModel;
	
	protected $logger;
	
	public function __construct(
        WebsiteFactory $websiteFactory,
		Website $websiteResourceModel,
		\Psr\Log\LoggerInterface $logger
    ) {
        $this->websiteFactory = $websiteFactory;
		$this->websiteResourceModel = $websiteResourceModel;
		$this->logger = $logger;
    }
	
    public function install( SchemaSetupInterface $setup, ModuleContextInterface $context ) {
        $installer = $setup;

        $installer->startSetup();

		$website = $this->websiteFactory->create();
        $website->load('rwd');
		
		//$this->logger->info( var_dump($website->getData()) );

        if($website->getData("website_id")){
            $website->setData("is_default", 1);
            $this->websiteResourceModel->save($website);
        }
        
        $installer->endSetup();
    }
}