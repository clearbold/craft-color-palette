<?php
/**
 * @link https://clearbold.com
 * @copyright Copyright (c) Clearbold, LLC.
 * @license https://craftcms.github.io/license/
 */

namespace clearbold\colorpalette\models;

use Craft;

use clearbold\colorpalette\Plugin;
use clearbold\colorpalette\models\CollectionModel;
use craft\db\Query;
use craft\base\Model;

class ColorPaletteModel extends Model
{
    // Public
    // =========================================================================

    // Private
    // =========================================================================

    // Static
    // =========================================================================

    // Public Methods
    // =========================================================================
    public function init()
    {
        parent::init();
    }

    public function __toString(): string
    {
        return '';
    }

    public function collections()
    {
        $collections = [];
        foreach ($this->collectionRecords() as $collection) {
            $collectionModel = new CollectionModel;
            $collectionModel->setAttributes($collection, false);
            $collections[] = $collectionModel;
        }
        return $collections;
    }

    public function all()
    {
        return $this->collections();
    }

    public function collection($handle ='')
    {
        $collection = $this->collectionRecord($handle);
        $collectionModel = new CollectionModel;
        $collectionModel->setAttributes($collection, false);
        return $collectionModel;
    }

    public function handle($handle ='')
    {
        return $this->collection($handle);
    }

    // Protected Methods
    // =========================================================================
    protected function collectionRecords()
    {
        $tableSchema = Craft::$app->db->schema->getTableSchema('{{%colorpalette_collections}}');
        if ($tableSchema !== null) {
            $query = new Query();
            return $query->select(['guid', 'name', 'handle'])
                ->from('{{%colorpalette_collections}}')
                ->all();
        }
        return false;
    }

    protected function collectionRecord($handle = '')
    {
        $tableSchema = Craft::$app->db->schema->getTableSchema('{{%colorpalette_collections}}');
        if ($tableSchema !== null) {
            $query = new Query();
            $result = $query->select(['guid', 'name', 'handle'])
                ->from('{{%colorpalette_collections}}');
            if (strlen($handle) > 0)
                $result->where(['handle' => $handle]);
            return $result->one();
        }
        return false;
    }
}
