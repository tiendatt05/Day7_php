<?php
require_once 'AffiliatePartner.php';

class AffiliateManager
{
    private array $partners = [];

    public function addPartner(AffiliatePartner $affiliate): void
    {
        $this->partners[] = $affiliate;
    }

    public function listPartners(): void
    {
        foreach ($this->partners as $partner) {
            echo $partner->getSummary() . PHP_EOL;
        }
    }

    public function totalCommission(float $orderValue): float
    {
        $total = 0;
        foreach ($this->partners as $partner) {
            $total += $partner->calculateCommission($orderValue);
        }
        return $total;
    }

    public function getPartners(): array
    {
        return $this->partners;
    }
}
