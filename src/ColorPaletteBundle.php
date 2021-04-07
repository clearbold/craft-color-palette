<?php
/**
 * @link https://clearbold.com
 * @copyright Copyright (c) Clearbold, LLC.
 * @license https://craftcms.github.io/license/
 */

namespace clearbold\colorpalette;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

class ColorPaletteBundle extends AssetBundle
{
    public function init()
    {
        // define the path that your publishable resources live
        $this->sourcePath = '@clearbold/colorpalette/resources';

        // define the dependencies
        $this->depends = [
            CpAsset::class,
        ];

        // define the relative path to CSS/JS files that should be registered with the page
        // when this asset bundle is registered
        $this->js = [
            'js/lib/axios.js',
            'js/lib/uuidv4.min.js',
            'js/lib/vue.js',
            'js/lib/sortable.min.js',
            'js/lib/vue.draggable.min.js',
            'js/plugin.js'
        ];

        $this->css = [
            'css/styles.css',
        ];

        parent::init();
    }
}
