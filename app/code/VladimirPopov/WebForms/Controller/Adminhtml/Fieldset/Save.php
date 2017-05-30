<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Controller\Adminhtml\Fieldset;

use Magento\Backend\App\Action;

class Save extends \Magento\Backend\App\Action
{
    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        $data = $this->getRequest()->getPostValue('fieldset');
        if ($this->getRequest()->getParam('id')) {
            $model = $this->_objectManager->create('VladimirPopov\WebForms\Model\Fieldset')->load($this->getRequest()->getParam('id'));
            return $this->_authorization->isAllowed('VladimirPopov_WebForms::form' . $model->getWebformId());
        }

        if (!empty($data['webform_id'])) {
            return $this->_authorization->isAllowed('VladimirPopov_WebForms::form' . $data['webform_id']);
        }

        return $this->_authorization->isAllowed('VladimirPopov_WebForms::manage_forms');
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $store = $this->getRequest()->getParam('store');
        $data = $this->getRequest()->getPostValue('fieldset');

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $model = $this->_objectManager->create('VladimirPopov\WebForms\Model\Fieldset');

            !empty($data['id']) ? $id = $data['id'] : $id = $this->getRequest()->getParam('id');
            if ($id) {
                $model->load($id);
                if ($store) {
                    unset($data['id']);
                    unset($data['webform_id']);
                    $model->saveStoreData($store, $data);
                }
            }

            $this->_eventManager->dispatch(
                'webforms_fieldset_prepare_save',
                ['fieldset' => $model, 'request' => $this->getRequest()]
            );

            try {

                if(!$store)
                    $model->setData($data)->save();

                $this->messageManager->addSuccessMessage(__('You saved this fieldset.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('webforms/form/edit', ['id' => $model->getWebformId(), 'active_tab' => 'fieldsets_section', 'store'=> $store]);
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the fieldset.'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $id, 'webform_id' => $this->getRequest()->getParam('webform_id'), 'store' => $store]);
        }
        return $resultRedirect->setPath('webforms/form/');
    }
}