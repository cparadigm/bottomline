<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use VladimirPopov\WebForms\Model;

class UpgradeData implements UpgradeDataInterface
{
    protected $fileCollectionFactory;

    protected $fileFactory;

    protected $storeManager;

    protected $_random;

    public function __construct(
        Model\FileFactory $fileFactory,
        Model\ResourceModel\File\CollectionFactory $fileCollectionFactory,
        \Magento\Store\Model\StoreManager $storeManager,
        \Magento\Framework\Math\Random $random
    )
    {
        $this->fileFactory = $fileFactory;
        $this->fileCollectionFactory = $fileCollectionFactory;
        $this->storeManager = $storeManager;
        $this->_random = $random;
    }

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '2.7.8', '<')) {
            $connection = $setup->getConnection();

            // register existing files in new table

            $select = $connection->select()
                ->from(['v' => $setup->getTable('webforms_results_values')], ['v.result_id', 'v.value', 'v.key', 'v.field_id'])
                ->join(['f' => $setup->getTable('webforms_fields')], 'f.id = v.field_id', [])
                ->join(['r' => $setup->getTable('webforms_results')], 'r.id = v.result_id', ['r.webform_id', 'r.store_id'])
                ->where('f.type = "file" or f.type = "image"')
                ->where('v.value <> ""');

            $query = $select->query();

            while ($file = $query->fetch()) {
                $rel_path = 'webforms' . '/' .
                    $file['result_id'] . '/' .
                    $file['field_id'] . '/' .
                    $file['key'] . '/' .
                    \Magento\Framework\File\Uploader::getCorrectFileName($file['value']);
                $store = $this->storeManager->getStore($file['store_id']);
                $full_path = $store->getBaseMediaDir() . '/' . $rel_path;
                if (file_exists($full_path)) {

                    // check if file has not been imported already

                    $collection = $this->fileCollectionFactory->create()
                        ->addFilter('field_id', $file['field_id'])
                        ->addFilter('result_id', $file['result_id']);
                    if (!$collection->getSize()) {
                        $link_hash = $this->_random->getRandomString(40);
                        $size = filesize($full_path);
                        $mime = mime_content_type($full_path);

                        if (class_exists('finfo')) {
                            $finfo = new \finfo(FILEINFO_MIME_TYPE);
                            $mime = $finfo->file($full_path);
                        }

                        $model = $this->fileFactory->create();
                        $model->setData('result_id', $file['result_id'])
                            ->setData('field_id', $file['field_id'])
                            ->setData('name', $file['value'])
                            ->setData('size', $size)
                            ->setData('mime_type', $mime)
                            ->setData('path', $rel_path)
                            ->setData('link_hash', $link_hash);
                        $model->save();
                    }
                }
            }
        }

        $setup->endSetup();
    }
}