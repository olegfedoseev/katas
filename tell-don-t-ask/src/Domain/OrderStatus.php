<?php

namespace Archel\TellDontAsk\Domain;

/**
 * Class OrderStatus
 * @package Archel\TellDontAsk\Domain
 */
class OrderStatus
{
    /**
     * @var string
     */
    private string $type;

    private const APPROVED = 'APPROVED';
    private const REJECTED = 'REJECTED';
    private const SHIPPED = 'SHIPPED';
    private const CREATED = 'CREATED';

    /**
     * OrderStatus constructor.
     * @param string $type
     */
    private function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * @return OrderStatus
     */
    public static function approved() : self
    {
        return new static(self::APPROVED);
    }

    /**
     * @return OrderStatus
     */
    public static function rejected() : self
    {
        return new static(self::REJECTED);
    }

    /**
     * @return OrderStatus
     */
    public static function shipped() : self
    {
        return new static(self::SHIPPED);
    }

    /**
     * @return OrderStatus
     */
    public static function created() : self
    {
        return new static(self::CREATED);
    }

    public function isApproved() : bool
    {
        return $this->type === self::APPROVED;
    }

    public function isRejected() : bool
    {
        return $this->type === self::REJECTED;
    }

    public function isShipped() : bool
    {
        return $this->type === self::SHIPPED;
    }

    public function isCreated() : bool
    {
        return $this->type === self::CREATED;
    }
}
