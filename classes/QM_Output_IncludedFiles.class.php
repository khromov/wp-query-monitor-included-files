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
    ?>
    <div class="qm" id="<?php echo esc_attr($this->collector->id())?>">
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
          <?php foreach($data['included_files'] as $file) : ?>
            <tr>
              <td class="qm-ltr">
                <?php echo esc_html($file); ?>
              </td>
              <td class="qm-nowrap">
                <?php echo QM_Util::get_file_component($file)->name; ?>
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