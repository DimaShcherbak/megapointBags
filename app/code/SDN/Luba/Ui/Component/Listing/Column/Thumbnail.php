<?php

namespace SDN\Luba\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Helper\ImageFactory as ImageHelperFactory;
use Magento\Store\Model\StoreManagerInterface;

class Thumbnail extends Column
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var ImageHelperFactory
     */
    private $imageHelperFactory;

    /**
     * @param StoreManagerInterface $storeManager
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param ProductRepositoryInterface $productRepository
     * @param ImageHelperFactory $imageHelperFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
        StoreManagerInterface      $storeManager,
        ContextInterface           $context,
        UiComponentFactory         $uiComponentFactory,
        ProductRepositoryInterface $productRepository,
        ImageHelperFactory         $imageHelperFactory,
        array                      $components = [],
        array                      $data = []
    )
    {
        $this->storeManager = $storeManager;
        $this->productRepository = $productRepository;
        $this->imageHelperFactory = $imageHelperFactory;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @param array $dataSource
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                $sku = $item['sku']; // Получаем SKU продукта
                $store = $this->storeManager->getStore();

                try {
                    $product = $this->productRepository->get($sku); // Загружаем продукт по SKU
                    $imageHelper = $this->imageHelperFactory->create()
                        ->init($product, 'product_listing_thumbnail'); // Используйте нужный тип изображения

                    $item[$fieldName . '_src'] = $imageHelper->getUrl();
                    $item[$fieldName . '_alt'] = 'Image';
                    $item[$fieldName . '_link'] = $this->getContext()->getUrl(
                        'catalog/product/edit',
                        ['id' => $product->getId()] // Параметры для ссылки на редактирование продукта
                    );
                    $item[$fieldName . '_orig_src'] = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' . $product->getImage();
                } catch (\Exception $e) {
                    // Обработка ошибок при загрузке продукта
                    $item[$fieldName . '_src'] = '';
                    $item[$fieldName . '_alt'] = '';
                    $item[$fieldName . '_link'] = '';
                    $item[$fieldName . '_orig_src'] = '';
                }
            }
        }

        return $dataSource;
    }
}