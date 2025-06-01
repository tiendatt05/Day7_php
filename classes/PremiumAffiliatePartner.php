<?php
require_once 'AffiliatePartner.php';

class PremiumAffiliatePartner extends AffiliatePartner
{
    private float $bonusPerOrder;

    public function __construct(string $name, string $email, float $commissionRate, float $bonusPerOrder, bool $isActive = true)
    {
        parent::__construct($name, $email, $commissionRate, $isActive);
        $this->bonusPerOrder = $bonusPerOrder;
    }

    public function calculateCommission(float $orderValue): float
    {
        if (!$this->getIsActive()) {
            return 0;
        }
        $baseCommission = parent::calculateCommission($orderValue);
        return $baseCommission + $this->bonusPerOrder;
    }

    public function getSummary(): string
    {
        return parent::getSummary() . ", Bonus mỗi đơn: " . number_format($this->bonusPerOrder, 0) . " VNĐ";
    }
}
