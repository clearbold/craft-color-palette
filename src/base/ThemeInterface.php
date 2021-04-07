<?php
/**
 * @link https://clearbold.com
 * @copyright Copyright (c) Clearbold, LLC.
 * @license https://craftcms.github.io/license/
 */

namespace clearbold\colorpalette\base;

use Craft;
use craft\base\SavableComponentInterface;

interface ThemeInterface extends SavableComponentInterface
{
    // Static
    // =========================================================================

    public static function elementType();
    public static function hasElement(): bool;

    // Public Methods
    // =========================================================================

    public function __toString(): string;
}
