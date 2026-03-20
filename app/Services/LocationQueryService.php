<?php

declare(strict_types=1);

namespace App\Services;

use App\Data\RegionData;
use App\Models\Region;
use Spatie\LaravelData\DataCollection;

class LocationQueryService
{
    public function searchLocationByKeyword(string $keyword, int $limit = 5): DataCollection
    {
        $locations = Region::where('type', 'village')
            ->where(function ($q) use ($keyword) {
                $q->whereLike('name', "%$keyword%")
                    ->orWhereLike('postal_code', "%$keyword%")
                    // District
                    ->orWhereRelation('parent', 'name', 'LIKE', "%$keyword%")
                    // Regency
                    ->orWhereHas('parent.parent', function ($q) use ($keyword) {
                        $q->whereLike('name', "%$keyword%");
                    })
                    // Province
                    ->orWhereHas('parent.parent.parent', function ($q) use ($keyword) {
                        $q->whereLike('name', "%$keyword%");
                    });
            })
            ->with(['parent.parent.parent'])
            ->limit($limit)
            ->get();

        return new DataCollection(RegionData::class, $locations);
    }

    public function searchLocationByCode(string $code): RegionData
    {
        $location = RegionData::fromModel(
            Region::where('code', $code)->first()
        );

        return $location;
    }
}
