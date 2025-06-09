<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WithdrawalResource\Pages;
use App\Filament\Resources\WithdrawalResource\RelationManagers;
use App\Models\Withdrawal;
use App\Models\User; // Untuk Select User
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WithdrawalResource extends Resource
{
    protected static ?string $model = Withdrawal::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes'; // Ganti ikon sesuai keinginan

    protected static ?string $navigationGroup = 'Financial'; // Opsional: grup navigasi

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name') // Relasi ke user, tampilkan nama
                    ->searchable()
                    ->preload() // Preload opsi untuk pencarian lebih cepat
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->inputMode('decimal') // Meskipun disimpan decimal, input mode bisa decimal
                    ->prefix('IDR'), // Atau mata uang Anda
                Forms\Components\TextInput::make('method')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g., Bank Transfer, DANA, GOPAY'),
                Forms\Components\TextInput::make('virtual_account')
                    ->label('Account Details / VA Number')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g., Bank BCA 123456789 an John Doe'),
                Forms\Components\Select::make('status') // ENUM
                    ->options([
                        'pending' => 'Pending',
                        'accepted' => 'Accepted',
                        'rejected' => 'Rejected',
                        'expired' => 'Expired',
                    ])
                    ->required()
                    ->default('pending'),
                Forms\Components\DateTimePicker::make('expires_at')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name') // Tampilkan nama user
                    ->label('User')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money('IDR') // Atau mata uang Anda
                    ->sortable(),
                Tables\Columns\TextColumn::make('method')
                    ->searchable(),
                Tables\Columns\TextColumn::make('virtual_account')
                    ->label('Account Details')
                    ->limit(40) // Batasi panjang teks yang ditampilkan
                    ->tooltip(fn (Withdrawal $record): string => $record->virtual_account), // Tooltip untuk teks penuh
                Tables\Columns\TextColumn::make('status') // ENUM
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'accepted' => 'success',
                        'rejected' => 'danger',
                        'expired' => 'gray',
                        default => 'secondary',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('expires_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'accepted' => 'Accepted',
                        'rejected' => 'Rejected',
                        'expired' => 'Expired',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(), // Tambahkan ViewAction
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
            // Jika ada relasi yang ingin ditampilkan di halaman edit/view Withdrawal
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWithdrawals::route('/'),
            'create' => Pages\CreateWithdrawal::route('/create'),
            'edit' => Pages\EditWithdrawal::route('/{record}/edit'),
            // 'view' => Pages\ViewWithdrawal::route('/{record}'), // Aktifkan halaman view
        ];
    }
}