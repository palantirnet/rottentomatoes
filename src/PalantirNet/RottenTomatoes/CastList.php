<?php

namespace PalantirNet\RottenTomatoes;

/**
 * Data object for a Rotten Tomatoes cast list, or similar collection of cast members.
 */
class CastList extends ObjectBase implements \IteratorAggregate, \Countable {

  /**
   * An array of cast objects in this list.
   *
   * @var array
   */
  protected $cast;

  public function __construct(Connection $connection, array $cast, array $links = array()) {
    $data = array(
      'cast' => $cast,
      'links' => $links,
    );
    parent::__construct($connection, $data);

    $this->cast = $this->createCast($this->data['cast']);
  }

  /**
   * Converts an array of cast data into an array of cast member objects.
   *
   * This will generally be called from the constructor.
   *
   * @param array $cast
   *   The cast data to parse.
   */
  protected function createCast(array $cast) {
    $cast_list = array();
    foreach ($cast as $person) {
      $cast_list[] = new CastMember($this->connection, $person);
    }
    return $cast_list;
  }

  /**
   * Returns an iterator of cast objects.
   */
  public function getIterator() {
    return new \ArrayIterator($this->cast);
  }

  /**
   * Returns a count of the number of cast members in this list.
   *
   * @return int
   */
  public function count() {
    return count($this->cast);
  }

}
