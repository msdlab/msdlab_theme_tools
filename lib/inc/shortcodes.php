<?php
class MSDLab_Shortcodes
{

    //Properties
    private $options;

    //Methods
    function __construct($options)
    {
        $defaults = array();

        $this->options = wp_parse_args($options, $defaults);

        add_shortcode('mailto', array(&$this, 'msdlab_mailto_function'));
        add_shortcode('cleanmail', array(&$this, 'msdlab_clean_mail_function'));
        add_shortcode('button',array(&$this, 'msdlab_button_function'));

		add_filter('mce_buttons', array(&$this,'mce_buttons'));
		add_filter('mce_external_plugins', array(&$this,'register_mce_javascript'));

		add_action('after_wp_tiny_mce',array(&$this,'button_dialog_contents'));
        add_action('after_setup_theme',array(&$this,'admin_scripts_and_styles'));

    }

    function msdlab_clean_mail_function($atts){
        extract(shortcode_atts(array(
            'email' => '',
        ), $atts));
        if ($email == '' && preg_match('|[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}|i', $content, $matches)) {
            $email = $matches[0];
        }
        $email = antispambot($email);
        return 'mailto:' . $email;
    }

    function msdlab_mailto_function($atts, $content)
    {
        extract(shortcode_atts(array(
            'email' => '',
        ), $atts));
        $content = trim($content);
        if ($email == '' && preg_match('|[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}|i', $content, $matches)) {
            $email = $matches[0];
        }
        $email = antispambot($email);
        if (strlen($content) < 1) {
            $content = $email;
        }
        return '<a href="mailto:' . $email . '">' . $content . '</a>';
    }

    function admin_scripts_and_styles(){
	    wp_enqueue_script('my-custom-wpdialog-script', plugins_url('/../js/button-dialog.js', __FILE__), array('jquery','wpdialogs'));
    }

    function msdlab_button_function($atts, $content = null){
        extract( shortcode_atts( array(
            'url' => null,
            'target' => '_self',
            'class' => null,
	        'color' => null,
        ), $atts ) );
        if(strstr($url,'mailto:',0)){
            $parts = explode(':',$url);
            if(is_email($parts[1])){
                $url = $parts[0].':'.antispambot($parts[1]);
            }
        }
        $data_atts = array();
        foreach($atts as $k => $v){
        	if(strstr($k,'data-')){
        		$data_atts[] = $k .' = "' .$v. '"';
	        }
	        if(count($data_atts)>0){
        		$data = implode(' ',$data_atts);
	        }
        }
        $ret = '<a class="button btn ' . $class . ' ' . $color . '" href="' . $url . '" target="' . $target . '" '.$data.'>'.remove_wpautop($content).'</a>';
        return $ret;
    }

	function mce_buttons($buttons){
		array_push( $buttons, '|', 'cta_button_plugin' );
		return $buttons;
	}

	function register_mce_javascript(){
		$plugin_array['cta_button_plugin'] = plugins_url( '/../js/shortcodes.js',__FILE__ );
		return $plugin_array;
	}

	/*
	 * Prints the contents of my custom wpdialog
	 */
	function button_dialog_contents() {
		/*
		 * Enqueue and print your styles and scripts that are needed for the dialog
		 * Use wp_enqueue_style/wp_enqueue_script and wp_print_styles/wp_print_scripts
		 */


		// Print directly html
		?>
		<div style="display: none">
			<form id="button-wpdialog" tabindex="-1" class="msdlab_meta">
                <ul>
                    <li>
                        <label>Text</label>
                        <input type="text" id="button_text" name="button_text" />
                    </li>
                    <li>
                        <label>URL</label>
                        <input type="text" id="button_url" name="button_url" />
                    </li>
                    <li>
                        <label>Target</label>
                        <select id="button_target" name="button_target">
                            <option value="_self">Self</option>
                            <option value="_blank">New Tab</option>
                        </select>
                    </li>
                    <li>
                        <label>Color</label>
                        <select id="button_color" name="button_color">
                            <option value="red">Red</option>
                            <option value="blue">Blue</option>
                            <option value="green">Green</option>
                            <option value="offwhite">Off White</option>
                            <option value="white">White</option>
                        </select>
                    </li>
                </ul>
				<div class="submitbox">
					<div id="button-wpdialog-update" style="float: left;">
						<input id="button-wpdialog-submit" class="button-primary" type="submit" name="button-wpdialog-submit" value="<?php _e('Insert', 'textdomain'); ?>">
					</div>
					<div id="button-wpdialog-cancel" style="float: right;">
						<a class="submitdelete deletion" href="#"><?php _e('Cancel', 'textdomain')?></a>
					</div>
				</div>
			</form>
		</div>
		<?php
	}
}