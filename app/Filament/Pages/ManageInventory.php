<?php

namespace App\Filament\Pages;

use App\Livewire\ManageInventoryTable;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageInventory extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;
    protected static string|UnitEnum|null $navigationGroup = 'Inventory';
    protected static ?string $navigationLabel = 'Manage Inventory';
    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.manage-inventory';

    public function getManageInventoryTable(): string
    {
        return ManageInventoryTable::class;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }
}