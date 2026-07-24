<?php

namespace App\Filament\Resources\ContactSubmissions\Tables;

use App\Models\ContactSubmission;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ContactSubmissionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('subject')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('message')
                    ->limit(50)
                    ->toggleable(),
                IconColumn::make('is_read')
                    ->label('Read')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Submitted'),
            ])
            ->filters([
                SelectFilter::make('is_read')
                    ->label('Status')
                    ->options([
                        '0' => 'Unread',
                        '1' => 'Read',
                    ]),
            ])
            ->recordActions([
                Action::make('mark_read')
                    ->label('Mark as Read')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->action(function (ContactSubmission $record) {
                        $record->update(['is_read' => true]);
                        Notification::make()
                            ->title('Marked as read')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (ContactSubmission $record): bool => !$record->is_read),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}