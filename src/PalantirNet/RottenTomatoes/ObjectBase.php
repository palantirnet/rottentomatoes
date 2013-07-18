<?php

namespace PalantirNet\RottenTomatoes;

/**
 * Base object for all Rotten Tomatoes data objects.
 */
class ObjectBase {

  /**
   * The raw data returned from Rotten Tomatoes, as a nested array.
   *
   * @var array
   */
  protected $data;

  /**
   * The connection object for this data object.
   *
   * @var \PalantirNet\RottenTomatoes\Connection
   */
  protected $connection;

  /**
   * Constructs a new Rotten Tomatoes data object.
   *
   * @param \PalantirNet\RottenTomatoes\Connection $connection
   *   The Rotten Tomatoes connection this object came from.
   * @param array $data
   *   The data of this object.
   */
  public function __construct(Connection $connection, $data) {
    $this->connection = $connection;
    $this->data = $data;
  }

  public function getData() {
    return $this->data;
  }

  /**
   * Returns the link relationships available on this object.
   *
   * This does not return the URIs, just the link names.
   *
   * @return array
   *   An array of link names on this object.
   */
  public function getLinks() {
    if (empty($this->data['links'])) {
      return array();
    }
    return array_keys($this->data['links']);
  }

}
