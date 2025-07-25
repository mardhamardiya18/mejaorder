<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FoodResource\Pages;
use App\Filament\Resources\FoodResource\RelationManagers;
use App\Models\Food;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FoodResource extends Resource
{
    protected static ?string $model = Food::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\RichEditor::make('description')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('images')
                    ->columnSpanFull()
                    ->image()
                    ->directory('food-images')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->reactive(),
                Forms\Components\Toggle::make('is_promo')
                    ->columnSpanFull()
                    ->reactive(),
                Forms\Components\Select::make('discount_percentage')
                    ->options([
                        10 => '10%',
                        25 => '25%',
                        35 => '35%',
                        50 => '50%',
                    ])
                    ->columnSpanFull()
                    ->reactive() // Agar perubahan memicu update pada komponen lain
                    ->hidden(fn($get) => !$get('is_promo')) // Hanya tampil jika is_promo bernilai true
                    ->afterStateUpdated(function ($set, $get) {
                        $price = $get('price');
                        $percent = (int) $get('discount_percentage');
                        $isPromo = $get('is_promo');

                        $finalPrice = $isPromo && $price && $percent
                            ? $price - ($price * $percent / 100)
                            : $price;

                        $set('discount_price', $finalPrice);
                    }),
                Forms\Components\TextInput::make('discount_price')
                    ->numeric()
                    ->prefix('Rp')
                    ->readOnly()
                    ->columnSpanFull()
                    ->hidden(fn($get) => !$get('is_promo')),


                Forms\Components\Select::make('category_id')
                    ->required()
                    ->columnSpanFull()
                    ->relationship('category', 'name')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('images'),
                Tables\Columns\TextColumn::make('price')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('discount_price')
                    ->money('IDR')
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('discount_percentage')
                    ->suffix('%')
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_promo')
                    ->boolean()
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->searchable(),
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
            'index' => Pages\ListFood::route('/'),
            'create' => Pages\CreateFood::route('/create'),
            'edit' => Pages\EditFood::route('/{record}/edit'),
        ];
    }
}
