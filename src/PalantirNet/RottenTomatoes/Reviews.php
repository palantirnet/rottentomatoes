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
 * Data object for a Rotten Tomatoes movie.
 */
class Reviews extends ObjectBase {

  /**
   * The ID of this movie in Rotten Tomatoes.
   *
   * @var int
   */
  protected $id;

  /**
   * Count
   *
   * @var int
   *   An integer which represents the number of reviews retrieved from the API
   * call.
   */
  protected $count;

  /**
   * Constructs a new Rotten Tomatoes review object.
   *
   * @param \PalantirNet\RottenTomatoes\Connection $connection
   *   The Rotten Tomatoes connection this object came from.
   * @param array $data
   *   The data of this object.
   * @param int $id
   *   The ID of this movie in Rotten Tomatoes.
   */
  public function __construct(Connection $connection, $data, $id) {
    parent::__construct($connection, $data);
    $this->id = $id;
  }

  /**
   * Returns the URI of a Rotten Tomatoes object specified by ID.
   *
   * @param int $id
   *   The ID of the object.
   * @return string
   *   The URI to request to retrieve the specified object.
   */
  public static function createUri($id) {
    // e.g. /api/public/v1.0/movies/770672122/reviews.json?apikey=[your_api_key]
    // /api/public/v1.0/movies/{movie-id}/reviews.json?review_type={top_critic|all|dvd}&page_limit={results-per-page}&page={page-number}&country={country-code}
    return sprintf('/api/public/v1.0/movies/%d/reviews.json', $id);
  }

  /**
   * Returns the Rotten Tomatoes ID of this movie.
   *
   * @return int
   */
  public function id() {
    return $this->id;
  }

  /**
   * Return the count of reviews retrieved.
   *
   * This is useful as a test to make sure reviews were retrieved before
   * attempting further processing
   */
  public function getCount() {
    $data = $this->getData();
    return $data['total'];
  }

}

