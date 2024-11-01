<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly 
/* get edit data */
$user_id = VOSSLE__USER_ID;
$edit_id  = sanitize_text_field($_GET['ar']);
$ar_id = base64_decode(urldecode($edit_id));
if (empty($ar_id)) {
    $url = Vossle::get_page_url('safe_redirect');
    wp_redirect($url);
    exit();
}
$urlvos = 'https://api.vossle.com/api/getExperienceList';
$argss = array(
    'body' => array('userid' => $user_id)
);
$response1 = wp_remote_post($urlvos, $argss);
$body1 = wp_remote_retrieve_body($response1);
$list_ARarray = json_decode($body1);

$url = 'https://api.vossle.com/api/get_node/' . $ar_id;
$response = wp_remote_get($url);
$body = wp_remote_retrieve_body($response);
$list_array = json_decode($body);

$arData = array();
$arasset = array();
$marker_image = '';
$splash_screen = '';
$tossing_model = '';
$content_file = '';
$audio_file = '';
$transparent_background = 'No';
$background_color = '';
$background_colour = '';
$detection_point = '';
$ga_code = '';
$light_range = '';
$redirection_time = '';
$latitude = '';
$longitude = '';
$distance = '';
$sub_exp = '';
$background_image = '';
$large_assets = array();
if (!empty($list_array->data)) {
    $arData = (array)$list_array->data[0];
    $arasset = $arData['assets'];
    $detection_point = $arData['detection_point'];
    $sub_exp = $arData['sub_exp_type']->exp_type ?? null;
    $ga_code = $arData['ga_code_exp'];
    $light_range = $arData['light_range'];
    $redirection_time = $arData['custom_field_text'];
    $latitude = $arData['latitude'];
    $longitude = $arData['longitude'];
    $distance = $arData['distance'];

    foreach ($arasset as $assets) {
        if ($assets->type == 'content') {
            $content_file = $assets->assets;
            $transparent_background = $assets->transparent_background;
            $background_color = $assets->background_color;
        } else if ($assets->type == 'audio') {
            $audio_file = $assets->assets;
        } else if ($assets->type == 'tossing_model') {
            $tossing_model = $assets->assets;
        } else if ($assets->type == 'marker_image') {
            $marker_image = $assets->assets;
        } else if ($assets->type == 'tossing_image_modal') {
            $tossing_model_image = $assets->assets;
        } else if ($assets->type == 'splash_screen') {
            $splash_screen = $assets->assets;
        } else if ($assets->type && str_contains($assets->type, 'lipstick_colour')) {
            $large_assets[$assets->type] = $assets->assets;
        } else if ($assets->type && str_contains($assets->type, 'face_game')) {
            $large_assets[$assets->action] = $assets->assets;
        } else if ($assets->type && str_contains($assets->type, 'content_')) {
            $large_assets[] = $assets->assets;
        } else if ($assets->type == 'background_image') {
            $background_image = $assets->assets;
        } else if ($assets->type == 'background_colour') {
            $background_colour = $assets->assets;
        }
    }
}

?>

<script type='text/javascript'>
    var directoryname = '<?php echo esc_url(plugins_url()); ?>';
    var artoolkit_wasm_url = directoryname + '/vossle/assets/marker_vendor_new_old/wasm/NftMarkerCreator_wasm.wasm';
</script>

<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <?php include VOSSLE__PLUGIN_DIR . 'includes/merchant-header.php'; ?>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_sidebar.html -->

        <!-- partial -->
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row">
                    <div class="col-md-12 d-flex align-items-stretch grid-margin">
                        <div class="row flex-grow">
                            <div class="col-12 stretch-card">
                                <div class="card">
                                    <div class="card-header mb-2" style="background-color:#ffffff; border-bottom: 0px;">
                                        <h4 class="float-left m-0 p-0">Edit AR Experience <span id="text_slab"></span></h4>
                                        <div><?php print_r($arData) ?></div>
                                        <a href="" class="float-right m-0 p-0" style="cursor: pointer;"><i class="far fa-times-circle" style="font-size: xx-large;"></i></a>
                                    </div>
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-9 m-0 p-0 main_div" style="background-color:#ffffff;">
                                                <div class="card-body" style="background-color:#ffffff; min-height:400px;">
                                                    <div class="container">
                                                        <ul class="breadcrumb">
                                                            <li><a href="#home" id="home_tab" class="disabled-link">1. Select Type</a></li>
                                                            <li><a href="#setup" id="setup_tab" class="disabled-link active">2. Setup</a></li>
                                                            <li><a href="#finish" id="finish_tab" class="disabled-link">3. Finish</a></li>
                                                        </ul>
                                                        <div class="tab-content">
                                                            <div id="home" class="d-none" style="margin-top: 4rem; margin-left: 0.5rem;">
                                                                <div class="row">
                                                                    <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12 stretch-card">
                                                                        <div class="card ml-4 choose_option" val="markerbased" style="height: 150px;">
                                                                            <div class="card-body" style="cursor: pointer; background-color: #e57560; border-radius:10px; border-bottom:1px solid white;">
                                                                                <div class="container-fluid">
                                                                                    <div class="row">
                                                                                        <div class="col-4">
                                                                                            <img src="<?php echo esc_url(plugins_url('../assets/images/markerbased.png', __FILE__)); ?>" style="position: absolute; height: 200px; margin-top: -80px; margin-left: -105px;">
                                                                                        </div>
                                                                                        <div class="col-8 m-0 p-0">
                                                                                            <h4 style="color: white; margin-top: 10px; font-weight: 800; margin-left: 25px;">Marker AR</h2>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12 stretch-card">
                                                                        <div class="card choose_option" val="markerless_3d_model" style="margin-left:6rem; height: 150px;">
                                                                            <div class="card-body" style="cursor: pointer; background-color: #2bcb71; border-radius:10px; border-bottom:1px solid white;">
                                                                                <div class="container-fluid">
                                                                                    <div class="row">
                                                                                        <div class="col-4">
                                                                                            <img src="<?php echo esc_url(plugins_url('../assets/images/markerless.png', __FILE__)); ?>" style="position: absolute; height: 200px; margin-top: -80px; margin-left: -105px;">
                                                                                        </div>
                                                                                        <div class="col-8 m-0 p-0">
                                                                                            <h4 style="color: white; margin-top: 10px; font-weight: 800;">Markerless AR</h2>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div id="setup" class="d-block">
                                                                <div class="alert alert-danger alert-dismissable mt-3" id="errmsgDiv" style="visibility:hidden">
                                                                    <button type="button" class="close" data-bs-dismiss="alert" aria-hidden="true">×</button>
                                                                    <p id="errmsg"></p>
                                                                </div>
                                                                <form class="forms-sample ar_form_edit" name="Ar-form" method="post" action="#" enctype="multipart/form-data" id="ar_form">
                                                                    <?php wp_nonce_field(Vossle::NONCE) ?>
                                                                    <div id="qrcode" style="display:none;"></div>
                                                                    <input type="hidden" id="edit_form" value="edit_form" />
                                                                    <select class="exp_type_select" id="exp_type" name="exp_type" style="display:none;">
                                                                        <option value="0">Select Experience Type</option>
                                                                        <option id="markerbased" value="markerbased" <?php echo esc_html($arData['exp_type']->exp_type == 'markerbased' ? "selected" : ''); ?>>Marker AR</option>
                                                                        <option id="markerless_3d_model" value="markerless_3d_model" <?php echo esc_html($arData['exp_type']->exp_type == 'markerless' ? "selected" : ''); ?>>Markerless AR</option>
                                                                        <option id="face_detection" value="facedetection" <?php echo esc_html($arData['exp_type']->exp_type == 'facedetection' ? "selected" : ''); ?>>Face Detection</option>
                                                                        <option id="games" value="games" <?php echo esc_html($arData['exp_type']->exp_type == 'games' ? "selected" : ''); ?>>Games</option>
                                                                        <option id="work_flow" value="work_flow" <?php echo esc_html($arData['exp_type']->exp_type == 'workflow' ? "selected" : ''); ?>>Work Flow</option>
                                                                        <option id="ads" value="ads" <?php echo esc_html($arData['exp_type']->exp_type == 'ads' ? "selected" : ''); ?>>Advertisement</option>
                                                                    </select>
                                                                    <?php if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
                                                                        global $wpdb;
                                                                        $querystr = "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'vossle_url'";
                                                                        $prod_vos_url = $wpdb->get_results($querystr, OBJECT);
                                                                        if (!$prod_vos_url) {
                                                                            $wpdb->print_error(); // Get the last error message for debugging
                                                                        } else {
                                                                            $postArry = [];
                                                                            foreach ($prod_vos_url as $pstkry) {
                                                                                $postArry[] = $pstkry->post_id;
                                                                            }
                                                                        }
                                                                        $slug_url = VOSSLE__SERVER_URL . $arData['slug_exp_name'];
                                                                        $querystr1 = "SELECT post_id,meta_id FROM $wpdb->postmeta WHERE meta_value = '" . $slug_url . "'";
                                                                        $prod_vos_urll = $wpdb->get_results($querystr1, OBJECT);
                                                                        $edit_product = '';
                                                                        if (!$prod_vos_urll) {
                                                                            $wpdb->print_error(); // Get the last error message for debugging
                                                                        } else {
                                                                            $edit_product = $prod_vos_urll[0]->post_id;
                                                                            $product_metaid = $prod_vos_urll[0]->meta_id;
                                                                        }
                                                                        $args = array('post_type'   => 'product');
                                                                        $products = get_posts($args); ?>
                                                                        <div class="form-group row" id="product_list" style="margin-bottom:2rem;">
                                                                            <label for="product_list" id="product_list_label" class="col-sm-3 col-form-label" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;"> Product</label>
                                                                            <div class="col-sm-9">
                                                                                <select name="product_id" id="product_id" class="form-control">
                                                                                    <option value=" ">Select Product</option>
                                                                                    <?php foreach ($products as $prod => $prod_value) { ?>
                                                                                        <option value="<?php echo esc_html($prod_value->ID) ?>" <?php if (in_array($prod_value->ID, $postArry)) {
                                                                                                                                                    echo esc_html('disabled');
                                                                                                                                                } ?> <?php if ($prod_value->ID == $edit_product) {
                                                                                                                                                                                                                                    echo esc_html('selected');
                                                                                                                                                                                                                                } ?>><?php echo esc_html($prod_value->post_title) ?></option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row" id="exp_list" style="margin-bottom:2rem;">
                                                                            <label for="exp_list" id="exp_list_label" class="col-sm-3 col-form-label" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;"> AR Experience</label>
                                                                            <div class="col-sm-9">
                                                                                <select name="exp_id" id="exp_id" class="form-control">
                                                                                    <option value=" ">Select Ar Exp.</option>
                                                                                    <?php foreach ($list_ARarray->data as $expkey => $exp_value) {
                                                                                        $qrCodeexist = $exp_value->qr_code; ?>
                                                                                        <option data-url="<?php echo esc_html($exp_value->qr_code) ?>" value="<?php echo esc_html($exp_value->slug_exp_name) ?>" <?php if ($exp_value->slug_exp_name == $arData['slug_exp_name']) {
                                                                                                                                                                                                                        echo esc_html('selected');
                                                                                                                                                                                                                    } ?>><?php echo esc_html($exp_value->slug_exp_name) ?></option>
                                                                                    <?php } ?>

                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        <input type="hidden" id="qrCodeexist" name="qrCodeexist" value="" />
                                                                        <input type="hidden" id="vos_product_meta" name="vos_product_meta" value="<?php echo esc_html($product_metaid); ?>" />
                                                                    <?php } ?>
                                                                    <input type="hidden" id="sub_category" name="sub_game_category" value="<?php echo (!empty($sub_exp) && isset($sub_exp)) ? esc_html($sub_exp) : esc_html($detection_point); ?>" />
                                                                    <div class="form-group row" id="sub_div" style="display:none">
                                                                        <label for="exampleFormControlSelect2" class="col-sm-3 col-form-label">Marker Based Experience Type</label>

                                                                        <div class="col-sm-9">
                                                                            <select class="form-control" id="exp_type_mb" name="exp_type_m" readonly>
                                                                                <option id="3d_model" value="3d_model" <?php echo esc_html($arData['exp_type'] != 'markerless_3d_model' ? "selected" : '') ?> readonly>3D Model</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row" id="detection_point_box" style="margin-bottom:2rem; display:none;">
                                                                        <label for="detection_point" class="col-sm-3 col-form-label" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Face Detection Point</label>

                                                                        <div class="col-sm-9">
                                                                            <select name="detection_point" id="detection_point" class="form-control">
                                                                                <option value="eye" <?php echo esc_html($detection_point == 'eye' ? 'select' : '') ?>>Eye</option>
                                                                                <option value="head" <?php echo esc_html($detection_point == 'head' ? 'selected' : '') ?>>Head</option>
                                                                                <option value="neck" <?php echo esc_html($detection_point == 'neck' ? 'selected' : '') ?>>Neck</option>
                                                                                <option value="ear" <?php echo esc_html($detection_point == 'ears' ? 'selected' : '') ?>>Ears</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <!-- 	<?php if ($detection_point == 'toss' || $arData['detection_point'] == 'dodge_collect') { ?>
                                            <input type="hidden" name="detection_point" value="<?php echo esc_html(!empty($arData['detection_point']) ? $arData['detection_point'] : '') ?>" />
                                        <?php } ?> -->
                                                                    <?php if ($arData['detection_point'] !== 'head') {
                                                                        $style_display = "display:none";
                                                                    } ?>
                                                                    <div class="form-group row" id="head_section1" style="margin-bottom:2rem;<?php echo esc_html($style_display); ?>;">
                                                                        <label for="place_head" class="col-form-label col-sm-3" title="Place on head" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Place on Head</label>
                                                                        <div class="col-sm-3">
                                                                            <label class="switch"><input type="checkbox" id="place_head" value="place_head" name="place_head" <?php echo esc_html($arData['place_head'] == 'place_head' ? 'checked' : '')  ?> /><span class="slider round"></span></label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row" id="head_section2" style="margin-bottom:2rem;<?php echo esc_html($style_display); ?>;">
                                                                        <label for="above_head" class="col-form-label col-sm-3" title="Above head" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Above Head</label>
                                                                        <div class="col-sm-3">
                                                                            <label class="switch"><input type="checkbox" id="above_head" value="above_head" name="place_head" <?php echo esc_html($arData['above_head'] == 'above_head' ? 'checked' : '')  ?> /><span class="slider round"></span></label>
                                                                        </div>
                                                                    </div>


                                                                    <div class="form-group row" style="margin-bottom:2rem;">
                                                                        <label for="exp_name" class="col-sm-3 col-form-label exp_name_text" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Experience Name</label>

                                                                        <div class="col-sm-9">
                                                                            <input type="text" name="exp_name" id="exp_nm" class="form-control" placeholder="Please provide name" value="<?php echo esc_html(!empty($arData['exp_name']) ? $arData['exp_name'] : '') ?>" required disabled />
                                                                            <input type=hidden name="ar_id" value="<?php echo esc_html(!empty($arData['id']) != false ? $arData['id'] : '') ?>">
                                                                            <span id="slugExpName" class="m-0 p-0" style="display:none;"></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row" id="enable_splash_section" style="margin-bottom:2rem;display:none;">
                                                                        <label for="enable_splash" class="col-form-label col-sm-3 label_design" title="Splash Setting">Enable Splash Image</label>
                                                                        <div class="col-sm-3">
                                                                            <label class="switch">
                                                                                <input type="checkbox" id="enable_splash" value="TRUE" name="enable_splash" data-bs-toggle="tooltip" data-bs-placement="left" title="Switch it on." <?php echo ($splash_screen == '') ? '' : 'Checked' ?> />
                                                                                <span class="slider round"></span>
                                                                            </label>
                                                                        </div>

                                                                        <!-- <label for="use_gallery" class="col-form-label col-sm-3" title="Use Gallery" >Use Gallery</label> -->
                                                                        <!-- <div class="col-sm-6">
                                            <button type="button" class="form-control" id="use_gallery" >Use Gallery</button>
                                            <span id="use_gallery_status" class="m-0 p-0" style="display:none;"></span>
                                        </div> -->
                                                                    </div>
                                                                    <div class="form-group row <?php echo ($splash_screen == '') ? 'd-none' : '' ?>" id="splash_screen" style="margin-bottom:2rem;">
                                                                        <label for="splash_screen_up" class="col-sm-3 col-form-label splash_label" title="Tossing Model" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Splash Image</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="file" name="animation_file" id="splash_screen_up" class="file-upload-default" />
                                                                            <div class="input-group col-xs-12">
                                                                                <input type="text" id="splash_screen_ip" class="form-control file-upload-info" disabled="" placeholder="Upload panel image in jpg / png format." value="<?php echo esc_html(!empty($splash_screen) ? $splash_screen : '') ?>" />
                                                                                <span class="input-group-append">
                                                                                    <button class="file-upload-browse btn btn-info" type="button" id="up_6" style="border-radius: 10px; position: absolute; margin-left: -150px; height: 45px; width: 150px;" data-bs-toggle='tooltip' data-bs-placement='left' title='Upload Model'>Upload</button>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <?php if (!empty($large_assets) && $arData['exp_type']->exp_type == 'facedetection') {
                                                                        $total_chq = count($large_assets);
                                                                        $count = 1; ?>
                                                                        <div id="append_field">
                                                                            <input type="hidden" value="<?php echo $total_chq; ?>" id="total_chq">
                                                                            <?php foreach ($large_assets as $key => $value) { ?>
                                                                                <div class="start_field<?php echo esc_html($count); ?>" style="width: 100%;">
                                                                                    <div class="form-group row " style="margin-bottom:2rem;">
                                                                                        <label class="col-sm-3 col-form-label" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500; padding-left: 1.5em;">Choose Color</label>
                                                                                        <div class="col-sm-9">
                                                                                            <input type="color" name="<?php echo esc_html($key) ?>" class="form-control" value="<?php echo esc_html($value) ?>">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            <?php ++$count;
                                                                            } ?>


                                                                        </div>
                                                                        <div class="d-flex justify-content-end col-11  mb-3" id="btn_container">
                                                                            <a href="javascript:void(0);" onclick="remove_clr()" class=" add_more_clr me-4 btn" style="background-color:red !important; color:white;">Remove</a>
                                                                            <a href="javascript:void(0);" onclick="add_clr()" class="btn btn-info">Add More</a>
                                                                        </div>
                                                                    <?php } ?>
                                                                    <div class="form-group row" id="face_game_type_section" style="margin-bottom:2rem;display:none;">
                                                                        <label for="face_game_type" id="face_game_type_label" class="col-sm-3 col-form-label label_design">Face Game Type</label>
                                                                        <div class="col-sm-9">
                                                                            <select name="face_game_type" id="face_game_type" class="form-control">
                                                                                <option value="angry_pokemon" <?php echo ($arData['face_game_type'] == 'angry_pokemon') ? 'selected' : ''; ?>>Angry Pokemon</option>
                                                                                <option value="favourite_food" <?php echo ($arData['face_game_type'] == 'favourite_food') ? 'selected' : ''; ?>>Favorite Food</option>
                                                                                <option value="tarot_reading" <?php echo ($arData['face_game_type'] == 'tarot_reading') ? 'selected' : ''; ?>>Tarot Reading</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row countrowImage imagename_section1" style="margin-bottom:2rem;display:none;">
                                                                        <label for="imagename" class="col-sm-3 col-form-label label_design">Image Name</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" name="imagename" id="imagename" class="form-control" placeholder="Please provide image name for your AR Experience." value="<?php echo esc_html(!empty($large_assets) ? array_key_first($large_assets) : ''); ?>">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row sparkLines" id="content_file" style="margin-bottom:2rem;">
                                                                        <label for="3d_file_up" class="col-sm-3 col-form-label content_label" title="Content File" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Content File</label>

                                                                        <div class="col-sm-9">
                                                                            <input type="file" name="three_d_model_file" id="3d_file_up" class="file-upload-default" value="<?php echo esc_html(!empty($content_file) ? $content_file : current($large_assets)); ?>" />
                                                                            <div class="input-group col-xs-12">
                                                                                <input type="text" id="three_d_model_file_ip" class="form-control file-upload-info" disabled="" placeholder="Please upload 3D model file in GLB, jpg, jpeg and png format within 15 MB." value="<?php echo esc_html(!empty($content_file) ? $content_file : current($large_assets)) ?>" />
                                                                                <span class="input-group-append">
                                                                                    <button class="file-upload-browse btn btn-info" type="button" id="up_4" style="border-radius: 10px; position: absolute; margin-left: -150px; height: 45px; width: 150px;" data-bs-toggle='tooltip' data-bs-placement='left' title='Upload Model'>Upload</button>
                                                                                </span>
                                                                            </div>
                                                                            <span style="display:none" id="preview-6">&nbsp;<img id="blah_6" src="#" alt="" style="width:80px"><a href="javascript:void(0)" id="rmPreview_6">Remove</a><span>
                                                                        </div>
                                                                    </div>
                                                                    <div id="face_game_assets">
                                                                        <?php if (count($large_assets) > 9) {
                                                                            $count = 2;
                                                                            foreach (array_slice($large_assets, 1) as $key => $value) {
                                                                        ?>
                                                                                <?php if ($detection_point == 'face_game') {    ?>
                                                                                    <div class="form-group row countrowImage imagename_section<?php echo $count; ?>" style="margin-bottom:2rem;">
                                                                                        <label for="imagename<?php echo $count; ?>" class="col-sm-3 col-form-label label_design">Image Name<?php echo $count; ?></label>
                                                                                        <div class="col-sm-9">
                                                                                            <input type="text" name="imagename<?php echo $count; ?>" id="imagename<?php echo $count; ?>" class="form-control" placeholder="Please provide image name for your AR Experience." value="<?php echo esc_html(!empty($key) ? $key : ''); ?>">
                                                                                        </div>
                                                                                    </div>

                                                                                <?php } ?>
                                                                                <div class="form-group row sparkLines" id="image_section<?php echo $count; ?>" style="margin-bottom:2rem;">
                                                                                    <label for="image_section<?php echo $count; ?>" class="col-sm-3 col-form-label content_label" title="Content File" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Image Name<?php echo $count; ?></label>

                                                                                    <div class="col-sm-9">
                                                                                        <input type="file" name="imagefile<?php echo $count; ?>" id="imagefile<?php echo $count; ?>" class="file-upload-default" value="<?php echo esc_html(!empty($value)) ? $value : ''; ?>" />
                                                                                        <div class="input-group col-xs-12">
                                                                                            <input type="text" id="imagefile<?php echo $count; ?>_text" class="form-control file-upload-info" disabled="" placeholder="Please upload jpg, jpeg and png format within 15 MB." value="<?php echo esc_html(!empty($value)) ? $value : ''; ?>" />
                                                                                            <span class="input-group-append">
                                                                                                <button class="file-upload-browse btn btn-info faceImages_btn" type="button" div-id="<?php echo $count; ?>" id="imagefile<?php echo $count; ?>_btn" style="border-radius: 10px; position: absolute; margin-left: -150px; height: 45px; width: 150px;" data-bs-toggle='tooltip' data-bs-placement='left' title='Upload Model'>Upload</button>
                                                                                            </span>
                                                                                        </div>
                                                                                        <span style="display:none" id="preview-6">&nbsp;<img id="blah_6" src="#" alt="" style="width:80px"><a href="javascript:void(0)" id="rmPreview_6">Remove</a><span>
                                                                                    </div>
                                                                                </div>
                                                                        <?php ++$count;
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </div>


                                                                    <div class="form-group row" id="feedback_content" style="margin-bottom:2rem; display:none;">
                                                                        <label for="feedback_content" id="feedback_content_label" class="col-sm-3 col-form-label" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Feedback Form</label>

                                                                        <div class="col-sm-9">
                                                                            <select name="feedback_contents" id="feedback_contents" class="form-control">

                                                                                <option value="no_form" <?php echo esc_html($arData['feedback_contents'] == 'no_form' ? 'selected' : '') ?>>No Form</option>
                                                                                <option value="start_experience" <?php echo esc_html($arData['feedback_contents'] == 'start_experience' ? 'selected' : '') ?>>Start Experience</option>
                                                                                <option value="end_experience" <?php echo esc_html($arData['feedback_contents'] == 'end_experience' ? 'selected' : '') ?>>End experience</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row redirect_section" style="margin-bottom:2rem;">
                                                                        <label for="redirect_to_website" class="col-form-label col-sm-3 label_design" title="Redirect to Website Link">Enable Redirection</label>
                                                                        <div class="col-sm-3">
                                                                            <label class="switch"><input type="checkbox" id="redirect_to_website" value="TRUE" name="redirect_to_website" data-bs-toggle="tooltip" data-bs-placement="left" title="Redirect to website after experience complete" <?php echo ($arData['about'] == 'False') ? '' : 'Checked'; ?> /><span class="slider round"></span></label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row <?php echo ($arData['about'] == 'False') ? 'd-none' : '';  ?>" id="redirect_input_section" style="margin-bottom:2rem;">
                                                                        <label for="redirect_time" class="col-form-label col-sm-3 redirect_time_label label_design" title="Redirection Time">Redirection Time</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="range" name="redirection_time" class="form-range form-control redirect_time_input" max="60" id="redirect_time" value="<?php echo ($arData['about']) ? $redirection_time : '';  ?>" step="5" style="border: 1px solid #ffffff !important;" />
                                                                            <span id="getvalue_redirect_time">10 Seconds</span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row" id="tossing_model" style="margin-bottom:2rem; display:none;">
                                                                        <label for="tossing_model_up" class="col-sm-3 col-form-label tossing_label" title="Tossing Model" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Tossing Model</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="file" name="tossing_model" id="tossing_model_up" class="file-upload-default" value="<?php echo esc_html(!empty($tossing_model) ? $tossing_model : '') ?>" />
                                                                            <div class="input-group col-xs-12">
                                                                                <input type="text" id="tossing_model_ip" class="form-control file-upload-info" placeholder="Tossing 3d model in GLB (Must less than 1 mb)" value="<?php echo esc_html(!empty($tossing_model) ? $tossing_model : '') ?>" disabled />
                                                                                <span class="input-group-append">
                                                                                    <button class="file-upload-browse btn btn-info" type="button" id="up_5" style="border-radius: 10px; position: absolute; margin-left: -150px; height: 45px; width: 150px;" data-bs-toggle='tooltip' data-bs-placement='left' title='Upload Model'>Upload</button>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row gacode_exp_field" style="margin-bottom:2rem;">
                                                                        <label for="gacode_exp" class="col-sm-3 col-form-label gacode_exp_text label_design">GA Code</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" name="ga_code_exp" id="ga_code_exp" maxlength="15" class="form-control" placeholder="Please provide GA Code" value="<?php echo esc_html($ga_code); ?>">
                                                                        </div>
                                                                    </div>

                                                                    <?php if (isset($arData['arContent05']) && (!empty($arData['arContent05']))) ?>
                                                                    <div class="form-group row" id="tossing_model_pic" style="margin-bottom:2rem; display:flex;">
                                                                        <label for="tossing_image_modal" class="col-sm-3 col-form-label splash_label" title="Tossing Model" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Tossing Model Image</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="file" name="tossing_image_modal" id="tossing_image_modal" class="file-upload-default" />
                                                                            <div class="input-group col-xs-12">
                                                                                <input type="text" id="tossing_model_imge_ip" class="form-control file-upload-info" disabled="" placeholder="Upload panel image in jpg / png format." value="<?php echo esc_html(!empty($tossing_model_image) ? $tossing_model_image : '') ?>" />
                                                                                <span class="input-group-append">
                                                                                    <button class="file-upload-browse btn btn-info" type="button" id="toss_pic_up_6" style="border-radius: 10px; position: absolute; margin-left: -150px; height: 45px; width: 150px;" data-bs-toggle='tooltip' data-bs-placement='left' title='Upload Model'>Upload</button>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row" id="video_setting_section" style="margin-bottom:2rem; display:none;">
                                                                        <label for="transparent_background" class="col-form-label col-sm-3" title="Video Setting" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Video Setting</label>
                                                                        <div class="col-sm-3">
                                                                            <label class="switch">
                                                                                <input type="checkbox" id="transparent_background" name="transparent_background" <?php echo esc_html(!empty($transparent_background) && $transparent_background == 'Yes' ? 'checked' : '') ?> value="<?php echo esc_html(!empty($transparent_background) ? $transparent_background : 'Yes') ?>" data-bs-toggle='tooltip' data-bs-placement='left' title='Switch it on to transparent the background.' />
                                                                                <span class="slider round"></span>
                                                                            </label>
                                                                            <!-- <label for="transparent_background_label" style="color: #c2c2c2;font-size: 16px;margin-top: 9px;font-weight: 500;">Transparent Background</label> -->
                                                                        </div>
                                                                        <label class="col-form-label col-sm-3" for="background_color" style="color: #c2c2c2;font-size: 16px;margin-top: 9px;font-weight: 500;">Background Color</label>
                                                                        <div class="col-sm-3">
                                                                            <input class="form-control" type="color" id="background_color" name="button_color" value="<?php echo esc_html(!empty($background_color) ? $background_color : '') ?>" placeholder="Please provide RGB color." data-bs-toggle='tooltip' data-bs-placement='left' title='Green Screen Background Color' />
                                                                        </div>
                                                                    </div>

                                                                    <input type="hidden" name="content_file_type" id="content_file_type" value="" />
                                                                    <div class="form-group row" id="camera_setting_section" style="margin-bottom:2rem;display:none;"><label for="camera_background" class="col-form-label col-sm-3" title="Video Setting" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Content Anchored to Camera</label>
                                                                        <div class="col-sm-3"><label class="switch"><input type="checkbox" id="camera_background" value="1" name="camera_background" data-bs-toggle="tooltip" data-bs-placement="left" title="Switch it on." <?php echo esc_html(!empty($arData['cons_x']) && $arData['cons_x'] == '1' ? 'checked' : '')  ?> /><span class="slider round"></span></label></div>
                                                                    </div>
                                                                    <div class="form-group row" id="action" style="margin-bottom:2rem; display:none;">
                                                                        <label for="action" id="action_label" class="col-sm-3 col-form-label" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Choose Action</label>

                                                                        <div class="col-sm-9">
                                                                            <select name="action" id="actions" class="form-control">
                                                                                <option value="tap" <?php echo esc_html($arData['arContent01'] == 'tap' ? 'selected' : '') ?>>Tap</option>
                                                                                <option value="shake" <?php echo esc_html($arData['arContent01'] == 'shake' ? 'selected' : '') ?>>Shake</option>
                                                                                <option value="swipe" <?php echo esc_html($arData['arContent01'] == 'swipe' ? 'selected' : '') ?>>Swipe</option>
                                                                                <option value="parallel" <?php echo esc_html($arData['arContent01'] == 'parallel' ? 'selected' : '') ?>>Parallel</option>
                                                                                <option value="timed" <?php echo esc_html($arData['arContent01'] == 'timed' ? 'selected' : '') ?>>Timed</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                    $contentimages = [];
                                                                    $contentaction = [];
                                                                    $contentCamera = [];
                                                                    $videobg = [];
                                                                    $videocolor = [];
                                                                    if (!empty($arData['three_d_model_file2'])) {
                                                                        $contentimages[2] = $arData['three_d_model_file2'];
                                                                        $contentaction[2] = $arData['file_action2'];
                                                                        $contentCamera[2] = $arData['cons_y'];
                                                                        $videobg[2] = $arData['arContentFolder'];
                                                                        $videocolor[2] = $arData['arContent02'];
                                                                    }
                                                                    if (!empty($arData['three_d_model_file3'])) {
                                                                        $contentimages[3] = $arData['three_d_model_file3'];
                                                                        $contentaction[3] = $arData['file_action3'];
                                                                        $contentCamera[3] = $arData['cons_z'];
                                                                        $videobg[3] = $arData['ar_tags'];
                                                                        $videocolor[3] = $arData['arContent03'];
                                                                    }
                                                                    if (!empty($arData['three_d_model_file4'])) {
                                                                        $contentimages[4] = $arData['three_d_model_file4'];
                                                                        $contentaction[4] = $arData['file_action4'];
                                                                        $contentCamera[4] = $arData['original_Scale'];
                                                                        $videobg[4] = $arData['door_option'];
                                                                        $videocolor[4] = $arData['arContent04'];
                                                                    }
                                                                    if (!empty($arData['three_d_model_file5'])) {
                                                                        $contentimages[5] = $arData['three_d_model_file5'];
                                                                        $contentaction[5] = $arData['file_action5'];
                                                                        $contentCamera[5] = $arData['minimum_scale'];
                                                                        $videobg[5] = $arData['plank_option'];
                                                                        $videocolor[5] = $arData['arContent05'];
                                                                    }
                                                                    if ($contentimages) {
                                                                        foreach ($contentimages as $key_cont => $val_cont) {
                                                                    ?>
                                                                            <div class="form-group row sparkLines" id="content_file<?php echo esc_html($key_cont); ?>" style="margin-bottom:2rem;"><label for="3d_file_up<?php echo esc_html($key_cont); ?>" class="col-sm-3 col-form-label content_label" title="Content File" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Content File <?php echo esc_html($key_cont); ?></label>
                                                                                <div class="col-sm-8"><input type="file" name="work_content[<?php echo esc_html($key_cont); ?>]" id="3d_file_up<?php echo esc_html($key_cont); ?>" class="file-upload-default">
                                                                                    <div class="input-group col-xs-12"><input type="text" id="three_d_model_file_ip<?php echo esc_html($key_cont); ?>" class="form-control file-upload-info" disabled="" value="<?php echo esc_html($val_cont); ?>" placeholder="Upload content file in GLB, jpg, png and MP4 format." /><span class="input-group-append"><button class="file-upload-browse btn btn-info contentclass" type="button" div-id="<?php echo esc_html($key_cont); ?>" id="up_4<?php echo esc_html($key_cont); ?>" style="border-radius: 10px; position: absolute; margin-left: -150px; height: 45px; width: 150px;" data-bs-toggle="tooltip" data-bs-placement="left" title="Upload Model">Upload</button></span></div><span style="display:none" id="preview-6<?php echo esc_html($key_cont); ?>">&nbsp;<img id="blah_6<?php echo esc_html($key_cont); ?>" src="#" alt="" style="width:80px"><a href="javascript:void(0)" id="rmPreview_6<?php echo esc_html($key_cont); ?>">Remove</a><span>
                                                                                </div>
                                                                                <div class="col-sm-1"><a onclick="removecontentrow(<?php echo esc_html($key_cont); ?>)" class="btn btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></a></div>
                                                                            </div>

                                                                            <?php if ($videobg[$key_cont] == 'Yes') { ?>
                                                                                <div class="form-group row" id="video_setting_section<?php echo esc_html($key_cont); ?>" style="margin-bottom:2rem;"><label for="transparent_background" class="col-form-label col-sm-3" title="Video Setting" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Video Setting <?php echo esc_html($key_cont); ?></label>
                                                                                    <div class="col-sm-3"><label class="switch"><input type="checkbox" id="transparent_background<?php echo esc_html($key_cont); ?>" value="Yes" name="transparent_background<?php echo esc_html($key_cont); ?>" data-bs-toggle="tooltip" data-bs-placement="left" title="Switch it on to transparent the background." <?php echo esc_html(!empty($videobg[$key_cont]) && $videobg[$key_cont] == 'Yes' ? 'checked' : '')  ?> /><span class="slider round"></span></label><label for="transparent_background_label" style="color: #c2c2c2;font-size: 16px;margin-top: 9px;font-weight: 500;">Transparent Background</label></div><label class="col-form-label col-sm-3" for="background_color" style="color: #c2c2c2;font-size: 16px;margin-top: 9px;font-weight: 500;">Background Color</label>
                                                                                    <div class="col-sm-3"><input class="form-control" type="color" id="background_color<?php echo esc_html($key_cont); ?>" name="button_color<?php echo esc_html($key_cont); ?>" placeholder="Please provide RGB color." data-bs-toggle="tooltip" data-bs-placement="left" value="<?php echo esc_html(!empty($videocolor[$key_cont]) ? $videocolor[$key_cont] : '') ?>" title="Green Screen Background Color" /></div>
                                                                                </div><input type="hidden" name="content_file_type<?php echo esc_html($key_cont); ?>" id="content_file_type<?php echo esc_html($key_cont); ?>" value="" />
                                                                            <?php } ?>

                                                                            <div class="form-group row" id="camera_setting_section<?php echo esc_html($key_cont); ?>" style="margin-bottom:2rem;"><label for="camera_background" class="col-form-label col-sm-3" title="Video Setting" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Content Anchored to Camera <?php echo esc_html($key_cont); ?></label>
                                                                                <div class="col-sm-3"><label class="switch"><input type="checkbox" id="camera_background<?php echo esc_html($key_cont); ?>" value="1" name="camera_background<?php echo esc_html($key_cont); ?>" data-bs-toggle="tooltip" data-bs-placement="left" title="Switch it on." <?php echo esc_html(!empty($contentCamera[$key_cont]) && $contentCamera[$key_cont] == '1' ? 'checked' : '')  ?> /><span class="slider round"></span></label></div>
                                                                            </div>

                                                                            <div class="form-group row" id="action<?php echo esc_html($key_cont); ?>" style="margin-bottom:2rem;"><label for="action" id="action_label<?php echo esc_html($key_cont); ?>" class="col-sm-3 col-form-label" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Choose Action <?php echo esc_html($key_cont); ?></label>
                                                                                <div class="col-sm-9"><select name="actions[<?php echo esc_html($key_cont); ?>]" id="actions<?php echo esc_html($key_cont); ?>" class="form-control">
                                                                                        <option value="tap" <?php echo esc_html($contentaction[$key_cont] == 'tap' ? 'selected' : '') ?>>Tap</option>
                                                                                        <option value="shake" <?php echo esc_html($contentaction[$key_cont] == 'shake' ? 'selected' : '') ?>>Shake</option>
                                                                                        <option value="swipe" <?php echo esc_html($contentaction[$key_cont] == 'swipe' ? 'selected' : '') ?>>Swipe</option>
                                                                                        <option value="parallel" <?php echo esc_html($contentaction[$key_cont] == 'parallel' ? 'selected' : '') ?>>Parallel</option>
                                                                                        <option value="timed" <?php echo esc_html($contentaction[$key_cont] == 'timed' ? 'selected' : '') ?>>Timed</option>
                                                                                    </select></div>
                                                                            </div>
                                                                    <?php }
                                                                    } ?>
                                                                    <div id="multiple_content"></div>
                                                                    <div class="form-group row" id="enable_shadow_section" style="margin-bottom:2rem;display:none;"><label for="enable_shadow" class="col-form-label col-sm-3" title="Video Setting" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Enable Shadow</label>
                                                                        <div class="col-sm-3"><label class="switch"><input type="checkbox" id="enable_shadow" value="TRUE" name="enable_shadow" data-bs-toggle="tooltip" data-bs-placement="left" title="Switch it on." <?php echo esc_html(!empty($arData['getEmail']) && $arData['getEmail'] == 'TRUE' ? 'checked' : '')  ?> /><span class="slider round"></span></label></div>
                                                                    </div>
                                                                    <div class="form-group row panelblock" id="panel_image" style="margin-bottom:2rem; display:none;">
                                                                        <label for="panel_image_up" class="col-sm-3 col-form-label panel_label" title="Panel Image" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Panel Image</label>
                                                                        <div class="col-sm-8">
                                                                            <input type="file" name="image_file" id="panel_image_up" class="file-upload-default" />
                                                                            <div class="input-group col-xs-12">
                                                                                <input type="text" id="panel_image_ip" class="form-control file-upload-info" disabled="" value="<?php echo esc_html(!empty($arData['image_file']) ? $arData['image_file'] : '') ?>" placeholder="Upload panel image in jpg / png format." />
                                                                                <span class="input-group-append">
                                                                                    <button class="file-upload-browse btn btn-info" type="button" id="up_7" style="border-radius: 10px; position: absolute; margin-left: -150px; height: 45px; width: 150px;" data-bs-toggle='tooltip' data-bs-placement='left' title='Upload Model'>Upload</button>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-1">
                                                                            <a id="add_morePanel" style="display:none;" class="btn btn-default"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                    $panelimages = [];
                                                                    if (!empty($arData['panel_image2'])) {
                                                                        $panelimages[2] = $arData['panel_image2'];
                                                                    }
                                                                    if (!empty($arData['panel_image3'])) {
                                                                        $panelimages[3] = $arData['panel_image3'];
                                                                    }
                                                                    if (!empty($arData['panel_image4'])) {
                                                                        $panelimages[4] = $arData['panel_image4'];
                                                                    }
                                                                    if (!empty($arData['panel_image5'])) {
                                                                        $panelimages[5] = $arData['panel_image5'];
                                                                    }

                                                                    if ($panelimages) {
                                                                        foreach ($panelimages as $key_p => $val_p) {
                                                                    ?>
                                                                            <div class="form-group row panelblock" id="panel_image<?php echo esc_html($key_p); ?>" style="margin-bottom:2rem;"><label for="panel_image_up" class="col-sm-3 col-form-label panel_label" title="Panel Image" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Panel Image <?php echo esc_html($key_p); ?></label>
                                                                                <div class="col-sm-8"><input type="file" name="image_filep[<?php echo esc_html($key_p); ?>]" id="panel_image_up<?php echo esc_html($key_p); ?>" class="file-upload-default" />
                                                                                    <div class="input-group col-xs-12"><input type="text" id="panel_image_ip<?php echo esc_html($key_p); ?>" class="form-control file-upload-info" disabled="" value="<?php echo esc_html($val_p); ?>" placeholder="Upload panel image in jpg / png format." /><span class="input-group-append"><button class="file-upload-browse btn btn-info panelclass" type="button" id="up_7<?php echo esc_html($key_p); ?>" style="border-radius: 10px; position: absolute; margin-left: -150px; height: 45px; width: 150px;" data-bs-toggle="tooltip" div-id="<?php echo esc_html($key_p); ?>" data-bs-placement="left" title="Upload Model">Upload</button></span></div>
                                                                                </div>
                                                                                <div class="col-sm-1"><a onclick="removepanelrow(<?php echo esc_html($key_p); ?>)" class="btn btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></a></div>
                                                                            </div>
                                                                    <?php }
                                                                    } ?>
                                                                    <div id="multiple_panel"></div>
                                                                    <div class="form-group row" id="audio_file_section" style="margin-bottom:2rem; display:none;">
                                                                        <label for="audio_file_up" class="col-sm-3 col-form-label audio_label" title="Audio File" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Audio File</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="file" name="audio" id="audio_file_up" class="file-upload-default" />
                                                                            <div class="input-group col-xs-12">
                                                                                <input type="text" id="audio_file_ip" class="form-control file-upload-info " placeholder="Upload audio file in MP3 format." value="<?php echo esc_html($audio_file); ?>" disabled />
                                                                                <span class="input-group-append">
                                                                                    <button class="file-upload-browse btn btn-info" type="button" id="up_2" style="border-radius: 10px; position: absolute; margin-left: -150px; height: 45px; width: 150px;">Upload</button>
                                                                                </span>
                                                                            </div>
                                                                            <span style="display:none;" id="preview-2">&nbsp;<img id="blah_2" src="#" alt="" style="width:80px"><a href="javascript:void(0)" id="rmPreview_2">Remove</a><span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row" id="audio_setting_section" style="margin-bottom:2rem; display:none;">
                                                                        <label for="audio_setting" class="col-form-label col-sm-3 audio_setting_label" title="Audio File Setting" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Audio Setting</label>
                                                                        <div class="col-sm-9">
                                                                            <label class="switch">
                                                                                <input type="checkbox" id="audio_setting" value="on" name="startAudio" <?php echo esc_html(!empty($arData['startAudio']) && $arData['startAudio'] == 'on' ? 'checked' : '') ?> />
                                                                                <span class="slider round"></span>
                                                                            </label>
                                                                            <label for="audio_seeting" style="color: #c2c2c2;font-size: 16px;margin-top: 9px;font-weight: 500;">(Toggle the switch to start audio on deployment of model.)</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row" id="audio_content" style="margin-bottom:2rem;display:none;">
                                                                        <label for="feedback_content" id="feedback_content_label" class="col-sm-3 col-form-label" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Audio set</label>

                                                                        <div class="col-sm-9">
                                                                            <select name="content_audio_set" id="content_audio_set" class="form-control">

                                                                                <option value="from_begin" <?php echo esc_html($arData['vuforia_target_id'] == 'from_begin' ? 'selected' : ''); ?>>From beginning</option>
                                                                                <option value="1" <?php echo esc_html($arData['vuforia_target_id'] == '1' ? 'selected' : ''); ?>>content file1</option>
                                                                                <option value="2" <?php echo esc_html($arData['vuforia_target_id'] == '2' ? 'selected' : ''); ?>>content file2</option>
                                                                                <option value="3" <?php echo esc_html($arData['vuforia_target_id'] == '3' ? 'selected' : ''); ?>>content file3</option>
                                                                                <option value="4" <?php echo esc_html($arData['vuforia_target_id'] == '4' ? 'selected' : ''); ?>>content file4</option>
                                                                                <option value="5" <?php echo esc_html($arData['vuforia_target_id'] == '5' ? 'selected' : ''); ?>>content file5</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row" id="sound_file_section" style="margin-bottom:2rem; display:none;">
                                                                        <label for="sound_file_up" class="col-form-label col-sm-3 sound_label" title="sound File" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Target Audio</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="file" name="sound" id="sound_file_up" class="file-upload-default" value="<?php echo esc_html(!empty($arData['successAudio']) ? $arData['successAudio'] : ''); ?>" />
                                                                            <div class="input-group col-xs-12">
                                                                                <input type="text" id="sound_file_ip" class="form-control file-upload-info" value="<?php echo esc_html(!empty($arData['successAudio']) ? $arData['successAudio'] : ''); ?>" placeholder="Upload sound file in MP3 format." disabled />
                                                                                <span class="input-group-append">
                                                                                    <button class="file-upload-browse btn btn-info" type="button" id="up_3" style="border-radius: 10px; position: absolute; margin-left: -150px; height: 45px; width: 150px;">Upload</button>
                                                                                </span>
                                                                            </div>
                                                                            <span style="display:none;" id="preview-2">&nbsp;<img id="blah_2" src="#" alt="" style="width:80px"><a href="javascript:void(0)" id="rmPreview_2">Remove</a><span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row" id="light_setting_section" style="margin-bottom:2rem; display:none;">
                                                                        <label for="light_setting" class="col-form-label col-sm-3 light_setting_label" title="Light Setting" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Light Setting Range</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="range" name="resizing" class="form-range form-control" min="0" max="5" id="light_setting" value="<?php echo esc_html($arData['light_range']); ?>" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row" id="screen_gesture_section" style="margin-bottom:2rem;display:none;"><label for="screen_gesture" class="col-form-label col-sm-3" title="Video Setting" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Enable Screen Gesture</label>
                                                                        <div class="col-sm-3"><label class="switch"><input type="checkbox" id="screen_gesture" value="TRUE" name="screen_gesture" data-bs-toggle="tooltip" data-bs-placement="left" title="Switch it on." <?php echo esc_html(!empty($arData['getMobile']) && $arData['getMobile'] == 'TRUE' ? 'checked' : '');  ?> /><span class="slider round"></span></label></div>
                                                                    </div>
                                                                    <div class="form-group row" id="action_control_section" style="margin-bottom:2rem;display:none;"><label for="action_control" class="col-form-label col-sm-3" title="Video Setting" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Action control</label>
                                                                        <div class="col-sm-3"><label class="switch"><input type="checkbox" id="action_control" value="TRUE" name="action_control" data-bs-toggle="tooltip" data-bs-placement="left" title="Switch it on." <?php echo esc_html(!empty($arData['getFeedback']) && $arData['getFeedback'] == 'TRUE' ? 'checked' : '');  ?> /><span class="slider round"></span></label></div>
                                                                    </div>
                                                                    <div class="form-group row label_for_hide" style="margin-bottom:2rem;">
                                                                        <label for="label" class="col-sm-3 col-form-label" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Label</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" name="label" id="label" maxlength="25" class="form-control" placeholder="Please provide label for your AR Experience." value="<?php echo esc_html(!empty($arData['label']) ? $arData['label'] : ''); ?>" />
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row" style="display:none;">
                                                                        <label class="col-sm-3 col-form-label">Watermark Camera Setting
                                                                            <div class="help-tip">
                                                                                <p>To add a custom watermark and text on screenshot, choose custom settings.</p>
                                                                            </div>
                                                                        </label>
                                                                        <div class="col-sm-4">
                                                                            <div class="form-radio">
                                                                                <label class="form-check-label">
                                                                                    <input type="radio" class="form-check-input cstm_camera" name="custom_camera_enabled" value="0" checked> Default(Vossle Logo)
                                                                                    <i class="input-helper"></i><i class="input-helper"></i></label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-5">
                                                                            <div class="form-radio">
                                                                                <label class="form-check-label">
                                                                                    <input type="radio" class="form-check-input cstm_camera" name="custom_camera_enabled" value="1" disabled> Custom
                                                                                    <i class="input-helper"></i><i class="input-helper"></i></label>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row d-none" id="enable_image_clr" style="margin-bottom:2rem;">
                                                                        <label for="enable_clr" class="col-form-label col-sm-3 label_design" title="Geo location Setting">Switch To Choose Colour as Background</label>
                                                                        <div class="col-sm-3">
                                                                            <label class="switch"><input type="checkbox" id="enable_clr" value="TRUE" name="enable_clr" data-bs-toggle="tooltip" data-bs-placement="left" title="Switch it on." <?php echo (!empty($background_image)) ? '' : 'checked'; ?> /><span class="slider round"></span></label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row  <?php echo ($background_image == '') ? 'd-none' : '' ?>" id="background_image_section" style="margin-bottom:2rem;">
                                                                        <label for="background_image" class="col-sm-3 col-form-label splash_label" title="Tossing Model" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Background Image</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="file" name="background_image" id="background_image" class="file-upload-default" <?php echo ($background_image != '') ? $background_image : ''; ?> />
                                                                            <div class="input-group col-xs-12">
                                                                                <input type="text" id="background_image_ip" class="form-control file-upload-info" disabled="" placeholder="Upload panel image in jpg / png format." value="<?php echo ($background_image != '') ? $background_image : ''; ?>" />
                                                                                <span class="input-group-append">
                                                                                    <button class="file-upload-browse btn btn-info" type="button" id="up_11" style="border-radius: 10px; position: absolute; margin-left: -150px; height: 45px; width: 150px;" data-bs-toggle='tooltip' data-bs-placement='left' title='Upload Model'>Upload</button>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <?php if ($sub_exp == '3d_ads') { ?>
                                                                        <div class="form-group row  <?php echo ($background_image != '') ? 'd-none' : ''; ?>" id="clr_background" style="margin-bottom:2rem;">
                                                                            <label for="clr_optional" class="col-sm-3 col-form-label label_design">Background Colour</label>
                                                                            <div class="col-sm-9">
                                                                                <input type="color" name="background_colour" id="clr_optional" class="form-control" value="<?php echo ($background_image == '') ? $background_colour : ''; ?>">
                                                                            </div>
                                                                        </div>
                                                                    <?php } ?>

                                                                    <div class="form-group row website_section" style="margin-bottom:2rem;">
                                                                        <label for="about_us" class="col-sm-3 col-form-label" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Website</label>
                                                                        <div class="col-sm-9">
                                                                            <input class="form-control" type="text" id="about_us" name="about" placeholder="Please provide websit URL." value="<?php echo esc_html(!empty($arData['website']) ? $arData['website'] : ''); ?>" />
                                                                            <p style="color: red; font-size: 12px; margin-top: 5px; font-weight: 500;"><span id="display_count"></span></p>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row game_instruction_section" style="margin-bottom:2rem; display:none;">
                                                                        <label for="game_instruction" class="col-sm-3 col-form-label" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Game Instruction</label>
                                                                        <div class="col-sm-9">
                                                                            <textarea row="5" minlength="50" maxlength="200" class="form-control" id="game_instruction" name="text_to_display" placeholder="Please provide Instruction for game."><?php echo esc_textarea($arData['text_to_display']); ?></textarea>
                                                                            <p id="game_instruction_count" style="font-size: 12px; margin-top: 5px; font-weight: 500;">Minimum required character is 50 out of 200 Character(s).</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row bullet_freq_section" style="margin-bottom:2rem;display:none;">
                                                                        <label for="bullet_freq" class="col-sm-3 col-form-label label_design">Bullet frequency</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="number" name="bullet_frq" id="bullet_frq" class="form-control" min="0.5" max="1.0" step="0.1" placeholder="Please provide bullet frequency for your AR Experience." value="0.5">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row game_duration_section" style="margin-bottom:2rem;display:none;">
                                                                        <label for="game_duration" class="col-sm-3 col-form-label label_design">Game Duration</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="number" name="game_duration" id="game_duration" class="form-control" min="30" max="60" placeholder="Please provide game duration for your AR Experience." value="<?php echo esc_html($arData['game_duration']); ?>" onKeypress="event.preventDefault();">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row" id="feedback_game" style="margin-bottom:2rem;display:none;">
                                                                        <label for="feedback_game" id="feedback_game_label" class="col-sm-3 col-form-label label_design">Feedback Form</label>
                                                                        <div class="col-sm-9">
                                                                            <select name="feedbackForm_game" id="feedbackForm_game" class="form-control">
                                                                                <option value="visible" <?php echo ($arData['feedbackForm_game'] == 'visible') ? 'selected' : '' ?>>Visible</option>
                                                                                <option value="hidden" <?php echo ($arData['feedbackForm_game'] == 'hidden') ? 'selected' : '' ?>>Hidden</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row game_speed_section" style="margin-bottom:2rem;display:none;">
                                                                        <label for="game_speed" class="col-sm-3 col-form-label label_design">Randomize Speed (seconds)</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="number" name="image_speed" id="image_speed" class="form-control" placeholder="Please provide randomize speed for your AR Experience." value="<?php echo esc_html($arData['image_speed']) ?>" min="0.1" max="5" step="0.1">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row redirect_btn_clr" style="margin-bottom:2rem;display:none;">
                                                                        <label for="game_speed" class="col-sm-3 col-form-label label_design">Redirect Button Colour</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="color" name="redirection_button_colour" id="redir_btn_clr" class="form-control" value="<?php echo esc_html($arData['redirection_button_colour']) ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row " id="enable_geolocation_section" style="margin-bottom:2rem;">
                                                                        <label for="enable_geolocation" class="col-form-label col-sm-3 label_design" title="Geo location Setting">Enable Geo Location</label>
                                                                        <div class="col-sm-3">
                                                                            <label class="switch"><input type="checkbox" id="enable_geolocation" value="TRUE" name="enable_geolocation" data-bs-toggle="tooltip" data-bs-placement="left" title="Switch it on." <?php echo ($latitude == '' || $longitude == '' || $distance == '') ? '' : 'checked' ?> /><span class="slider round"></span></label>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row geoLocation_field <?php echo ($latitude == '' || $longitude == '' || $distance == '') ? 'd-none' : '' ?>" style="margin-bottom:2rem;">
                                                                        <label for="latitude_exp" class="col-sm-3 col-form-label geoLocation_exp_text label_design">Geo Location</label>
                                                                        <div class="col-sm-3">
                                                                            <input type="number" step="any" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="11" name="latitude" id="latitude_exp" class="form-control geo_number" placeholder="Please provide Latitude (°N or °S)" value="<?php echo esc_html($latitude); ?>">
                                                                        </div>
                                                                        <div class="col-sm-3">
                                                                            <input type="number" step="any" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="11" name="longitude" id="longitude_exp" class="form-control geo_number" placeholder="Please provide Longitude (°E or °W)" value="<?php echo esc_html($longitude); ?>">
                                                                        </div>
                                                                        <div class="col-sm-3">
                                                                            <input type="number" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="5" minlength="3" name="distance" id="distance_exp" class="form-control" placeholder="Please provide Distance (m)" value="<?php echo esc_html($distance); ?>">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row text-center">
                                                                        <div class="col-sm-3"></div>
                                                                        <div class="col-sm-6">
                                                                            <button type="submit" class="btn btn-success mr-2" id="sv1" style="background-color: white !important; color: #2680eb; border: 2px solid #2680eb; width: 250px; height: 40px; border-radius: 10px;">Save Experience</button>
                                                                        </div>
                                                                        <div class="col-sm-3"></div>
                                                                    </div>
                                                                    <input type="hidden" name="client_id" value="<?php echo esc_html(VOSSLE__USER_ID); ?>" />
                                                                </form>
                                                                <input type="hidden" id="ar_hold">
                                                            </div>
                                                            <div id="finish" class="d-none">
                                                                <div class="container mt-5">
                                                                    <div class="row mt-2">
                                                                        <div class="col-1"></div>
                                                                        <div class="col-10">
                                                                            <div class="text-center congrats_text" style="position: relative; text-align: center; color: white;">
                                                                                <img src="<?php echo esc_url(plugins_url('../assets/images/background_finish.png', __FILE__)); ?>" class="img-fluid w-100">
                                                                                <div class="centered" style="position: absolute;top: 65%;left: 50%;transform: translate(-50%, -50%);color:white;width:500px;">
                                                                                    <h4 class="m-0 p-0">Congratulations!</h4>
                                                                                    <p class="m-0 p-0">You are ready to experience virtual objects in your real-world.<br>
                                                                                    </p>
                                                                                    <p class="m-0 p-0">Just copy or download image.</p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-1"></div>
                                                                        <div class="col-1"></div>
                                                                        <div class="col-10 qr_code_section">
                                                                            <div class="text-center">
                                                                                <div class="qr_code_replacable"><img src="<?php echo esc_url(VOSSLE__S3_URL . '/dashboard/assets/qrCode/1606852311_qrcode.png'); ?>" height="150px"></div>
                                                                                <p class="m-0 p-0">or</p>
                                                                                <div class="container">
                                                                                    <div class="row mx-4 url_div" style=" background-color:#9a9a9a; min-height: 40px; border-radius: 10px;">
                                                                                        <div class="col-10 url_replacable" style="padding: 10px;"><input type="text" class="m-0 p-0 url_changeable w-100" id="url_changeable" value="google.com" style="color: white; background: none; border: 0px; text-align: center; font-size: 1rem; height: 100%;" readonly /></div>
                                                                                        <div class="col-2" style="padding: 10px;"><i class="far fa-copy copy_and_share_url" style="font-size:2rem; color:white;"></i></div>
                                                                                        <div class="col-12 copy_status text-white bg-warning" style="display:none;">URL copied successfully.</div>
                                                                                    </div>
                                                                                </div>
                                                                                <!-- <button onclick="DownloadImage('qrcode')" class="btn btn-success qr_code_btn_replacable mt-4" style="background-color: white !important; color: #2680eb; border: 2px solid #2680eb; width: 150px; height: 36px; border-radius: 10px;">Download</button> -->
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-5 marker_image_section" style="display:none;">
                                                                            <div class="text-center">
                                                                                <div class="marker_image_replacable"><img src="<?php echo esc_url(VOSSLE__S3_URL . "MarkerImage/" . $arData['client_id'] . '/' . $arData['marker_image']); ?>" height="150px"></div>
                                                                                <!-- <p class="m-0 p-0 mb-4" style="font-size:0.8rem; color:#1a3b51;"><i class="fas fa-hand-point-right"></i> <b><u>Click here</u></b> to go back to setup page.</p> -->
                                                                                <!-- <button onclick="DownloadImage('markerimage')" class="marker_image_btn_replacable" ><i class="fas fa-download px-4 py-2" style="font-size: 2rem; color: #1a3b51; background-color: #e9ebeb; border-radius: 10px;"></i></a> -->

                                                                            </div>
                                                                        </div>
                                                                        <div class="col-2"></div>
                                                                    </div>
                                                                    <div class="row mt-2">
                                                                        <h6 style="text-align:justify !important; color: #c2c2c2;">
                                                                            You can download this marker image from the list of AR Experiences.
                                                                        </h6>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3 m-0 p-0 markerbased_display_section_box" style="background-color:white; display:none;">
                                                <div class="container markerbased_display_section">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <h6 class="mb-2" style="color:1a3b51;">Marker Image</h6>
                                                            <div id="markerPreview">
                                                                <input type="file" name="marker_image" class="file-upload-default" accept="image/*" id="imageLoader" style="display:none;">
                                                                <button class="btn btn-info mt-1" type="button" id="up_1" style="background-color: #e9ebeb !important; color: #2680eb; border: 2px solid #2680eb; width: 100%; height: 40px; border-radius: 10px;">Upload Marker</button>
                                                                <div class="wrapper" class="mt-3">
                                                                    <?php if (!empty($marker_image)) { ?>
                                                                        <img src="<?php echo esc_url(VOSSLE__S3_URL . $marker_image) ?>" id="imageContainer-display" class="img-responsive mt-1" style="height:18em; width:18em;" />
                                                                    <?php } ?>
                                                                    <canvas id="imageCanvas" class="d-none"></canvas>
                                                                    <div class="spinner-container">
                                                                        <div class="spinner"></div>
                                                                    </div>
                                                                    <div class="checkmark-cover">
                                                                        <div class="tasks-list-mark"></div>
                                                                    </div>
                                                                </div>
                                                                <canvas id="hideCanvas"></canvas>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12 text-center">
                                                            <h4 style="margin: 0; font-weight: 500;">Rating</h4>
                                                        </div>
                                                        <div class="col-12">
                                                            <div id="confidenceLevel" class="confidence" style="text-align: center;">
                                                                <img class="confidenceEl" src="<?php echo esc_url(plugins_url('../assets/marker_vendor_new_old/icons/star2.svg', __FILE__)); ?>"></img>
                                                                <img class="confidenceEl" src="<?php echo esc_url(plugins_url('../assets/marker_vendor_new_old/icons/star2.svg', __FILE__)); ?>"></img>
                                                                <img class="confidenceEl" src="<?php echo esc_url(plugins_url('../assets/marker_vendor_new_old/icons/star2.svg', __FILE__)); ?>"></img>
                                                                <img class="confidenceEl" src="<?php echo esc_url(plugins_url('../assets/marker_vendor_new_old/icons/star2.svg', __FILE__)); ?>"></img>
                                                                <img class="confidenceEl" src="<?php echo esc_url(plugins_url('../assets/marker_vendor_new_old/icons/star2.svg', __FILE__)); ?>"></img>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--     <div class="col-3 m-0 p-0 markerbased_display_section_box" style="background-color:white;display:none;">
                            <div class="container markerbased_display_section">
                                <div class="row">
                                    
                                    <div class="col-12">
                                        <h6 class="mb-4" style="color:1a3b51;">Marker Image</h6>
                                    </div>
                                   

                                    <div class="col-12">
                                        <div id="markerPreview">
                                             <input type="file" name="markerimage" class="file-upload-default" id="imageLoader" style="display:none;">
                                             <input type="hidden" name="marker_image" id="uploaded-image" />
                                            <button class="btn btn-info mt-1" type="button" id="up_1" style="background-color: #e9ebeb !important; color: #2680eb; border: 2px solid #2680eb; width: 100%; height: 40px; border-radius: 10px;">Upload Marker</button>
                                            <div class="wrapper" id="imagepreview">
                                             <?php if (!empty($marker_image)) { ?>
                                                <img src="<?php echo esc_url(VOSSLE__S3_URL . $marker_image) ?>" id="imageContainer-display" class="img-responsive mt-1" style="height:18em; width:18em;" />
                                            <?php } ?>
                                            </div>
                                            <!--canvas id="hideCanvas"></canvas-->
                                        </div>
                                    </div>


                                    <div class="col-12">

                                    </div>

                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<!-- content-wrapper ends -->
<!-- partial:partials/_footer.html -->

<!-- partial -->
</div>
<!-- main-panel ends -->
</div>
<!-- page-body-wrapper ends -->
</div>
<!-- Trigger/Open The Modal -->
<button id="myBtn" style="display:none;">Open Modal</button>
<!-- container-scroller -->
<div id="overlay"></div>
<div class="modal" id="addBookDialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title">Message<span id="cpn_nm"></span></h5>
                <?php   /*   <button type="button" class="close" data-dismiss="modal">&times;</button> */ ?>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                Please wait.. while we are creating your AR Experience.
                <div class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" id="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">0%</div>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-light" id="close_model">Close</button>
            </div>
        </div>
    </div>
</div>