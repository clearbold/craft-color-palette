<?php
/**
 * @link https://clearbold.com
 * @copyright Copyright (c) Clearbold, LLC.
 * @license https://craftcms.github.io/license/
 */

namespace clearbold\colorpalette\controllers;

use craft\web\Controller;

/**
 * Class BaseCpController
 *
 * @author Mark Reeves, Clearbold, LLC <hello@clearbold.com>
 * @since 0.0.4
 */
class BaseCpController extends Controller
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->requirePermission('accessPlugin-colorpalette');

        parent::init();
    }
}
