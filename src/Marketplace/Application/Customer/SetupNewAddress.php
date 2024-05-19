<?php
namespace Marketplace\Application\Customer;
use Marketplace\Domain\Customer\DTO\SetupNewAddressDTO;
use Marketplace\Domain\Customer\Entity\CustomerAddress;
use Marketplace\Infrastructure\Customer\Infrastructure\Repository\CustomerRepository;
use Marketplace\Infrastructure\Order\Repository\OrderRepository;

class SetupNewAddress {
    private CustomerRepository $customerRepository;
    private OrderRepository $orderRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function execute(SetupNewAddressDTO $setupNewAddressDTO): void
    {
        $customer = $this->customerRepository->findOneBy(["id"=> $setupNewAddressDTO->userId]);
        $address = $setupNewAddressDTO->address;
        $customerAddress = new CustomerAddress();
        $customerAddress->setCustomer($customer)
            ->setAddress($address)
            ->setDefaultAddress(true);
        $customer->addCustomerAddress($customerAddress);
        $this->customerRepository->save($customer);
    }

}
