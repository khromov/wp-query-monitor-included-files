<?php
/**
 * Output class
 *
 * Class QM_Output_IncludedFiles
 */
class QM_Output_IncludedFiles extends QM_Output_Html {

  public function __construct( QM_Collector $collector ) {
    parent::__construct( $collector );
    add_filter( 'qm/output/menus', array( $this, 'admin_menu' ), 101 );
    add_filter( 'qm/output/title', array( $this, 'admin_title' ), 101 );
    add_filter( 'qm/output/menu_class', array( $this, 'admin_class' ) );
  }

  /**
   * Outputs data in the footer
   */
  public function output() {
    $data = $this->collector->get_data();
    $opcache_enabled = function_exists('opcache_get_status');
    ?>
    <!-- Print total stats for included files -->
    <div class="qm" id="<?php echo esc_attr($this->collector->id())?>">
      <table cellspacing="0">
        <thead>
        <tr>
          <th scope="col">
            <?php echo __('Total number of included files','query-monitor'); ?>
          </th>
          <th scope="col">
            <?php echo __('Total file size','query-monitor'); ?>
          </th>
          <th scope="col">
            <?php echo __('Total OPCache memory consumption','query-monitor'); ?>
          </th>
        </tr>
        </thead>
        <tbody>
        <tr>
          <td class="qm-ltr">
            <?php echo $data['included_files_number']; ?>
          </td>
          <td class="qm-ltr">
            <?php echo $data['total_filesize_kb']; ?> KB
          </td>
          <td class="qm-nowrap">
            <?php
              if($opcache_enabled)
                echo $data['total_opcache_filesize_kb'] . " KB";
              else
                echo "N/A";
            ?>
          </td>
        </tr>
        </tbody>
      </table>
    </div>

    <!-- Print stats for included files -->
    <div class="qm" id="<?php echo esc_attr($this->collector->id())?>-aggregated">
      <table cellspacing="0">
        <thead>
        <tr>
          <th scope="col">
            <?php echo __('Included files by component','query-monitor'); ?>
          </th>
          <th scope="col">
            <?php echo __('Number of included files','query-monitor'); ?>
          </th>
          <th scope="col">
            <?php echo __('Total file size','query-monitor'); ?>
          </th>
          <th scope="col">
            <?php echo __('Zend OPCache memory consumption','query-monitor'); ?>
          </th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($data['stats'] as $component => $component_stats) : ?>
          <tr>
            <td class="qm-ltr">
              <?php echo esc_html($component); ?>
            </td>
            <td class="qm-ltr">
              <?php echo esc_html($component_stats['number_of_files']); ?>
            </td>
            <td class="qm-nowrap">
              <?php echo $component_stats['size_kb'] ?> KB
            </td>
            <td class="qm-nowrap">
              <?php
              if($opcache_enabled)
                echo $component_stats['opcache_size_kb'] . " KB";
              else
                echo "N/A";
              ?>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>



    <!-- Print detailed included files info -->
    <div class="qm" id="<?php echo esc_attr($this->collector->id())?>-full">
      <table cellspacing="0">
        <thead>
          <tr>
            <th scope="col">
              <?php echo __('Included files','query-monitor'); ?>
            </th>
            <th scope="col">
              <?php echo __('Component','query-monitor'); ?>
            </th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($data['included_files'] as $key => $file) : ?>
            <tr>
              <td class="qm-ltr">
                <?php echo esc_html($file); ?>
              </td>
              <td class="qm-nowrap">
                <?php echo $data['included_files_component'][$key]; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php
  }

  /**
   * Adds data to top admin bar
   *
   * @param array $title
   *
   * @return array
   */
  public function admin_title( array $title ) {
    $data = $this->collector->get_data();

    $title[] = sprintf(
      _x( '%s<small>F</small>', 'number of included files', 'query-monitor' ),
      $data['included_files_number']
    );

    return $title;
  }

  /**
   * @param array $class
   *
   * @return array
   */
  public function admin_class( array $class ) {
    $class[] = 'qm-included_files';
    return $class;
  }

  public function admin_menu( array $menu ) {

    $data = $this->collector->get_data();

    $menu[] = $this->menu( array(
      'id'    => 'qm-included_files',
      'href'  => '#qm-included_files',
      'title' => sprintf( __( 'Included files (%s)', 'query-monitor' ), $data['included_files_number'] )
    ));

    return $menu;
  }
}