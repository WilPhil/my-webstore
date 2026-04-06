<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                TextInput::make('password')
                    ->same('password_confirmation')
                    ->password()
                    ->revealable()
                    ->required(fn (?Model $record) => is_null($record))
                    ->maxLength(255)
                    ->dehydrateStateUsing(fn ($state) => ! empty($state) ? Hash::make($state) : ''),
                TextInput::make('password_confirmation')
                    ->password()
                    ->revealable()
                    ->required(fn (?Model $record) => is_null($record))
                    ->maxLength(255)
                    ->dehydrated(false),
                Select::make('role')
                    ->required()
                    ->multiple()
                    ->preload()
                    ->relationship('roles', 'name'),

            ]);
    }
}
