<?php

namespace App\Filament\Resources\ReturnExchanges;

use App\Filament\Resources\ReturnExchanges\Pages\EditReturnExchange;
use App\Filament\Resources\ReturnExchanges\Pages\ListReturnExchanges;
use App\Filament\Resources\ReturnExchanges\Schemas\ReturnExchangeForm;
use App\Filament\Resources\ReturnExchanges\Tables\ReturnExchangesTable;
use App\Models\ReturnExchange;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ReturnExchangeResource extends Resource
{
    protected static ?string $model = ReturnExchange::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-arrow-uturn-left';

    protected static ?string $navigationLabel = 'Returns & Exchanges';

    protected static ?string $pluralModelLabel = 'Returns & Exchanges';

    public static function form(Schema $schema): Schema
    {
        return ReturnExchangeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReturnExchangesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListReturnExchanges::route('/'),
            'edit' => EditReturnExchange::route('/{record}/edit'),
        ];
    }
}