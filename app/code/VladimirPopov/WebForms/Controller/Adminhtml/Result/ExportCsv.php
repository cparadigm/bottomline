<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Controller\Adminhtml\Result;

use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class ExportCsv extends  \VladimirPopov\WebForms\Controller\Adminhtml\Result\Index
{
    /**
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    protected $_fileFactory;

    public function __construct(
        \Magento\Framework\Registry $coreRegistry,
        Context $context,
        PageFactory $resultPageFactory,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory
    )
    {
        $this->_fileFactory = $fileFactory;
        parent::__construct($coreRegistry,$context,$resultPageFactory);
    }

    /**
     * Export results grid to CSV format
     *
     * @return ResponseInterface
     */
    public function execute()
    {

        $this->_initForm('webform_id');

        $this->_view->loadLayout();
        $fileName = 'results.csv';
        $content = $this->_view->getLayout()->getBlock('admin.result.grid');

        return $this->_fileFactory->create(
            $fileName,
            $content->getCsvFile($fileName),
            DirectoryList::VAR_DIR
        );
    }
}
