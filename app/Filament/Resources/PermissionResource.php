<?php

namespace App\Filament\Resources;

use Althinect\FilamentSpatieRolesPermissions\Resources\PermissionResource\Pages\ViewPermission;
use Althinect\FilamentSpatieRolesPermissions\Resources\PermissionResource\RelationManager\RoleRelationManager;
use App\Filament\Resources\PermissionResource\Pages;
use App\Filament\Resources\PermissionResource\Pages\CreatePermission;
use App\Filament\Resources\PermissionResource\Pages\EditPermission;
use App\Filament\Resources\PermissionResource\Pages\ListPermissions;
use App\Filament\Resources\PermissionResource\RelationManagers;
use Spatie\Permission\Models\Permission;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Collection;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Spatie\Permission\Models\Permission as ModelsPermission;
use Spatie\Permission\Models\Role;

class PermissionResource extends Resource
{

    protected static ?string $model = ModelsPermission::class;

    protected static ?string $modelLabel = 'Permisos';

    protected static ?string $navigationGroup = 'Administracion General';

    protected static ?string $label = 'Permiso';

    protected static ?string $pluralLabel = 'Permisos';

    protected static ?string $icon = 'heroicon-o-collection';

    protected static ?string $navigationIcon = 'heroicon-o-lock-closed';





    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label(__('filament-spatie-roles-permissions::filament-spatie.field.name'))
                                ->required(),
                            Select::make('guard_name')
                                ->label(__('filament-spatie-roles-permissions::filament-spatie.field.guard_name'))
                                ->options(config('filament-spatie-roles-permissions.guard_names'))
                                ->default(config('filament-spatie-roles-permissions.default_guard_name'))
                                ->visible(fn () => config('filament-spatie-roles-permissions.should_show_guard', true))
                                ->live()
                                ->afterStateUpdated(fn (Set $set) => $set('roles', null))
                                ->required(),
                            Select::make('roles')
                                ->multiple()
                                ->label(__('filament-spatie-roles-permissions::filament-spatie.field.roles'))
                                ->relationship(
                                    name: 'roles',
                                    titleAttribute: 'name',
                                    modifyQueryUsing: function(Builder $query, Get $get) {
                                        if (!empty($get('guard_name'))) {
                                            $query->where('guard_name', $get('guard_name'));
                                        }

                                        return $query;
                                    }
                                )
                                ->preload(config('filament-spatie-roles-permissions.preload_roles', true)),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                TextColumn::make('name')
                    ->label(__('filament-spatie-roles-permissions::filament-spatie.field.name'))
                    ->searchable(),
                TextColumn::make('guard_name')
                    ->toggleable(isToggledHiddenByDefault: config('filament-spatie-roles-permissions.toggleable_guard_names.permissions.isToggledHiddenByDefault', true))
                    ->label(__('filament-spatie-roles-permissions::filament-spatie.field.guard_name'))
                    ->searchable()
                    ->visible(fn () => config('filament-spatie-roles-permissions.should_show_guard', true)),
            ])
            ->filters([
                SelectFilter::make('models')
                    ->label('Models')
                    ->multiple()
                    ->options(function () {
                        $commands = new \Althinect\FilamentSpatieRolesPermissions\Commands\Permission();

                        /** @var \ReflectionClass[] */
                        $models = $commands->getAllModels();

                        $options = [];

                        foreach ($models as $model) {
                            $options[$model->getShortName()] = $model->getShortName();
                        }

                        return $options;
                    })
                    ->query(function (Builder $query, array $data) {
                        if (isset($data['values'])) {
                            $query->where(function (Builder $query) use ($data) {
                                foreach ($data['values'] as $key => $value) {
                                    if ($value) {
                                        $query->orWhere('name', 'like', eval(config('filament-spatie-roles-permissions.model_filter_key')));
                                    }
                                }
                            });
                        }

                        return $query;
                    }),
                SelectFilter::make('guard_name')
                    ->label(__('filament-spatie-roles-permissions::filament-spatie.field.guard_name'))
                    ->multiple()
                    ->options(config('filament-spatie-roles-permissions.guard_names')),
            ])->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
                BulkAction::make('Attach to roles')
                    ->action(function (Collection $records, array $data): void {
                        Role::whereIn('id', $data['roles'])->each(function (Role $role) use ($records): void {
                            $records->each(fn (Permission $permission) => $role->givePermissionTo($permission));
                        });
                    })
                    ->form([
                        Select::make('roles')
                            ->multiple()
                            ->label(__('filament-spatie-roles-permissions::filament-spatie.field.role'))
                            ->options(Role::query()->pluck('name', 'id'))
                            ->required(),
                    ])->deselectRecordsAfterCompletion(),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RoleRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPermissions::route('/'),
            'create' => CreatePermission::route('/create'),
            'edit' => EditPermission::route('/{record}/edit'),
            'view' => ViewPermission::route('/{record}'),
        ];
    }
}
