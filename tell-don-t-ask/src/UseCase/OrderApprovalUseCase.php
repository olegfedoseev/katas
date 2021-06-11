<?php

namespace Archel\TellDontAsk\UseCase;

use Archel\TellDontAsk\Repository\OrderRepository;

/**
 * Class OrderApprovalUseCase
 * @package Archel\TellDontAsk\UseCase
 */
class OrderApprovalUseCase
{
    private OrderRepository $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function run(OrderApprovalRequest $request) : void
    {
        $order = $this->orderRepository->getById($request->getOrderId());

        if ($request->isApproved()) {
            $order->approve();
        } else {
            $order->reject();
        }

        $this->orderRepository->save($order);
    }
}
