<?php
/**
 * @author      Vladimir Popov
 * @copyright   Copyright © 2017 Vladimir Popov. All rights reserved.
 */

namespace VladimirPopov\WebForms\Controller\Adminhtml\Form;

use Magento\Authorization\Model\RulesFactory;
use Magento\Authorization\Model;
use Magento\Backend\App\Action;
use Magento\Backend\Model\Authorization;


class Save extends \Magento\Backend\App\Action
{
    protected $roleLocator;

    protected $_rulesFactory;

    protected $_rulesCollectionFactory;

    protected $_aclBuilder;

    protected $_cache;

    protected $authSession;

    public function __construct(
        Action\Context $context,
        Authorization\RoleLocator $roleLocator,
        RulesFactory $rulesFactory,
        \Magento\Authorization\Model\ResourceModel\Rules\CollectionFactory $rulesCollectionFactory,
        \Magento\Framework\Acl\Builder $aclBuilder,
        \Magento\Framework\Acl\CacheInterface $cache,
        \Magento\Backend\Model\Auth\Session $authSession
    )
    {
        $this->roleLocator = $roleLocator;
        $this->_rulesFactory = $rulesFactory;
        $this->_rulesCollectionFactory = $rulesCollectionFactory;
        $this->_aclBuilder = $aclBuilder;
        $this->_cache = $cache;
        $this->authSession = $authSession;

        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        if($this->getRequest()->getParam('id')){
            $collection = $this->_rulesCollectionFactory->create()
                ->addFilter('role_id', $this->roleLocator->getAclRoleId())
                ->addFilter('resource_id', 'VladimirPopov_WebForms::form'.$this->getRequest()->getParam('id'))
                ->addFilter('permission', 'allow');
            if($collection->count() === 0) return false;
            return true;
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
        $data = $this->getRequest()->getPostValue('form');
        $store = $this->getRequest()->getParam('store');

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $model = $this->_objectManager->create('VladimirPopov\WebForms\Model\Form');

            $id = empty($data['id'])? false: $data['id'];

            if ($id) {
                $model->load($id);
            }

            $this->_eventManager->dispatch(
                'webforms_form_prepare_save',
                ['form' => $model, 'request' => $this->getRequest()]
            );

            try {
                // update fields position
                $fieldsData = $this->getRequest()->getParam('fields_position');
                if (is_array($fieldsData))
                    foreach ($fieldsData['position'] as $field_id => $position) {
                        $this->_objectManager->create('VladimirPopov\WebForms\Model\Field')
                            ->setId($field_id)
                            ->setPosition($position)
                            ->save()
                        ;
                    }

                // update fieldsets position
                $fieldsetsData = $this->getRequest()->getParam('fieldsets_position');
                if (is_array($fieldsetsData))
                    foreach ($fieldsetsData['position'] as $fieldset_id => $position) {
                        $this->_objectManager->create('VladimirPopov\WebForms\Model\Fieldset')
                            ->setId($fieldset_id)
                            ->setPosition($position)
                            ->save()
                        ;
                    }

                if ($store)
                    $model->saveStoreData($store, $data);
                else
                    $model->setData($data)->save();

                // update role permissions
                if(!$this->_authorization->isAllowed('Magento_Backend::all')){
                    $collection = $this->_rulesCollectionFactory->create()
                        ->addFilter('role_id', $this->roleLocator->getAclRoleId())
                        ->addFilter('resource_id', 'VladimirPopov_WebForms::form'.$model->getId())
                        ->addFilter('permission', 'allow');
                    if($collection->count() === 0) {
                        $this->_rulesFactory->create()->setData([
                            'role_id' => $this->roleLocator->getAclRoleId(),
                            'resource_id' => 'VladimirPopov_WebForms::form' . $model->getId(),
                            'permission' => 'allow'
                        ])->save();
                    }
                    // refresh ACL
                    $this->_cache->clean();
                    $this->authSession->setAcl($this->_aclBuilder->getAcl());
                }

                $this->messageManager->addSuccessMessage(__('You saved this form.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the form.'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}