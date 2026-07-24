<?php

namespace App\Filament\Resources\NewsletterSubscriptions;

use App\Filament\Resources\NewsletterSubscriptions\Pages\ListNewsletterSubscriptions;
use App\Filament\Resources\NewsletterSubscriptions\Tables\NewsletterSubscriptionsTable;
use App\Models\NewsletterSubscription;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class NewsletterSubscriptionResource extends Resource
{
    protected static ?string $model = NewsletterSubscription::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMegaphone;

    protected static string|UnitEnum|null $navigationGroup = 'Communications';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->disabled(),
                \Filament\Forms\Components\Toggle::make('is_active')
                    ->label('Active'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return NewsletterSubscriptionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNewsletterSubscriptions::route('/'),
        ];
    }
}