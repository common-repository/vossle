<?php

class Vossle
{
	const NONCE = 'vossle-enter-api';
	const API_HOST = 'https://api.vossle.com/api/';
	const API_PORT = 80;
	const MAX_DELAY_BEFORE_MODERATION_EMAIL = 86400; // One day in seconds

	private static $initiated = false;
	private static $notices   = array();
	private static $allowed   = array(
		'a' => array(
			'href' => true,
			'title' => true,
		),
		'b' => array(),
		'code' => array(),
		'del' => array(
			'datetime' => true,
		),
		'em' => array(),
		'i' => array(),
		'q' => array(
			'cite' => true,
		),
		'strike' => array(),
		'strong' => array(),
	);

	public static function init()
	{
		if (!self::$initiated) {
			self::init_hooks();
		}

		if (isset($_POST['action']) && sanitize_text_field($_POST['action']) == 'enter-key') {
			self::enter_api_key();
		}
	}

	/**
	 * Initializes WordPress hooks
	 */
	private static function init_hooks()
	{
		self::$initiated = true;

		add_action('wp_ajax_vossle_check_experience_name', array('Vossle', 'vossle_check_experience_name'));
		add_action('wp_ajax_vossle_remove_ar_experience', array('Vossle', 'vossle_remove_ar_experience'));
		add_action('wp_ajax_vossle_add_ar_experience', array('Vossle', 'vossle_add_ar_experience'));
		add_action('wp_ajax_vossle_edit_ar_experience', array('Vossle', 'vossle_edit_ar_experience'));
	}

	public static function vossle_check_experience_name()
	{
		if (current_user_can('edit_others_posts')) {
			if (!wp_verify_nonce(sanitize_text_field($_POST['nonce_ajax']), self::NONCE))
				return false;
			$text = sanitize_text_field($_POST['text']);
			$slug = sanitize_text_field($_POST['slug']);

			// $url = 'https://dashboard.vossle.com/API/checkExperienceName';
			$http_host = self::API_HOST;
			$url = "{$http_host}checkExpName";

			$args = array(
				'body' => array('slug_exp_name' => $text, 'slug' => $slug)
			);

			$response = wp_remote_post($url, $args);

			$body = wp_remote_retrieve_body($response);
			wp_send_json($body, 200);
		}
	}

	public static function vossle_edit_ar_experience()
	{
		if (current_user_can('edit_others_posts')) {
			if (!wp_verify_nonce(sanitize_text_field($_POST['_wpnonce']), self::NONCE))
				return false;
			// $url = 'https://dashboard.vossle.com/API/edit';
			$http_host = self::API_HOST;
			$url = "{$http_host}update";
			$product_id = sanitize_text_field($_POST['product_id']);
			$exp_id = sanitize_text_field($_POST['exp_id']);
			$vos_product_meta = sanitize_text_field($_POST['vos_product_meta']);
			$exp_type = sanitize_text_field($_POST['exp_type']);
			if ($exp_id && $product_id) {
				global $wpdb;
				$slug_url = VOSSLE__SERVER_URL . $exp_id;
				//update_post_meta( $product_id, 'vossle_url', $slug_url, true );
				$dbData = array();
				$dbData['post_id'] = $product_id;
				$dbData['meta_value'] = $slug_url;
				$wpdb->update('wp_postmeta', $dbData, array('meta_id' => $vos_product_meta));
			}

			$content_folder = plugin_dir_path(__FILE__) . 'bucket/content/';
			$animation_file_folder = plugin_dir_path(__FILE__) . 'bucket/content/';
			$image_file_folder = plugin_dir_path(__FILE__) . 'bucket/content/';
			$audio_folder = plugin_dir_path(__FILE__) . 'bucket/content/';
			$sound_folder = plugin_dir_path(__FILE__) . 'bucket/content/';
			$tossing_model_folder = plugin_dir_path(__FILE__) . 'bucket/content/';
			$marker_image_folder = plugin_dir_path(__FILE__) . 'bucket/content/';
			$tossing_image_modal = plugin_dir_path(__FILE__) . 'bucket/content/';
			$local_file = [];

			//content
			if (isset($_FILES['three_d_model_file']['name']) && !empty($_FILES['three_d_model_file']['name'])) {
				         if ($_FILES["three_d_model_file"]["size"] > 5000000) {
                             wp_send_json(array('status' => 'failed', 'message' => 'File is too large.'), 200);
                          }
				$file_name = sanitize_file_name($_FILES['three_d_model_file']['name']);
				$ext = pathinfo($file_name, PATHINFO_EXTENSION);
				if (!in_array($ext, array('glb', 'gltf', 'zip', 'fbx', 'jpg', 'png', 'jpeg', 'mp4', 'm4v'))) {
					wp_send_json(array('status' => 'failed', 'message' => 'File format invalid.'), 200);
				} else {
					chmod($_FILES["three_d_model_file"]["tmp_name"], 0644);
					move_uploaded_file($_FILES['three_d_model_file']['tmp_name'], $content_folder . $file_name);
					$content_path = $content_folder . $file_name;
					$local_file['content'] = $content_path; //path to a local file on your server
				}
			}
			if ($exp_type == 1) {
				if (isset($_FILES['marker_image']['name']) && !empty($_FILES['marker_image'])) {
					     if ($_FILES["marker_image"]["size"] > 5000000) {
                             wp_send_json(array('status' => 'failed', 'message' => 'File is too large.'), 200);
                          }
					$marker_file_name = sanitize_file_name($_FILES['marker_image']['name']);
					$markerext = pathinfo($marker_file_name, PATHINFO_EXTENSION);
					if (!in_array($markerext, array('png'))) {
						wp_send_json(array('status' => 'failed', 'message' => 'File format invalid.'), 200);
					} else {
						    chmod($_FILES["marker_image"]["tmp_name"], 0644);
						move_uploaded_file($_FILES['marker_image']['tmp_name'], $marker_image_folder . $marker_file_name);
						$marker_image_path = $marker_image_folder . $marker_file_name;
						$local_file['marker_image'] = $marker_image_path;
					}
				}
			}
			//background image

			//tossing_model
			if (isset($_FILES['tossing_model']['name']) && !empty($_FILES['tossing_model']['name'])) {
				     if ($_FILES["tossing_model"]["size"] > 5000000) {
                             wp_send_json(array('status' => 'failed', 'message' => 'File is too large.'), 200);
                          }

				$tossing_file_name = sanitize_file_name($_FILES['tossing_model']['name']);
				$tossext = pathinfo($tossing_file_name, PATHINFO_EXTENSION);
				if (!in_array($tossext, array('glb', 'gltf'))) {
					wp_send_json(array('status' => 'failed', 'message' => 'File format invalid.'), 200);
				} else {
					   chmod($_FILES["tossing_model"]["tmp_name"], 0644);
					move_uploaded_file($_FILES['tossing_model']['tmp_name'], $tossing_model_folder . $tossing_file_name);
					$tossing_modal_path = $tossing_model_folder . $tossing_file_name;
					$local_file['tossing_model'] = $tossing_modal_path; //path to a local file on your server
				}
			}

			// tossing modal image
			if (isset($_FILES['tossing_image_modal']['name']) && !empty($_FILES['tossing_image_modal']['name'])) {
				      if ($_FILES["tossing_image_modal"]["size"] > 5000000) {
                             wp_send_json(array('status' => 'failed', 'message' => 'File is too large.'), 200);
                          }
				$tossing_image_modal_name = sanitize_file_name($_FILES['tossing_image_modal']['name']);
				$tossimgeext = pathinfo($tossing_image_modal_name, PATHINFO_EXTENSION);
				if (!in_array($tossimgeext, array('png', 'jpeg', 'jpg'))) {
					wp_send_json(array('status' => 'failed', 'message' => 'File format invalid.'), 200);
				} else {
					    chmod($_FILES["tossing_image_modal"]["tmp_name"], 0644);
					move_uploaded_file($_FILES['tossing_image_modal']['tmp_name'], $tossing_image_modal . $tossing_image_modal_name);
					$tossing_image_modal_path = $tossing_image_modal . $tossing_image_modal_name;
					$local_file['tossing_image_modal'] = $tossing_image_modal_path;
				}
			}

			if (isset($_FILES['animation_file']['name']) && !empty($_FILES['animation_file']['name'])) {
				      if ($_FILES["animation_file"]["size"] > 5000000) {
                             wp_send_json(array('status' => 'failed', 'message' => 'File is too large.'), 200);
                          }
				$splash_image_name = sanitize_file_name($_FILES['animation_file']['name']);
				$splashimgeext = pathinfo($splash_image_name, PATHINFO_EXTENSION);
				if (!in_array($splashimgeext, array('png', 'jpeg', 'jpg'))) {
					wp_send_json(array('status' => 'failed', 'message' => 'File format invalid.'), 200);
				} else {
					   chmod($_FILES["animation_file"]["tmp_name"], 0644);
					move_uploaded_file($_FILES['animation_file']['tmp_name'], $animation_file_folder . $splash_image_name);
					$splash_image_path = $animation_file_folder . $splash_image_name;
					$local_file['splash_screen'] = $splash_image_path;
				}
			}
			// audio file
			if (isset($_FILES['audio']['name']) && !empty($_FILES['audio']['name'])) {
				   if ($_FILES["audio"]["size"] > 5000000) {
                             wp_send_json(array('status' => 'failed', 'message' => 'File is too large.'), 200);
                          }

				$audio_name = sanitize_file_name($_FILES['audio']['name']);
				$audioext = pathinfo($audio_name, PATHINFO_EXTENSION);
				if (!in_array($audioext, array('mp3'))) {
					wp_send_json(array('status' => 'failed', 'message' => 'File format invalid.'), 200);
				} else {
					   chmod($_FILES["audio"]["tmp_name"], 0644);
					move_uploaded_file($_FILES['audio']['tmp_name'], $audio_folder . $audio_name);
					$audio_folder_path = $audio_folder . $audio_name;
					$local_file['audio'] = $audio_folder_path;
				}
			}
			//sound file
			if (isset($_FILES['sound']['name']) && !empty($_FILES['sound']['name'])) {
				     if ($_FILES["sound"]["size"] > 5000000) {
                             wp_send_json(array('status' => 'failed', 'message' => 'File is too large.'), 200);
                          }
				$sound_name = sanitize_file_name($_FILES['sound']['name']);
				$soundext = pathinfo($sound_name, PATHINFO_EXTENSION);
				if (!in_array($soundext, array('mp3'))) {
					wp_send_json(array('status' => 'failed', 'message' => 'File format invalid.'), 200);
				} else {
					   chmod($_FILES["sound"]["tmp_name"], 0644);
					move_uploaded_file($_FILES['sound']['tmp_name'], $sound_folder . $sound_name);
					$sound_folder_path = $sound_folder . $sound_name;
					$local_file['sound'] = $sound_folder_path;
				}
			}

			$post_fields = [];
			//background image
			if (isset($_FILES['background_image']['name']) && !empty($_FILES['background_image']['name'])) {
				     if ($_FILES["background_image"]["size"] > 5000000) {
                             wp_send_json(array('status' => 'failed', 'message' => 'File is too large.'), 200);
                          }

				$background_image_name = sanitize_file_name($_FILES['background_image']['name']);
				$backgroundimgeext = pathinfo($background_image_name, PATHINFO_EXTENSION);
				if (!in_array($backgroundimgeext, array('png', 'jpeg', 'jpg'))) {
					wp_send_json(array('status' => 'failed', 'message' => 'File format invalid.'), 200);
				} else {
					  chmod($_FILES["background_image"]["tmp_name"], 0644);
					move_uploaded_file($_FILES['background_image']['tmp_name'], $animation_file_folder . $background_image_name);
					$background_image_path = $animation_file_folder . $background_image_name;
					$local_file['background_image'] = $background_image_path;
				}
			} else if ($_POST['exp_type'] == 16 && isset($_POST['background_colour'])) {
				$post_fields['background_colour'] = sanitize_text_field($_POST['background_colour']);
			}
			if (isset($_POST['exp_type'])) {
				$post_fields['exp_type_id'] = sanitize_text_field($_POST['exp_type']);
				$post_fields['exp_type_m'] = sanitize_text_field($_POST['exp_type_m']);
				$post_fields['detection_point'] = sanitize_text_field($_POST['detection_point']);
				$post_fields['ar_id'] = sanitize_text_field($_POST['ar_id']);
				$post_fields['feedback_contents'] = sanitize_text_field($_POST['feedback_contents']);
				$post_fields['background_color'] = sanitize_text_field($_POST['button_color']);
				$post_fields['content_file_type'] = sanitize_text_field($_POST['content_file_type']);
				$post_fields['enable_splash'] = sanitize_text_field($_POST['enable_splash']);
				$post_fields['action'] = sanitize_text_field($_POST['action']);
				$post_fields['content_audio_set'] = sanitize_text_field($_POST['content_audio_set']);
				$post_fields['redirection_button_colour'] = sanitize_text_field($_POST['redirection_button_colour']);
				$post_fields['bullet_frq'] = sanitize_text_field($_POST['bullet_frq']);
				$post_fields['game_duration'] = sanitize_text_field($_POST['game_duration']);
				$post_fields['feedbackForm_game'] = sanitize_text_field($_POST['feedbackForm_game']);
				$post_fields['light_range'] = sanitize_text_field($_POST['resizing']);
				$post_fields['label'] = sanitize_text_field($_POST['label']);
				$post_fields['custom_camera_enabled'] = sanitize_text_field($_POST['custom_camera_enabled']);
				$post_fields['website'] = sanitize_text_field($_POST['about']);
				$post_fields['text_to_display'] = sanitize_text_field($_POST['text_to_display']);
				$post_fields['user_id'] = sanitize_text_field($_POST['client_id']);
				$post_fields['place_head'] = sanitize_text_field($_POST['place_head']);
				$post_fields['transparent_background'] = sanitize_text_field($_POST['transparent_background']);
				$post_fields['startAudio'] = sanitize_text_field($_POST['startAudio']);
				$post_fields['redirect_to_website'] = sanitize_text_field($_POST['redirect_to_website']);
				$post_fields['redirection_time'] = sanitize_text_field($_POST['redirection_time']);
				$post_fields['ga_code_exp'] = sanitize_text_field($_POST['ga_code_exp']);
				$enable_geolocation = sanitize_text_field($_POST['enable_geolocation']);
				if ($enable_geolocation) {
					$post_fields['latitude'] = sanitize_text_field($_POST['latitude']);
					$post_fields['longitude'] = sanitize_text_field($_POST['longitude']);
					$post_fields['distance'] = sanitize_text_field($_POST['distance']);
				}
				$post_fields['face_game_type'] = sanitize_text_field($_POST['face_game_type']);
				$post_fields['image_speed'] = sanitize_text_field($_POST['image_speed']);
				$post_fields['imagename'] = sanitize_text_field($_POST['imagename']);
				$post_fields['sub_category'] = sanitize_text_field($_POST['sub_game_category']);
				$post_fields['sub_game_category'] = $post_fields['sub_category'];
				if ($post_fields['sub_category'] == 'lipstick_tryon') {
					for ($i = 1; $i <= 6; $i++) {
						if (isset($_POST['lipstick_colour_' . $i])) {
							$post_fields['lipstick_colour_' . $i] =  sanitize_text_field($_POST['lipstick_colour_' . $i]);
						}
					}
				}
				if ($post_fields['sub_category'] == 'face_filter') $post_fields['exp_subtype_id'] = 23;
				if ($post_fields['sub_category'] == 'world_mapping') $post_fields['exp_subtype_id'] = 24;
				if ($post_fields['sub_category'] == '3d_ads') $post_fields['exp_subtype_id'] = 25;
				if ($post_fields['sub_category'] == 'lipstick_tryon') $post_fields['exp_subtype_id'] = 12;
				if ($post_fields['sub_category'] == 'watch_tryon') $post_fields['exp_subtype_id'] = 13;
				if ($post_fields['sub_category'] == 'facedetection') $post_fields['exp_subtype_id'] = 14;
				if ($post_fields['sub_category'] == 'ring_tryon') $post_fields['exp_subtype_id'] = 15;
				if ($post_fields['sub_category'] == 'palm_tryon') $post_fields['exp_subtype_id'] = 26;
				if ($post_fields['sub_category'] == 'toss') $post_fields['exp_subtype_id'] = 7;
				if ($post_fields['sub_category'] == 'dodge_collect') $post_fields['exp_subtype_id'] = 8;
				if ($post_fields['sub_category'] == 'shooter') $post_fields['exp_subtype_id'] = 9;
				if ($post_fields['sub_category'] == 'face_game') {
					$post_fields['exp_subtype_id'] = 10;
					for ($i = 2; $i <= 11; $i++) {
						$post_fields['imagename' . $i] = sanitize_text_field($_POST['imagename' . $i]);
						if (isset($_FILES['imagefile' . $i]['name']) && !empty($_FILES['imagefile' . $i]['name'])) {
							$file_name = sanitize_file_name($_FILES['imagefile' . $i]['name']);
							$ext = pathinfo($file_name, PATHINFO_EXTENSION);
							move_uploaded_file($_FILES['imagefile' . $i]['tmp_name'], $content_folder . $file_name);
							$content_path = $content_folder . $file_name;
							$local_file['imagefile' . $i] = $content_path;
						}
					}
				}
				if ($post_fields['sub_category'] == 'treasure_hunt') {
					$post_fields['exp_subtype_id'] = 11;
					for ($i = 2; $i <= 10; $i++) {
						if (isset($_FILES['imagefile' . $i]['name']) && !empty($_FILES['imagefile' . $i]['name'])) {
							$file_name = sanitize_file_name($_FILES['imagefile' . $i]['name']);
							$ext = pathinfo($file_name, PATHINFO_EXTENSION);
							move_uploaded_file($_FILES['imagefile' . $i]['tmp_name'], $content_folder . $file_name);
							$content_path = $content_folder . $file_name;
							$local_file['imagefile' . $i] = $content_path;
						}
					}
				}
			}

			$boundary = wp_generate_password(24);
			$headers  = array(
				'content-type' => 'multipart/form-data; boundary=' . $boundary,
			);
			$payload = '';
			// First, add the standard POST fields:
			foreach ($post_fields as $name => $value) {
				$payload .= '--' . $boundary;
				$payload .= "\r\n";
				$payload .= 'Content-Disposition: form-data; name="' . $name .
					'"' . "\r\n\r\n";
				$payload .= $value;
				$payload .= "\r\n";
			}

			// Upload the file
			if (!empty($local_file)) {
				foreach ($local_file as $filekey => $filevalue) {
					$payload .= '--' . $boundary;
					$payload .= "\r\n";
					$payload .= 'Content-Disposition: form-data; name="' . $filekey .
						'"; filename="' . basename($filevalue) . '"' . "\r\n";
					// $payload .= 'Content-Type: image/jpeg' . "\r\n";
					$payload .= "\r\n";
					$payload .= file_get_contents($filevalue);
					$payload .= "\r\n";
				}
			}
			$payload .= '--' . $boundary . '--';

			$response = wp_remote_post(
				$url,
				array(
					'headers'    => $headers,
					'body'       => $payload,
				)
			);


			if (is_wp_error($response)) {
				$error_message = $response->get_error_message();
				// echo "Something went wrong: $error_message";
				wp_send_json(array('status' => 'failed', 'message' => 'Something went wrong:' . $error_message), 200);
			} else {
				$body = wp_remote_retrieve_body($response);
				if (!empty($local_file)) {
					foreach ($local_file as $filekeys => $filevalues) {
						if (file_exists($filevalues)) {
							unlink($filevalues);
						}
					}
				}
				wp_send_json($body, 200);
			}
		}
	}

	public static function vossle_add_ar_experience()
	{
		if (current_user_can('edit_others_posts')) {
			if (!wp_verify_nonce(sanitize_text_field($_POST['_wpnonce']), self::NONCE))
				return false;
			$exp_type = sanitize_text_field($_POST['exp_type']);
			$product_id = sanitize_text_field($_POST['product_id']);
			$exp_id = sanitize_text_field($_POST['exp_id']);

			if ($exp_id) {
				$slug_url = VOSSLE__SERVER_URL . $exp_id;
				add_post_meta($product_id, 'vossle_url', $slug_url, true);
				$body = array('status' => 'success', 'message' => 'Saved Success.', 'ar' => '', 'qr_code' => true, 'slug_url' => $exp_id, 'marker_image' => '', 'data' => 'Saved Success');
				wp_send_json($body, 200);
			} else {
				$http_host = self::API_HOST;
				$url = "{$http_host}ar";

				$content_folder = plugin_dir_path(__FILE__) . 'bucket/content/';
				$animation_file_folder = plugin_dir_path(__FILE__) . 'bucket/content/';
				$image_file_folder = plugin_dir_path(__FILE__) . 'bucket/content/';
				$audio_folder = plugin_dir_path(__FILE__) . 'bucket/content/';
				$sound_folder = plugin_dir_path(__FILE__) . 'bucket/content/';
				$tossing_model_folder = plugin_dir_path(__FILE__) . 'bucket/content/';
				$marker_image_folder = plugin_dir_path(__FILE__) . 'bucket/content/';
				$tossing_image_modal = plugin_dir_path(__FILE__) . 'bucket/content/';
				$local_file = [];
				//content

				if (isset($_FILES['three_d_model_file']['name']) && !empty($_FILES['three_d_model_file']['name'])) {
					    if ($_FILES["three_d_model_file"]["size"] > 5000000) {
                             wp_send_json(array('status' => 'failed', 'message' => 'File is too large.'), 200);
                          }
					$file_name = sanitize_file_name($_FILES['three_d_model_file']['name']);
					$ext = pathinfo($file_name, PATHINFO_EXTENSION);
					if (!in_array($ext, array('glb', 'gltf', 'zip', 'fbx', 'jpg', 'png', 'jpeg', 'mp4', 'm4v'))) {
						wp_send_json(array('status' => 'failed', 'message' => 'File format invalid.'), 200);
					} else {
						chmod($_FILES["three_d_model_file"]["tmp_name"], 0644);
						move_uploaded_file($_FILES['three_d_model_file']['tmp_name'], $content_folder . $file_name);
						$content_path = $content_folder . $file_name;
						$local_file['content'] = $content_path; //path to a local file on your server
					}
				}


				//marker_image
				if ($exp_type == 1) {
					if (isset($_FILES['marker_image']['name']) && !empty($_FILES['marker_image'])) {
						 if ($_FILES["marker_image"]["size"] > 5000000) {
                             wp_send_json(array('status' => 'failed', 'message' => 'File is too large.'), 200);
                          }
						$marker_file_name = sanitize_file_name($_FILES['marker_image']['name']);
						$markerext = pathinfo($marker_file_name, PATHINFO_EXTENSION);
						if (!in_array($markerext, array('png'))) {
							wp_send_json(array('status' => 'failed', 'message' => 'File format invalid.'), 200);
						} else {
							chmod($_FILES["marker_image"]["tmp_name"], 0644);
							move_uploaded_file($_FILES['marker_image']['tmp_name'], $marker_image_folder . $marker_file_name);
							$marker_image_path = $marker_image_folder . $marker_file_name;
							$local_file['marker_image'] = $marker_image_path;
						}
					}
				}
				//tossing_model
				if (isset($_FILES['tossing_model']['name']) && !empty($_FILES['tossing_model']['name'])) {
					 if ($_FILES["tossing_model"]["size"] > 5000000) {
                             wp_send_json(array('status' => 'failed', 'message' => 'File is too large.'), 200);
                          }
					$tossing_file_name = sanitize_file_name($_FILES['tossing_model']['name']);
					$tossext = pathinfo($tossing_file_name, PATHINFO_EXTENSION);
					if (!in_array($tossext, array('glb', 'gltf'))) {
						wp_send_json(array('status' => 'failed', 'message' => 'File format invalid.'), 200);
					} else {
						chmod($_FILES["tossing_model"]["tmp_name"], 0644);
						move_uploaded_file($_FILES['tossing_model']['tmp_name'], $tossing_model_folder . $tossing_file_name);
						$tossing_modal_path = $tossing_model_folder . $tossing_file_name;
						$local_file['tossing_model'] = $tossing_modal_path; //path to a local file on your server
					}
				}
				// tossing modal image
				if (isset($_FILES['tossing_image_modal']['name']) && !empty($_FILES['tossing_image_modal']['name'])) {
					   if ($_FILES["tossing_image_modal"]["size"] > 5000000) {
                             wp_send_json(array('status' => 'failed', 'message' => 'File is too large.'), 200);
                          }
					$tossing_image_modal_name = sanitize_file_name($_FILES['tossing_image_modal']['name']);
					$tossimgeext = pathinfo($tossing_image_modal_name, PATHINFO_EXTENSION);
					if (!in_array($tossimgeext, array('png', 'jpeg', 'jpg'))) {
						wp_send_json(array('status' => 'failed', 'message' => 'File format invalid.'), 200);
					} else {
						chmod($_FILES["tossing_image_modal"]["tmp_name"], 0644);
						move_uploaded_file($_FILES['tossing_image_modal']['tmp_name'], $tossing_image_modal . $tossing_image_modal_name);
						$tossing_image_modal_path = $tossing_image_modal . $tossing_image_modal_name;
						$local_file['tossing_image_modal'] = $tossing_image_modal_path;
					}
				}
				//splash image
				if (isset($_FILES['animation_file']['name']) && !empty($_FILES['animation_file']['name'])) {
					    if ($_FILES["animation_file"]["size"] > 5000000) {
                             wp_send_json(array('status' => 'failed', 'message' => 'File is too large.'), 200);
                          }
					$splash_image_name = sanitize_file_name($_FILES['animation_file']['name']);
					$splashimgeext = pathinfo($splash_image_name, PATHINFO_EXTENSION);
					if (!in_array($splashimgeext, array('png', 'jpeg', 'jpg'))) {
						wp_send_json(array('status' => 'failed', 'message' => 'File format invalid.'), 200);
					} else {
						chmod($_FILES["animation_file"]["tmp_name"], 0644);
						move_uploaded_file($_FILES['animation_file']['tmp_name'], $animation_file_folder . $splash_image_name);
						$splash_image_path = $animation_file_folder . $splash_image_name;
						$local_file['splash_screen'] = $splash_image_path;
					}
				}

				// audio file
				if (isset($_FILES['audio']['name']) && !empty($_FILES['audio']['name'])) {
					 if ($_FILES["audio"]["size"] > 5000000) {
                             wp_send_json(array('status' => 'failed', 'message' => 'File is too large.'), 200);
                          }
					$audio_name = sanitize_file_name($_FILES['audio']['name']);
					$audioext = pathinfo($audio_name, PATHINFO_EXTENSION);
					if (!in_array($audioext, array('mp3'))) {
						wp_send_json(array('status' => 'failed', 'message' => 'File format invalid.'), 200);
					} else {
						chmod($_FILES["audio"]["tmp_name"], 0644);
						move_uploaded_file($_FILES['audio']['tmp_name'], $audio_folder . $audio_name);
						$audio_folder_path = $audio_folder . $audio_name;
						$local_file['audio'] = $audio_folder_path;
					}
				}
				//sound file
				if (isset($_FILES['sound']['name']) && !empty($_FILES['sound']['name'])) {
					    if ($_FILES["sound"]["size"] > 5000000) {
                             wp_send_json(array('status' => 'failed', 'message' => 'File is too large.'), 200);
                          }

					$sound_name = sanitize_file_name($_FILES['sound']['name']);
					$soundext = pathinfo($sound_name, PATHINFO_EXTENSION);
					if (!in_array($soundext, array('mp3'))) {
						wp_send_json(array('status' => 'failed', 'message' => 'File format invalid.'), 200);
					} else {
						  chmod($_FILES["sound"]["tmp_name"], 0644);
						move_uploaded_file($_FILES['sound']['tmp_name'], $sound_folder . $sound_name);
						$sound_folder_path = $sound_folder . $sound_name;
						$local_file['sound'] = $sound_folder_path;
					}
				}

				$post_fields = [];
				//background image
				if (isset($_FILES['background_image']['name']) && !empty($_FILES['background_image']['name'])) {
					            if ($_FILES["background_image"]["size"] > 5000000) {
                                       wp_send_json(array('status' => 'failed', 'message' => 'File is too large.'), 200);
                                  }

					$background_image_name = sanitize_file_name($_FILES['background_image']['name']);
					$backgroundimgeext = pathinfo($background_image_name, PATHINFO_EXTENSION);
					if (!in_array($backgroundimgeext, array('png', 'jpeg', 'jpg'))) {
						wp_send_json(array('status' => 'failed', 'message' => 'File format invalid.'), 200);
					} else {
						      chmod($_FILES["background_image"]["tmp_name"], 0644);
						move_uploaded_file($_FILES['background_image']['tmp_name'], $animation_file_folder . $background_image_name);
						$background_image_path = $animation_file_folder . $background_image_name;
						$local_file['background_image'] = $background_image_path;
					}
				} else if ($_POST['exp_type'] == 16 && isset($_POST['background_colour'])) {
					$post_fields['background_colour'] = sanitize_text_field($_POST['background_colour']);
				}
				if (isset($_POST['exp_type'])) {
					$post_fields['exp_type'] = sanitize_text_field($_POST['exp_type']);
					$post_fields['exp_name'] = sanitize_text_field($_POST['exp_name']);
					$post_fields['sub_category'] = sanitize_text_field($_POST['sub_category']);
					$post_fields['detection_point'] = sanitize_text_field($_POST['detection_point']);
					$post_fields['feedback_contents'] = sanitize_text_field($_POST['feedback_contents']);
					$post_fields['background_color'] = sanitize_text_field($_POST['button_color']);
					$post_fields['action'] = sanitize_text_field($_POST['action']);
					$post_fields['content_audio_set'] = sanitize_text_field($_POST['content_audio_set']);
					$post_fields['redirection_button_colour'] = sanitize_text_field($_POST['redirection_button_colour']);
					$post_fields['light_range'] = sanitize_text_field($_POST['light_range']);
					$post_fields['label'] = sanitize_text_field($_POST['label']);
					$post_fields['bullet_frq'] = sanitize_text_field($_POST['bullet_frq']);
					$post_fields['game_duration'] = sanitize_text_field($_POST['game_duration']);
					$post_fields['feedbackForm_game'] = sanitize_text_field($_POST['feedbackForm_game']);
					$post_fields['custom_camera_enabled'] = sanitize_text_field($_POST['custom_camera_enabled']);
					$post_fields['website'] = sanitize_text_field($_POST['about']);
					$post_fields['text_to_display'] = sanitize_text_field($_POST['text_to_display']);
					$post_fields['user_id'] = sanitize_text_field($_POST['client_id']);
					$post_fields['place_head'] = sanitize_text_field($_POST['place_head']);
					$post_fields['qrCodeLogo'] = sanitize_text_field($_POST['qrCodeLogo']);
					$post_fields['slug_exp_name'] = sanitize_text_field($_POST['slug_exp_name']);
					$post_fields['redirect_to_website'] = sanitize_text_field($_POST['redirect_to_website']);
					if ($post_fields['redirect_to_website']) {
						$post_fields['redirection_time'] = sanitize_text_field($_POST['redirection_time']);
					}
					$post_fields['ga_code_exp'] = sanitize_text_field($_POST['ga_code_exp']);
					$enable_geolocation = sanitize_text_field($_POST['enable_geolocation']);
					if ($enable_geolocation) {
						$post_fields['latitude'] = sanitize_text_field($_POST['latitude']);
						$post_fields['longitude'] = sanitize_text_field($_POST['longitude']);
						$post_fields['distance'] = sanitize_text_field($_POST['distance']);
					}
					$post_fields['face_game_type'] = sanitize_text_field($_POST['face_game_type']);
					$post_fields['image_speed'] = sanitize_text_field($_POST['image_speed']);
					$post_fields['imagename'] = sanitize_text_field($_POST['imagename']);
					$post_fields['sub_game_category'] = $post_fields['sub_category'];
					if ($post_fields['sub_category'] == 'lipstick_tryon') {
						for ($i = 1; $i <= 6; $i++) {
							if (isset($_POST['lipstick_colour_' . $i])) {
								$post_fields['lipstick_colour_' . $i] =  sanitize_text_field($_POST['lipstick_colour_' . $i]);
							}
						}
					}
					if ($post_fields['sub_category'] == 'face_filter') $post_fields['exp_subtype_id'] = 23;
					if ($post_fields['sub_category'] == 'world_mapping') $post_fields['exp_subtype_id'] = 24;
					if ($post_fields['sub_category'] == '3d_ads') $post_fields['exp_subtype_id'] = 25;
					if ($post_fields['sub_category'] == 'lipstick_tryon') $post_fields['exp_subtype_id'] = 12;
					if ($post_fields['sub_category'] == 'watch_tryon') $post_fields['exp_subtype_id'] = 13;
					if ($post_fields['sub_category'] == 'facedetection') $post_fields['exp_subtype_id'] = 14;
					if ($post_fields['sub_category'] == 'ring_tryon') $post_fields['exp_subtype_id'] = 15;
					if ($post_fields['sub_category'] == 'palm_tryon') $post_fields['exp_subtype_id'] = 26;
					if ($post_fields['sub_category'] == 'toss') $post_fields['exp_subtype_id'] = 7;
					if ($post_fields['sub_category'] == 'dodge_collect') $post_fields['exp_subtype_id'] = 8;
					if ($post_fields['sub_category'] == 'shooter') $post_fields['exp_subtype_id'] = 9;
					if ($post_fields['sub_category'] == 'face_game') {
						$post_fields['exp_subtype_id'] = 10;
						for ($i = 2; $i <= 11; $i++) {
							$post_fields['imagename' . $i] = sanitize_text_field($_POST['imagename' . $i]);
							$file_name = sanitize_file_name($_FILES['imagefile' . $i]['name']);
							$ext = pathinfo($file_name, PATHINFO_EXTENSION);
							move_uploaded_file($_FILES['imagefile' . $i]['tmp_name'], $content_folder . $file_name);
							$content_path = $content_folder . $file_name;
							$local_file['imagefile' . $i] = $content_path;
						}
					}
					if ($post_fields['sub_category'] == 'treasure_hunt') {
						$post_fields['exp_subtype_id'] = 11;
						for ($i = 2; $i <= 10; $i++) {
							$file_name = sanitize_file_name($_FILES['imagefile' . $i]['name']);
							$ext = pathinfo($file_name, PATHINFO_EXTENSION);
							move_uploaded_file($_FILES['imagefile' . $i]['tmp_name'], $content_folder . $file_name);
							$content_path = $content_folder . $file_name;
							$local_file['imagefile' . $i] = $content_path;
						}
					}
				}


				$boundary = wp_generate_password(24);
				$headers  = array(
					'content-type' => 'multipart/form-data; boundary=' . $boundary,
				);
				$payload = '';
				// First, add the standard POST fields:
				foreach ($post_fields as $name => $value) {
					$payload .= '--' . $boundary;
					$payload .= "\r\n";
					$payload .= 'Content-Disposition: form-data; name="' . $name .
						'"' . "\r\n\r\n";
					$payload .= $value;
					$payload .= "\r\n";
				}

				// Upload the file
				if (!empty($local_file)) {
					foreach ($local_file as $filekey => $filevalue) {
						$payload .= '--' . $boundary;
						$payload .= "\r\n";
						$payload .= 'Content-Disposition: form-data; name="' . $filekey .
							'"; filename="' . basename($filevalue) . '"' . "\r\n";
						// $payload .= 'Content-Type: image/jpeg' . "\r\n";
						$payload .= "\r\n";
						$payload .= file_get_contents($filevalue);
						$payload .= "\r\n";
					}
				}
				$payload .= '--' . $boundary . '--';

				$response = wp_remote_post(
					$url,
					array(
						'headers'    => $headers,
						'body'       => $payload,
					)
				);

				if (is_wp_error($response)) {
					$error_message = $response->get_error_message();
					// echo "Something went wrong: $error_message";
					wp_send_json(array('status' => 'failed', 'message' => 'Something went wrong:' . $error_message), 200);
				} else {
					$body = wp_remote_retrieve_body($response);
					if (!empty($local_file)) {
						foreach ($local_file as $filekeys => $filevalues) {
							if (file_exists($filevalues)) {
								unlink($filevalues);
							}
						}
					}
					if ($product_id) {
						$slug_url = VOSSLE__SERVER_URL . $post_fields['slug_exp_name'];
						add_post_meta($product_id, 'vossle_url', $slug_url, true);
					}
					wp_send_json($body, 200);
				}
			}
		}
	}

	public static function vossle_remove_ar_experience()
	{
		if (current_user_can('edit_others_posts')) {
			if (!wp_verify_nonce(sanitize_text_field($_POST['nonce_ajax']), self::NONCE))
				return false;

			$id = sanitize_text_field($_POST['id']);
			$client_id = sanitize_text_field($_POST['user_id']);
			// $url = 'https://dashboard.vossle.com/API/removeAr';
			$http_host = self::API_HOST;
			$url = "{$http_host}delete";
			$args = array(
				'body' => array('ar_id' => $id, 'user_id' => $client_id)
			);
			$response = wp_remote_post($url, $args);
			$body = wp_remote_retrieve_body($response);
			// return $body;
			wp_send_json($body, 200);
		}
	}

	public static function view($name, array $args = array())
	{
		$args = apply_filters('vossle_view_arguments', $args, $name);

		foreach ($args as $key => $val) {
			$$key = $val;
		}

		// load_plugin_textdomain( 'vossle' );

		$file = VOSSLE__PLUGIN_DIR . 'views/' . $name . '.php';

		include($file);
	}

	public static function enter_api_key()
	{
		if (!wp_verify_nonce(sanitize_text_field($_POST['_wpnonce']), self::NONCE))
			return false;

		// foreach( array( 'vossle_strictness', 'vossle_show_user_comments_approved' ) as $option ) {
		// 	update_option( $option, isset( $_POST[$option] ) && (int) $_POST[$option] == 1 ? '1' : '0' );
		// }

		if (self::predefined_api_key()) {
			return false; //shouldn't have option to save key if already defined
		}

		$new_key = preg_replace('/[^a-f0-9]/i', '', sanitize_text_field($_POST['key']));
		$old_key = self::get_api_key();

		if (empty($new_key)) {
			if (!empty($old_key)) {
				delete_option('vossle_ar_api_key');
				delete_option('vossle_ar_user_id');
				self::$notices[] = 'new-key-empty';
				$url = self::get_page_url('safe_redirect');
				wp_redirect($url);
				exit();
			}
		} elseif ($new_key != $old_key) {
			self::save_key($new_key);
		}
		$enable_try = 'no';
		if (isset($_POST['vos_tryon_button'])) {
			$enable_try = sanitize_text_field($_POST['vos_tryon_button']);
			update_option('vos_tryon_button', $enable_try);
			update_option('vos_tryon_button_title', sanitize_text_field($_POST['vos_tryon_button_title']));
			update_option('vos_tryon_button_position', sanitize_text_field($_POST['vos_tryon_button_position']));
		}

		return true;

		// if ( wp_get_referer() ) {
		// 	wp_safe_redirect( wp_get_referer() );
		// 	exit();
		// } else {
		// 	wp_safe_redirect( get_home_url() );
		// 	exit();
		// }
	}



	public static function save_key($api_key)
	{
		$key_status = self::verify_key($api_key);
		if (isset($key_status->status) && isset($key_status->data) && $key_status->status == 'success') {
			if (in_array($key_status->status, array('active', 'active-dunning', 'no-sub', 'success'))) {
				update_option('vossle_ar_api_key', $key_status->data->wordpress_key);
				update_option('vossle_ar_user_id', $key_status->data->id);
			}

			if ($key_status->status == 'active' || $key_status->status == 'success')
				self::$notices['status'] = 'new-key-valid';
			elseif ($key_status->status == 'notice')
				self::$notices['status'] = $key_status->status;
			else
				self::$notices['status'] = $key_status->status;
		} elseif (in_array($key_status, array('invalid', 'failed')))
			self::$notices['status'] = 'new-key-' . $key_status;
	}

	public static function get_api_key()
	{
		return apply_filters('vossle_get_api_key', defined('VOSSLE_AR_API_KEY') ? constant('VOSSLE_AR_API_KEY') : get_option('vossle_ar_api_key'));
	}

	public static function get_user_id()
	{
		return apply_filters('vossle_get_user_id', defined('VOSSLE_AR_USER_ID') ? constant('VOSSLE_AR_USER_ID') : get_option('vossle_ar_user_id'));
	}

	public static function check_key_status($key, $ip = null)
	{
		return self::http_post(self::build_query(array('key' => $key)), 'verify_key', $ip);
	}

	public static function verify_key($key, $ip = null)
	{
		// Shortcut for obviously invalid keys.
		if (strlen($key) > 12) {
			return 'invalid';
		}

		$response = self::check_key_status($key, $ip);
		try {
			//code...
			$response = json_decode($response[1]);
			// print_r($response[1]);
			if (empty($response) || !isset($response->status) || $response->status != 'success') {

				return 'failed';
			}
		} catch (\Throwable $th) {
			//throw $th;
			$response = 'failed';
		}
		return $response;
	}

	public static function get_page_url($page = 'config')
	{

		// $args = array( 'page' => 'vossle%2Fvossle.php%2Fvossle_settings' );
		if ($page == 'stats')
			$args = array('page' => 'vossle-key-config', 'view' => 'setup');
		elseif ($page == 'delete_key')
			$args = array('page' => 'vossle-key-config', 'view' => 'setup', 'action' => 'delete-key', '_wpnonce' => wp_create_nonce(self::NONCE));
		elseif ($page == 'safe_redirect')
			$args = array('page' => 'vossle/vossle.php');
		elseif (!empty(self::get_api_key()) && !empty(VOSSLE__USER_ID))
			$args = array('page' => 'vossle/vossle.php/vossle_settings');
		else
			$args = array('page' => 'vossle/vossle.php');

		$url = add_query_arg($args, admin_url('admin.php'));

		return $url;
	}

	public static function predefined_api_key()
	{
		if (defined('VOSSLE_AR_API_KEY')) {
			return true;
		}

		return apply_filters('vossle_predefined_api_key', false);
	}

	/**
	 * Make a POST request to the Vossle API.
	 *
	 * @param string $request The body of the request.
	 * @param string $path The path for the request.
	 * @param string $ip The specific IP address to hit.
	 * @return array A two-member array consisting of the headers and the response body, both empty in the case of a failure.
	 */
	public static function http_post($request, $path, $ip = null)
	{

		// $content_length = strlen( $request );

		$api_key   = self::get_api_key();
		$host      = self::API_HOST;

		// if ( !empty( $api_key ) )
		// 	$host = $api_key.'.'.$host;

		$http_host = $host;
		$http_args = array(
			'body' => $request,
			// 'headers' => array(
			// 	'Content-Type' => 'application/x-www-form-urlencoded; charset=' . get_option( 'blog_charset' ),
			// 	'Host' => $host,
			// 	'User-Agent' => $vossle_ua,
			// ),
			// 'httpversion' => '1.0',
			// 'timeout' => 15
		);
		$path = 'getUserdetails';
		$vossle_url = $http_vossle_url = "{$http_host}{$path}";
		//$vossle_url = $http_vossle_url = "{$http_host}/{$path}";
		$response = wp_remote_post($vossle_url, $http_args);

		if (is_wp_error($response)) {

			self::log(compact('vossle_url', 'http_args', 'response'));
			return array('', '');
		}

		$simplified_response = array($response['headers'], $response['body']);
		return $simplified_response;
	}

	/**
	 * Essentially a copy of WP's build_query but one that doesn't expect pre-urlencoded values.
	 *
	 * @param array $args An array of key => value pairs
	 * @return string A string ready for use as a URL query string.
	 */
	public static function build_query($args)
	{
		return _http_build_query($args, '', '&');
	}

	/**
	 * Log debugging info to the error log.
	 *
	 * Enabled when WP_DEBUG_LOG is enabled (and WP_DEBUG, since according to
	 * core, "WP_DEBUG_DISPLAY and WP_DEBUG_LOG perform no function unless
	 * WP_DEBUG is true), but can be disabled via the vossle_debug_log filter.
	 *
	 * @param mixed $vossle_debug The data to log.
	 */
	public static function log($vossle_debug)
	{
		if (apply_filters('vossle_debug_log', defined('WP_DEBUG') && WP_DEBUG && defined('WP_DEBUG_LOG') && WP_DEBUG_LOG && defined('VOSSLE_DEBUG') && VOSSLE_DEBUG)) {
			error_log(print_r(compact('vossle_debug'), true));
		}
	}

	public static function get_vossle_user($api_key)
	{
		$vossle_user = false;

		$vossle_subscription_verification = self::http_post(self::build_query(array('key' => $api_key)), 'verify-subscription');

		if (!empty($vossle_subscription_verification[1])) {
			if ('invalid' !== $vossle_subscription_verification[1]) {
				$vossle_user = json_decode($vossle_subscription_verification[1]);
			}
		}

		return $vossle_user;
	}

	public static function display_alert()
	{
		self::view('notice', array(
			'type' => 'alert',
			'code' => (int) get_option('vossle_alert_code'),
			'msg'  => get_option('vossle_alert_msg')
		));
	}

	public static function display_status()
	{
		if (!empty(self::$notices)) {
			foreach (self::$notices as $index => $type) {
				if (is_object($type)) {
					$notice_header = $notice_text = '';

					if (property_exists($type, 'notice_header')) {
						$notice_header = wp_kses($type->notice_header, self::$allowed);
					}

					if (property_exists($type, 'notice_text')) {
						$notice_text = wp_kses($type->notice_text, self::$allowed);
					}

					if (property_exists($type, 'status')) {
						$type = wp_kses($type->status, self::$allowed);
						self::view('notice', compact('type', 'notice_header', 'notice_text'));

						unset(self::$notices[$index]);
					}
				} else {
					self::view('notice', compact('type'));

					unset(self::$notices[$index]);
				}
			}
		}
	}
}
