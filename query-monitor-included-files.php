<?php
/**
 * Plugin Name: Query Monitor: Included files
 * Description: Shows number of included files in Query Monitor
 * Version: 1.3
 * Author: khromov
 * GitHub Plugin URI: khromov/wp-query-monitor-included-files
 */

add_action('plugins_loaded', function() {
  /**
   * Register collector, only if Query Monitor is enabled.
   */
  if(class_exists('QM_Collectors')) {
    include 'classes/QM_Collector_IncludedFiles.class.php';

    QM_Collectors::add( new QM_Collector_IncludedFiles() );
  }

  /**
   * Register output. The filter won't run if Query Monitor is not
   * installed so we don't have to explicity check for it.
   */
  add_filter( 'qm/outputter/html', function(array $output, QM_Collectors $collectors) {
    include 'classes/QM_Output_IncludedFiles.class.php';
    if ( $collector = QM_Collectors::get( 'included_files' ) ) {
      $output['included_files'] = new QM_Output_IncludedFiles( $collector );
    }
    return $output;
  }, 101, 2 );
});
