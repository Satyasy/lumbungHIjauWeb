<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(191),
            Forms\Components\TextInput::make('email')
                ->email()
                ->required()
                ->maxLength(191)
                ->unique(ignoreRecord: true), // unique kecuali record saat ini (untuk edit)
            Forms\Components\TextInput::make('phone_number')
                ->tel()
                ->maxLength(191)
                ->nullable(),
            Forms\Components\TextInput::make('password')
                ->password()
                ->revealable() // Tombol show/hide password
                ->dehydrateStateUsing(fn ($state) => Hash::make($state)) // Hash password saat disimpan
                ->dehydrated(fn ($state) => filled($state)) // Hanya proses jika field diisi (untuk edit agar password tidak wajib diubah)
                ->required(fn (string $context): bool => $context === 'create'), 
            Forms\Components\Select::make('role')
                ->options([
                    'user' => 'User',
                    'collector' => 'Collector',
                    'admin' => 'Admin',
                ])
                ->required(),
            Forms\Components\TextInput::make('balance')
                ->numeric()
                ->inputMode('decimal')
                ->default(0.00),
            Forms\Components\Textarea::make('address')
                ->columnSpanFull()
                ->nullable(), // Mengambil lebar penuh jika dalam grid
            // Forms\Components\DateTimePicker::make('email_verified_at')
            //     ->nullable(),
        ]);
}
// ...
    public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('name')
                ->searchable(),
            Tables\Columns\TextColumn::make('email')
                ->searchable(),
            Tables\Columns\TextColumn::make('phone_number')
                ->searchable(),
            Tables\Columns\TextColumn::make('role')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'admin' => 'danger',
                    'collector' => 'success',
                    'user' => 'info',
                    default => 'gray',
                }),
            Tables\Columns\TextColumn::make('balance')
                ->money('IDR')
                ->sortable(),
            // Tables\Columns\TextColumn::make('email_verified_at')
            //     ->dateTime()
            //     ->sortable(),
            // Tables\Columns\TextColumn::make('created_at')
            //     ->dateTime()
            //     ->sortable()
            //     ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
