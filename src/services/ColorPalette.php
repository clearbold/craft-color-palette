<?php /** @noinspection PhpStatementWithoutBracesInspection */

/**
 * @link https://clearbold.com
 * @copyright Copyright (c) Clearbold, LLC.
 * @license https://craftcms.github.io/license/
 */

namespace clearbold\colorpalette\services;

use Craft;
use yii\base\Component;

/**
 * Platform(s) service
 *
 * @property
 * @author Mark Reeves, Clearbold, LLC <hello@clearbold.com>
 * @since 0.0.4
 */
class ColorPalette extends Component
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    public function emptyTables() {
        Craft::$app->db->createCommand()->delete('{{%colorpalette_colors}}')->execute();
        Craft::$app->db->createCommand()->delete('{{%colorpalette_themes}}')->execute();
        Craft::$app->db->createCommand()->delete('{{%colorpalette_collections}}')->execute();
    }

    public function insertCollection($collection = null): int {
        Craft::$app->db->createCommand()
            ->upsert('{{%colorpalette_collections}}', [
                'guid' => $collection->id,
                'name' => $collection->name,
                'handle' => $collection->handle
            ])
            ->execute();
        return Craft::$app->db->getLastInsertID();
    }

    public function insertTheme($collectionId = 0, $theme = null): int {
        Craft::$app->db->createCommand()
            ->upsert('{{%colorpalette_themes}}', [
                'collectionId' => $collectionId,
                'guid' => $theme->id,
                'name' => $theme->name,
                'handle' => $theme->handle
            ])
            ->execute();
        return Craft::$app->db->getLastInsertID();
    }

    public function insertColor($themeId = 0, $color = null) {
        Craft::$app->db->createCommand()
            ->upsert('{{%colorpalette_colors}}', [
                'themeId' => $themeId,
                'guid' => $color->id,
                'name' => $color->name,
                'handle' => $color->handle,
                'color' => $color->color,
                'alpha' => $color->alpha
            ])
            ->execute();
    }
}