<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionDetailResource\Pages\ListTransactionDetails;
use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Tables\Actions\Action;
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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->label('Transaction Code')
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Customer Name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->label('Customer Phone')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('external_id')
                    ->maxLength(255),
                Forms\Components\TextInput::make('checkout_url')
                    ->maxLength(255),
                Forms\Components\TextInput::make('payment_type')
                    ->maxLength(255),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('subtotal')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('ppn')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('total')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('barcode_id')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Transaction Code')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('barcode.images')
                    ->label('Barcode Image'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Customer Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Customer Phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('external_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('checkout_url')
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Payment Status')
                    ->badge()
                    ->colors([
                        'success' => fn($state): bool => in_array($state, ['SUCCESS', 'PAID', 'SETTLED']),
                        'warning' => fn($state): bool => $state === 'PENDING',
                        'danger'  => fn($state): bool => in_array($state, ['FAILED', 'EXPIRED']),
                    ])
                    ->searchable(),
                Tables\Columns\TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->money('IDR')
                    ->numeric(),
                Tables\Columns\TextColumn::make('ppn')
                    ->money('IDR')
                    ->numeric(),
                Tables\Columns\TextColumn::make('total')
                    ->numeric()
                    ->money('IDR')
                    ->sortable(),


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
                Tables\Actions\EditAction::make(),
                Action::make('see transaction')
                    ->color('success')
                    ->url(fn(Transaction $record): string => static::getUrl('transaction-detail.index', [
                        'parent' => $record->id,
                    ])),
            ])
            ->bulkActions([]);
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

            'transaction-detail.index' => ListTransactionDetails::route('/{parent}/transaction-details'),
        ];
    }
}
