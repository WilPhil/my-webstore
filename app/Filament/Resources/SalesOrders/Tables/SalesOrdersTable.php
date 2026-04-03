<?php

namespace App\Filament\Resources\SalesOrders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SalesOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('trx_id')
                    ->label('TRX ID'),
                TextColumn::make('customer_full_name')
                    ->label('Customer Name'),
                TextColumn::make('status')
                    ->formatStateUsing(fn ($state) => $state->label()),
                TextColumn::make('grand_total')
                    ->label('Grand Total')
                    ->money('IDR'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
