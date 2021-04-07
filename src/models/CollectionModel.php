<?php
/**
 * @link https://clearbold.com
 * @copyright Copyright (c) Clearbold, LLC.
 * @license https://craftcms.github.io/license/
 */

namespace clearbold\colorpalette\models;

use Craft;

use clearbold\colorpalette\Plugin;
use craft\db\Query;
use craft\base\Model;

class CollectionModel extends Model
{
    // Public
    // =========================================================================
    public $guid;
    public $name;
    public $handle;

    public function init()
    {
        parent::init();
    }

    public function __toString(): string
    {
        return $this->handle;
    }

    public function themes()
    {
        $themes = [];
        foreach ($this->themeRecords() as $theme) {
            $themeModel = new ThemeModel;
            $themeModel->setAttributes($theme, false);
            $themes[] = $themeModel;
        }
        return $themes;
    }

    public function all()
    {
        return $this->themes();
    }

    public function theme($handle ='')
    {
        $theme = $this->themeRecord($handle);
        $themeModel = new ThemeModel;
        $themeModel->setAttributes($theme, false);
        return $themeModel;
    }

    public function handle($handle ='')
    {
        return $this->theme($handle);
    }

    // Private
    // =========================================================================

    // Static
    // =========================================================================

    // Public Methods
    // =========================================================================

    // Protected Methods
    // =========================================================================
    protected function themeRecords()
    {
        $tableSchema = Craft::$app->db->schema->getTableSchema('{{%colorpalette_themes}}');
        if ($tableSchema !== null && strlen($this->guid) > 0) {
            $query = new Query();
            return $query->select(['themes.guid', 'themes.name', 'themes.handle'])
                ->from('{{%colorpalette_themes}} themes')
                ->innerJoin('{{%colorpalette_collections}} collections', 'collections.id = themes.collectionId')
                ->where(['collections.guid' => $this->guid])
                ->all();
        }
        return false;
    }

    protected function themeRecord($handle = '')
    {
        $tableSchema = Craft::$app->db->schema->getTableSchema('{{%colorpalette_themes}}');
        if ($tableSchema !== null) {
            $query = new Query();
            $result = $query->select(['themes.guid', 'themes.name', 'themes.handle'])
                ->from('{{%colorpalette_themes}} themes')
                ->innerJoin('{{%colorpalette_collections}} collections', 'collections.id = themes.collectionId')
                ->where(['collections.guid' => $this->guid]);
            if (strlen($handle) > 0)
                $result->where(['themes.handle' => $handle]);
            return $result->one();
        }
        return false;
    }
}
