<?php
/**
 * @link https://clearbold.com
 * @copyright Copyright (c) Clearbold, LLC.
 * @license https://craftcms.github.io/license/
 */

namespace clearbold\colorpalette;

use Craft;
use craft\base\Element;
use craft\base\Plugin as BasePlugin;

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
        return Craft::t('unisoncrm', $message, $params, $language);
    }

    /**
     * @inheritDoc
     */
    public $schemaVersion = '0.0.1';

    /**
     * @inheritdoc
     */
    public $hasCpSettings = true;

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

        $this->_setPluginComponents();
        $this->_registerCpRoutes();
        $this->_registerVariables();

        // Craft::info(
        //     Craft::t(
        //         'colorpalette',
        //         '{name} plugin loaded',
        //         ['name' => $this->name]
        //     ),
        //     __METHOD__
        // );
    }

    /**
     * @inheritdoc
     */
    public function getCpNavItem(): array
    {
        $ret = parent::getCpNavItem();

        $ret['label'] = Craft::t('colorpalette', 'Color Palette');

        $ret['subnav'] = [
            'palettes' => ['label' => 'Palettes', 'url' => 'color-palette/index']
        ];

        return $ret;
    }

    /**
     * Register CRM's project config event listeners
     */
    //private function _registerProjectConfigEventListeners()
    //{
    //    $projectConfigService = Craft::$app->getProjectConfig();
    //
    //    Event::on(ProjectConfig::class, ProjectConfig::EVENT_REBUILD, function(RebuildConfigEvent $event) {
    //        $event->config['colorpalette'] = ProjectConfigData::rebuildProjectConfig();
    //    });
    //}

    /**
     * Register UnisonCRM’s template variable.
     */
    //private function _registerVariables()
    //{
    //    Event::on(CraftVariable::class, CraftVariable::EVENT_INIT, function(Event $event) {
    //        /** @var CraftVariable $variable */
    //        // $variable = $event->sender;
    //        // $variable->attachBehavior('colorpalette', CraftVariableBehavior::class);
    //    });
    //}
}
