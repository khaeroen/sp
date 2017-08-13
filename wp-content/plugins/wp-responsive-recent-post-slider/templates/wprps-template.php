<?php 
function get_wprps_slider( $atts, $content = null ) {
            // setup the query
            extract(shortcode_atts(array(
		"limit" => '',	
		"category" => '',
		"design" => '',	
        "show_date" => '',
        "show_category_name" => '',
        "show_content" => '',
        "content_words_limit" => '',
		"dots"     			=> '',
		"arrows"     		=> '',				
		"autoplay"     		=> '',		
		"autoplay_interval"  => '',				
		"speed"             => '',
		"hide_post"        => '',
		"post_type"       => '',
		"show_author" => '',
		'show_read_more'  => 'true',
	), $atts));
	
	// Enqueue required script
	wp_enqueue_script( 'wpos-slick-jquery' );
	
	if( $post_type ) { 
		$postType = $post_type; 
	} else {
		$postType = 'post';
	}
	if( $hide_post ) { 
		$hidePost = $hide_post; 
	} else {
		$hidePost = array();
	}
	 if( $show_author ) { 
        $showAuthor = $show_author; 
    } else {
        $showAuthor = 'true';
    }
	
	if( $limit ) { 
		$posts_per_page = $limit; 
	} else {
		$posts_per_page = '8';
	}
	if( $category ) { 
		$cat = $category; 
	} else {
		$cat = '';
	}	
	
	if( $design ) { 
		$postdesign = $design; 
	} else {
		$postdesign = 'design-1';
	}	
	
    if( $show_date ) { 
        $showDate = $show_date; 
    } else {
        $showDate = 'true';
    }
	if( $show_category_name ) { 
        $showCategory = $show_category_name; 
    } else {
        $showCategory = 'true';
    }
    if( $show_content ) { 
        $showContent = $show_content; 
    } else {
        $showContent = 'true';
    }
	 if( $content_words_limit ) { 
        $words_limit = $content_words_limit; 
    } else {
        $words_limit = '20';
    }
	
	if( $dots ) { 
		$dotsv = $dots; 
	} else {
		$dotsv = 'true';
	}
	
	if( $arrows ) { 
		$arrowsv = $arrows; 
	} else {
		$arrowsv = 'true';
	}
	
	if( $autoplay ) { 
		$autoplayv = $autoplay; 
	} else {
		$autoplayv = 'true';
	}
	
	if( $autoplay_interval ) { 
		$autoplayIntervalv = $autoplay_interval; 
	} else {
		$autoplayIntervalv = '3000';
	}
	
	if( $speed ) { 
		$speedv = $speed; 
	} else {
		$speedv = '300';
	}
	$showreadmore = ( $show_read_more == 'false' )	? 'false' 	: 'true';
	$is_rtl 		= (is_rtl()) ? 'true': 'false';
	ob_start();
	
	$posts_type 	= $postType ;
	$orderby 		= 'post_date';
	$order 			= 'DESC';
				 
		
        $args = array ( 
            'post_type'      => $posts_type, 
            'orderby'        => $orderby, 
            'order'          => $order,
            'posts_per_page' => $posts_per_page, 
			'cat'       => $cat
            
            ); 
			
			if(!empty($hidePost)){
				$hidePosId = explode(',',$hidePost);
				$args['post__not_in'] = $hidePosId;
			}
				
        $query = new WP_Query($args);
      $post_count = $query->post_count;
         
             if ( $query->have_posts() ) :
			 ?>
		<div class="recent-post-slider <?php echo $postdesign; ?>">
				<?php
			 while ( $query->have_posts() ) : $query->the_post();
                         
               
             switch ($postdesign) {
				 case "design-1":
					include('designs/design-1.php');
					break;
				 case "design-2":
					include('designs/design-2.php');
					break;
				 case "design-3":
					include('designs/design-3.php');
					break;
				 case "design-4":
					include('designs/design-4.php');
					break;			
				 default:					 
						include('designs/design-1.php');
					}


					endwhile; ?>
		  </div><!-- #post-## -->		
		  <?php
            endif; ?>
			
			<?php
             wp_reset_query(); 
			 ?>
			 <script type="text/javascript">
		jQuery(document).ready(function(){
		jQuery('.recent-post-slider.<?php echo $postdesign; ?>').slick({
			dots 			: <?php echo $dotsv; ?>,
			infinite 		: true,
			arrows 			: <?php echo $arrowsv; ?>,
			speed 			: <?php echo $speedv; ?>,
			autoplay 		: <?php echo $autoplayv; ?>,		
			rtl 			: <?php echo $is_rtl;?>,				
			autoplaySpeed 	: <?php echo $autoplayIntervalv; ?>,
			slidesToShow 	: 1,
			slidesToScroll 	: 1,
			responsive: [
    {
      breakpoint: 768,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
        infinite: true,
        dots: true
      }
    },
    {
      breakpoint: 640,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
  ]
		});
	});
	</script>
			 <?php				
		return ob_get_clean();			             
	}
add_shortcode('recent_post_slider','get_wprps_slider');


function wprps_limit_words($string, $word_limit)
{
  $words = explode(' ', $string, ($word_limit + 1));
  if(count($words) > $word_limit)
  array_pop($words);
  return implode(' ', $words);
}


// Manage Category Shortcode Columns

add_filter("manage_category_custom_column", 'category_columns', 10, 3);
add_filter("manage_edit-category_columns", 'category_manage_columns'); 
function category_manage_columns($theme_columns) {
    $new_columns = array(
            'cb' => '<input type="checkbox" />',
            'name' => __('Name'),
            'post_slider_shortcode' => __( 'Category Shortcode', 'wp-responsive-recent-post-slider' ),
            'slug' => __('Slug'),
            'posts' => __('Posts')
			);
    return $new_columns;
}

function category_columns($out, $column_name, $theme_id) {
    $theme = get_term($theme_id, 'category');
    switch ($column_name) {      

        case 'title':
            echo get_the_title();
        break;
        case 'post_slider_shortcode':        

             echo '[recent_post_slider category="' . $theme_id. '"]';
        break;

        default:
            break;
    }
    return $out;   

}