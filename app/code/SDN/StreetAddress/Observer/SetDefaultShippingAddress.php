<?php
declare(strict_types=1);

namespace SDN\StreetAddress\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Customer\Api\Data\AddressInterface;

class SetDefaultShippingAddress implements ObserverInterface
{
    protected $addressFactory;
    protected $addressRepository;

    public function __construct(
        \Magento\Customer\Api\Data\AddressInterfaceFactory $addressFactory,
        \Magento\Customer\Api\AddressRepositoryInterface $addressRepository
    ) {
        $this->addressFactory = $addressFactory;
        $this->addressRepository = $addressRepository;
    }

    public function execute(Observer $observer)
    {
        $customer = $observer->getCustomer();

        // Create a new shipping address
        $shippingAddress = $this->addressFactory->create();
        $shippingAddress->setCustomerId($customer->getId())
                        ->setFirstname($customer->getFirstname())
                        ->setLastname($customer->getLastname())
                        ->setStreet(['-'])
                        ->setCity('-')
//                        ->setPostcode('12345')
                        ->setTelephone('-')
                        ->setCountryId('UA')
                        ->setCompany('-')
//                        ->setRegion('New York')
                        ->setIsDefaultShipping(true);

        // Save the address
        $this->addressRepository->save($shippingAddress);
    }
}
