<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WasteCategoryResource\Pages;
use App\Filament\Resources\WasteCategoryResource\RelationManagers;
use App\Models\WasteCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WasteCategoryResource extends Resource
{
    protected static ?string $model = WasteCategory::class;

    protected static ?string $navigationIcon = 'hugeicons-waste-restore';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(191)
                    ->label('Nama Kategori'),
                    
                Forms\Components\Select::make('type')
                    ->options([
                        'organic' => 'Organik',
                        'inorganic' => 'Anorganik',
                    ])
                    ->required()
                    ->label('Tipe'),
                    
                Forms\Components\TextInput::make('price_per_kg')
                    ->required()
                    ->numeric()
                    ->inputMode('decimal')
                    ->prefix('Rp')
                    ->label('Harga per Kg'),
                    
                Forms\Components\FileUpload::make('image_path')
                    ->image()
                    ->directory('waste-categories')
                    ->maxSize(2048)
                    ->label('Gambar')
                    ->columnSpanFull()
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->disk('public')
                    ->square()
                    ->label('Gambar'),
                    
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label('Nama Kategori'),
                    
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'organic' => 'success',
                        'inorganic' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'organic' => 'Organik',
                        'inorganic' => 'Anorganik',
                        default => $state,
                    })
                    ->label('Tipe'),
                    
                Tables\Columns\TextColumn::make('price_per_kg')
                    ->money('IDR')
                    ->sortable()
                    ->label('Harga per Kg'),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Dibuat pada'),
                    
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Diperbarui pada'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListWasteCategories::route('/'),
            'create' => Pages\CreateWasteCategory::route('/create'),
            'edit' => Pages\EditWasteCategory::route('/{record}/edit'),
        ];
    }
}
