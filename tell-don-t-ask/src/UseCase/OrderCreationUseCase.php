<?php

namespace Archel\TellDontAsk\UseCase;

use Archel\TellDontAsk\Domain\Order;
use Archel\TellDontAsk\Domain\OrderItem;
use Archel\TellDontAsk\Domain\OrderStatus;
use Archel\TellDontAsk\Repository\OrderRepository;
use Archel\TellDontAsk\Repository\ProductCatalog;

/**
 * Class OrderCreationUseCase
 * @author Daniel J. Perez <danieljordi@bab-soft.com>
 * @package Archel\TellDontAsk\UseCase
 */
class OrderCreationUseCase
{
    private OrderRepository $orderRepository;
    private ProductCatalog $productCatalog;

    public function __construct(OrderRepository $orderRepository, ProductCatalog $productCatalog)
    {
        $this->orderRepository = $orderRepository;
        $this->productCatalog = $productCatalog;
    }

    public function run(SellItemsRequest $request) : void
    {
        $order = new Order('EUR');

        foreach ($request->getRequests() as $item) {
            $product = $this->productCatalog->getByName($item->getProductName());

            $order->addItem(new OrderItem($product, $item->getQuantity()));
        }

        $this->orderRepository->save($order);
    }
}
