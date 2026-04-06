<?php

namespace App\Filament\Resources\SalesOrders\Pages;

use App\Data\SalesOrderData;
use App\Filament\Resources\SalesOrders\SalesOrderResource;
use App\Services\SalesOrderService;
use App\States\SalesOrder\Pending;
use App\States\SalesOrder\Process;
use Filament\Actions\Action;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;

class ViewSalesOrder extends ViewRecord
{
    protected static string $resource = SalesOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('process_state')
                ->label('Process')
                ->icon(Heroicon::OutlinedArrowPathRoundedSquare)
                ->visible(
                    function (?Model $record) {
                        $current_status = get_class($record->status);
                        $valid_status = [
                            Pending::class,
                            Process::class,
                        ];

                        return in_array($current_status, $valid_status);
                    }
                )
                ->schema(function (?Model $record) {
                    $valid_transitions = $record->status->transitionableStates();
                    $options = collect($valid_transitions)->mapWithKeys(
                        fn ($class) => [
                            $class => (new $class($record->status))->label(),
                        ]
                    )->toArray();

                    return [
                        Radio::make('status')
                            ->label('Status')
                            ->options($options)
                            ->required()
                            ->inlineLabel()
                            ->inline(),
                    ];
                })
                ->action(function (array $data, ?Model $record) {
                    $record->status->transitionTo(data_get($data, 'status'));
                })
                ->modalWidth(Width::Small),
            Action::make('receipt_number')
                ->label('Input Receipt Number')
                ->icon(Heroicon::OutlinedNewspaper)
                ->visible(fn (?Model $record) => get_class($record->status) === Process::class && empty($record->shipping_receipt_number))
                ->schema([
                    TextInput::make('shipping_receipt_number')
                        ->label('Receipt Number')
                        ->inlineLabel()
                        ->required(),
                ])
                ->action(function (array $data, ?Model $record) {
                    app(SalesOrderService::class)->updateShippingReceiptNumber(
                        SalesOrderData::fromModel($record),
                        data_get($data, 'shipping_receipt_number')
                    );
                })
                ->modalWidth(Width::Small),
        ];
    }
}
