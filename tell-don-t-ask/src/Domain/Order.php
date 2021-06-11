<?php

namespace Archel\TellDontAsk\Domain;

use Archel\TellDontAsk\Domain\Exceptions\ApprovedOrderCannotBeRejectedException;
use Archel\TellDontAsk\Domain\Exceptions\RejectedOrderCannotBeApprovedException;
use Archel\TellDontAsk\Domain\Exceptions\ShippedOrdersCannotBeChangedException;
use Archel\TellDontAsk\Domain\Exceptions\OrderCannotBeShippedException;
use Archel\TellDontAsk\Domain\Exceptions\OrderCannotBeShippedTwiceException;

/**
 * Class Order
 * @package Archel\TellDontAsk\Domain
 */
class Order
{
    private string $currency;
    /**
     * @var array<OrderItem>
     */
    private array $items = [];
    private OrderStatus $status;
    private int $id;

    public function __construct(string $currency = 'EUR')
    {
        $this->setStatus(OrderStatus::created());
        $this->currency = $currency;
    }

    /**
     * @return float
     */
    public function getTotal() : float
    {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item->getTaxedAmount();
        }

        return $total;
    }

    /**
     * @return string
     */
    public function getCurrency() : string
    {
        return $this->currency;
    }

    /**
     * @return array
     */
    public function getItems() : array
    {
        return $this->items;
    }

    /**
     * @param OrderItem $item
     */
    public function addItem(OrderItem $item) : void
    {
        $this->items[] = $item;
    }

    /**
     * @return float
     */
    public function getTax() : float
    {
        $tax = 0;
        foreach ($this->items as $item) {
            $tax += $item->getTax();
        }

        return $tax;
    }

    /**
     * @return OrderStatus
     */
    public function getStatus() : OrderStatus
    {
        return $this->status;
    }

    /**
     * @param OrderStatus $orderStatus
     */
    public function setStatus(OrderStatus $orderStatus) : void
    {
        $this->status = $orderStatus;
    }

    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id) : void
    {
        $this->id = $id;
    }

    /**
     * @throws ShippedOrdersCannotBeChangedException
     * @throws RejectedOrderCannotBeApprovedException
     */
    public function approve(): void
    {
        if ($this->status->isShipped()) {
            throw new ShippedOrdersCannotBeChangedException();
        }

        if ($this->status->isRejected()) {
            throw new RejectedOrderCannotBeApprovedException();
        }

        $this->setStatus(OrderStatus::approved());
    }

    /**
     * @throws ApprovedOrderCannotBeRejectedException
     * @throws ShippedOrdersCannotBeChangedException
     */
    public function reject(): void
    {
        if ($this->status->isShipped()) {
            throw new ShippedOrdersCannotBeChangedException();
        }

        if ($this->status->isApproved()) {
            throw new ApprovedOrderCannotBeRejectedException();
        }

        $this->setStatus(OrderStatus::rejected());
    }

    /**
     * @throws OrderCannotBeShippedException
     * @throws OrderCannotBeShippedTwiceException
     */
    public function isReadyToBeShipped(): bool
    {
        if ($this->status->isCreated() || $this->status->isRejected()) {
            throw new OrderCannotBeShippedException();
        }

        if ($this->status->isShipped()) {
            throw new OrderCannotBeShippedTwiceException();
        }

        return true;
    }

    /**
     * @throws OrderCannotBeShippedException
     * @throws OrderCannotBeShippedTwiceException
     */
    public function shipped(): void
    {
        if ($this->isReadyToBeShipped()) {
            $this->setStatus(OrderStatus::shipped());
        }
    }
}
