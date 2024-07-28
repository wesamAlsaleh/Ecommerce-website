<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Faker\Core\File;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MarkdownEditor;
use App\Filament\Resources\ProductResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProductResource\RelationManagers;
use Filament\Tables\Actions\ActionGroup; // dropdown action group
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Product information')->schema([
                        TextInput::make('name')
                            ->label('Name')
                            ->placeholder('Enter the product name')
                            ->live(onBlur: true)
                            ->afterStateUpdated(
                                // this will automatically generate a slug based on the product name
                                function (string $operation, $state, Set $set) {
                                    if ($operation !== 'create') {
                                        return;
                                    }
                                    $set('slug', Str::slug($state));
                                }
                            )
                            ->required(),

                        TextInput::make('slug')
                            ->label('Slug')
                            ->placeholder('Enter the product slug')
                            ->maxlength(255)
                            ->unique(Product::class, 'slug', ignoreRecord: true)  // Ensure the slug is unique, but ignore the current record when in edit mode
                            ->required(),

                        MarkdownEditor::make('description')
                            ->columnSpanFull()
                            ->fileAttachmentsDirectory('products')
                            ->label('Description')
                            ->placeholder('Enter the product description')
                            ->required(),
                    ])->columns(2), //end of section 1

                    Section::make('Product images')->schema([
                        FileUpload::make('images')
                            ->label('Images')
                            ->placeholder('Upload product images')
                            ->directory(function ($get) {
                                $slug = $get('slug');
                                return $slug ? "products/{$slug}" : 'products'; // store the images in a folder named after the product slug (if it exists), otherwise store them in a folder named 'products' 
                            })
                            ->maxFiles(5)
                            ->reorderable()
                            ->multiple()
                            ->required(),
                    ]), //end of section 2
                ])->columnSpan(2), //end of group 1

                Group::make()->schema([
                    Section::make('Product price')->schema([
                        TextInput::make('price')
                            ->label('Price')
                            ->numeric()
                            ->prefix('BHD')
                            ->placeholder('Enter the product price')
                            ->required(),
                    ]), //end of section 1

                    Section::make('Product Association')->schema([
                        Select::make('category_id')
                            ->label('Category')
                            ->placeholder('Select the product category')
                            ->preload()
                            ->searchable()
                            // ->options(fn () => \App\Models\Category::pluck('name', 'id')) // get the categories from the database to display in the select dropdown 
                            ->required()
                            ->relationship('category', 'name'), // get the category name from the database to display in the select dropdown (using the relationship method we created in the Product model)

                        Select::make('brand_id')
                            ->label('Brand')
                            ->placeholder('Select the product brand')
                            ->preload()
                            ->searchable()
                            // ->options(fn () => \App\Models\Brand::pluck('name', 'id')) // get the brands from the database to display in the select dropdown
                            ->relationship('brand', 'name') // get the brand name from the database to display in the select dropdown (using the relationship method we created in the Product model)
                            ->required(),
                    ]), //end of section 2

                    Section::make('Product Status')->schema([
                        Toggle::make('in_stock')
                            ->label('In stock')
                            ->required()
                            ->default(false),

                        Toggle::make('is_active')
                            ->label('Active')
                            ->required()
                            ->default(false),

                        Toggle::make('is_featured')
                            ->label('Featured')
                            ->required()
                            ->default(false),

                        Toggle::make('on_sale')
                            ->label('On sale')
                            ->required()
                            ->default(false),
                    ]), //end of section 3
                ])->columnSpan(1), //end of group 2
            ])->columns(3); //end of form (3 columns, 2 in the first group, 1 in the second group) 
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),

                TextColumn::make('category.name') // get the category name from the database to display in the table (power of Product relationships)
                    ->label('Category')
                    ->searchable(),

                TextColumn::make('brand.name') // get the brand name from the database to display in the table (power of Product relationships)
                    ->label('Brand')
                    ->searchable(),

                TextColumn::make('slug')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),

                TextColumn::make('price')
                    ->money()
                    ->sortable(),

                IconColumn::make('is_active')
                    ->boolean(),

                IconColumn::make('in_stock')
                    ->boolean(),

                IconColumn::make('is_featured')
                    ->boolean(),

                IconColumn::make('on_sale')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name'), // get the category name from the database to display in the filter dropdown (power of Product relationships)

                SelectFilter::make('brand_id')
                    ->label('Brand')
                    ->relationship('brand', 'name'), // get the brand name from the database to display in the filter dropdown (power of Product relationships)

                SelectFilter::make('is_active')
                    ->label('Active')
                    ->options([
                        1 => 'Yes',
                        0 => 'No',
                    ]),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ])
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}

/**
 * ->dehydrated() // hide the field from the form, but still send the data to the server
 */
