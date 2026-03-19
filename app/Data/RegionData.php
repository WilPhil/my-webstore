<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Data;

class RegionData extends Data
{
    #[Computed]
    public string $label;

    public function __construct(
        //
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
}
