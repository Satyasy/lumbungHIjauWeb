<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionDetailResource\Pages;
use App\Models\TransactionDetail;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TransactionDetailResource extends Resource
{
    protected static ?string $model = TransactionDetail::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    protected static ?string $navigationGroup = 'Transactions';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('transaction_id')
                    ->relationship('transaction', 'id')
                    ->required()
                    ->searchable()
                    ->preload(),
                    
                Forms\Components\Select::make('category_id')
                    ->relationship('wasteCategory', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                    
                Forms\Components\TextInput::make('estimated_weight')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->label('Estimated Weight (kg)'),
                    
                Forms\Components\TextInput::make('actual_weight')
                    ->numeric()
                    ->minValue(0)
                    ->nullable()
                    ->label('Actual Weight (kg)'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('transaction.id')
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('wasteCategory.name')
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('estimated_weight')
                    ->suffix(' kg')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('actual_weight')
                    ->suffix(' kg')
                    ->sortable()
                    ->toggleable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListTransactionDetails::route('/'),
            'create' => Pages\CreateTransactionDetail::route('/create'),
            'edit' => Pages\EditTransactionDetail::route('/{record}/edit'),
        ];
    }
} 