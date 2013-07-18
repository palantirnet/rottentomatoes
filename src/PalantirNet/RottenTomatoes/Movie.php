<?php

namespace PalantirNet\RottenTomatoes;

/**
 * Data object for a Rotten Tomatoes movie.
 */
class Movie extends ObjectBase {

  /**
   * The ID of this movie in Rotten Tomatoes.
   *
   * @var int
   */
  protected $id;

  /**
   * Constructs a new Rotten Tomatoes movie object.
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
    return sprintf('/api/public/v1.0/movies/%d.json', $id);
  }

  /**
   * Returns the URI from which to request a movie by Alternate ID.
   *
   * @link
   *
   * @return string
   *   The URI to request to retrieve a movie by alternate ID.
   */
  public static function createAlternateUri() {
    return '/api/public/v1.0/movie_alias.json';
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
   * Returns the title of this movie.
   */
  public function title() {
    return $this->data['title'];
  }

  /**
   * Returns the Abbridged Cast List for this movie.
   *
   * @return CastList
   */
  public function getAbbridgedCastList() {
    return new CastList($this->connection, $this->data['abridged_cast']);
  }

  /**
   * Returns the Full Cast List for this movie.
   *
   * @return CastList
   */
  public function getFullCastList() {
    return $this->connection->followLink('cast', $this->data['links']['cast']);
  }

}

