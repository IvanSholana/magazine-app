<?php
namespace App\Filament\Resources;

// Mengimpor kelas yang dibutuhkan dari namespace lainnya.
use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

// Mendefinisikan kelas CategoryResource yang merupakan turunan dari Filament\Resources\Resource.
class CategoryResource extends Resource
{
    // Menentukan model yang terkait dengan resource ini.
    protected static ?string $model = Category::class;

    // Menentukan ikon navigasi untuk resource ini.
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // Mendefinisikan schema form untuk resource ini.
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Bagian ini digunakan untuk mendefinisikan elemen-elemen form.
                Forms\Components\TextInput::make('name')
                ->required()
                // ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', Str::slug($state)))
                // ->live(debounce: 250)
                ->maxLength(255),

                // Forms\Components\TextInput::make('slug')
                // ->required()
                // ->disabled(),

                Forms\Components\FileUpload::make('icon')
                -> required()
                ->image(),
            ]);
    }

    // Mendefinisikan tabel untuk resource ini.
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Bagian ini digunakan untuk mendefinisikan kolom-kolom tabel.
                TextColumn::make('name')->searchable(),
                TextColumn::make('slug'),
                ImageColumn::make('icon')
            ])
            ->filters([
                // Bagian ini digunakan untuk mendefinisikan filter-filter tabel.

            ])
            ->actions([
                // Mendefinisikan aksi yang dapat dilakukan pada setiap baris tabel, seperti mengedit.
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Mendefinisikan aksi yang dapat dilakukan secara massal pada tabel, seperti menghapus.
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    // Mendefinisikan relasi yang dimiliki oleh resource ini.
    public static function getRelations(): array
    {
        return [
            // Bagian ini digunakan untuk mendefinisikan relasi.
        ];
    }

    // Mendefinisikan halaman-halaman yang tersedia untuk resource ini.
    public static function getPages(): array
    {
        return [
            // Menentukan route untuk halaman daftar kategori.
            'index' => Pages\ListCategories::route('/'),
            // Menentukan route untuk halaman pembuatan kategori baru.
            'create' => Pages\CreateCategory::route('/create'),
            // Menentukan route untuk halaman pengeditan kategori.
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
