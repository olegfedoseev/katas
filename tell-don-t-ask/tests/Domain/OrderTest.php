<?php


namespace Archel\TellDontAskTest\Domain;


use Archel\TellDontAsk\Domain\Exceptions\ApprovedOrderCannotBeRejectedException;
use Archel\TellDontAsk\Domain\Exceptions\OrderCannotBeShippedException;
use Archel\TellDontAsk\Domain\Exceptions\OrderCannotBeShippedTwiceException;
use Archel\TellDontAsk\Domain\Exceptions\RejectedOrderCannotBeApprovedException;
use Archel\TellDontAsk\Domain\Exceptions\ShippedOrdersCannotBeChangedException;
use Archel\TellDontAsk\Domain\Order;
use Archel\TellDontAsk\Domain\OrderStatus;
use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{
    public function testCouldNotApproveShippedOrders(): void
    {
        $this->expectExceptionObject(new ShippedOrdersCannotBeChangedException());

        $order = new Order();
        $order->setStatus(OrderStatus::shipped());
        $order->approve();
    }

    public function testCouldNotApproveRejectedOrders(): void
    {
        $this->expectExceptionObject(new RejectedOrderCannotBeApprovedException());

        $order = new Order();
        $order->setStatus(OrderStatus::rejected());
        $order->approve();
    }

    public function testCouldApproveNewOrders(): void
    {
        $order = new Order();
        $order->setStatus(OrderStatus::created());
        $order->approve();

        $this->assertTrue($order->getStatus()->isApproved());
    }

    public function testCouldNotRejectShippedOrders(): void
    {
        $this->expectExceptionObject(new ShippedOrdersCannotBeChangedException());

        $order = new Order();
        $order->setStatus(OrderStatus::shipped());
        $order->reject();
    }

    public function testCouldNotRejectApprovedOrders(): void
    {
        $this->expectExceptionObject(new ApprovedOrderCannotBeRejectedException());

        $order = new Order();
        $order->setStatus(OrderStatus::approved());
        $order->reject();
    }

    public function testCouldRejectNewOrders(): void
    {
        $order = new Order();
        $order->setStatus(OrderStatus::created());
        $order->reject();

        $this->assertTrue($order->getStatus()->isRejected());
    }

    public function testCouldNotShipRejectedOrders(): void
    {
        $this->expectExceptionObject(new OrderCannotBeShippedException());

        $order = new Order();
        $order->setStatus(OrderStatus::rejected());
        $order->isReadyToBeShipped();
    }

    public function testCouldNotShipNotApprovedOrders(): void
    {
        $this->expectExceptionObject(new OrderCannotBeShippedException());

        $order = new Order();
        $order->setStatus(OrderStatus::created());
        $order->isReadyToBeShipped();
    }

    public function testCouldNotShipAlreadyShippedOrders(): void
    {
        $this->expectExceptionObject(new OrderCannotBeShippedTwiceException());

        $order = new Order();
        $order->setStatus(OrderStatus::shipped());
        $order->isReadyToBeShipped();
    }

    public function testCouldShipApprovedOrders(): void
    {
        $order = new Order();
        $order->setStatus(OrderStatus::approved());

        $this->assertTrue($order->isReadyToBeShipped());
    }
}
