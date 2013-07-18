Rotten Tomatoes Bridge
======================

This library is a simple API wrapper around the Rotten Tomatoes API.  It is
built on the Guzzle HTTP library.

### Installing via Composer

The recommended way to install this library is through [Composer](http://getcomposer.org).

1. Add ``palantirnet/rottentomatoes`` as a dependency in your project's ``composer.json`` file:

        {
            "require": {
                "palantirnet/rottentomatoes": "1.0"
            }
        }

2. Download and install Composer:

        curl -s http://getcomposer.org/installer | php

3. Install your dependencies:

        php composer.phar install

4. Require Composer's autoloader

    Composer also prepares an autoload file that's capable of autoloading all of the classes in any of the libraries that it downloads. To use it, just add the following line to your code's bootstrap process:

        require 'vendor/autoload.php';

You can find out more on how to install Composer, configure autoloading, and other best-practices for defining dependencies at [getcomposer.org](http://getcomposer.org).


## Using the library

To connect to Rotten Tomatoes, you need an API key issued by them.  It is a simple
pseudorandom alphanumeric string. You can register for one on the [Rotten Tomatoes Developer site][http://developer.rottentomatoes.com/]

Once you have the key, the rest is easy:

```php
<?php

use Guzzle\RottenTomatoes\RottenTomatoesClient;
use PalantirNet\RottenTomatoes\Connection;

$apikey = 'the API key from Rotten Tomatoes';

// You could use an alternate URI if you wanted to use a mock server or proxy
// or something like that.
$base_uri = 'http://api.rottentomatoes.com/api/public/v1.0.json';

$connection = new Connection(new RottenTomatoesClient($base_uri, array('apikey' => $apikey)));
```

Congratulations, you now have a connection to Rotten Tomatoes!  You can look up
movies and reviews with the appropriate methods:

```php
<?php

$movie1 = $connection->getMovieById($rotten_tomatoes_id_of_a_movie);
$movie2 = $connection->getMovieByImdbId($imdb_id_of_a_movie);
```

In either case, you get back a \PalantirNet\RottenTomatoes\Movie object.

Note:
IMDB IDs often have leading zeros, so if you're providing a literal ID be careful
that it doesn't get interpreted as an octal number.  That is, don't do this:

```php
$connection->getMoviebyImdbId(01234); // Don't do this!
```

Because PHP will interpret that as "1234" in base 8 (Octal).  Instead, quote
the ID:

```php
$connection->getMoviebyImdbId('01234');
```

Movies can be used to retrieve related Reviews, as well as related cast data.
Those have their own objects.  (See the inline documentation.)

The raw data of a movie (or review) can be retrieved as an array with the getData() method.
There are also a small number of utility methods available.  (PRs for more will
be accepted!)

```php
$movie = $connection->getMoviebyImdbId('01234');
$title = $movie->title();
$data = $movie->getData();
```

