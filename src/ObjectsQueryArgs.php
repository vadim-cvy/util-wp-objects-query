<?php
namespace Cvy\WP\ObjectsQuery;

class ObjectsQueryArgs
{
  static public function merge( array $query_args_a, array $query_args_b, array $merge_strategy = [] ) : array
  {
    // todo: handle order by

    $meta_query = static::merge_meta_query(
      static::normalize_meta_query( $query_args_a )['meta_query'],
      static::normalize_meta_query( $query_args_b )['meta_query'],
      $merge_strategy['meta_query_default_relation'] ?? 'AND',
    );

    $merged_args = array_merge_recursive( $query_args_a, $query_args_b );

    $merged_args['meta_query'] = $meta_query;

    return $merged_args;
  }

  static protected function normalize_meta_query( array $query_args ) : array
  {
    // todo: handle meta query like [ 'meta_key' => ..., 'meta_value' => ... ] (note: it can be used in order by, etc)

    $query_args['meta_query'] = $query_args['meta_query'] ?? [];

    return $query_args;
  }

  static protected function merge_meta_query( array $a, array $b, string $default_merge_relation ) : array
  {
    if ( empty( $a ) && empty( $b ) )
    {
      return [];
    }

    if ( empty( $a ) )
    {
      return $b;
    }

    if ( empty( $b ) )
    {
      return $a;
    }

    $a_rel = $a['relation'] ?? 'AND';
    $b_rel = $b['relation'] ?? 'AND';

    if ( $a_rel === $b_rel )
    {
      return array_merge( $a, $b );
    }

    return [
      'relation' => $default_merge_relation,
      $a,
      $b,
    ];
  }
}
