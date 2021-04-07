<?php /** @noinspection PhpStatementWithoutBracesInspection */

/**
 * @link https://clearbold.com
 * @copyright Copyright (c) Clearbold, LLC.
 * @license https://craftcms.github.io/license/
 */

namespace clearbold\colorpalette\fields;

use clearbold\colorpalette\Plugin;
use clearbold\colorpalette\base\Theme;
use clearbold\colorpalette\models\ThemeModel;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\base\PreviewableFieldInterface;
use craft\helpers\Html;
use craft\helpers\Json as JsonHelper;

/**
 * Color Palette field
 *
 * @author Mark Reeves <mjr@clearbold.com>
 * @since 0.0.4
 */
class ColorPalette extends Field implements PreviewableFieldInterface
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('colorpalette', 'Color Palette');
    }

    /**
     * @inheritdoc
     */
    public static function valueType(): string
    {
        return 'string|null';
    }

    /**
     */
    public $collection;

    /**
     * @inheritdoc
     */
    public function getSettingsHtml()
    {
        return Craft::$app->getView()->renderTemplate('colorpalette/_components/fieldtypes/ColorPalette/settings',
            [
                'field' => $this
            ]);
    }

    public function collections()
    {
        $collections = [];
        $tableSchema = Craft::$app->db->schema->getTableSchema('{{%colorpalette_collections}}');
        if ($tableSchema !== null) {
            $collections = (new \yii\db\Query())
                ->select(['name', 'guid'])
                ->from('{{%colorpalette_collections}}')
                ->all();
        }
        return $collections;
    }

    /**
     * @inheritdoc
     */
    public function normalizeValue($value, ElementInterface $element = null)
    {
        if ($value instanceof Theme)
        {
            return $value;
        }

        if (is_string($value))
        {
            $value = JsonHelper::decodeIfJson($value);
        }

        if ($value == '')
            return null;

        $attributes = [
            'guid' => (isset($value['guid'])) ? $value['guid'] : $value,
        ];
        $theme = new ThemeModel;
        $theme->setAttributes($attributes, false);
        $theme->ownerElement = $element;
        return $theme;
    }

    public function serializeValue($value, ElementInterface $element = null)
    {
        return parent::serializeValue($value, $element);
    }

    /**
     * @inheritdoc
     */
    public function getInputHtml($value, ElementInterface $element = null): string
    {
        $name = $this->handle;

        $id = Html::input($name);

        $forms = array();
        $forms[] = array(
            'label' => 'None',
            'value' => 'none'
        );

        $tableSchema = Craft::$app->db->schema->getTableSchema('{{%colorpalette_themes}}');
        if ($tableSchema !== null && strlen($this->collection) > 0) {
            $rows = (new \yii\db\Query())
                ->select(['themes.name', 'themes.guid'])
                ->from('{{%colorpalette_themes}} themes')
                ->innerJoin('{{%colorpalette_collections}} collections', 'themes.collectionId = collections.id')
                ->where(['collections.guid' => $this->collection])
                ->all();
            foreach($rows as $row) {
                $forms[] = array(
                    'label' => $row['name'],
                    'value' => $row['guid']
                );
            }
        }

        return '<div class="express-form-field">'.
            Craft::$app->getView()->renderTemplate('_includes/forms/select', [
                'id' => $id,
                'name' => $name,
                'value' => $value->guid ?? '',
                'options' => $forms
            ]).
            '</div>';
    }

    // Private Methods
    // =========================================================================

    /**
     * @param
     *
     * @return mixed
     */
}
