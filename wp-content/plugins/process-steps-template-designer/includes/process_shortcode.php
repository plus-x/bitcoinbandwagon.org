<?php

if (!class_exists('ProcessShortcode')) {

    class ProcessShortcode {
        /**
         * The Constructor
         */
        public function __construct() {
            // register actions
            add_action('init', array($this, 'pp_register_shortcode'));
            add_action('wp_enqueue_scripts', array($this, 'pp_load_scripts_styles'));
       		foreach (array('post.php','post-new.php') as $hook) {
    		add_action("admin_head-$hook", array( $this,'pstd_cats'));
			}
        
		}
        function pp_register_shortcode() {
            add_shortcode('cool-process', array($this, 'pp_view'));//used to register shortcode handler

        }

        function pp_view($atts, $content = null) {
            //shortcode_atts Combines user shortcode attributes with known attributes and fills in defaults when needed.
            $attribute = shortcode_atts(array(
               'type'=>'',
			   'animation'=>'',
               'show-posts'=>'',
			   'category'=>'',
			   'choose-color'=>'',  //attribute defined to make color of vertical process dynamic
			   'icon-size'=>''      //attribute defined to make icon-size dynamic
            ), $atts);
 
                    //to enqueue css and js files 
					wp_enqueue_style('font-aws');
                    wp_enqueue_style('cool_process_styles');
					wp_enqueue_style('pp_slick_style');
					wp_enqueue_script('pp_slickmin');
					
				
                    $pp_view='';
					$p_type='';
					$pp_skin = isset($attribute['skin']) ? $attribute['skin'] : 'default';
					$wrp_cls = '';
					$wrapper_cls = '';
					$post_skin_cls = '';
					$stories_images_link ='';
					$story_desc_type='';
					
					$pp_html='';
					$pp_format_html='';
					$args = array();
					$cat_timeline = array();

if ($attribute['category'] && $attribute['category'] !="all") {
   	$category = $attribute['category'];
	$args['tax_query'] = array(
        array(
              'taxonomy' => 'process-categories',
              'field' => 'slug',
              'terms' => $attribute['category'],
              ));
              }
                if (isset($attribute['show-posts'])&& $attribute['type'] == "vertical-process") {
                	$args['posts_per_page'] = $attribute['show-posts'];
	                } else {
	                	
	                	$args['posts_per_page']=$attribute['show-posts']=="" ?4:-1;
	              
	                       }
						
					$step_counts= $attribute['show-posts'];
			        $args['post_status']=array('publish');
					$args['post_type']='process_posts';
					$args['orderby']='meta_value_num';
					$args['order']='ASC';
					$args['meta_key']='pp_post_order';

					$i=0;
					//$process_id=rand(1,10);
					$process_id=md5(uniqid (rand (),false)); //for generating unique random id for each process
					 

					$pp_loop = new WP_Query($args);
					//$counter=0; //for vertical process
				
					if ($pp_loop->have_posts()) {

						while ($pp_loop->have_posts()) : $pp_loop->the_post();
							 global $post;
							$pp_post_lbl = get_post_meta($post->ID, 'pp_post_lbl', true);
						    $pp_post_order = get_post_meta($post->ID, 'pp_post_order', true);
						
							$pp_format_html='';
							$post_content = get_the_content();
							
							if(function_exists('get_fa')){
					        $post_icon=get_fa(true);
							}
							
							if(isset($post_icon)){
								$icon=$post_icon;
							}else{
								if(isset($default_icon)&& !empty($default_icon)){
									$icon='<i class="fa '.$default_icon.'" aria-hidden="true"></i>';
								}else {
									$icon = '<i class="fa fa-clock-o" aria-hidden="true"></i>';
								}
							}
							$clt_icon='';
							$p_type=$attribute['type'];
							if (isset($attribute['type']) && $attribute['type'] == "default") {

								$clt_icon ='<span class="icon-placeholder"><span class="ps-lbl">'.$icon.'</span></span> ';

							}else if (isset($attribute['type']) && $attribute['type'] == "vertical-process"){ //vertical process condition
								$clt_icon ='<span class="icon-placeholder-v">'.$icon.'</span> ';

						    }else if(isset($attribute['type']) && $attribute['type'] == "with-number") {
								$clt_icon ='<span class="label-placeholder"><span class="ps-lbl">'.$pp_post_lbl.'</span></span> ';

							}else if(isset($attribute['type']) && $attribute['type'] == "with-image") {
							
							$img_thumb = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full');
								$bg_img=$img_thumb[0];
								if(isset($img_thumb[0])){
							//	$clt_icon ='<div class="img-placeholder" style="background-image:url('.$bg_img.'");background-repeat: no-repeat;

				                 $clt_icon ='<div class="img-placeholder" style="
				                 background-image:url('.$bg_img.');
				                 background-repeat: no-repeat;
			                     background-position: center top;
				                 background-color: #ccc;
			                     -o-background-size: cover;
				                 -moz-background-size: cover;
				                 -webkit-background-size:cover;">';
                                  $clt_icon .='</div>';
								}
							}
							
                if (isset($attribute['type']) && $attribute['type'] == "vertical-process")
               	 {
							//vertical process html
						    $pp_html.='<li class="ps_timeline-item">';
							$pp_html .=	'<div class="ps_timeline-marker"></div>';
							$pp_html .=	'<div class="ps_timeline-content">';
							$pp_html .= '<h3 class="ps_timeline-label">' . $pp_post_lbl . '</h3>';
							$pp_html .= '<h4 class="ps_timeline-title">' . get_the_title($post->ID) . '</h4>';
							$pp_html .='<p>'.$clt_icon.'</p><div class="ps_v_content"> ' . $post_content . '</div>';
			                $pp_html .='</div></li>';
					 }else{
							$i++;
							//html for processes other than vertical process
						    $pp_html .= '<li>
								<span class="cool-process-icon">'.$clt_icon.'</span>
								<div class="cool-process-detail'.$post_skin_cls.'">';
							    if($pp_post_lbl && in_array($attribute['type'],array('default','with-image'))){
								$pp_html .='<h3>'.$pp_post_lbl.'</h3>';
							    }
							$pp_html .='<h2 class="content-title">' . get_the_title() . '</h2>';
							$pp_html .= '<span></span>';
							$pp_html .= '<div class="clearboth"></div><div class="process-description">';
							$pp_html .= '<div class="content-details"> ' . $post_content . '</div>';
							$pp_html .= '</div></div></li>';
					   }

						endwhile;
						wp_reset_postdata();//end of wp_query loop
						
					}
					else{
				
				        $pp_html .= '<li><div class="no-content"><h4>';
				        $pp_html .= __('Sorry,You have not added any process yet', 'cool-process');
				        $pp_html .= '</h4></div></li>';
					
			        }
					
				if($attribute['animation']=="yes"){
				    $animation_styles='#process-'.$process_id.' .cool-process-steps ul li:hover i {
				    fill: #fff;
				    -webkit-animation: toRightFromLeft .3s forwards;
				    animation: toRightFromLeft .3s forwards;
				    }';
				}else{
					$animation_styles='';
				}
				
					//slick slider
				if($attribute['type'] != "vertical-process") 
					{

					$pp_wrp_id="process-".$process_id;
					$pp_view = '<div id="'.$pp_wrp_id.'" class="cool-process '.$p_type.'">
					<div id="cool_process" class="cool-process-steps process-steps-'.$step_counts.'" >';

						if(!empty($attribute['show-posts'])&& $attribute['show-posts']!=false){
							$slide=$attribute['show-posts'];
	    		    $prevArrow='<button type="button" class="slick-prev slick-arrow"><i class="fa fa fa-arrow-circle-o-left"></i></button>';

       			     $next_arrow='<button type="button" class="slick-next slick-arrow"><i class="fa fa fa-arrow-circle-o-right"></i></button>';
						  
       			     //icon size dynamic
       			      $select_size='';
                            if($attribute['icon-size']!=""){
                             $size=$attribute['icon-size'];
                            //select_size is any variable to which we assign css
                             $select_size='#process-'.$process_id.' .cool-process-steps .cool-process-icon i {
                                   font-size: '.$size.' !important;
                               }';
                             }else if($attribute['icon-size']==""){
					         $select_size='#process-'.$process_id.' .cool-process-steps .cool-process-icon i {
                                   font-size: 80px !important;
                               }';
					         }   


						 if ( ! wp_script_is( 'jquery', 'done' ) ) {
                             wp_enqueue_script( 'jquery' );
                               }

                            wp_add_inline_script( 'pp_slickmin', "jQuery(document).ready(function($){  $('#process-slider-".$process_id."').slick({
							  infinite: false,
                              slidesToShow: ".$slide.",
                              slidesToScroll: 1,
							  arrows: true,
							  nextArrow:'".$next_arrow."',
							  prevArrow:'".$prevArrow."',
							 responsive: [
							   {
                               breakpoint: 1000,
                               settings: {
                                          slidesToShow: 3,
                                          slidesToScroll: 1,
		                                  arrows: true
                                         }
                                     },
							 
							  {
                               breakpoint: 768,
                               settings: {
                                          slidesToShow: 2,
                                          slidesToScroll: 1,
		                                  arrows: true
                                         }
                              },
   
                              {
                               breakpoint: 600,
                               settings: {
                                          slidesToShow: 2,
                                          slidesToScroll: 1,
		                                  arrows: true
                                         }
                              },
                              {
                               breakpoint: 480,
                               settings: {
                                          slidesToShow: 1,
                                          slidesToScroll: 1,
		                                  arrows: true
                                         }
                              }
                                         ]
                             });
	   
                             });" );
							}
                         
				}

			$select_color='';

		 //dynamic color and size of vertical process
		 if ( $attribute['type'] == "vertical-process")
		 {

			     $select_color='';
					   $clr=(isset($attribute['choose-color'])&& $attribute['choose-color']!="") ?$attribute['choose-color'] :"#ccc";
			           $select_color='#ps-timeline'.$process_id.' .ps_timeline-content .ps_timeline-label{color:'.$clr.'!important;}
			           #ps-timeline'.$process_id.' .icon-placeholder-v i {
                       color:'.$clr.'!important;}
				       #ps-timeline'.$process_id.' .ps_timeline-marker:after { background: '.$clr.'!important; }
				       #ps-timeline'.$process_id.' .ps_timeline-marker:before{background: '.$clr.'!important;}
				       #ps-timeline'.$process_id.' .ps_timeline-content .ps_timeline-title { border-bottom-color: '.$clr.'!important;}';
			
				$select_size='';	
					if($attribute['icon-size']!=""){
						$size=$attribute['icon-size'];
						//select_size is any variable to which we assign css
					    $select_size='#ps-timeline'.$process_id.' .icon-placeholder-v i {
					    font-size:'.$size.'!important;
					    margin-left: -3px;}';
				    }else if($attribute['icon-size']==""){
					$select_size='#ps-timeline'.$process_id.' .icon-placeholder-v i {
					font-size:48px!important;}';
					}

				//view of vertical process	
		            $pp_view .= '<div id="ps-timeline'.$process_id.'" class="row example-centered"><ul class="ps_timeline ps_timeline-centered">';
	                $pp_view .=$pp_html;
				    $pp_view .= '</ul></div><style type="text/css">'.$select_color.$select_size.'</style>';
			}
					//view of processes other than vertical process
			else{					

					$pp_view .= '<ul class="responsive" id="process-slider-'.$process_id.'">';
					$pp_view .=$pp_html;
					$pp_view .= '</ul></div></div><div style="clear:both"></div><style type="text/css">'.$animation_styles.$select_size.'</style>';
                    }
			
                    return $pp_view;


        }

        /*
         * Include this plugin's public JS & CSS files on posts.
         */

        function pp_load_scripts_styles() {
            wp_register_style('cool_process_styles', COOL_PROCESS_PLUGIN_URL . 'css/cool-process.css', null, null, 'all');
			wp_register_style('font-aws', COOL_PROCESS_PLUGIN_URL . 'icons-selector/css/font-awesome/css/font-awesome.min.css', null, null, 'all');
            wp_register_style('pp_slick_style', COOL_PROCESS_PLUGIN_URL . 'slick/slick.css', null, null, 'all');
            wp_register_script('pp_slickmin','https://cdn.jsdelivr.net/jquery.slick/1.5.9/slick.min.js', null, null, 'all');
		  }

  public function pstd_cats() {

		    $plugin_url = plugins_url('/', __FILE__);
		    $terms = get_terms(array(
		        'taxonomy' => 'process-categories',
		        'hide_empty' => false,
		    ));

		    if (!empty($terms) || !is_wp_error($terms)) {
		    	$ctl_terms_l['all']='All Cateogires';
		        foreach ($terms as $term) {
		            $ctl_terms_l[$term->slug] =$term->slug;
		        }
		    }
		 if (isset($ctl_terms_l) && array_filter($ctl_terms_l) != null) {
				 $category =json_encode($ctl_terms_l);
		    } else {
		        $category = json_encode(array('0' => 'No category'));
		    }
		    ?>
		    <!-- TinyMCE Shortcode Plugin -->
		    <script type='text/javascript'>
		        var pstd_cat_obj = {
		            'category':'<?php echo $category; ?>'
		        };
		    </script>
		    <!-- TinyMCE Shortcode Plugin -->
		    <?php
		}

    }

} // end class


