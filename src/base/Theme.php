<?php
/**
 * @link https://clearbold.com
 * @copyright Copyright (c) Clearbold, LLC.
 * @license https://craftcms.github.io/license/
 */

namespace clearbold\colorpalette\base;

use clearbold\colorpalette\models\ColorModel;

use Craft;
use craft\base\ElementInterface;
use craft\base\SavableComponent;
use craft\db\Query;

abstract class Theme extends SavableComponent implements ThemeInterface
{
    // Static
    // =========================================================================

    public static function elementType()
    {
        return null;
    }

    public static function hasElement(): bool
    {
        return (static::elementType() ?? false) ? true : false;
    }

    // Public
    // =========================================================================

    public $guid;
    public $name;
    public $handle;

    // Private
    // =========================================================================

    // Need to pass the element that owns this field to ensure multisite stuff works ok!
    /* @var ElementInterface|null */
    private $_ownerElement;

    // Public Methods
    // =========================================================================

    public function __toString(): string
    {
        $name = $this->themeRecord();
        return $name ? $name['handle'] : '';
    }

    public function collection()
    {
        return $this->collectionRecord();
    }

    public function theme()
    {
        return $this->themeRecord();
    }

    public function colors()
    {
        $colors = [];
        foreach ($this->colorRecords() as $color) {
            $colorModel = new ColorModel;
            $colorModel->setAttributes($color, false);
            $colors[] = $colorModel;
        }
        return $colors;
    }

    public function all()
    {
        return $this->colors();
    }

    public function color($handle)
    {
        $color = $this->colorRecord($handle);
        $colorModel = new ColorModel;
        $colorModel->setAttributes($color, false);
        return $colorModel;
    }

    public function handle($handle)
    {
        return $this->color($handle);
    }

    public function one()
    {
        $color = $this->colorRecord();
        $colorModel = new ColorModel;
        $colorModel->setAttributes($color, false);
        return $colorModel;
    }

    public function setOwnerElement(ElementInterface $ownerElement = null)
    {
        $this->_ownerElement = $ownerElement;
    }

    public function getOwnerElement()
    {
        return $this->_ownerElement;
    }

    public function extraFields()
    {
        $names = parent::extraFields();
        $names[] = 'owner';
        return $names;
    }

    // Protected Methods
    // =========================================================================

    protected function colorRecord($handle = '')
    {
        $tableSchema = Craft::$app->db->schema->getTableSchema('{{%colorpalette_colors}}');
        if ($tableSchema !== null) {
            $query = new Query();
            $result = $query->select(['guid', 'name', 'handle', 'color', 'alpha'])
                ->from('{{%colorpalette_colors}}');
            if (strlen($handle) > 0)
                $result->where(['handle' => $handle]);
            return $result->one();
        }
        return false;
    }

    protected function colorRecords()
    {
        $tableSchema = Craft::$app->db->schema->getTableSchema('{{%colorpalette_colors}}');
        if ($tableSchema !== null && strlen($this->guid) > 0) {
            $query = new Query();
            return $query->select(['colors.guid', 'colors.name', 'colors.handle', 'color', 'alpha'])
                ->from('{{%colorpalette_colors}} colors')
                ->innerJoin('{{%colorpalette_themes}} themes', 'themes.id = colors.themeId')
                ->where(['themes.guid' => $this->guid])
                ->all();
        }
        return false;
    }

    protected function themeRecord()
    {
        $tableSchema = Craft::$app->db->schema->getTableSchema('{{%colorpalette_themes}}');
        if ($tableSchema !== null && strlen($this->guid) > 0) {
            $query = new Query();
            return $query->select(['guid', 'name', 'handle'])
                ->from('{{%colorpalette_themes}}')
                ->where(['guid' => $this->guid])
                ->one();
        }
        return false;
    }

    protected function collectionRecord()
    {
        $tableSchema = Craft::$app->db->schema->getTableSchema('{{%colorpalette_collections}}');
        if ($tableSchema !== null && strlen($this->guid) > 0) {
            $query = new Query();
            return $query->select(['collections.guid', 'collections.name', 'collections.handle'])
                ->from('{{%colorpalette_collections}} collections')
                ->innerJoin('{{%colorpalette_themes}} themes', 'collections.id = themes.collectionId')
                ->where(['themes.guid' => $this->guid])
                ->one();
        }
        return false;
    }
}
