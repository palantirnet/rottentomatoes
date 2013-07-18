<?php

/**
 * @file
 * Contains \PalantirNet\RottenTomatoes\MovieNotFoundException.
 */

namespace PalantirNet\RottenTomatoes;

/**
 * Thrown when attempts to connect to Rotten Tomatoes fail.
 *
 * This exception will usually wrap a Guzzle exception.
 */
class ConnectionException extends \RuntimeException { }