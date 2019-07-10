<?php

function theme_enqueue_styles() {
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'avada-stylesheet' ) );
	wp_enqueue_style( 'child-style2', get_stylesheet_directory_uri() . '/style-uniform.css', array( 'avada-stylesheet' ) );

	//Added by Matia - Firespring
	wp_register_script( 'custom_js', get_stylesheet_directory_uri() . '/js/custom_js.js', array( 'jquery' ) );
  	wp_enqueue_script( 'custom_js' );
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

function custom_admin_script() {
    wp_enqueue_script('custom_js', get_stylesheet_directory_uri() . '/js/admin_custom_js.js', array('jquery'), true);
}
add_action('admin_enqueue_scripts', 'custom_admin_script');

function avada_lang_setup() {
	$lang = get_stylesheet_directory() . '/languages';
	load_child_theme_textdomain( 'Avada', $lang );
}
add_action( 'after_setup_theme', 'avada_lang_setup' );

/* Add GTM code provided by Sierra F*/
function my_custom_head_function_for_avada() {
    global $post;
?>
 
<?php
    if ($post->ID == 6 || $post->ID == 52 || $post->ID == 8348) { //homepage, dev docs, partner program
        $output = '<!-- Tag for Activity Group: IP1816638, Activity Name: Retargeting~IP1816638, Activity ID: 6918166 -->
        <!-- Expected URL: https://paymentspring.com/partner-program -->

        <!--
        Activity ID: 6918166
        Activity Name: Retargeting~IP1816638
        Activity Group Name: IP1816638
        -->

        <!--
        Start of DoubleClick Floodlight Tag: Please do not remove
        Activity name of this tag: Retargeting~IP1816638
        URL of the webpage where the tag is expected to be placed: https://paymentspring.com/partner-program
        This tag must be placed between the <body> and </body> tags, as close as possible to the opening tag.
        Creation Date: 12/29/2017
        -->
        <script type="text/javascript">
        var axel = Math.random() + "";
        var a = axel * 10000000000000;
        document.write(\'<iframe src="https://8315976.fls.doubleclick.net/activityi;src=8315976;type=ip1810;cat=retar0;dc_lat=;dc_rdid=;tag_for_child_directed_treatment=;ord=\' + a + \'?" width="1" height="1" frameborder="0" style="display:none"></iframe>\');
        </script>
        <noscript>
        <iframe src="https://8315976.fls.doubleclick.net/activityi;src=8315976;type=ip1810;cat=retar0;dc_lat=;dc_rdid=;tag_for_child_directed_treatment=;ord=1?" width="1" height="1" frameborder="0" style="display:none"></iframe>
        </noscript>
        <!-- End of DoubleClick Floodlight Tag: Please do not remove -->';

        echo $output;
    }
}
add_filter( 'avada_before_body_content', 'my_custom_head_function_for_avada' );



function SearchFilter($query) {
	
    if ($query->is_search) {
		//get ids of pages using uniform landing page template
		global $wpdb; 
		$template = 'uniform_flat_header_landing.php';
		$template2 = 'uniform_flat_header_landing_software_partner.php';
		$ids = $wpdb->get_results($wpdb->prepare( 
			"select distinct post_id 
			from $wpdb->postmeta 
			where meta_value = %s
			OR meta_value = %s", 
			$template, $template2
			) 
		,ARRAY_A);

		//add ids to current list
		//e-invoice: 8261, 
		//checkout-button: 8112, 
		//Placeholder /partner page: 11124,
		//Placeholder /campaign page: 14402, 
		//Cue Marketplace landing page: 11116, 
		//Generic thank you page - 11114
		//chaverware: 3216, 3092
		//Agape:1830,2101,2086, 1808, 2076
		//Merchant application Thank You page: 13475
		$no_search_ids = array(1874, 1887, 8261, 8112, 5803, 11124, 14402, 11116, 11114, 3216, 1830, 2101, 2086, 3092, 1808, 2076, 13475);
		if(!empty($ids) && is_array($ids)){
			foreach($ids as $key => $result_array){
				if( !in_array($result_array['post_id'], $no_search_ids) ){
					array_push($no_search_ids, $result_array['post_id']);
				}
			}
		}
		
		$query->set('post__not_in', $no_search_ids);			
	}
		        
    return $query;
}

add_filter('pre_get_posts','SearchFilter'); 

function admin_css_signup_plugin() {
  echo '<style>

    table.track_signup_table {
        border-collapse: collapse;
    }

    table.track_signup_table th:first-of-type {
        width: 130px;
    } 

    table.track_signup_table th {
        width: 300px;
    } 

    table.track_signup_table tr:nth-child(even) {
        background-color: #eeeeee;
    }

    table.track_signup_table tr:nth-child(2n+3) {
        background-color: #ffffff;
    }

    table.track_signup_table td {
        border: 1px solid #ccc;
        padding: 7px;
    }

  </style>';
}
add_action('admin_head', 'admin_css_signup_plugin');

/* Add a color metaboxes to Admin pages for the Generic Partnet Template */
function custom_main_meta_box_markup()
{
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");
    ?>
        <div>
            <label for="meta-box-main-color">Main Color</label>
            <input name="meta-box-main-color" type="text" value="<?php echo get_post_meta(get_the_ID(), "meta-box-main-color", true); ?>">
        </div>
    <?php
}

function custom_accent_meta_box_markup()
{
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");
    ?>
        <div>
            <label for="meta-box-accent-color">Accent Color</label>
            <input name="meta-box-accent-color" type="text" value="<?php echo get_post_meta(get_the_ID(), "meta-box-accent-color", true); ?>">
        </div>
    <?php
}

function custom_watermark_meta_box_markup()
{
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");
    ?>
        <div>
            <label for="meta-box-watermark">Show PaymentSpring Watermark in masthead?</label>
            <?php 
            $field_id_value = get_post_meta(get_the_ID(), 'meta-box-watermark', true);
            if($field_id_value == "yes") {
                $field_id_checked = 'checked="checked"'; 
            }?>
            <input name="meta-box-watermark" type="checkbox" value="yes" <?php echo $field_id_checked; ?>>
        </div>
    <?php
}

function position_bgimage_markup()
{
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");
    ?>
        <div style="font-size: 12px; color: gray; padding-bottom: 20px;">
            background-position; background-size<br>
            (ex: left 0px top 100px; 43%)
        </div>
        <div>
            <label for="meta-box-position-bgimage-desktop">Desktop Positioning</label>
            <input name="meta-box-position-bgimage-desktop" type="text" value="<?php echo get_post_meta(get_the_ID(), "meta-box-position-bgimage-desktop", true); ?>">
        </div>
        <div>
            <label for="meta-box-position-bgimage-mobile">Mobile Positioning</label>
            <input name="meta-box-position-bgimage-mobile" type="text" value="<?php echo get_post_meta(get_the_ID(), "meta-box-position-bgimage-mobile", true); ?>">
        </div>
    <?php
}

function bgcolor_meta_box_markup()
{
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");
    ?>
        <div>
            <label for="meta-box-bgcolor">Header Background Color</label>
            <input name="meta-box-bgcolor" type="text" value="<?php echo get_post_meta(get_the_ID(), "meta-box-bgcolor", true); ?>">
        </div>
    <?php
}

function add_custom_meta_boxes()
{
    add_meta_box("main-color-meta-box", "Main Color", "custom_main_meta_box_markup", "page", "side", "low", null);
    add_meta_box("accent-color-meta-box", "Accent Color", "custom_accent_meta_box_markup", "page", "side", "low", null);
    add_meta_box("watermark-meta-box", "Checkbox", "custom_watermark_meta_box_markup", "page", "side", "low", null);
    add_meta_box("position-bgimage-meta-box", "Header Background Image", "position_bgimage_markup", "page", "side", "low", null);
    add_meta_box("head-bgcolor-meta-box", "Background Color", "bgcolor_meta_box_markup", "page", "side", "low", null);
}

add_action("add_meta_boxes", "add_custom_meta_boxes");

function save_custom_meta_box($post_id, $post, $update)
{
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__))) {
        return $post_id;
    }

    if(!current_user_can("edit_post", $post_id)) {
        return $post_id;
    }

    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE) {
        return $post_id;
    }

    $slug = "page";
    if($slug != $post->post_type) {
        return $post_id;
    }
    
    $meta_box_text_value = "";

    if(isset($_POST["meta-box-accent-color"]))
    {
        $meta_box_text_value = sanitize_text_field($_POST["meta-box-accent-color"]);
    } 
    update_post_meta($post_id, "meta-box-accent-color", $meta_box_text_value);

    if(isset($_POST["meta-box-main-color"]))
    {
        $meta_box_text_value = sanitize_text_field($_POST["meta-box-main-color"]);
    } 
    update_post_meta($post_id, "meta-box-main-color", $meta_box_text_value);

    if(isset($_POST["meta-box-watermark"]))
    {
        $meta_box_text_value = sanitize_text_field($_POST["meta-box-watermark"]);
    } 
    update_post_meta($post_id, "meta-box-watermark", $meta_box_text_value);

    if(isset($_POST["meta-box-position-bgimage-desktop"]))
    {
        $meta_box_text_value = sanitize_text_field($_POST["meta-box-position-bgimage-desktop"]);
    } 
    update_post_meta($post_id, "meta-box-position-bgimage-desktop", $meta_box_text_value);

    if(isset($_POST["meta-box-position-bgimage-mobile"]))
    {
        $meta_box_text_value = sanitize_text_field($_POST["meta-box-position-bgimage-mobile"]);
    } 
    update_post_meta($post_id, "meta-box-position-bgimage-mobile", $meta_box_text_value);

    if(isset($_POST["meta-box-bgcolor"]))
    {
        $meta_box_text_value = sanitize_text_field($_POST["meta-box-bgcolor"]);
    } 
    update_post_meta($post_id, "meta-box-bgcolor", $meta_box_text_value);
}

add_action("save_post", "save_custom_meta_box", 10, 3);
/* End color meta box */

/* Add Read Time meta box to posts */
function custom_read_time_meta_box_markup() {
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");
    ?>
        <div>
            <label for="meta-box-read-time">Read Time</label>
            <input name="meta-box-read-time" type="text" value="<?php echo get_post_meta(get_the_ID(), "meta-box-read-time", true); ?>">
        </div>
    <?php
}

function add_read_time_meta_box()
{
    add_meta_box("read-time-meta-box", "Read Time", "custom_read_time_meta_box_markup", "post", "side", "low", null);
}

add_action("add_meta_boxes", "add_read_time_meta_box");

function save_read_time_meta_box($post_id, $post, $update) {
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__))) {
        return $post_id;
    }

    if(!current_user_can("edit_post", $post_id)) {
        return $post_id;
    }

    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE) {
        return $post_id;
    }

    $slug = "post";
    if($slug != $post->post_type) {
        return $post_id;
    }
    
    $meta_box_text_value = "";

    $read_time = sanitize_text_field($_POST["meta-box-read-time"]);
    if(isset($read_time))
    {
        echo $read_time;
        $meta_box_text_value = $read_time;
    } 
    update_post_meta($post_id, "meta-box-read-time", $meta_box_text_value);
}
add_action("save_post", "save_read_time_meta_box", 10, 3);

/* End Read Time meta box */

function add_blog_meta() {
    $categories = get_the_category();
    $separator = ', ';
    $output = '';
    $output .= "<div class='fusion-meta-info'>";

    if ( ! empty( $categories ) ) {
        $output .= "Categories: ";
        foreach( $categories as $category ) {
            if ($category->name != "Blog") {
                $output .= '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" alt="' . esc_attr( sprintf( __( 'View all posts in %s', 'textdomain' ), $category->name ) ) . '">' . esc_html( $category->name ) . '</a>' . $separator;
            }
        }
        $output = substr($output, 0, strlen($output) - 2);
    }

    $posttags = get_the_tags();
    if ($posttags) {
        if ( ! empty( $categories ) ) {
            $output .= " | ";
        }
        $output .= "Tags: ";
      foreach($posttags as $tag) {
        //$output .= $tag->name . ' '; 
        $output .= '<a href="' . esc_url( get_tag_link( $tag->term_id ) ) . '" alt="' . esc_attr( sprintf( __( 'View all posts in %s', 'textdomain' ), $tag->name ) ) . '">' . esc_html( $tag->name ) . '</a>' . $separator;
      }
      $output = substr($output, 0, strlen($output) - 2);
    }
    $output .= "</div>";
    echo trim( $output, $separator );
}

function add_blog_read_time_meta() {
    $output = '<div class="post-read-time">';
    if (strlen(get_post_meta( get_the_ID(), 'meta-box-read-time', true )) > 0) {
        $output .= get_post_meta( get_the_ID(), 'meta-box-read-time', true );
    }
    $output .= "</div>";
    echo $output;
}

add_action('fusion_blog_shortcode_loop_content','add_blog_read_time_meta', 10);

function add_pixel_gf_18_19() {
    //output this JS on GF 18 submission
    $output = 
    '<!-- Tag for Activity Group: IP1816638, Activity Name: Learn More Button~IP1816638, Activity ID: 6918160 -->
    <!-- Expected URL: https://paymentspring.com/partner-program -->

    <!--
    Activity ID: 6918160
    Activity Name: Learn More Button~IP1816638
    Activity Group Name: IP1816638
    -->

    <!--
    Start of DoubleClick Floodlight Tag: Please do not remove
    Activity name of this tag: Learn More Button~IP1816638
    URL of the webpage where the tag is expected to be placed: https://paymentspring.com/partner-program
    This tag must be placed between the <body> and </body> tags, as close as possible to the opening tag.
    Creation Date: 12/29/2017
    -->
    <script type="text/javascript">
    var axel = Math.random() + "";
    var a = axel * 10000000000000;
    document.write(\'<iframe src="https://8315976.fls.doubleclick.net/activityi;src=8315976;type=ip1810;cat=learn0;dc_lat=;dc_rdid=;tag_for_child_directed_treatment=;ord=\' + a + \'?" width="1" height="1" frameborder="0" style="display:none"></iframe>\');

    </script>
    <noscript>
    <iframe src="https://8315976.fls.doubleclick.net/activityi;src=8315976;type=ip1810;cat=learn0;dc_lat=;dc_rdid=;tag_for_child_directed_treatment=;ord=1?" width="1" height="1" frameborder="0" style="display:none"></iframe>
    </noscript>
    <!-- End of DoubleClick Floodlight Tag: Please do not remove -->';
    echo $output;

}
add_action( 'gform_after_submission_18', 'add_pixel_gf_18_19', 10, 2 );
add_action( 'gform_after_submission_19', 'add_pixel_gf_18_19', 10, 2 );

/* Display post template name in admin area */
add_filter( 'manage_pages_columns', 'page_column_views' );
add_action( 'manage_pages_custom_column', 'page_custom_column_views', 5, 2 );
function page_column_views( $defaults )
{
   $defaults['page-layout'] = __('Template');
   return $defaults;
}
function page_custom_column_views( $column_name, $id )
{
   if ( $column_name === 'page-layout' ) {
       $set_template = get_post_meta( get_the_ID(), '_wp_page_template', true );
       if ( $set_template == 'default' ) {
           echo 'Default';
       }
       $templates = get_page_templates();
       ksort( $templates );
       foreach ( array_keys( $templates ) as $template ) :
           if ( $set_template == $templates[$template] ) echo $template;
       endforeach;
   }
}

/* Blog Redesign */
function indiv_blog_featured_image($post_id) {
    return "<div class='indiv-blog-featured-image'>" . get_the_post_thumbnail( $post_id, 'medium' ) . "</div>";
}
add_shortcode('blog_featured_image','indiv_blog_featured_image');

function display_featured_post( $atts ) {
    global $wp;
    $permalink = home_url( $wp->request );
    if (strpos($permalink,'blog/page/') == 0) {
        //This is the first Blog page
        $att = shortcode_atts( array(
        'category' => 'featured'
        ), $atts );

        /*$featured_categories = $att['category'];
        $featured_post = new WP_Query( array( 
            'category_name' => $featured_categories, 
            'posts_per_page' => 1 
        ) );*/
		
		$featured_post = new WP_Query( array( 
			'posts_per_page' => 1,
			'orderby' => 'publish_date',
			'order' => 'DESC'
		) );
        
        global $post;
        if($featured_post->have_posts()) {
            while($featured_post->have_posts()) {
                $featured_post->the_post();

                $permalink = get_permalink();

                $theBox = '<div class="featured-blog">';
                $theBox .= '<div class="featured-blog-image" style="background-image: url(' . get_the_post_thumbnail_url( $post->ID, 'large' ) . ')">';
                $theBox .= '<a href="' . get_permalink() . '"></a></div>';
                $theBox .= '<div class="featured-blog-desc">';
                $theBox .= '<div class="h3"><a href="' . get_permalink() . '">' . get_the_title() . '</a></div><div class="">' . get_the_excerpt() . '</div><div class="featured-read-more">';
                $theBox .= do_shortcode('[fusion_button link="' . get_permalink() . '" title="read more" target="_self" link_attributes="" alignment="" modal="" hide_on_mobile="small-visibility,medium-visibility,large-visibility" class="button_no_border" id="" color="custom" button_gradient_top_color="" button_gradient_bottom_color="" button_gradient_top_color_hover="#febd3f" button_gradient_bottom_color_hover="#febd3f" accent_color="" accent_hover_color="" type="" bevel_color="" border_width="" size="" stretch="default" shape="" icon="" icon_position="left" icon_divider="no" animation_type="" animation_direction="left" animation_speed="0.3" animation_offset=""]read more[/fusion_button]');
                $theBox .= '</div>';
                $theBox .= '</div>';
                $theBox .= '</div>';

                return $theBox;
            }

        }
        else {
            return "Please select a Post to be featured";
        }
    }
    
    wp_reset_postdata();

}
add_shortcode('featuredBlog', 'display_featured_post');

/*Wordpress Search Form Shortcode */
add_shortcode('wpsearch', 'get_search_form');
/* END Blog Redesign */

function dynamic_copyright() {
	
	$firstYear = "2017";
	$thisYear = date(Y);
	
	$output = '';
	$output .= $firstYear . " - " . $thisYear;

	return $output;
}
add_shortcode('dynamic_copyright', 'dynamic_copyright');


