<?php
wp_enqueue_style('ep_fitness-style-64', wp_ep_fitness_URLPATH . 'assets/cube/css/cubeportfolio.css');


global $wp_roles;
global $product;
$iii=0;
$current_user = wp_get_current_user();
$roles = $current_user->roles;
$role = array_shift( $roles );

$default_fields = array();
$field_set=get_option('_ep_fitness_url_postype' );		

if($field_set!=""){ 
		$default_fields=get_option('_ep_fitness_url_postype' );
}else{															
		$default_fields['training-plans']='Training Plans';
		$default_fields['detox-plans']='Detox Plans';
		$default_fields['diet-plans']='Diet Plans';
		$default_fields['diet-guide']='Diet Guide';
		$default_fields['recipes']='Recipes';																	
}
if(sizeof($field_set)<1){																
		$default_fields['training-plans']='Training Plans';
		$default_fields['detox-plans']='Detox Plans';
		$default_fields['diet-plans']='Diet Plans';
		$default_fields['diet-guide']='Diet Guide';
		$default_fields['recipes']='Recipes';		
 }	
foreach ( $default_fields as $field_key => $field_value ) {
 $f_cpt=$field_key;
 break;
}	

$profile=(isset($_REQUEST['profile'])?$_REQUEST['profile']:$f_cpt);
$current_post_type=	$profile;	
$args = array(
	'post_type' => $profile, // enter your custom post type
	//'paged' => $paged,
	'post_status' => 'publish',
	//'fields' => 'all',
	//'orderby' => 'ASC',
	'posts_per_page'=> '9999',  // overrides posts per page in theme settings
);


$the_query = new WP_Query( $args );

$user_content= get_user_meta($current_user->ID, 'iv_user_content_setting', true);
if($user_content==''){$user_content='both_content';}



?>
   <div class="profile-content">   
	<div class="portlet light">
		  <div class="portlet-title tabbable-line clearfix">
			<div class="caption caption-md">
					  <span class="caption-subject"> 
						  <?php
									echo  $default_fields[$profile]; 
					?></span>
			</div>						
		  </div>     
                  
    <div class="clearfix">
		<?php
		$argscat = array(
						'type'                     => $current_post_type,
						'orderby'                  => 'name',
						'order'                    => 'ASC',
						'hide_empty'               => true,
						'hierarchical'             => 1,
						'exclude'                  => '',
						'include'                  => '',
						'number'                   => '',
						'taxonomy'                 => $current_post_type.'-category',
						'pad_counts'               => false

						);
					$categories = get_categories( $argscat );
		if(sizeof($categories)){
		?>
        <div id="js-filters-blog-posts" class="cbp-l-filters-list cbp-l-filters-left">
			
            <div data-filter="*" class="cbp-filter-item-active cbp-filter-item cbp-l-filters-list-first"><?php _e('All','epfitness'); ?>  (<div class="cbp-filter-counter"></div>)</div>
            <?php

					
					
					if ( $categories && !is_wp_error( $categories ) ) :
						$i=1;
						foreach ( $categories as $term ) {	?>
						<div data-filter=".<?php echo $term->slug;?>" class="cbp-filter-item <?php echo($i==sizeof($categories)?'cbp-l-filters-list-last':'');?>"><?php echo $term->name; ?> (<div class="cbp-filter-counter"></div>)</div>
						<?php
						$i++;												
						}
					endif;
				?>	
        </div>
        <?php
		}
        ?>      
    </div>

    <div id="js-grid-blog-posts" class="cbp">
		<?php
		
		if ( $the_query->have_posts() ) :
			$iii=0;
			while ( $the_query->have_posts() ) : $the_query->the_post();
				if ( function_exists('icl_object_id') ) {
					$id = icl_object_id(get_the_ID(),'page',true); 
				}else{
					$id = get_the_ID();	
				}
				$have_access='';
				$feature_img='';
				if(has_post_thumbnail()){
					$feature_image = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'medium' );
					if($feature_image[0]!=""){
						$feature_img =$feature_image[0];
					}
				}else{
					$feature_img= wp_ep_fitness_URLPATH."/assets/images/default-directory.jpg";

				}
				if(get_post_meta( $id,'_ep_post_for', true )=='role'){ 
					$package_arr=get_post_meta( $id,'_ep_postfor_package', true);					
					if(is_array($package_arr)){									
						if(in_array(strtolower($role), array_map('strtolower', $package_arr) )){	
							  if($user_content=='both_content'  OR $user_content=='package_only'){ 
								$have_access='1';
							  }else{
							   $have_access='0';
							  }					
															
						}				
					}
				}	
				if(get_post_meta( $id,'_ep_post_for', true )=='user'){ 
					
					$user_arr= array();
					if(get_post_meta( $id,'_ep_postfor_user', true )!=''){$user_arr=get_post_meta( $id,'_ep_postfor_user', true ); }
					
					if(is_array($user_arr)){		
						 if(in_array($current_user->ID, $user_arr)){
														
								if($user_content=='both_content'  OR $user_content=='specific_content'){
								$have_access='1';
							  }else{
								$have_access='0';
							  }	
								
							}
					}						
				}
				if ( class_exists( 'WooCommerce' ) ) {
					if(get_post_meta( $id,'_ep_post_for', true )=='Woocommerce'){ 
						$product_arr= array();
						if(get_post_meta( $id,'_ep_postfor_woocommerce', true )!=''){$product_arr=get_post_meta( $id,'_ep_postfor_woocommerce', true ); }
						if($user_content=='both_content'  OR $user_content=='woocommerce_content'){
							foreach($product_arr as $selected_product){										
								if( wc_customer_bought_product( $current_user->email, $current_user->ID, $selected_product ) ){												
									$have_access='1';
								}
							}	
						}												
					}
				}
				$currentCategory=wp_get_object_terms( $id, $current_post_type.'-category');
				$cat_link='';$cat_name='';$cat_slug='';
				if(isset($currentCategory)){
					if(isset($currentCategory[0]->slug)){					
						for($i=0;$i<20;$i++){
							if(isset($currentCategory[$i]->slug)){
								$cat_slug=$cat_slug.' '.(isset($currentCategory[$i]->slug) ? $currentCategory[$i]->slug :'');
							}						
						}						
						$cat_name = $currentCategory[0]->name;
						$cat_link= get_term_link($currentCategory[0], $current_post_type.'-category');
					}
				}
				
			// Check  Access
			if($have_access==1){	
				$post_content = get_post($id);					
			?>
					<div class="cbp-item <?php echo $cat_slug; ?> ">
						<a href="<?php echo'?&profile=post&id='.$post_content->ID;?>" class="cbp-caption">
							<div class="cbp-caption-defaultWrap">
								
							   <div class="image-container" style="background: url('<?php echo esc_attr($feature_img);?>') center center no-repeat; background-size: cover;">
								</div>
							</div>
							<div class="cbp-caption-activeWrap">
								<div class="cbp-l-caption-alignCenter">
									<div class="cbp-l-caption-body">
										<div class="cbp-l-caption-text"><?php _e('VIEW DETAIL','epfitness'); ?> </div>
									</div>
								</div>
							</div>
						</a>
						<a href="<?php echo'?&profile=post&id='.$post_content->ID;?>" class="cbp-l-grid-blog-title"><?php echo $post_content->post_title;?> </a>
							 <div class="cbp-l-grid-blog-desc"></div>
					</div>
      
       <?php
				$iii++;
			}
			endwhile;
	  endif;
       ?> 
    </div>
    <?php 
    
    if($iii==0){
		 _e( 'Sorry, no posts matched your criteria.','epfitness' ); 
	}
    ?> 

 </div>
</div>
	
<?php
wp_enqueue_script('ep_fitness-ar-script-23', wp_ep_fitness_URLPATH . 'assets/cube/js/jquery.cubeportfolio.min.js');
						
?>

			
<script>
	function loadBackupScript(callback) {
			var script;
			
			script = document.createElement('script');
			script.onload = callback;  
			script.src = '<?php echo wp_ep_fitness_URLPATH . 'assets/cube/js/jquery.cubeportfolio.min.js';?>';
			document.head.appendChild(script);
		}
loadBackupScript(function() { 		
	setTimeout(function(){		
	(function($, window, document, undefined) {
    //'use strict';

    // init cubeportfolio
    jQuery('#js-grid-blog-posts').cubeportfolio({
        filters: '#js-filters-blog-posts',
        search: '#js-search-blog-posts',
        defaultFilter: '*',
        animationType: '3dflip',
        gapHorizontal: 70,
        gapVertical: 30,
        gridAdjustment: 'responsive',
        mediaQueries: [{
            width: 1500,
            cols: 4,
        }, {
            width: 1100,
            cols: 4,
        }, {
            width: 800,
            cols: 3,
        }, {
            width: 480,
            cols: 2,
            options: {
                caption: '',
                gapHorizontal: 50,
                gapVertical: 20,
            }
        }, {
            width: 320,
            cols: 1,
            options: {
                caption: '',
                gapHorizontal: 50,
            }
        }],
        caption: 'revealBottom',
        displayType: 'fadeIn',
        displayTypeSpeed: 400,
    });
})(jQuery, window, document);
					
				},1000);
		
 });		

</script>				

        
    
