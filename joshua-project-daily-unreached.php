<?php
/*
Plugin Name: The Joshua Project, Daily Unreached People Widget
Description: Creates a widget showing the Daily Unreached People Group from the Joshua Project
Author: Topher
Author URI: http://topher1kenobe.com
Version: 1.0
License: GPL
*/

/**
 * Adds Joshua_Project_Daily_Unreached_Widget widget.
 */
class Joshua_Project_Daily_Unreached_Widget extends WP_Widget {

	/**
	 * Make some vars
	 */
	private $domain      = NULL;
	private $api_key     = NULL;
	private $jp_data_url = NULL;
	private $jp_data     = NULL;
	private $old_vals    = array();


	/**
	 * Register widget with WordPress.
	 */
	function __construct() {

		$this->domain      = 'http://api.joshuaproject.net';
		$this->api_key     = '540ca9d0286e';
		$this->jp_data_url = $this->domain . '/v1/people_groups/daily_unreached.json?api_key=' . $this->api_key;

		$this->data_fetcher();

		parent::__construct(
			'joshua-project-daily-unreached-widget', // Base ID
			__( 'Joshua Project Daily Unreached', 'joshua-project-daily-unreached-widget' ), // Name
			array( 'description' => __( 'Renders the Daily Unreached People Group from The Joshua Project.', 'joshua-project-daily-unreached-widget' ), )
		);

		add_action( 'wp_head', array( $this, 'widget_css' ) );

	}

	/**
	 * Data fetcher
	 */
	private function data_fetcher() {

		$transient_name = 'jp_daily_unreached_people';

		$remote_data = get_transient( $transient_name );

		if ( $remote_data == '' ) {

			$get_data = wp_remote_get( esc_url( $this->jp_data_url ) );

			set_transient( $transient_name, $remote_data, 60 * 60 * 12 );

			$remote_data = $get_data['body'];

		}

		$remote_data_array = json_decode( $remote_data );

		$this->jp_data = $remote_data_array[0];



	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args	  Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {

		$title  = apply_filters( 'widget_title', $instance['title'] );
		$output = '';

		$output .= '<img src="' . esc_url( $this->jp_data->PeopleGroupPhotoURL ) . '" />';

		$output  .= '<ul>' . "\n";
			$output .= '<li class="image"><a href="' . esc_url( $this->jp_data->PeopleGroupURL ) . '">' . $this->jp_data->PeopNameInCountry . '</a>, in <a href="' . $this->jp_data->CountryURL . '">' . $this->jp_data->Ctry . '</a></li>';
			$output .= '<li class="population"><span>Population:</span> ' . number_format( absint( $this->jp_data->Population ) ) . '</li>';
			$output .= '<li class="language"><span>Language:</span> ' . $this->jp_data->PrimaryLanguageName . '</li>';
			$output .= '<li class="religion"><span>Religion:</span> ' . $this->jp_data->PrimaryReligion . '</li>';
			$output .= '<li class="status"><span>Status:</span> ' . $this->jp_data->JPScaleText . '</li>';
		$output  .= '</ul>' . "\n";

		$output .= '<p>Data from the <a href="http://www.joshuaproject.net/" target="_new">Joshua Project</a></p>' . "\n";

		echo wp_kses_post( $args['before_widget'] );
		if ( ! empty( $title ) )
		echo wp_kses_post( $args['before_title'] ) . esc_html( $title ) . wp_kses_post( $args['after_title'] );
		echo wp_kses_post( $output );
		echo wp_kses_post( $args['after_widget'] );
	}

	/**
	 * Front-end css for widget.
	 */
	public function widget_css() {

		$output = '';

		// make sure we actually have a widget
		if ( is_active_widget( false, false, $this->id_base, true ) ) {

			// don't show the styles if the filter has them off
			if ( ! apply_filters( 't1k-jp-unreached-people-styles', true ) ) { return; }

			$output .= '<style type="text/css">' . "\n";

				$output .= '.widget_joshua-project-daily-unreached-widget img { max-width: 100%; }' . "\n";
				$output .= '.widget_joshua-project-daily-unreached-widget ul { list-style-type: none; border-bottom: 1px solid #ccc;}' . "\n";

			$output .= '</style>' . "\n";
		}

		print $output;

	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {

		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		} else {
			$title = '';
		}

		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}

} // class Joshua_Project_Daily_Unreached_Widget


// register Joshua_Project_Widget widget
function register_joshua_project_daily_unreached_widget() {
	register_widget( 'Joshua_Project_Daily_Unreached_Widget' );
}
add_action( 'widgets_init', 'register_joshua_project_daily_unreached_widget' );
