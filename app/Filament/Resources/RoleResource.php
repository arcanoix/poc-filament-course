<?php

namespace App\Filament\Resources;

use Althinect\FilamentSpatieRolesPermissions\Resources\RoleResource\Pages\CreateRole;
use Althinect\FilamentSpatieRolesPermissions\Resources\RoleResource\Pages\EditRole;
use Althinect\FilamentSpatieRolesPermissions\Resources\RoleResource\Pages\ListRoles;
use Althinect\FilamentSpatieRolesPermissions\Resources\RoleResource\Pages\ViewRole;
use Althinect\FilamentSpatieRolesPermissions\Resources\RoleResource\RelationManager\PermissionRelationManager;
use Althinect\FilamentSpatieRolesPermissions\Resources\RoleResource\RelationManager\UserRelationManager;
use App\Filament\Resources\RoleResource\Pages;
use App\Filament\Resources\RoleResource\RelationManagers;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Validation\Rules\Unique;
use Spatie\Permission\Contracts\Role;
use Spatie\Permission\Models\Role as ModelsRole;

class RoleResource extends Resource
{
    protected static ?string $model = ModelsRole::class;

    protected static ?string $modelLabel = 'Roles';

    protected static ?string $navigationGroup = 'Administracion General';

    protected static ?string $label = 'Rol';

    protected static ?string $pluralLabel = 'Roles';

    protected static ?string $icon = 'heroicon-o-collection';

    protected static ?string $navigationIcon = 'heroicon-o-lock-closed';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label(__('filament-spatie-roles-permissions::filament-spatie.field.name'))
                                    ->required()
                                    ->unique(ignoreRecord: true, modifyRuleUsing: function (Unique $rule) {
                                        // If using teams and Tenancy, ensure uniqueness against current tenant
                                        if(config('permission.teams', false) && Filament::hasTenancy()) {
                                            // Check uniqueness against current user/team
                                            $rule->where(config('permission.column_names.team_foreign_key', 'team_id'), Filament::getTenant()->id);
                                        }
                                        return $rule;
                                    }),

                                Select::make('guard_name')
                                    ->label(__('filament-spatie-roles-permissions::filament-spatie.field.guard_name'))
                                    ->options(config('filament-spatie-roles-permissions.guard_names'))
                                    ->default(config('filament-spatie-roles-permissions.default_guard_name'))
                                    ->visible(fn () => config('filament-spatie-roles-permissions.should_show_guard', true))
                                    ->required(),

                                Select::make('permissions')
                                    ->columnSpanFull()
                                    ->multiple()
                                    ->label(__('filament-spatie-roles-permissions::filament-spatie.field.permissions'))
                                    ->relationship(
                                        name: 'permissions',
                                        modifyQueryUsing: fn (Builder $query) => $query->orderBy('name'),
                                    )
                                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->name} ({$record->guard_name})")
                                    ->searchable(['name', 'guard_name']) // searchable on both name and guard_name
                                    ->preload(config('filament-spatie-roles-permissions.preload_permissions')),

                                Select::make(config('permission.column_names.team_foreign_key', 'team_id'))
                                    ->label(__('filament-spatie-roles-permissions::filament-spatie.field.team'))
                                    ->hidden(fn () => ! config('permission.teams', false) || Filament::hasTenancy())

                                    ->dehydrated(fn ($state) => (int) $state > 0)
                                    ->placeholder(__('filament-spatie-roles-permissions::filament-spatie.select-team'))
                                    ->hint(__('filament-spatie-roles-permissions::filament-spatie.select-team-hint')),
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
                TextColumn::make('permissions_count')
                    ->counts('permissions')
                    ->label(__('filament-spatie-roles-permissions::filament-spatie.field.permissions_count'))
                    ->toggleable(isToggledHiddenByDefault: config('filament-spatie-roles-permissions.toggleable_guard_names.roles.isToggledHiddenByDefault', true)),
                TextColumn::make('guard_name')
                    ->toggleable(isToggledHiddenByDefault: config('filament-spatie-roles-permissions.toggleable_guard_names.roles.isToggledHiddenByDefault', true))
                    ->label(__('filament-spatie-roles-permissions::filament-spatie.field.guard_name'))
                    ->searchable(),
            ])
            ->filters([

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PermissionRelationManager::class,
            UserRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRoles::route('/'),
            'create' => CreateRole::route('/create'),
            'edit' => EditRole::route('/{record}/edit'),
            'view' => ViewRole::route('/{record}'),
        ];
    }
}
