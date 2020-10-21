<?php
/**
 * Short description here.
 *
 * PHP version 5
 *
 * @category Foo
 * @package Foo_Helpers
 * @author Syaripah Ainun <syaripahsah@gmial.com>
 * @copyright 2013-2014 Foo Inc.
 * @license MIT License
 * @link http://example.com
 */

namespace App\Events;

use Illuminate\Queue\SerializesModels;

/**
 * The Foo class.
 */

abstract class Event
{
    use SerializesModels;
}
