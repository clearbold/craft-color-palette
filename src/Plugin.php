<?php
/**
 * @link https://clearbold.com
 * @copyright Copyright (c) Clearbold, LLC.
 * @license https://craftcms.github.io/license/
 */

namespace clearbold\colorpalette;

use clearbold\colorpalette\fields\ColorPalette as ColorPaletteField;
use clearbold\colorpalette\variables\ColorPaletteVariable;
use clearbold\colorpalette\models\ColorPaletteModel;

use Craft;
use craft\base\Element;
use craft\events\RegisterComponentTypesEvent;
use craft\services\Fields;
use craft\base\Plugin as BasePlugin;
use craft\events\RegisterUserPermissionsEvent;
use craft\services\UserPermissions;

//use craft\events\RegisterTemplateRootsEvent;
//use craft\web\View;
use craft\web\twig\variables\CraftVariable;
use yii\base\Event;

/**
 *
 *
 * @author Mark Reeves, Clearbold, LLC <hello@clearbold.com>
 * @since 0.0.1
 */
class Plugin extends BasePlugin
{
    /**
     * @param $message
     * @param array $params
     * @param null $language
     * @return string
     * @see Craft::t()
     *
     * @since 0.0.1
     */
    public static function t($message, $params = [], $language = null)
    {
        return Craft::t('colorpalette', $message, $params, $language);
    }

    /**
     * @inheritDoc
     */
    public $schemaVersion = '0.0.1';

    /**
     * @inheritdoc
     */
    public $hasCpSettings = false;

    /**
     * @inheritdoc
     */
    public $hasCpSection = true;

    /**
     * @inheritdoc
     */
    public $minVersionRequired = '0.0.1';

    public static $plugin;

    // Public Methods
    // =========================================================================

    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Event::on(Fields::class, Fields::EVENT_REGISTER_FIELD_TYPES, function(RegisterComponentTypesEvent $event) {
            $event->types[] = ColorPaletteField::class;
        });

        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('colorpalette', ColorPaletteModel::class);
            }
        );

         Craft::info(
             Craft::t(
                 'colorpalette',
                 '{name} plugin loaded',
                 ['name' => $this->name]
             ),
             __METHOD__
         );
    }

    /**
     * @inheritdoc
     */
    public function getCpNavItem(): array
    {
        $ret = parent::getCpNavItem();

        $ret['label'] = Craft::t('colorpalette', 'Color Palette');

        $ret['subnav'] = [
            'configure' => ['label' => 'Configure', 'url' => 'colorpalette/index'],
            'examples' => ['label' => 'Twig Examples', 'url' => 'colorpalette/examples'],
            'css' => ['label' => 'CSS', 'url' => 'colorpalette/css']
        ];

        return $ret;
    }
}
