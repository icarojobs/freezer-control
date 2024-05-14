<?php

namespace App\Filament\App\Resources\ProductResource\Pages;

use App\Filament\App\Resources\ProductResource;
use App\Filament\Pages\Settings;
use App\Forms\Components\CustomPlasceHolder;
use App\Models\Product;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;

class Freezer extends Page
{
    protected static string $resource = Product::class;

    protected static string $view = 'filament.app.resources.product-resource.pages.freezer';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                CustomPlasceHolder::make('placeholder')
                    ->label('Informações gerais')
                    ->content('Personalização das informações do site institucional')->columnSpan(3),

                Grid::make(3)
                    ->schema([

                        /**
                         * SECTION 1.
                         */
                        Grid::make(9)->schema([

                            Group::make()->schema([
                                TextInput::make('header_name')
                                    ->label('Nome site principal'),
                            ])->columnSpan(3),

                            Group::make()->schema([
                                TextInput::make('footer_phone')
                                    ->label('Telefone')
                                    ->prefixIcon('heroicon-m-phone-arrow-down-left')
                                    ->suffixIconColor('success')
                                    ->mask('(99) 99999-9999'),
                            ])->columnSpan(3),

                            Group::make()->schema([
                                TextInput::make('footer_email')
                                    ->label('E-mail institucional principal')
                                    ->prefixIcon('heroicon-m-envelope')
                                    ->suffixIconColor('success'),
                            ])->columnSpan(3)

                        ])->columnSpanFull(),


                        /**
                         * SECTION 2.
                         */
                        Tabs::make('informations')->tabs([

                            Tab::make('Cabeçalho')->icon('heroicon-m-square-3-stack-3d')
                                ->schema([
                                    Section::make('')->schema([
                                        Grid::make(6)->schema([

                                            Group::make()->schema([
                                                CustomPlasceHolder::make('section_logo_url')
                                                    ->label('Imagens de cabeçalho e icone')
                                                    ->content('Somente PNG. Max size of 1024')->columnSpanFull(),


                                                Grid::make(7)->schema([
                                                    Group::make()->schema([
                                                        FileUpload::make('header_logo_url')
                                                            ->disableLabel(true)
                                                            ->disk('public')
                                                            ->directory('setting_images')
                                                            ->image()
                                                            ->openable()
                                                            ->acceptedFileTypes(['image/png', 'image/jpeg'])
                                                            ->loadingIndicatorPosition('left')
                                                            ->removeUploadedFileButtonPosition('center')
                                                            ->uploadButtonPosition('left')
                                                            ->uploadProgressIndicatorPosition('left') ,


                                                    ])->columnSpan(5),


                                                    Group::make()->schema([
                                                        FileUpload::make('head_icon')
                                                            ->disableLabel(true)
                                                            ->disk('public')
                                                            ->directory('setting_images')
                                                            ->image()
                                                            ->avatar()
                                                            ->openable()
                                                            ->acceptedFileTypes(['image/png', 'image/jpeg'])
                                                            ->loadingIndicatorPosition('left')
                                                            ->removeUploadedFileButtonPosition('center')
                                                            ->uploadButtonPosition('left')
                                                            ->uploadProgressIndicatorPosition('left')->alignBetween(),
                                                    ])->columnSpan(2)

                                                ])->columnSpanFull(),



                                            ])->columnSpan(3),

                                            Group::make()->schema([
                                                TextInput::make('header_title')
                                                    ->label('Titulo cabeçalho')
                                                    ->prefix('Titulo')
                                                    ->minLength(30)
                                                    ->maxLength(50),

                                                Textarea::make('header_description')
                                                    ->label('Texto descritivo')
                                                    ->rows(4)
                                                    ->minLength(80)
                                                    ->maxLength(240)
                                                    ->columnSpanFull(),
                                            ])->columnSpan(3),


                                        ])->columnSpanFull(),
                                    ]),


                                ]),


                            Tab::make($this->settings->section_title)->icon('heroicon-m-square-3-stack-3d')
                                ->schema([
                                    Section::make('')->schema([
                                        Grid::make(6)->schema([

                                            Group::make()->schema([
                                                CustomPlasceHolder::make('section_logo_url')
                                                    ->label('')
                                                    ->content('Somente PNG. Max size of 1024')->columnSpan(2),

                                                FileUpload::make('section_logo_url')
                                                    ->disableLabel(true)
                                                    ->disk('public')
                                                    ->directory('setting_images')
                                                    ->acceptedFileTypes(['image/png']),

                                            ])->columnSpan(2),

                                            Group::make()->schema([
                                                Grid::make(7)->schema([
                                                    Group::make()->schema([
                                                        TextInput::make('section_title')
                                                            ->label('Titulo da sessão'),

                                                        Textarea::make('section_subtitle')
                                                            ->minLength(20)
                                                            ->maxLength(50)
                                                            ->rows(2)
                                                            ->label('SubTitulo sessão'),

                                                    ])->columnSpan(3),


                                                    Group::make()->schema([
                                                        Textarea::make('section_description')
                                                            ->label('Descrição de sessão resumida')
                                                            ->rows(11)
                                                            ->minLength(160)
                                                            ->autosize()
                                                            ->maxLength(400)
                                                            ->columnSpanFull(),
                                                    ])->columnSpan(4)
                                                ])->columnSpanFull(),
                                            ])->columnSpan(4),


                                        ])->columnSpanFull(),
                                    ]),


                                ]),

                            Tab::make('Sobre ' . $this->settings->header_name)->icon('heroicon-m-home-modern')
                                ->schema([
                                    Textarea::make('section_about')
                                        ->label('Conteúdo sobre a empresa')
                                        ->rows(6)
                                        ->cols(20)
                                        ->columnSpanFull(),
                                ]),

                        ])->columnSpanFull()->activeTab(1)->persistTabInQueryString('settings-tab'),
                    ]),
            ])->statePath('data')->model(Settings::class)->columns([
                'default' => 2,
                'sm' => 1,
                'md' => 2,
                'lg' => 2,
                'xl' => 2,
                '2xl' => 2
            ]);
    }
}
