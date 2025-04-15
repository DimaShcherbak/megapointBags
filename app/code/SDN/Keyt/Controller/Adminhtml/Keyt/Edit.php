<?php
declare(strict_types=1);

namespace SDN\Keyt\Controller\Adminhtml\Keyt;

class Edit extends \SDN\Keyt\Controller\Adminhtml\Keyt
{

    protected $resultPageFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * Edit action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('keyt_id');
        $model = $this->_objectManager->create(\SDN\Keyt\Model\Keyt::class);
        
        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Keyt no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->_coreRegistry->register('sdn_keyt_keyt', $model);
        
        // 3. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Product Kate') : __('New Product Kate'),
            $id ? __('Edit Product Kate') : __('New Product Kate')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Keyts'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? __('Edit Keyt %1', $model->getId()) : __('New Keyt'));
        return $resultPage;
    }
}

