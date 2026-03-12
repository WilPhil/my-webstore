<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('cover')
                            ->collection('cover'),
                        SpatieMediaLibraryFileUpload::make('gallery')
                            ->collection('gallery')
                            ->multiple(),
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('slug')
                            ->required(),
                        TextInput::make('sku')
                            ->label('SKU')
                            ->required(),
                        SpatieTagsInput::make('tags')
                            ->type('category')
                            ->label('Category'),
                        Textarea::make('description')
                            ->columnSpanFull(),
                        TextInput::make('stock')
                            ->required()
                            ->numeric()
                            ->default(0),
                        TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->prefix('Rp'),
                        TextInput::make('weight')
                            ->required()
                            ->numeric()
                            ->suffix('gram')
                            ->default(0),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
