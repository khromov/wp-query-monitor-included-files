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

    $opcache_enabled = function_exists('opcache_get_status');
    $included_files_opcode_stats = $opcache_enabled ? opcache_get_status( true ) : null;

    $included_files_component = array();

    $this->data['total_filesize'] = 0;
    $this->data['total_opcache_filesize'] = 0;

    //Calculate stats
    $this->data['stats'] = array();
    $this->data['stats_raw'] = array();
    foreach($included_files as $included_file)
    {
      $component = QM_Util::get_file_component($included_file)->name;
      $included_files_component[] = $component;

      //Create component array if it does not exist
      if(!isset($this->data['stats'][$component]))
          $this->data['stats'][$component] = array();

      //Create size array if it does not exist
      if(!isset($this->data['stats'][$component]['size']))
          $this->data['stats'][$component]['size'] = 0;

      //Create number of files array if it does not exist
      if(!isset($this->data['stats'][$component]['number_of_files']))
          $this->data['stats'][$component]['number_of_files'] = 0;

      //Create opcode array if it does not exist
      if($opcache_enabled) {
        if(!isset($this->data['stats'][$component]['opcache_size']))
          $this->data['stats'][$component]['opcache_size'] = 0;
      }

      //Record total filesize, opcode size and number of files
      $filesize = filesize($included_file);
      $this->data['stats'][$component]['size'] += $filesize;
      $this->data['total_filesize'] += $filesize;
      $this->data['stats'][$component]['number_of_files']++;

      if($opcache_enabled) {
          $this->data['stats'][$component]['opcache_size'] += $included_files_opcode_stats['scripts'][$included_file]['memory_consumption'];
          $this->data['total_opcache_filesize'] += $included_files_opcode_stats['scripts'][$included_file]['memory_consumption'];
      }
    }

    //Order data array by number of files
    $counts = array();
    foreach ($this->data['stats'] as $component => $values) {
      $counts[$component] = $values['size'];
    }

    array_multisort($counts, SORT_DESC, $this->data['stats']);

    //Convert file sizes to KB
    foreach($this->data['stats'] as $component => $values) {
      $this->data['stats'][$component]['size_kb'] = $this->format_bytes_to_kb($this->data['stats'][$component]['size'], 2);

      if($opcache_enabled)
        $this->data['stats'][$component]['opcache_size_kb'] = $this->format_bytes_to_kb($this->data['stats'][$component]['opcache_size'], 2);
    }

    //Convert totals
    $this->data['total_filesize_kb'] =  $this->format_bytes_to_kb($this->data['total_filesize'], 2);
    $this->data['total_opcache_filesize_kb'] =  $this->format_bytes_to_kb($this->data['total_opcache_filesize'], 2);

    //Misc stats
    $this->data['included_files_component'] = $included_files_component;
    $this->data['included_files_number'] = sizeof($included_files);
    $this->data['included_files'] = $included_files;
  }

  /**
   * Adapted from:
   * http://stackoverflow.com/a/2510459
   */
  private function format_bytes_to_kb($bytes, $precision = 2) {

    $bytes = max($bytes, 0);
    $bytes /= pow(1000, 1);

    return round($bytes, $precision);
  }
}