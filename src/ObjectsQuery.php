<?php
namespace Cvy\WP\ObjectsQuery;

use \Exception;

abstract class ObjectsQuery
{
  protected $args = [];

  protected $is_executed = false;

  protected $ids = [];

  public function __construct( array $args )
  {
    $this->patch( $args );
  }

  public function patch( array $args, array $merge_strategy = [] ) : void
  {
    if ( $this->is_executed )
    {
      throw new Exception( 'This action is not permitted during/after query execution!' );
    }

    $this->args = ObjectsQueryArgs::merge( $this->args, $args, $merge_strategy );
  }

  public final function get_results() : array
  {
    if ( ! $this->is_executed )
    {
      $this->before_execute();

      $this->ids = $this->execute();

      $this->is_executed = true;
    }

    return $this->ids;
  }

  protected function before_execute() : void
  {
    // you may override this method and apply query modifiers before execution
  }

  abstract protected function execute() : array;
}
