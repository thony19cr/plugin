<?php

global $wpdb;
	$newpost_id='';
	$post_name='ep_fitness_paypal_setting';			
	
	$row = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE post_name = '".$post_name."' ");
				if(sizeof($row )>0){
				  $newpost_id= $row->ID;
				  }	
	$paypal_mode=get_post_meta( $newpost_id,'ep_fitness_paypal_mode',true);
	$newpost_id='';
	$post_name='ep_fitness_stripe_setting';
	$row = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE post_name = '".$post_name."' ");
				if(sizeof($row )>0){
				  $newpost_id= $row->ID;
				}	
	
	$stripe_mode=get_post_meta( $newpost_id,'ep_fitness_stripe_mode',true);				
			
?>
<div class="bootstrap-wrapper">
	<div class="welcome-panel container-fluid">
		
		<div class="row">
			<div class="col-md-12">				
				<h3 class="page-header" >Payment Gateways </h3>				
				<div class="col-md-12" id="update_message">
				</div>				
			</div>
		</div>
		
		
			<div class="row">
			<div class="col-md-12">
						<div id="update_message"> </div>
					<table class="table">
					  <thead>
						<tr>
						  <th>#</th>
						  <th>Gateways Name</th>
						  <th>Mode</th>
						  <th>Status</th>
						  <th>Action</th>
						</tr>
					  </thead>
					  <tbody>
						<tr>
						  <td>1</td>
						  <td> <label>
							<input name='payment_gateway' id='payment_gateway'  type="radio" <?php echo (get_option('ep_fitness_payment_gateway')=='paypal-express')? 'checked':'' ?> value="paypal-express">
									Paypal
						  </label>
						  </td>
						  <td><?php echo strtoupper($paypal_mode); ?></td>
						  <td><?php echo (get_option('ep_fitness_payment_gateway')=='paypal-express')? 'Active':'Inactive' ?> </td>
						   <td><a class="btn btn-primary btn-xs" href="?page=wp-ep_fitness-payment-paypal"> Edit Setting</a></td>
						</tr>
						<tr>
						  <td>2</td>
						  <td>
						   <label>
						  <input name='payment_gateway' id='payment_gateway'  type="radio" <?php echo (get_option('ep_fitness_payment_gateway')=='stripe')? 'checked':'' ?>  value="stripe">
									Stripe
						  </label> </td>
						  <td><?php echo strtoupper($stripe_mode); ?></td>
						  <td><?php echo (get_option('ep_fitness_payment_gateway')=='stripe')? 'Active':'Inactive' ?></td>
						  <td> <a class="btn btn-primary btn-xs" href="?page=wp-ep_fitness-payment-stripe"> Edit Setting</a> </td>
						</tr>
						<?php
						if ( class_exists( 'WooCommerce' ) ) {
						?>
						<tr>
						  <td>3</td>
						  <td><input name='payment_gateway' id='payment_gateway'  type="radio" <?php echo (get_option('ep_fitness_payment_gateway')=='woocommerce')? 'checked':'' ?>  value="woocommerce">
									<b>WooCommerce</b> [You need to select the payment gateway from woocommerce settings]
						  </label> </td>
						  <td></td>
						  <td><?php echo (get_option('ep_fitness_payment_gateway')=='woocommerce')? 'Active':'Inactive' ?></td>
						  <td>  </td>
						</tr>
						<?php
							}
						?>
					  </tbody>
					</table>
			
		</div>
				
			
		</div>
		
	</div>
</div>
<script type="text/javascript">
jQuery(document).ready(function () {

		   jQuery("input[type='radio']").click(function(){
				iv_update_email_settings();
		   });

		});
	
function  iv_update_email_settings (){
var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
		
		var search_params = {
			"action": "ep_fitness_gateway_settings_update",
			"payment_gateway": jQuery("input[name=payment_gateway]:checked").val(),	
			
		};
		jQuery.ajax({
			url: ajaxurl,
			dataType: "json",
			type: "post",
			data: search_params,
			success: function(response) { 
				jQuery('#update_message').html('<div class="alert alert-info alert-dismissable"><a class="panel-close close" data-dismiss="alert">x</a>'+response.msg +'.</div>');
						             		
			
			}
		});
}
</script>
