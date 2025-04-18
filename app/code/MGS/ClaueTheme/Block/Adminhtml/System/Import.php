<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MGS\ClaueTheme\Block\Adminhtml\System;

/**
 * Export CSV button for shipping table rates
 *
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Import extends \MGS\Fbuilder\Block\Adminhtml\System\Import
{
    /**
     * @var \Magento\Backend\Model\UrlInterface
     */
    protected $_backendUrl;
	
	protected $collectionFactory;
	
	protected $_request;
	
    /**
     * @param \Magento\Framework\Data\Form\Element\Factory $factoryElement
     * @param \Magento\Framework\Data\Form\Element\CollectionFactory $factoryCollection
     * @param \Magento\Framework\Escaper $escaper
     * @param \Magento\Backend\Helper\Data $helper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Data\Form\Element\Factory $factoryElement,
        \Magento\Framework\Data\Form\Element\CollectionFactory $factoryCollection,
        \Magento\Framework\Escaper $escaper,
        \Magento\Backend\Model\UrlInterface $backendUrl,
		\Magento\Cms\Model\ResourceModel\Page\CollectionFactory $collectionFactory,
		\Magento\Framework\App\Request\Http $request,
        array $data = []
    ) {
        parent::__construct($factoryElement, $factoryCollection, $escaper, $backendUrl, $collectionFactory, $data);
        $this->_backendUrl = $backendUrl;
		$this->collectionFactory = $collectionFactory;
		$this->_request = $request;
    }

    /**
     * @return string
     */
    public function getElementHtml()
    {
        /** @var \Magento\Backend\Block\Widget\Button $buttonBlock  */
		$collection = $this->collectionFactory->create();
		
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$activeKey = $objectManager->get('Magento\Framework\App\Config\ScopeConfigInterface')->getValue('active_theme/activate/claue');
		$attr = '';
		$activeUrl = $objectManager->get('Magento\Backend\Model\UrlInterface')->getUrl("adminhtml/system_config/edit/section/active_theme");
		if($activeKey==''){
			$attr = 'disabled="disabled"';
		}
		
		$html = '<input type="file" id="fbuilder_import_file" name="import_file" accept="application/xml" style="margin-bottom:5px"'.$attr.'/><br/><select id="fbuilder_import_page_id" name="groups[import][fields][page_id][value]" class="select admin__control-select" data-ui-id="select-groups-import-fields-page_id-value" style="width:210px; margin-right:10px"'.$attr.'>
		
		<option value="">'.__('Choose Page to Import').'</option>';
		if(count($collection)>0){
			foreach($collection as $page){
				if($page->getId()){
					$html .= '<option value="'.$page->getId().'">'. $page->getTitle() . ' (ID:'.$page->getId().')' . '</option>';
				}
			}
		}
		
		$html .= '</select>';
		
		if($storeId = $this->_request->getParam('store')){
			$url = $this->_backendUrl->getUrl("adminhtml/fbuilder/import", ['store'=>$storeId]);
		}elseif($websiteId = $this->_request->getParam('website')){
			$url = $this->_backendUrl->getUrl("adminhtml/fbuilder/import", ['website'=>$websiteId]);
		}else{
			$url = $this->_backendUrl->getUrl("adminhtml/fbuilder/import");
		}
		
		if($activeKey==''){
			$html .= '<button type="button" class="action-default scalable" data-ui-id="widget-button-2"'.$attr.'><span id="wait-text" style="display:none">'.__('Please wait...').'</span><span id="import-text">'.__('Import').'</span></button><br/>';
			
			$html .= '<span style="color:#ff0000; font-size:14px">Please <a href="'.$activeUrl.'">activate</a> for Claue theme.</span>';
		}
		else{
			$html .= '<button type="button" class="action-default scalable" onclick="importPage(\''.$url.'\')" data-ui-id="widget-button-2"><span id="wait-text" style="display:none">'.__('Please wait...').'</span><span id="import-text">'.__('Import').'</span></button>';
		}

        return $html;
    }
}
