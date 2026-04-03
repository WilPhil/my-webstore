<?php

namespace App\Filament\Resources\SalesOrders\Schemas;

use App\Services\LocationQueryService;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

class SalesOrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Sales Order General Information')
                    ->description('Meta & Customer Info')
                    ->schema([
                        TextEntry::make('trx_id')
                            ->label('TRX ID')
                            ->inlineLabel(),
                        TextEntry::make('status')
                            ->formatStateUsing(fn ($state) => $state->label())
                            ->inlineLabel(),
                        TextEntry::make('due_date_at')
                            ->label('Due Date')
                            ->inlineLabel(),
                        TextEntry::make('customer_full_name')
                            ->label('Customer Name')
                            ->inlineLabel(),
                        TextEntry::make('customer_email_address')
                            ->label('Customer Email')
                            ->inlineLabel(),
                        TextEntry::make('customer_phone_number')
                            ->label('Customer Phone')
                            ->inlineLabel(),
                        TextEntry::make('address_line')
                            ->label('Address Line')
                            ->formatStateUsing(function (?Model $record) {
                                $address = $record->address_line;
                                $location = app(LocationQueryService::class)->searchLocationByCode($record->destination_code)->label;

                                return "$address, {$location}";
                            })
                            ->inlineLabel(),
                    ])
                    ->columnSpanFull(),
                Section::make('Shipping Details')
                    ->collapsible()
                    ->schema([
                        TextEntry::make('shipping_driver')
                            ->label('Vendor')
                            ->inlineLabel(),
                        TextEntry::make('shipping_courier')
                            ->label('Courier')
                            ->inlineLabel(),
                        TextEntry::make('shipping_service')
                            ->label('Service')
                            ->inlineLabel(),
                        TextEntry::make('shipping_estimated_delivery')
                            ->label('Estimated Delivery')
                            ->inlineLabel(),
                        TextEntry::make('shipping_weight')
                            ->label('Weight')
                            ->suffix(' gram')
                            ->inlineLabel(),
                        TextEntry::make('shipping_receipt_number')
                            ->label('Receipt Number')
                            ->default('-')
                            ->inlineLabel(),
                    ])
                    ->columnSpanFull(),
                RepeatableEntry::make('items')
                    ->hiddenLabel()
                    ->schema([
                        TextEntry::make('name')
                            ->formatStateUsing(fn (?Model $record) => "($record->sku) $record->name"),
                        TextEntry::make('quantity'),
                        TextEntry::make('price')
                            ->money('IDR'),
                        TextEntry::make('total')
                            ->money('IDR'),
                    ])
                    ->columns(4)
                    ->columnSpanFull(),
                Section::make('Summaries')
                    ->schema([
                        TextEntry::make('payment_label')
                            ->label('Payment With')
                            ->inlineLabel(),
                        TextEntry::make('payment_paid_at')
                            ->label('Paid At')
                            ->inlineLabel()
                            ->default('-'),
                        TextEntry::make('sub_total')
                            ->label('Sub Total')
                            ->inlineLabel()
                            ->money('IDR'),
                        TextEntry::make('shipping_total')
                            ->label('Shipping Total')
                            ->inlineLabel()
                            ->money('IDR'),
                        TextEntry::make('grand_total')
                            ->label('Grand Total')
                            ->inlineLabel()
                            ->money('IDR'),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
