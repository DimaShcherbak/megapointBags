<?php

namespace SDN\UpdatePrice\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\State;
use SDN\Keyt\Model\KeytFactory;
use SDN\Luba\Model\ProductFactory;

class UpdatePriceCommand extends Command
{
    protected $productCollectionFactory;
    protected $keytFactory;
    protected $productFactory;
    protected $state;

    public function __construct(
        CollectionFactory $productCollectionFactory,
        KeytFactory       $keytFactory,
        ProductFactory    $productFactory,
        State             $state
    )
    {
        parent::__construct();
        $this->productCollectionFactory = $productCollectionFactory;
        $this->keytFactory = $keytFactory;
        $this->productFactory = $productFactory;
        $this->state = $state;
    }

    protected function configure()
    {
        $this->setName('update:prices')
            ->setDescription('Update product prices in Magento 2');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->state->emulateAreaCode('frontend', [$this, 'updatePrice']);
        $output->writeln('<info>Цены продуктов успешно обновлены.</info>');
        return 0;
    }

    public function updatePrice()
    {
        $dollar_exchange_rate = 38;  // Курс доллара
        $margin = 0.4; // Маржа (наценка)

        // Коллекция Magento
        $productCollection = $this->productCollectionFactory->create();
        $productCollection->addAttributeToSelect(['price']);

        foreach ($productCollection as $product) {
            $sku = $product->getSku();

            // Проверка типа продукта (только Simple Product)
            if ($product->getTypeId() !== \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE) {
                continue;
            }

            // Коллекция Keyt
            $keytProduct = $this->keytFactory->create()->load($sku, 'sku');
            $currentPriceKeyt = ($keytProduct->getId()) ? $keytProduct->getPrice() : null;

            // Коллекция Luba
            $lubaProduct = $this->productFactory->create()->load($sku, 'sku');
            $currentPriceLuba = ($lubaProduct->getId()) ? $lubaProduct->getPrice() : null;

            // Находим максимальную цену из трех коллекций
            $availablePrices = array_filter([$currentPriceKeyt, $currentPriceLuba]);

            if (empty($availablePrices)) {
                // Если SKU отсутствует во всех коллекциях, пропускаем обработку
                continue;
            }

            $maxPrice = max($availablePrices);

            // Вычисляем новую цену
            $newPrice = ceil(($maxPrice + ($maxPrice * $margin)) * $dollar_exchange_rate);

            // Устанавливаем новую цену в Magento
            $product->setPrice($newPrice);
            $product->save();
        }
    }
}
