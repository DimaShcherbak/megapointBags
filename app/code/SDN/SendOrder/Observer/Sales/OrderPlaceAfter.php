<?php
declare(strict_types=1);

namespace SDN\SendOrder\Observer\Sales;

use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Store\Model\StoreManagerInterface;

class OrderPlaceAfter implements ObserverInterface
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param ProductRepository $productRepository
     */

    /**
     * @param ProductRepository $productRepository
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(ProductRepository $productRepository, StoreManagerInterface $storeManager)
    {
        $this->productRepository = $productRepository;
        $this->storeManager = $storeManager;
    }

    /**
     * @param $productId
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getProduct($productId): string
    {
        $store = $this->storeManager->getStore();
        $product = $this->productRepository->getById($productId);
        return $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' . $product->getImage();
    }

    public function getProductPrice($productId) {
        return $this->productRepository->getById($productId)->getPrice();
    }

    public function getComments($order)
    {
        foreach ($order->getStatusHistoryCollection() as $status) {
            if ($status->getComment()) {
                return $status->getComment();
            }
        }
    }

    /**
     * @param Observer $observer
     * @return void
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute(Observer $observer): void
    {
        $order = $observer->getEvent()->getData('order');
        $order_id = $order->getData('increment_id');
        $customerName = $order->getData('customer_firstname') . "\n";
        $customerLastName = $order->getData('customer_lastname') . "\n";
        $customerPhone = $order->getData('addresses')[0]->getData('telephone') . "\n";
        $street = $order->getData('addresses')[0]->getData('street') ? 'Улица:' . ' ' . $order->getData('addresses')[0]->getData('street') . "\n" : '';
        $wherehouse = $order->getData('addresses')[0]->getData('company') ? 'Номер отделения:' . ' №' . $order->getData('addresses')[0]->getData('company') . "\n" : '';
        $city = $order->getData('addresses')[0]->getData('city') . "\n";
        $shipping = $order->getData('shipping_description') . "\n";
        $comment = $this->getComments($order) ? $this->getComments($order) : '-' . "\n";
        $parse_mode = 'HTML';
        $chat_id = '-764969191'; // Telegram chat ID
        $productsText = '';

        foreach ($order->getAllVisibleItems() as $item) {
            $productName = $item->getData('name');
            $productSku = $item->getData('sku');
            $productId = $item->getData('product_id');
            $product_price = (int)$this->getProductPrice($productId);
            $img = $this->getProduct($productId);
            $imageLink = "<a href='$img'>Изображение</a>";
            $productsText .= "Имя продукта: $productName\nSKU: $productSku\nЦена: $product_price грн\n$imageLink\n\n";
        }

        $text = 'Имя: ' . $customerName . 'Фамилия: ' . $customerLastName . 'Телефон: ' . $customerPhone . 'Тип доставки: ' . $shipping . 'Город: ' . $city . $street . $wherehouse
            . 'Order_id: ' . $order_id . "\n" . $productsText . 'Коментарий: ' . $comment;
        $message = urlencode("$text");
        fopen("https://api.telegram.org/bot5036551008:AAGuZp0dALHhV_WiMayiTrMrzT963c8W7TU/sendMessage?chat_id=$chat_id&text=" . $message, 'rb');
    }
}
