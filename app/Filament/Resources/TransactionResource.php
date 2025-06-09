<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'mdi-transition-masked';

public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Select::make('user_id')
                ->relationship('user', 'name')
                ->searchable()
                ->preload()
                ->required(),
            Forms\Components\Select::make('collector_id')
                ->relationship(name: 'collector', titleAttribute: 'id') // Menampilkan ID collector
                // Untuk menampilkan nama user collector, perlu customisasi lebih atau Eager Loading
                // Atau ->relationship('collector.user', 'name') jika relasi di Collector model ada 'user'
                // Atau buat accessor di Collector model misal: getCollectorInfoAttribute
                ->getOptionLabelFromRecordUsing(fn (App\Models\Collector $record) => "{$record->user->name} (ID: {$record->id})")// Menampilkan nama dan ID
                ->searchable(['user.name']) // Memungkinkan pencarian berdasarkan nama user di collector
                ->preload()
                ->nullable(),
            Forms\Components\Textarea::make('pickup_location')
                ->required()
                ->columnSpanFull(),
            Forms\Components\TextInput::make('total_weight')
                ->numeric()
                ->inputMode('decimal')
                ->nullable(),
            Forms\Components\TextInput::make('total_price')
                ->numeric()
                ->inputMode('decimal')
                ->nullable(),
            Forms\Components\Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'verified' => 'Verified',
                    'rejected' => 'Rejected',
                    'completed' => 'Completed',
                    'cancelled' => 'Cancelled',
                ])
                ->required()
                ->default('pending'),
            Forms\Components\TextInput::make('verification_token')
                ->maxLength(64)
                ->nullable()
                ->unique(ignoreRecord: true),
            Forms\Components\DateTimePicker::make('token_expires_at')
                ->nullable(),
            Forms\Components\Textarea::make('rejection_reason')
                ->nullable()
                ->columnSpanFull(),
        ]);
}
public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('user.name')
                ->label('Requester')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('collector.user.name') // Asumsi relasi collector->user ada
                ->label('Collector')
                ->placeholder('Not Assigned')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'pending' => 'warning',
                    'verified' => 'info',
                    'completed' => 'success',
                    'rejected' => 'danger',
                    'cancelled' => 'gray',
                    default => 'gray',
                })
                ->searchable(),
            Tables\Columns\TextColumn::make('total_weight')
                ->suffix(' kg')
                ->sortable(),
            Tables\Columns\TextColumn::make('total_price')
                ->money('IDR')
                ->sortable(),
            Tables\Columns\TextColumn::make('pickup_location')
                ->limit(30)
                ->tooltip(fn (Transaction $record): string => $record->pickup_location),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('status')
                ->options([
                    'pending' => 'Pending',
                    'verified' => 'Verified',
                    'rejected' => 'Rejected',
                    'completed' => 'Completed',
                    'cancelled' => 'Cancelled',
                ]),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\ViewAction::make(), // Tambahkan view action
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ]);
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
