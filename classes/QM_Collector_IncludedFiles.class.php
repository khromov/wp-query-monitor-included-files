<?php
/**
 * Data collector class
 */
class QM_Collector_IncludedFiles extends QM_Collector {

  public $id = 'included_files';

  public function name() {
    return __( 'Included files', 'query-monitor' );
  }

  public function process() {
    $included_files = get_included_files();
    $this->data['included_files_number'] = sizeof($included_files);
    $this->data['included_files'] = $included_files;
  }
}