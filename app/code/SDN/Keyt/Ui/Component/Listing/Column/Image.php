<?php
namespace SDN\Keyt\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Helper\ImageFactory as ImageHelperFactory;
use Magento\Store\Model\StoreManagerInterface;

class Image extends Column
{
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
        StoreManagerInterface $storeManager,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        ProductRepositoryInterface $productRepository,
        ImageHelperFactory $imageHelperFactory,
        array $components = [],
        array $data = []
    ) {
        $this->storeManager = $storeManager;
        $this->productRepository = $productRepository;
        $this->imageHelperFactory = $imageHelperFactory;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $store = $this->storeManager->getStore();
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                $sku = $item['sku']; // Получаем SKU продукта
                try {
                    $product = $this->productRepository->get($sku); // Загружаем продукт по SKU
                    $url = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' . $product->getImage();
                    $imageHelper = $this->imageHelperFactory->create()
                        ->init($product, 'product_listing_thumbnail'); // Используйте нужный тип изображения

                    $item[$fieldName . '_src'] = $url;
                    $item[$fieldName . '_alt'] = 'Image';
//                    $item[$fieldName . '_link'] = $this->getContext()->getUrl(
//                        'catalog/product/edit',
//                        ['id' => $product->getId()] // Параметры для ссылки на редактирование продукта
//                    );
                    $item[$fieldName . '_orig_src'] = $url;
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
