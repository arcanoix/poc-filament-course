<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CriterioResource\Pages;
use App\Filament\Resources\CriterioResource\RelationManagers;
use App\Models\Criterio;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CriterioResource extends Resource
{
    protected static ?string $model = Criterio::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
       // $dynamicFields = Criterio::all();

        $formSchema = [
            Section::make()->schema([
                Forms\Components\TextInput::make('label')
                    ->required()
                    ->maxLength(100),
                    Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('type')
                    ->columnSpanFull(),
                    Forms\Components\TextInput::make('options')
                    ->columnSpanFull(),
            ])
        ];

        /*

        foreach ($dynamicFields as $field) {
            switch ($field->type) {
                case 'text':
                    $formSchema[] = TextInput::make($field->name)
                        ->label($field->label)
                        ->required();
                    break;
                case 'textarea':
                    $formSchema[] = Textarea::make($field->name)
                        ->label($field->label)
                        ->required();
                    break;
                case 'select':
                    $formSchema[] = Select::make($field->name)
                        ->label($field->label)
                        ->options(json_decode($field->options, true))
                        ->required();
                    break;
                // Add more cases for other field types as needed
            }
        }
*/
        return $form->schema($formSchema);

    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->searchable(),
            Tables\Columns\TextColumn::make('type')
                ->searchable(),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable(),
            Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->sortable(),
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
            'index' => Pages\ListCriterios::route('/'),
            'create' => Pages\CreateCriterio::route('/create'),
            'edit' => Pages\EditCriterio::route('/{record}/edit'),
        ];
    }
}
