<?php
class AffiliatePartner
{
    public const PLATFORM_NAME = "VietLink Affiliate";

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
        echo "<!-- Đối tượng AffiliatePartner '{$this->name}' đã bị hủy -->\n";
    }

    public function calculateCommission(float $orderValue): float
    {
        return $orderValue * ($this->commissionRate / 100);
    }

    public function getSummary(): string
    {
        return "CTV: {$this->name} | Email: {$this->email} | Tỷ lệ hoa hồng: {$this->commissionRate}% | "
            . "Trạng thái: " . ($this->isActive ? "Hoạt động" : "Không hoạt động") . " | Nền tảng: " . self::PLATFORM_NAME;
    }

    public function getName(): string
    {
        return $this->name;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getCommissionRate(): float
    {
        return $this->commissionRate;
    }
    public function getIsActive(): bool
    {
        return $this->isActive;
    }
}

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
        $baseCommission = parent::calculateCommission($orderValue);
        return $baseCommission + $this->bonusPerOrder;
    }

    public function getBonusPerOrder(): float
    {
        return $this->bonusPerOrder;
    }

    public function getSummary(): string
    {
        return parent::getSummary() . " | Bonus mỗi đơn: " . number_format($this->bonusPerOrder, 0) . " VNĐ";
    }
}

class AffiliateManager
{
    private array $partners = [];

    public function addPartner(AffiliatePartner $affiliate): void
    {
        if ($affiliate->getIsActive()) {
            $this->partners[] = $affiliate;
        }
    }

    public function getPartners(): array
    {
        return $this->partners;
    }

    public function listPartners(): void
    {
        foreach ($this->partners as $partner) {
            echo $partner->getSummary() . "<br>";
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
}

$manager = new AffiliateManager();

$affiliate1 = new AffiliatePartner("Nguyen Van A", "a@example.com", 5.0);
$affiliate2 = new AffiliatePartner("Tran Thi B", "b@example.com", 7.5);
$premiumAffiliate = new PremiumAffiliatePartner("Le Van C", "c@example.com", 6.0, 100000);

$manager->addPartner($affiliate1);
$manager->addPartner($affiliate2);
$manager->addPartner($premiumAffiliate);

$orderValue = 2000000;

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Quản lý Cộng tác viên - Affiliate Management (MVP)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container my-5">
    <h1 class="mb-4 text-center">Quản lý Cộng tác viên - Affiliate Management (MVP)</h1>

    <h3>Danh sách cộng tác viên</h3>
    <table class="table table-striped table-bordered align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Họ tên</th>
                <th>Email</th>
                <th>Tỷ lệ hoa hồng (%)</th>
                <th>Bonus mỗi đơn (VNĐ)</th>
                <th>Trạng thái</th>
                <th>Nền tảng</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($manager->getPartners() as $index => $partner): ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td><?= htmlspecialchars($partner->getName()) ?></td>
                <td><?= htmlspecialchars($partner->getEmail()) ?></td>
                <td><?= number_format($partner->getCommissionRate(), 2) ?></td>
                <td>
                    <?= ($partner instanceof PremiumAffiliatePartner) ? number_format($partner->getBonusPerOrder(), 0) : '-' ?>
                </td>
                <td>
                    <?= ($partner->getIsActive()) ? '<span class="badge bg-success">Hoạt động</span>' : '<span class="badge bg-secondary">Không hoạt động</span>' ?>
                </td>
                <td><?= AffiliatePartner::PLATFORM_NAME ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <h3>Hoa hồng từng người khi bán đơn hàng trị giá <?= number_format($orderValue, 0) ?> VNĐ</h3>
    <ul class="list-group mb-4">
        <?php foreach ($manager->getPartners() as $partner): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <?= htmlspecialchars($partner->getName()) ?>
                <span class="badge bg-primary rounded-pill"><?= number_format($partner->calculateCommission($orderValue), 0) ?> VNĐ</span>
            </li>
        <?php endforeach; ?>
    </ul>

    <h3>Tổng hoa hồng hệ thống cần chi trả</h3>
    <div class="alert alert-info fs-5 fw-semibold">
        <?= number_format($manager->totalCommission($orderValue), 0) ?> VNĐ
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
