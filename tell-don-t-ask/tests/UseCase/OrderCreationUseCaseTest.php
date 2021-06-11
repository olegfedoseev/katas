<?php

namespace Archel\TellDontAskTest\UseCase;

use Archel\TellDontAsk\Domain\Category;
use Archel\TellDontAsk\Domain\Product;
use Archel\TellDontAsk\UseCase\OrderCreationUseCase;
use Archel\TellDontAsk\UseCase\SellItemRequest;
use Archel\TellDontAsk\UseCase\SellItemsRequest;
use Archel\TellDontAsk\UseCase\UnknownProductException;
use Archel\TellDontAskTest\Doubles\InMemoryProductCatalog;
use Archel\TellDontAskTest\Doubles\TestOrderRepository;
use PHPUnit\Framework\TestCase;

/**
 * Class OrderCreationUseCaseTest
 * @package Archel\TellDontAskTest\UseCase
 */
class OrderCreationUseCaseTest extends TestCase
{
    /**
     * @var TestOrderRepository
     */
    private $orderRepository;

    /**
     * @var Category
     */
    private $food;

    /**
     * @var InMemoryProductCatalog
     */
    private $productCatalog;

    /**
     * @var OrderCreationUseCase
     */
    private $useCase;

    public function setUp(): void
    {
        $this->orderRepository = new TestOrderRepository();
        $this->food = new Category('food', 10.0);

        $product1 = new Product('salad', 3.56, $this->food);
        $product2 = new Product('tomato', 4.65, $this->food);

        $products = [$product1, $product2];

        $this->productCatalog = new InMemoryProductCatalog(...$products);
        $this->useCase = new OrderCreationUseCase($this->orderRepository, $this->productCatalog);
    }

    /**
     * @test
     */
    public function sellMultipleItems() : void
    {
        $saladRequest = new SellItemRequest();
        $saladRequest->setProductName("salad");
        $saladRequest->setQuantity(2);

        $tomatoRequest = new SellItemRequest();
        $tomatoRequest->setProductName("tomato");
        $tomatoRequest->setQuantity(3);

        $request = new SellItemsRequest();
        $request->setRequests(...[$saladRequest, $tomatoRequest]);

        $this->useCase->run($request);

        $insertedOrder = $this->orderRepository->getSavedOrder();
        $products = $insertedOrder->getItems();
        $this->assertTrue($insertedOrder->getStatus()->isCreated());
        $this->assertEquals($insertedOrder->getTotal(), 23.20);
        $this->assertEquals($insertedOrder->getTax(), 2.13);
        $this->assertEquals("EUR", $insertedOrder->getCurrency());
        $this->assertEquals(2, count($products));
        $this->assertEquals("salad", $products[0]->getProduct()->getName());
        $this->assertEquals(3.56, $products[0]->getProduct()->getPrice());
        $this->assertEquals(2, $products[0]->getQuantity());
        $this->assertEquals(7.84, $products[0]->getTaxedAmount());
        $this->assertEquals(0.72, $products[0]->getTax());

        $this->assertEquals("tomato", $products[1]->getProduct()->getName());
        $this->assertEquals(4.65, $products[1]->getProduct()->getPrice());
        $this->assertEquals(3, $products[1]->getQuantity());
        $this->assertEquals(15.36, $products[1]->getTaxedAmount());
        $this->assertEquals(1.41, $products[1]->getTax());
    }

    /**
     * @test
     */
    public function unknownProduct() : void
    {
        $this->expectException(UnknownProductException::class);
        $request = new SellItemsRequest();
        $unknownProductRequest = new SellItemRequest();
        $unknownProductRequest->setQuantity(1);
        $unknownProductRequest->setProductName('unknown product');
        $request->setRequests(...[$unknownProductRequest]);

        $this->useCase->run($request);
    }


}
