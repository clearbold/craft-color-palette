<?php
/**
 * @link https://clearbold.com
 * @copyright Copyright (c) Clearbold, LLC.
 * @license https://craftcms.github.io/license/
 */

namespace clearbold\colorpalette\controllers;

use clearbold\colorpalette\services\ColorPalette;

use Craft;
use craft\db\Query;
use yii\web\BadRequestHttpException;
use yii\web\Response;

/**
 * Class ColorPaletteController
 *
 * @author Mark Reeves, Clearbold, LLC <hello@clearbold.com>
 * @since 0.0.4
 */
class ColorPaletteController extends BaseCpController
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    /**
     * @return Response
     */
    public function actionIndex(): Response
    {
        $collections = [];
        $query = new Query();
        $collectionsRecords = $query->select('id, guid, name, handle')
            ->from('{{%colorpalette_collections}}')
            ->all();

        foreach($collectionsRecords as $collectionRecord) {
            $themes = [];
            $query = new Query();
            $collectionThemesRecords = $query->select('id, guid, name, handle')
                ->from('{{%colorpalette_themes}}')
                ->where(['collectionId' => $collectionRecord['id']])
                ->all();
            foreach($collectionThemesRecords as $collectionThemeRecord) {
                $colors = [];
                $query = new Query();
                $themeColorsRecords = $query->select('guid, name, handle, color, alpha')
                    ->from('{{%colorpalette_colors}}')
                    ->where(['themeId' => $collectionThemeRecord['id']])
                    ->all();
                foreach($themeColorsRecords as $themeColorRecord) {
                    $color = [
                        "id" => $themeColorRecord['guid'],
                        "name" => $themeColorRecord['name'],
                        "handle" => $themeColorRecord['handle'],
                        "color" => $themeColorRecord['color'],
                        "alpha" => $themeColorRecord['alpha']
                    ];
                    $colors[] = $color;
                }
                $themes[] = [
                    "id" => $collectionThemeRecord['guid'],
                    "name" => $collectionThemeRecord['name'],
                    "handle" => $collectionThemeRecord['handle'],
                    "colors" => $colors
                ];
            }
            $collections[] = [
                "id" => $collectionRecord['guid'],
                "name" => $collectionRecord['name'],
                "handle" => $collectionRecord['handle'],
                "themes" => $themes
            ];
        }

        $colorPalette = [
            "palette" => [
                "collections" => $collections
            ]
        ];

        // Uncomment to load test data
        //$colorPalette = [
        //    "palette" => [
        //        "collections" => [
        //            [
        //                "id" => "55af1e37-0734-46d8-b070-a1e42e4fcabc",
        //                "name" => "Heroes",
        //                "handle" => "heroes",
        //                "themes" => [
        //                    [
        //                        "id" => "55af1e37-0734-46d8-b070-a1e42e4fcdef",
        //                        "name" => "Black Panther",
        //                        "handle" => "blackPanther",
        //                        "colors" => [
        //                            [
        //                                "id" => "55af1e37-0734-46d8-b070-a1e42e4fcghi",
        //                                "name" => "Background",
        //                                "handle" => "background",
        //                                "color" => "#C4A8FF",
        //                                "alpha" => "1"
        //                            ],
        //                            [
        //                                "id" => "55af1e37-0734-46d8-b070-a1e42e4fcjkl",
        //                                "name" => "Text",
        //                                "handle" => "text",
        //                                "color" => "#0C0B13",
        //                                "alpha" => "1"
        //                            ],
        //                            [
        //                                "id" => "55af1e37-0734-46d8-b070-a1e42e4fcmno",
        //                                "name" => "Button",
        //                                "handle" => "button",
        //                                "color" => "#1A0554",
        //                                "alpha" => "1"
        //                            ],
        //                            [
        //                                "id" => "55af1e37-0734-46d8-b070-a1e42e4fcpqr",
        //                                "name" => "Highlight",
        //                                "handle" => "highlight",
        //                                "color" => "#664EAE",
        //                                "alpha" => "1"
        //                            ],
        //                        ]
        //                    ],
        //                    [
        //                        "id" => "55af1e37-0734-46d8-b070-a1e42e4fcstu",
        //                        "name" => "Captain Marvel",
        //                        "handle" => "captainMarvel",
        //                        "colors" => [
        //                            [
        //                                "id" => "55af1e37-0734-46d8-b070-a1e42e4fcvwx",
        //                                "name" => "Background",
        //                                "handle" => "background",
        //                                "color" => "#CC4224",
        //                                "alpha" => "1"
        //                            ],
        //                            [
        //                                "id" => "55af1e37-0734-46d8-b070-a1e42e4fcyza",
        //                                "name" => "Text",
        //                                "handle" => "text",
        //                                "color" => "#000000",
        //                                "alpha" => "1"
        //                            ],
        //                            [
        //                                "id" => "55af1e37-0734-46d8-b070-a1e42e4fcaab",
        //                                "name" => "Button",
        //                                "handle" => "button",
        //                                "color" => "#2A75B3",
        //                                "alpha" => "1"
        //                            ],
        //                            [
        //                                "id" => "55af1e37-0734-46d8-b070-a1e42e4fcbbc",
        //                                "name" => "Highlight",
        //                                "handle" => "highlight",
        //                                "color" => "#F3D403",
        //                                "alpha" => "1"
        //                            ],
        //                        ],
        //                    ],
        //                    [
        //                        "id" => "55af1e37-0734-46d8-b070-a1e42e4fcccd",
        //                        "name" => "Iron Man",
        //                        "handle" => "ironMan",
        //                        "colors" => [
        //                            [
        //                                "id" => "55af1e37-0734-46d8-b070-a1e42e4fcdde",
        //                                "name" => "Background",
        //                                "handle" => "background",
        //                                "color" => "#D3AF37",
        //                                "alpha" => "1"
        //                            ],
        //                            [
        //                                "id" => "55af1e37-0734-46d8-b070-a1e42e4fceef",
        //                                "name" => "Text",
        //                                "handle" => "text",
        //                                "color" => "#CC0000",
        //                                "alpha" => "1"
        //                            ],
        //                            [
        //                                "id" => "55af1e37-0734-46d8-b070-a1e42e4fcffg",
        //                                "name" => "Button",
        //                                "handle" => "button",
        //                                "color" => "#E30022",
        //                                "alpha" => "1"
        //                            ],
        //                            [
        //                                "id" => "55af1e37-0734-46d8-b070-a1e42e4fcggh",
        //                                "name" => "Highlight",
        //                                "handle" => "highlight",
        //                                "color" => "#FCC200",
        //                                "alpha" => "1"
        //                            ],
        //                        ]
        //                    ],
        //                ],
        //            ],
        //            [
        //                "id" => "55af1e37-0734-46d8-b070-a1e42e4fchhi",
        //                "name" => "Villains",
        //                "handle" => "villains",
        //                "themes" => [
        //                    [
        //                        "id" => "55af1e37-0734-46d8-b070-a1e42e4fciij",
        //                        "name" => "Thanos",
        //                        "handle" => "thanos",
        //                        "colors" => [
        //                            [
        //                                "id" => "55af1e37-0734-46d8-b070-a1e42e4fcjjk",
        //                                "name" => "Background",
        //                                "handle" => "background",
        //                                "color" => "#B4A24C",
        //                                "alpha" => "1"
        //                            ],
        //                            [
        //                                "id" => "55af1e37-0734-46d8-b070-a1e42e4fckkl",
        //                                "name" => "Text",
        //                                "handle" => "text",
        //                                "color" => "#1B2B42",
        //                                "alpha" => "1"
        //                            ],
        //                            [
        //                                "id" => "55af1e37-0734-46d8-b070-a1e42e4fcllm",
        //                                "name" => "Button",
        //                                "handle" => "button",
        //                                "color" => "#A788A8",
        //                                "alpha" => "1"
        //                            ],
        //                            [
        //                                "id" => "55af1e37-0734-46d8-b070-a1e42e4fcmmn",
        //                                "name" => "Highlight",
        //                                "handle" => "highlight",
        //                                "color" => "#306493",
        //                                "alpha" => "1"
        //                            ],
        //                        ]
        //                    ]
        //                ],
        //            ]
        //        ]
        //    ]
        //];
        return $this->asJson($colorPalette);
    }

    public function actionSave(): Response {
        try {
            $this->requirePostRequest();
        } catch(BadRequestHttpException $e) {
            //$response = false;
        }

        $request = Craft::$app->getRequest();

        $data = json_decode($request->post('palette'));

        $colorPalette = new ColorPalette();

        $colorPalette->emptyTables();
        foreach ($data as $collection) {
            $collectionId = $colorPalette->insertCollection($collection);
            foreach ($collection->themes as $theme) {
                $themeId = $colorPalette->insertTheme($collectionId, $theme);
                foreach ($theme->colors as $color) {
                    $colorPalette->insertColor($themeId, $color);
                }
            }
        }
        Craft::$app->getSession()->setNotice('Color palette saved.');
        return $this->redirectToPostedUrl();
    }
}
