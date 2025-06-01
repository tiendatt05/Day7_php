<?php

class AffiliatePartner
{
    const PLATFORM_NAME = "VietLink Affiliate";

    private string $name;
    private string $email;
    private float $commissionRate;
    private bool $isActive;

    public function __construct(string $name, string $email, float $commissionRate, bool $isActive = true)
    {
        $this->name = $name;
        $this->email = $email;
        $this->commissionRate = $commissionRate;
        $this->isActive = $isActive;
    }

    public function __destruct()
    {
        echo "AffiliatePartner '{$this->name}' object is destroyed.\n";
    }

    public function calculateCommission(float $orderValue): float
    {
        if (!$this->isActive) {
            return 0;
        }
        return $orderValue * ($this->commissionRate / 100);
    }

    public function getSummary(): string
    {
        return "CTV: {$this->name}, Email: {$this->email}, Hoa hồng: {$this->commissionRate}%, "
             . "Trạng thái: " . ($this->isActive ? 'Hoạt động' : 'Không hoạt động') . ", "
             . "Nền tảng: " . self::PLATFORM_NAME;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getIsActive(): bool
    {
        return $this->isActive;
    }
}
