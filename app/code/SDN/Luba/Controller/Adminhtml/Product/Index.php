<?php
declare(strict_types=1);

namespace SDN\Luba\Controller\Adminhtml\Product;

class Index extends \Magento\Backend\App\Action
{

    protected $resultPageFactory;

    /**
     * Constructor
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context        $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
//        if ($this->_auth->getUser()->getUserName() !== 'kate@gmail.com') {
//            $this->messageManager->addErrorMessage(__('You are not authorized to access this page.'));
//            $resultRedirect = $this->resultRedirectFactory->create();
//            $resultRedirect->setPath('admin/dashboard/index');
//            return $resultRedirect;
//        }

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__("Product"));
        return $resultPage;
    }
}

