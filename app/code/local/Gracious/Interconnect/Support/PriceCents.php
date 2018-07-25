<?php

/**
 * Class Gracious_Interconnect_Support_PriceCents
 */
class Gracious_Interconnect_Support_PriceCents
{

    /**
     * @var float|int
     */
    protected $price = 0;

    /**
     * PriceCents constructor.
     *
     * @param float $price
     */
    function __construct($price)
    {
        $price = $price != null ? $price : 0;
        $this->price = (float)$price;
    }

    /**
     * @param float|int $price
     *
     * @return static
     */
    public static function create($price)
    {
        return new static($price);
    }

    /**
     * @return float|int
     */
    public function toInt()
    {
        return (int)(100 * $this->price);
    }
}