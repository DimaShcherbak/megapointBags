<?php

namespace SDN\AddProductToProviderGrid\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use SDN\Keyt\Model\KeytFactory;
//use SDN\Luba\Model\ProductFactory;
//use SDN\LubaYasha\Model\LubaFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;

class SaveKeytObserver implements ObserverInterface
{
    private KeytFactory $_keytFactory;
//    private ProductFactory $lubaFactory;
//    private LubaFactory $productLubaFactory;
    private ProductRepositoryInterface $productRepository;
    private $isExecuting = false; // Инициализация флага

    public function __construct(
        ProductRepositoryInterface $productRepository,
        KeytFactory $keytFactory,
//        ProductFactory $lubaFactory,
//        LubaFactory $productLubaFactory
    )
    {
        $this->productRepository = $productRepository;
        $this->_keytFactory = $keytFactory;
//        $this->lubaFactory = $lubaFactory;
//        $this->productLubaFactory = $productLubaFactory;
    }

    /**
     * @throws NoSuchEntityException
     * @throws CouldNotSaveException
     * @throws StateException
     * @throws InputException
     */
    public function execute(Observer $observer): void
    {
        // Проверка, чтобы избежать повторного выполнения
        if ($this->isExecuting) {
            return;
        }

        $this->isExecuting = true;

        $product = $observer->getProduct();
        $provider = $product->getProvider();
        $productType = $product->getTypeId();

//        if ($productType === \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE) {
        if (in_array($productType, ['simple', 'virtual'])) {
            // Додаємо продукт в грід Каті та Влада, якщо є id "34"
            if ($provider !== null && str_contains($provider, "34")) {
                $this->saveOrUpdateKeyt($product, $this->_keytFactory);
            }
            // Додаємо продукт в грід Люби та Яші, якщо є id "36"
//            if ($provider !== null && str_contains($provider, "36")) {
//                $this->saveOrUpdateKeyt($product, $this->productLubaFactory);
//            }
        }
        $this->isExecuting = false;
    }

    /**
     * @throws NoSuchEntityException
     * @throws CouldNotSaveException
     * @throws StateException
     * @throws InputException
     */
    private function saveOrUpdateKeyt($product, $modelFactory)
    {
        $productId = $product->getEntityId();
        $attributeCode = 'supplier_price';
        $product = $this->productRepository->getById($productId);
        $model = $modelFactory->create();
        $sku = $product->getSku();
        $supplier_price = (float)$product->getSupplierPrice() ?: null;
        $qty = isset($product->getQuantityAndStockStatus()['qty']) ? $product->getQuantityAndStockStatus()['qty'] : "5";

        $existingModelBySku = $model->load($sku, 'sku');
        $existingModelByProduct_id = $model->load($productId, 'product_id');

        // Если запись не существует, создаем новую
        if (!$existingModelByProduct_id->getId() && !$existingModelBySku->getId()) {
            $model->setData('product_id', $productId);
            $model->setData('name', $product->getName());
            $model->setData('sku', $sku);
            $model->setData('qty', $qty);
            $model->setData('price', $supplier_price);
            $model->save();
        } else {
            // Если запись существует, обновляем её
            if ($existingModelBySku->getId()) {
                $existingModelBySku->setData('product_id', $productId);
                $existingModelBySku->setData('sku', $sku);
                $existingModelBySku->setData('name', $product->getName());
                $existingModelBySku->setData('qty', $qty);
//                $existingModelBySku->setData('price', $supplier_price);
                $existingModelBySku->save();
            } elseif ($existingModelByProduct_id->getId()) {
                $existingModelByProduct_id->setData('sku', $sku);
                $existingModelByProduct_id->setData('name', $product->getName());
                $existingModelByProduct_id->setData('qty', $qty);
//                $existingModelByProduct_id->setData('price', $supplier_price);
                $existingModelByProduct_id->save();
            }
        }

        // Удаляем значение поля 'Цена поставщика' из коллекции magento после добавления ее в грид поставщика
        $product->setData($attributeCode, null);
        $this->productRepository->save($product);
    }
}
