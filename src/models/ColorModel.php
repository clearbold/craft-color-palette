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

class ColorModel extends Model
{
    // Public
    // =========================================================================
    public $guid;
    public $name;
    public $handle;
    public $color;
    public $alpha;

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
        return $this->handle;
    }

    public function getHex()
    {
        return $this->color;
    }

    public function getRgb()
    {
        // https://stackoverflow.com/questions/15202079/convert-hex-color-to-rgb-values-in-php#15202130
        $split = str_split($this->color, 2);
        $r = hexdec($split[0]);
        $g = hexdec($split[1]);
        $b = hexdec($split[2]);

        return "rgb($r, $g, $b)";
    }

    public function getRgba()
    {
        // https://stackoverflow.com/questions/15202079/convert-hex-color-to-rgb-values-in-php#15202130
        $split = str_split($this->color, 2);
        $r = hexdec($split[0]);
        $g = hexdec($split[1]);
        $b = hexdec($split[2]);
        $alpha = $this->alpha;

        return "rgb($r, $g, $b, $alpha)";
    }

    // Protected Methods
    // =========================================================================
}
