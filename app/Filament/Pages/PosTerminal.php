<?php

namespace App\Filament\Pages;

use App\Livewire\PosTerminal as PosTerminalComponent;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class PosTerminal extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingCart;

    protected static ?string $navigationLabel = 'POS Terminal';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.pos-terminal';

    public function getPosTerminalComponent(): string
    {
        return PosTerminalComponent::class;
    }
}