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

use Guzzle\Http\Exception\ClientErrorResponseException;
use Guzzle\Common\Exception\GuzzleException;

/**
 * Main factory object for all requests to Rotten Tomatoes.
 */
class Connection {

  /**
   * The guzzle client that will be used for all server communication.
   *
   * @var Guzzle\RottenTomatoes\RottenTomatoesClient
   */
  protected $client;

  /**
   * Constructs a new Connection.
   *
   * @param \Guzzle\RottenTomatoes\RottenTomatoesClient $client
   *   The client connection for this factory object.
   */
  public function __construct(RottenTomatoesClient $client) {
    $this->client = $client;
  }

  /**
   * Retrieves a movie object by Rotten Tomatoes ID.
   *
   * @param int $id
   *   The Rotten Tomatoes ID.
   * @return \PalantirNet\RottenTomatoes\Movie
   */
  public function getMovieById($id) {
    $uri = Movie::createUri($id);
    $data = $this->load($uri);
    return new Movie($this, $data, $id);
  }

  /**
   * Retrieves a movie object by Rotten Tomatoes ID.
   *
   * @param string $id
   *   The IMDB ID.  Note: This must be treated as a string, not an int, because
   *   IMDB IDs sometimes have leading 0s.
   * @return \PalantirNet\RottenTomatoes\Movie
   */
  public function getMovieByImdbId($id) {
    $uri = Movie::createAlternateUri();
    $data = $this->load($uri, array(
      'id' => $id,
      'type' => 'imdb',
    ));
    // $id above is the IMDB ID, not RT ID. When creating the Movie object,
    // be sure to use the RT ID otherwise it will have an inconsistent ID.
    return new Movie($this, $data, $data['id']);
  }

  /**
   * Retrieve reviews for a movie object by Rotten Tomatoes ID.
   *
   * @param string $id
   *   The Rotten Tomatoes ID.
   * @param array $params
   *   Reviews can be filtered by type, country and have paging.
   * @return \PalantirNet\RottenTomatoes\Reviews
   */
  public function getReviewsById($id, $params = array()) {
    $uri = Reviews::createUri($id);
    $data = $this->load($uri, $params);
    return new Reviews($this, $data, $id);
  }

  /**
   * Lazy-loads the data for this object.
   *
   * @param string $uri
   *   The Rotten Tomatoes URI from which to request data.
   * @param array $params
   *   Additional parameters to add to the GET query.
   * @return array
   *   A nested array of data representing the return value from Rotten Tomatoes.
   */
  protected function load($uri, array $params = array()) {
    $request = $this->client->createRequest('GET', $uri);
    foreach ($params as $key => $value) {
      $request->getQuery()->set($key, $value);
    }
    try {
      $body = $request->send()->getBody(TRUE);
      $data = json_decode($body, TRUE);
      if (!empty($data['error'])) {
        throw new MovieNotFoundException($data['error']);
      }
      return $data;
    }
    catch (ClientErrorResponseException $e) {
      switch ($e->getResponse()->getStatusCode()) {
        case 403:
          $message = 'Error connecting to Rotten Tomatoes.  Check your API token.';
          break;
        case 404:
          $message = 'Error connecting to Rotten Tomatoes. URL not found.';
          break;
        default:
          $message = 'Error connecting to Rotten Tomatoes.';
          break;
      }
      throw new ConnectionException($message, $e->getCode(), $e);
    }
    catch (GuzzleException $e) {
      $message = 'Error connecting to Rotten Tomatoes.';
      throw new ConnectionException($message, $e->getCode(), $e);
    }
  }

  /**
   * Traverses a link relationship and returns the resulting object.
   *
   * @todo Refactor this to not be hard coded.
   *
   * @param type $name
   *   The name of the link relationship we're following.
   * @param type $uri
   *   The URI to follow.
   * @return \PalantirNet\RottenTomatoes\ObjectBase
   */
  public function followLink($name, $uri) {
    $data = $this->load($uri);

    if ($name == 'cast') {
      return new CastList($this, $data['cast'], $data['links']);
    }

  }
}
