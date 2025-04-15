<?php
declare(strict_types=1);

namespace SDN\Keyt\Controller\Adminhtml\Keyt;

use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{

    protected $dataPersistor;
    private \SDN\SinhronizeProduct\Observer\Model\SaveData $saveData;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     * @param \SDN\SinhronizeProduct\Observer\Model\SaveData $saveData
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \SDN\SinhronizeProduct\Observer\Model\SaveData $saveData
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->saveData = $saveData;
        parent::__construct($context);

    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('keyt_id');
        
            $model = $this->_objectManager->create(\SDN\Keyt\Model\Keyt::class)->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This Keyt no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
        
            $model->setData($data);
        
            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the Keyt.'));
                $this->dataPersistor->clear('sdn_keyt_keyt');
                $this->saveData->saveDataProduct();
        
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['keyt_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Keyt.'));
            }
        
            $this->dataPersistor->set('sdn_keyt_keyt', $data);
            return $resultRedirect->setPath('*/*/edit', ['keyt_id' => $this->getRequest()->getParam('keyt_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}

