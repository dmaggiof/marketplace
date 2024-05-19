<?php
namespace Marketplace\Application\Customer;
use Marketplace\Application\Cart\DTO\CartDetailsDTO;
use Marketplace\Application\Customer\DTO\CustomerPurchasing;
use Marketplace\Domain\Cart\Entity\Cart;
use Marketplace\Domain\Customer\Entity\Customer;
use Marketplace\Domain\Customer\Exceptions\CustomerHasNoAddressConfigured;
use Marketplace\Domain\Order\Entity\Order;
use Marketplace\Domain\Order\Entity\OrderLine;
use Marketplace\Infrastructure\Customer\Infrastructure\Repository\CustomerRepository;
use Marketplace\Infrastructure\Order\Repository\OrderRepository;

class MakePurchase {
    private CustomerRepository $customerRepository;
    private OrderRepository $orderRepository;

    public function __construct(CustomerRepository $customerRepository, OrderRepository $orderRepository)
    {
        $this->customerRepository = $customerRepository;
        $this->orderRepository = $orderRepository;
    }

    /**
     * @throws CustomerHasNoAddressConfigured
     */
    public function execute(CustomerPurchasing $customerPurchasing): CartDetailsDTO
    {
        $customer = $this->customerRepository->findOneBy(["id"=> $customerPurchasing->getUserId()]);

        $cart = $customer->getPendingCart();

        $this->completeCart($customer);

        $this->convertCartToOrder($cart, $customer);

        $cartDetailsDto = new CartDetailsDTO($cart->getProductCarts()->toArray());
        return $cartDetailsDto;
    }

    private function convertCartToOrder(Cart $cart, Customer $customer)
    {
        $products = $cart->getProductCarts();
        $order = new Order();
        $order->setCustomerId($customer);
        $order->setStatus("finished");
        foreach ($products as $product) {
            $orderLine = new OrderLine();
            $orderLine->setProductId($product->getProduct());
            $orderLine->setOrderId($order);
            $orderLine->setQuantity($product->getQuantity());
            $orderLine->setPrice($product->getPrice());
            $order->addOrderLine($orderLine);
        }
        $this->orderRepository->save($order);

    }

    /**
     * @param Customer|null $customer
     * @return void
     * @throws CustomerHasNoAddressConfigured
     */
    public function completeCart(Customer $customer): void
    {
        $customer->makePurchase();
        $this->customerRepository->save($customer);
    }
}
