<?php

/**
 * Class CustomerHistoricInfo
 * @package Gracious\Interconnect\Model
 * Provides historic information about a customer
 */
class Gracious_Interconnect_Model_CustomerHistoricInfo implements JsonSerializable {

    /**
     * @var string
     */
    protected $email;

    /**
     * @var int
     */
    protected $totalOrderCount;

    /**
     * @var float
     */
    protected $totalOrderAmount;

    /**
     * @var string
     */
    protected $firstOrderDate;

    /**
     * @var string
     */
    protected $lastOrderDate;

    /**
     * @var string
     */
    protected $registrationDate;

    /**
     * CustomerHistoricInfo constructor.
     * @param string $email
     * @param int $totalOrderCount
     * @param float $totalOrderAmount
     * @param string $firstOrderDate
     * @param string $lastOrderDate
     * @param string $registrationDate |null
     */
    public function __construct($email, $totalOrderCount, $totalOrderAmount, $firstOrderDate, $lastOrderDate, $registrationDate = null) {
        $this->email = $email;
        $this->totalOrderCount = $totalOrderCount;
        $this->totalOrderAmount = $totalOrderAmount;
        $this->firstOrderDate = $firstOrderDate;
        $this->lastOrderDate = $lastOrderDate;
        $this->registrationDate = $registrationDate;
    }

    /**
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @return int
     */
    public function getTotalOrderCount() {
        return $this->totalOrderCount;
    }

    /**
     * @return float
     */
    public function getTotalOrderAmount() {
        return $this->totalOrderAmount;
    }

    /**
     * @return string
     */
    public function getFirstOrderDate() {
        return $this->firstOrderDate;
    }

    /**
     * @return string
     */
    public function getLastOrderDate() {
        return $this->lastOrderDate;
    }

    /**
     * @return string
     */
    public function getRegistrationDate() {
        return $this->registrationDate;
    }

    /**
     * @return bool
     */
    public function isRegisteredCustomer() {
        return is_string($this->registrationDate) && trim($this->registrationDate) != '';
    }

    /**
     * @return array
     */
    public function toArray() {
        return [
            'email' => $this->email,
            'totalOrderCount' => $this->totalOrderCount,
            'totalOrderAmount' => $this->totalOrderAmount,
            'firstOrderDate' => $this->firstOrderDate,
            'lastOrderDate' => $this->lastOrderDate,
            'registrationDate' => $this->registrationDate,
            'isRegistered' => $this->isRegisteredCustomer()
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize() {
        return json_encode($this->toArray());
    }
}