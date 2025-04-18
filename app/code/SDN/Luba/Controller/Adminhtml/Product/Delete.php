<?php
declare(strict_types=1);

namespace SDN\Luba\Controller\Adminhtml\Product;

class Delete extends \SDN\Luba\Controller\Adminhtml\Product
{

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('product_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create(\SDN\Luba\Model\Product::class);
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the Product.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['product_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a Product to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}

