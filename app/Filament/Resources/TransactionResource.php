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
use Filament\Navigation\NavigationItem;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'mdi-transition-masked';

    protected static ?string $navigationGroup = 'Transactions';
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count() ?: null;
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function getNavigationItems(): array
    {
        return [
            ...parent::getNavigationItems(),
            NavigationItem::make('Transaction Details')
                ->icon('heroicon-o-rectangle-stack')
                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.resources.transaction-details.*'))
                ->url(fn (): string => TransactionDetailResource::getUrl('index')),
        ];
    }

public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Select::make('user_id')
                ->relationship('user', 'name')
                ->searchable()
                ->preload()
                ->required(),
            Forms\Components\Textarea::make('pickup_location')
                    ->nullable()
                ->columnSpanFull(),
            Forms\Components\TextInput::make('total_weight')
                ->numeric()
                ->inputMode('decimal')
                ->nullable(),
            Forms\Components\TextInput::make('total_price')
                ->numeric()
                ->inputMode('decimal')
                ->nullable(),
                Forms\Components\FileUpload::make('image_path')
                ->image()
                    ->directory('transaction_proofs')
                ->nullable(),
                Forms\Components\Select::make('status')
                ->options([
                    'cart' => 'Cart',
                    'pending' => 'Pending',
                    'verified' => 'Verified',
                    'rejected' => 'Rejected',
                ])
                ->required()
                ->default('cart'),
            Forms\Components\Textarea::make('rejection_reason')
                ->nullable()
                    ->columnSpanFull()
                    ->visible(fn ($get) => $get('status') === 'rejected'),
        ]);
}

public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('user.name')
                ->label('User')
                ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'cart' => 'gray',
                    'pending' => 'warning',
                    'verified' => 'success',
                    'rejected' => 'danger',
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
                ->tooltip(fn ($record) => $record->pickup_location),
                Tables\Columns\ImageColumn::make('image_path')
                    ->disk('public'),
                Tables\Columns\TextColumn::make('rejection_reason')
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->rejection_reason)
                    ->visible(fn ($record) => $record->status === 'rejected'),
            Tables\Columns\TextColumn::make('created_at')
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
