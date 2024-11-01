<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
	//phpcs:disable VariableAnalysis
	// There are "undefined" variables here because they're defined in the code that includes this file as a template.
?>	
<div class="container-fluid m-0 p-0 vossle-body-wrapper">
	<div class="vossle-head">
		<div class="inner-image">
			<img src="<?php echo esc_url( plugins_url( '../assets/images/logo_white.png', __FILE__ ) ); ?>" alt="Vossle Logo" class="vossle_logo" />
		</div>
	</div>
	<div class="main-panel">
		<div class="main-panel-wrapper">
			<div class="row">
				<div class="col-12">
					<?php Vossle::display_status(); ?>
					<div class="card p-0">	
						<div class="card-header">
							<?php esc_attr_e('Vossle Configuration Page', 'vossle'); ?>
						</div>
						<div class="card-body">
							<form action="<?php echo esc_url( Vossle::get_page_url() );  ?>" method="post">
								<?php wp_nonce_field( Vossle::NONCE ) ?>
								<input type="hidden" name="action" value="enter-key">
								<h5 class="card-title"><?php esc_attr_e( 'API Configuration' , 'vossle' ); ?></h5>
								<label for="basic-url" class="form-label"><?php esc_attr_e('Your API Key', 'vossle'); ?></label>
								<div class="input-group mb-3">
									<span class="input-group-text" id="basic-addon3"><b>Enter API Key</b></span>
									<input type="text" class="form-control" name="key" id="basic-url" aria-describedby="basic-addon3" value="<?php echo Vossle::get_api_key();   ?>" />
								</div></br>

								<h5 class="card-title"><?php esc_attr_e( 'Activate Woocommerce to enable Vossle AR' , 'vossle' ); ?></h5>
								<?php $disabled_vossle_woo = 'disabled'; 
										if(in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))){ 
											$disabled_vossle_woo = '';
											$checked_btn = get_option('vos_tryon_button');
											$checked = '';
											$position_g = '';
											$position_ac = 'checked';
											$position_bc = '';
											$checked_position = get_option('vos_tryon_button_position');
											if($checked_btn == 'yes'){ $checked = 'checked'; }
											if($checked_position){
												$position_ac = '';
											if($checked_position == 'below_gallary'){ $position_g = 'checked'; }
											if($checked_position == 'above_cart'){ $position_ac = 'checked'; }
											if($checked_position == 'below_cart'){ $position_bc = 'checked'; }}
									?>
									<div class="input-group mb-3">
										<span class="input-group-text" id="basic-addon4"><b>Enable AR button</b></span>
										<input type="checkbox" class="form-control" name="vos_tryon_button" id="vos_tryon_button" aria-describedby="basic-addon4" value="yes" <?php echo esc_html($checked); echo esc_html($disabled_vossle_woo); ?>/> Unchecking the checkbox will hide the Vossle AR button on product page.
									</div></br>
									<div class="input-group mb-3">
										<span class="input-group-text" id="basic-addon5"><b>Title for AR button</b></span>
										<input type="text" class="form-control" name="vos_tryon_button_title" id="vos_tryon_button_title" aria-describedby="basic-addon5" value="<?php echo get_option('vos_tryon_button_title') ?>" <?php echo esc_html($disabled_vossle_woo); ?>/>
									</div></br>
									<div class="input-group mb-3">
										<span class="input-group-text" id="basic-addon4"><b>Set Position for AR button</b></span>
										<input type="radio" class="form-control" name="vos_tryon_button_position" id="vos_tryon_button_position1" aria-describedby="basic-addon4" value="below_gallary" <?php echo esc_html($position_g); echo esc_html($disabled_vossle_woo); ?>/> Below Product Gallary
										<input type="radio" class="form-control" name="vos_tryon_button_position" id="vos_tryon_button_position2" aria-describedby="basic-addon4" value="above_cart" <?php echo esc_html($position_ac); echo esc_html($disabled_vossle_woo); ?>/> Above Add to cart button
										<input type="radio" class="form-control" name="vos_tryon_button_position" id="vos_tryon_button_position3" aria-describedby="basic-addon4" value="below_cart" <?php echo esc_html($position_bc); echo esc_html($disabled_vossle_woo); ?>/> Below Add to cart button
									</div></br>
								<?php  } ?>
								
								<div class="d-grid gap-2 col-3 mx-auto">
									<button class="btn btn-outline-primary" type="submit"><?php esc_attr_e( 'Submit' , 'vossle' ); ?></button>
								</div>
							</form>
							<h5 class="card-title"><?php esc_attr_e( 'How to use the Vossle AR plugin:' , 'vossle' ); ?></h5>
							<ol>
								<li>Install the WooCommerce and Vossle plugin. Activate the Vossle plugin by the WordPress API key available on Vossle’s dashboard.</li>
								<li>Go to the WooCommerce setting in the Vossle plugin. Enable the AR button and other configurations. </li>
								<li>Click on the “Add Experience” option and choose the product.<b> You can either create a new experience or choose an existing one and submit the form. </b></li>
								<li>Click on the “View in AR” button on the product detail page, a QR code and link to the experience are available now. You can also edit and delete the experience with Vossle Plugin and hide the “View in AR” button from the Vossle settings.</li>
							</ol>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>