<?php
declare(strict_types=1);

namespace SDN\Keyt\Controller\Adminhtml\Keyt;

class Delete extends \SDN\Keyt\Controller\Adminhtml\Keyt
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
        $id = $this->getRequest()->getParam('keyt_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create(\SDN\Keyt\Model\Keyt::class);
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the Keyt.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['keyt_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a Keyt to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}

