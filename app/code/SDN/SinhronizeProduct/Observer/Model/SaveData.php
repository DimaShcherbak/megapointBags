<?php
//declare(strict_types=1);

namespace SDN\SinhronizeProduct\Observer\Model;

use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Validation\ValidationException;
use Magento\InventoryApi\Api\Data\SourceItemInterfaceFactory;
use Magento\InventoryApi\Api\SourceItemsSaveInterface;
use SDN\Keyt\Model\ResourceModel\Keyt\CollectionFactory as KeytProductCollection;
use SDN\Luba\Model\ResourceModel\Product\CollectionFactory as LubaProductCollection;

class SaveData
{
    private SourceItemsSaveInterface $sourceItemsSaveInterface;
    private SourceItemInterfaceFactory $sourceItemFactory;
    private LubaProductCollection $LubaProduct;
    private KeytProductCollection $KeytProduct;

    /**
     * @param SourceItemsSaveInterface $sourceItemsSaveInterface
     * @param SourceItemInterfaceFactory $sourceItemFactory
     * @param LubaProductCollection $LubaProduct
     * @param KeytProductCollection $KeytProduct
     */
    public function __construct(SourceItemsSaveInterface   $sourceItemsSaveInterface,
                                SourceItemInterfaceFactory $sourceItemFactory,
                                LubaProductCollection      $LubaProduct,
                                KeytProductCollection      $KeytProduct
    )
    {
        $this->sourceItemsSaveInterface = $sourceItemsSaveInterface;
        $this->sourceItemFactory = $sourceItemFactory;
        $this->LubaProduct = $LubaProduct;
        $this->KeytProduct = $KeytProduct;
    }

    /**
     * @param $updateItemSku
     * @param $updateItemQtyRequest
     * @return float
     */
    public function otherCountProduct($updateItemSku, $updateItemQtyRequest): float
    {
        $referer = $_SERVER["HTTP_REFERER"];
        $keytProduct = 0;
        $lubaProduct = 0;

        switch (true) {
            case str_contains($referer, '/sdn_luba/product/'):
                // Получаем количество продуктов Keyt
                $keytProductCollection = $this->KeytProduct->create();
                $keytProductCollection->addFieldToFilter('sku', $updateItemSku);
                $keytProduct = $keytProductCollection->getFirstItem()->getQty();
                break;
            case str_contains($referer, '/sdn_keyt/'):
                // Получаем количество продуктов Luba
                $lubaProductCollection = $this->LubaProduct->create();
                $lubaProductCollection->addFieldToFilter('sku', $updateItemSku);
                $lubaProduct = $lubaProductCollection->getFirstItem()->getQty();
                break;
            default:
                // Если "luba" и "keyt" не найдены в HTTP_REFERER, возвращаем 0
                return (float)$updateItemQtyRequest;
        }
        return (float)$updateItemQtyRequest + (float)$keytProduct + (float)$lubaProduct;
    }

    /**
     * @return string
     */
    public function sourceCode(): string
    {
        $referer = $_SERVER["HTTP_REFERER"];

        switch (true) {
            case str_contains($referer, '/sdn_luba/product/'):
                $sourceCode = 'Luba_Yasha';
                break;
            case str_contains($referer, '/sdn_keyt/keyt/'):
                $sourceCode = 'Kate_Vlad';
                break;
            default:
                return 'default';
        }
        return $sourceCode;
    }

    /**
     * @throws ValidationException
     * @throws CouldNotSaveException
     * @throws InputException
     */
    public function goSaveDataProduct($updateItemSku, $updateItemQtyRequest): void
    {
//        $updateItemSku = $_REQUEST['sku'];
//        $updateItemQtyRequest = (float)$_REQUEST['qty'];
        $updateItemQty = $this->otherCountProduct($updateItemSku, $updateItemQtyRequest);

        $sourceItem1 = $this->sourceItemFactory->create();
        $sourceItem1->setSourceCode($this->sourceCode());
        $sourceItem1->setSku($updateItemSku);
        $sourceItem1->setQuantity($updateItemQtyRequest);
        $sourceItem1->setStatus(1);

        $sourceItem2 = $this->sourceItemFactory->create();
        $sourceItem2->setSourceCode('default');
        $sourceItem2->setSku($updateItemSku);
        $sourceItem2->setQuantity($updateItemQty);
        $sourceItem2->setStatus(1);

        $this->sourceItemsSaveInterface->execute([$sourceItem1, $sourceItem2]);
    }

    /**
     * @throws ValidationException
     * @throws CouldNotSaveException
     * @throws InputException
     */
    public function saveDataProduct()
    {
        if (isset($_REQUEST['items'])) {
            foreach ($_REQUEST['items'] as $item) {
                if (isset($item['sku'])) {
                    $sku = $item['sku'];
                    $qty = $item['qty'];
                    $this->goSaveDataProduct($sku, $qty);
                }
            }
        }
        if (isset($_REQUEST['qty']) && isset($_REQUEST['sku'])) {
                    $sku = $_REQUEST['sku'];
                    $qty = $_REQUEST['qty'];
            $this->goSaveDataProduct($sku, $qty);
        }
    }
}
