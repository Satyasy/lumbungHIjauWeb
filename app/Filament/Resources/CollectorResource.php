<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CollectorResource\Pages;
use App\Filament\Resources\CollectorResource\RelationManagers;
use App\Models\Collector;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CollectorResource extends Resource
{
    protected static ?string $model = Collector::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Select::make('user_id')
                ->relationship('user', 'name') 
                ->searchable()
                ->preload() // Preload opsi untuk pencarian lebih cepat
                ->required()
                // ->options(fn() => \App\Models\User::where('role', 'collector')->pluck('name', 'id'))
                ->unique(table: 'collectors', column: 'user_id', ignoreRecord: true),
            Forms\Components\Textarea::make('assigned_area')
                ->nullable()
                ->columnSpanFull(),
        ]);
}

public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('user.name') // Tampilkan nama user melalui relasi
                ->label('Nama Kolektor') // Custom label
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('user.email') // Tampilkan email user
                ->label('Email Kolektor')
                ->searchable(),
            Tables\Columns\TextColumn::make('assigned_area')
                ->limit(50) // Batasi panjang teks yang ditampilkan
                ->tooltip(fn ($record) => $record->assigned_area), // Tampilkan full teks saat hover
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
        ->filters([
            //
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListCollectors::route('/'),
            'create' => Pages\CreateCollector::route('/create'),
            'edit' => Pages\EditCollector::route('/{record}/edit'),
        ];
    }
}
