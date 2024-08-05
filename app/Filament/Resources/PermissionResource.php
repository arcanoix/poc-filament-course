<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermissionResource\Pages;
use App\Filament\Resources\PermissionResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Spatie\Permission\Models\Permission;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static ?string $navigationIcon = 'heroicon-o-finger-print';

    protected static ?string $navigationGroup = 'Administracion General';

    protected static ?string $title = 'Permisos';

    protected static ?string $modelLabel = 'Permisos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('name')
                            ->label('Nombre')
                            ->required(),
                        Select::make('guard_name')
                            ->options(['web' => 'Web', 'api' => 'API'])
                            ->default(config('filament-spatie-roles-permissions.default_guard_name'))
                            ->visible(fn () => config('filament-spatie-roles-permissions.should_show_guard', true))
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('roles', null))
                            ->label('Guardia')
                            ->placeholder('Seleccionar guardia')
                            ->required(),
                        Select::make('roles')
                            ->multiple()
                            ->label("Rol")
                            ->relationship(
                                name: 'roles',
                                titleAttribute: 'name'
                            )->placeholder('Seleccionar rol')
                            ->preload(),
                    ]),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
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
            'index' => Pages\ListPermissions::route('/'),
            'create' => Pages\CreatePermission::route('/create'),
            'edit' => Pages\EditPermission::route('/{record}/edit'),
        ];
    }
}
