<?php

namespace Archel\TellDontAsk\UseCase;

use Archel\TellDontAsk\Domain\Exceptions\OrderCannotBeShippedException;
use Archel\TellDontAsk\Domain\Exceptions\OrderCannotBeShippedTwiceException;
use Archel\TellDontAsk\Domain\OrderStatus;
use Archel\TellDontAsk\Repository\OrderRepository;
use Archel\TellDontAsk\Service\ShipmentService;

/**
 * Class OrderShipmentUseCase
 * @package Archel\TellDontAsk\UseCase
 */
class OrderShipmentUseCase
{
    private OrderRepository $orderRepository;
    private ShipmentService $shipmentService;

    public function __construct(OrderRepository $orderRepository, ShipmentService $shipmentService)
    {
        $this->orderRepository = $orderRepository;
        $this->shipmentService = $shipmentService;
    }

    /**
     * @param OrderShipmentRequest $request
     * @throws OrderCannotBeShippedException
     * @throws OrderCannotBeShippedTwiceException
     */
    public function run(OrderShipmentRequest $request) : void
    {
        $order = $this->orderRepository->getById($request->getOrderId());

        if ($order->isReadyToBeShipped()) {
            $this->shipmentService->ship($order);
            $order->shipped();

            $this->orderRepository->save($order);
        }
    }
}
