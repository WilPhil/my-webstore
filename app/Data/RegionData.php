<?php

declare(strict_types=1);

namespace App\Data;

use App\Models\Region;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Data;

class RegionData extends Data
{
    #[Computed]
    public string $label;

    public function __construct(
        public string $code,
        public string $province,
        public string $regency,
        public string $district,
        public string $village,
        public string $postal_code,
        public string $region = 'indonesia',
    ) {
        $this->label = "$this->village, $this->district, $this->regency, $this->province, $this->postal_code";
    }

    public static function fromModel(Region $region): self
    {
        return new self(
            code: $region->code,
            province: $region->parent->parent->parent->name,
            regency: $region->parent->parent->name,
            district: $region->parent->name,
            village: $region->name,
            postal_code: $region->postal_code,
        );
    }
}
