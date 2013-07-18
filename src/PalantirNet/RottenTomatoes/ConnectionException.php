<?php

/*
 * This file is part of the Rotten Tomatoes package.
 *
 * (c) Palantir.net http://www.palantir.net/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PalantirNet\RottenTomatoes;

/**
 * Thrown when attempts to connect to Rotten Tomatoes fail.
 *
 * This exception will usually wrap a Guzzle exception.
 */
class ConnectionException extends \RuntimeException { }