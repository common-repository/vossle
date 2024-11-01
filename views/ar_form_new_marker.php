<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly 

$user_id = VOSSLE__USER_ID;
if (empty($user_id)) {
    $url = Vossle::get_page_url('safe_redirect');
    wp_redirect($url);
    exit();
}
$url = 'https://api.vossle.com/api/getExperienceList';
$args = array(
    'body' => array('userid' => $user_id)
);
$response = wp_remote_post($url, $args);
$body = wp_remote_retrieve_body($response);
$list_array = json_decode($body);

?>

<script type='text/javascript'>
    var directoryname = '<?php echo plugins_url(); ?>';
    var artoolkit_wasm_url = directoryname + '/vossle/assets/marker_vendor_new_old/wasm/NftMarkerCreator_wasm.wasm';
</script>
<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <?php include VOSSLE__PLUGIN_DIR . 'includes/merchant-header.php'; ?>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">

        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row">
                    <div class="col-md-12 d-flex align-items-stretch grid-margin">
                        <div class="row flex-grow">
                            <div class="col-12 stretch-card">
                                <div class="card">
                                    <div class="card-header mb-2 add_new_header">
                                        <h4 class="float-left m-0 p-0 add_new_header_txt"><span class="mobile_hide">Create New AR Experience</span> <span id="text_slab"></span></h4>
                                    </div>
                                    <div class="container-fluid main_screen">
                                        <div class="row">
                                            <div class="col-12 m-0 p-0 main_div" style="background-color:#ffffff;">
                                                <div class="card-body add_new_body" style="background-color:#ffffff; min-height:400px;">
                                                    <div class="container">
                                                        <ul class="breadcrumb">
                                                            <li><a href="#home" id="home_tab" class="disabled-link active"><i class="fa fa-check-circle" aria-hidden="true"></i> Select Type</a></li>
                                                            <li><a href="#setup" id="setup_tab" class="disabled-link"><i class="fa fa-check-circle" aria-hidden="true"></i> Setup</a></li>
                                                            <li><a href="#finish" id="finish_tab" class="disabled-link"><i class="fa fa-check-circle" aria-hidden="true"></i> Finish</a></li>
                                                        </ul>

                                                        <div class="tab-content">
                                                            <div id="home" class="d-block mt-5">
                                                                <div class="row mx-auto">
                                                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 stretch-card markerbased_div pl-0">
                                                                        <div class="card choose_option" val="markerbased" style="/* height: 100px; */background-color: white !important;">
                                                                            <div class="card-body bg-danger" style="cursor: pointer; background-color: #e57560; border-radius:10px; border-bottom:1px solid white;">
                                                                                <div class="container-fluid">
                                                                                    <div class="row">
                                                                                        <div class="col-4 pl-0">
                                                                                            <div class="p-3 text-center" style="background-color:white; border-radius: 50px; height:80px; width:80px;">
                                                                                                <img src="<?php echo esc_url(plugins_url('../assets/images/markerbased-new.png', __FILE__)); ?>" style="width: 50px;" />
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-8 m-0 p-0">
                                                                                            <h4 class="type_text">Marker AR</h2>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 stretch-card markerless_div p-0">
                                                                        <div class="card choose_option" val="markerless_3d_model" style="/* height: 100px; */background-color: white !important;">
                                                                            <div class="card-body bg-success" style="cursor: pointer; background-color: #2bcb71; border-radius:10px; border-bottom:1px solid white;">
                                                                                <div class="container-fluid">
                                                                                    <div class="row">
                                                                                        <div class="col-4 pl-0">
                                                                                            <div class="p-3 text-center" style="background-color:white; border-radius: 50px; height:80px; width:80px;">
                                                                                                <img src="<?php echo esc_url(plugins_url('../assets/images/markerless-new.png', __FILE__)); ?>" style="width: 50px;" />
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-8 m-0 p-0">
                                                                                            <h4 class="type_text">Markerless AR</h2>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 stretch-card face_detection_div pr-0">
                                                                        <div class="card choose_option" val="facedetection" style="/* height: 100px; */background-color: white !important;">
                                                                            <div class="card-body bg-warning" style="cursor: pointer; border-radius:10px; border-bottom:1px solid white;">
                                                                                <div class="container-fluid">
                                                                                    <div class="row">
                                                                                        <div class="col-4 pl-0">
                                                                                            <div class="p-3 text-center" style="background-color:white; border-radius: 50px; height:80px; width:80px;">
                                                                                                <img src="<?php echo esc_url(plugins_url('../assets/images/face-tryons.png', __FILE__)); ?>" style="width: 50px;" />
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-8 m-0 p-0">
                                                                                            <h4 class="type_text">Tryons</h4>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-12 m-3"></div>
                                                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 stretch-card games_div pl-0">
                                                                        <div class="card choose_option" val="games" style="/* height: 100px; */background-color: white !important;">
                                                                            <div class="card-body bg-info" style="cursor: pointer; background-color: cadetblue; border-radius:10px; border-bottom:1px solid white;">
                                                                                <div class="container-fluid">
                                                                                    <div class="row">
                                                                                        <div class="col-4 pl-0">
                                                                                            <div class="p-3 text-center" style="background-color:white; border-radius: 50px; height:80px; width:80px;">
                                                                                                <img src="<?php echo esc_url(plugins_url('../assets/images/mobile-game.png', __FILE__)); ?>" style="width: 50px;" />
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-8 m-0 p-0">
                                                                                            <h4 class="type_text">Games</h4>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 stretch-card advertisement_div pr-0">
                                                                        <div class="card choose_option" val="ads" style="/* height: 100px; */background-color: white !important;">
                                                                            <div class="card-body" style="cursor: pointer; border-radius:10px; border-bottom:1px solid white; background-color: #d300ff;">
                                                                                <div class="container-fluid">
                                                                                    <div class="row">
                                                                                        <div class="col-5 pl-0">
                                                                                            <div class="p-3 text-center" style="background-color:white; border-radius: 50px; height:80px; width:80px;">
                                                                                                <img src="<?php echo esc_url(plugins_url('../assets/images/face-tryons.png', __FILE__)); ?>" style="width: 50px;" />
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-7 m-0 p-0">
                                                                                            <h4 class="type_text">Advertisement</h4>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- face detection sub catageory -->
                                                            <div id="sub_face" class="d-none mt-3">
                                                                <div class="row mx-auto">
                                                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 stretch-card">
                                                                        <div class="card choose_option" val="face_detect">
                                                                            <div class="card-body" style="cursor: pointer; background-color: #ffaf00; border-radius:5px; border:2px solid #C184FF;">
                                                                                <div class="container-fluid">
                                                                                    <div class="row">
                                                                                        <div class="col-4 pr-0">
                                                                                            <div class="p-3 text-center" style="background-color:white; border-radius: 50px; height:80px; width:80px;">
                                                                                                <img src="<?php echo esc_url(plugins_url('../assets/images/facetryon.png', __FILE__)); ?>" style="width: 50px;" />
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-8 my-auto p-0">
                                                                                            <h4 class="type_text">Face Detection</h4>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- lipistic hide -->
                                                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 stretch-card">
                                                                        <div class="card choose_option" val="lipstick_tryon">
                                                                            <div class="card-body" style="cursor: pointer; background-color: #ffaf00; border-radius:5px; border:2px solid #C184FF;">
                                                                                <div class="container-fluid">
                                                                                    <div class="row">
                                                                                        <div class="col-4 pr-0">
                                                                                            <div class="p-3 text-center" style="background-color:white; border-radius: 50px; height:80px; width:80px;">
                                                                                                <img src="<?php echo esc_url(plugins_url('../assets/images/lipsticktryon.png', __FILE__)); ?>" style="width: 50px;" />
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-8 my-auto p-0">
                                                                                            <h4 class="type_text">Lipstick Try-on</h4>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- watch tryon -->
                                                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 stretch-card">
                                                                        <div class="card choose_option" val="watch_tryon">
                                                                            <div class="card-body" style="cursor: pointer; background-color: #ffaf00; border-radius:5px; border:2px solid #C184FF;">
                                                                                <div class="container-fluid">
                                                                                    <div class="row">
                                                                                        <div class="col-4 pr-0">
                                                                                            <div class="p-3 text-center" style="background-color:white; border-radius: 50px; height:80px; width:80px;">
                                                                                                <img src="<?php echo esc_url(plugins_url('../assets/images/wristtryon.png', __FILE__)); ?>" style="width: 50px;" />
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-8 my-auto p-0">
                                                                                            <h4 class="type_text">Wrist Detection</h4>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                <div class="row mx-auto mt-4">
                                                                    <!-- ring tryon -->
                                                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 stretch-card">
                                                                        <div class="card choose_option" val="ring_tryon">
                                                                            <div class="card-body" style="cursor: pointer; background-color: #ffaf00; border-radius:5px; border:2px solid #C184FF;">
                                                                                <div class="container-fluid">
                                                                                    <div class="row">
                                                                                        <div class="col-6 pr-0">
                                                                                            <div class="p-3 text-center" style="background-color:white; border-radius: 50px; height:80px; width:80px;">
                                                                                                <img src="<?php echo esc_url(plugins_url('../assets/images/ringtryon.png', __FILE__)); ?>" style="width: 50px;" />
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-6 my-auto p-0">
                                                                                            <h4 class="type_text">Ring Tryon</h4>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- ring tryon end  -->
                                                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 stretch-card">
                                                                        <div class="card choose_option" val="palm_tryon">
                                                                            <div class="card-body" style="cursor: pointer; background-color: #ffaf00; border-radius:5px; border:2px solid #C184FF;">
                                                                                <div class="container-fluid">
                                                                                    <div class="row">
                                                                                        <div class="col-5 pr-0">
                                                                                            <div class="p-3 text-center" style="background-color:white; border-radius: 50px; height:80px; width:80px;">
                                                                                                <img src="<?php echo esc_url(plugins_url('../assets/images/ringtryon.png', __FILE__)); ?>" style="width: 50px;" />
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-7 my-auto p-0">
                                                                                            <h4 class="type_text">Palm Tryon</h4>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div id="sub_advertisement" class="d-none mt-3">
                                                                <div class="row mx-auto">
                                                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 stretch-card">
                                                                        <div class="card choose_option" val="face_filter">
                                                                            <div class="card-body" style="cursor: pointer; background-color: #d300ff;; border-radius:5px; border:2px solid #C184FF;">
                                                                                <div class="container-fluid">
                                                                                    <div class="row">
                                                                                        <div class="col-6 pr-0">
                                                                                            <div class="p-3 text-center" style="background-color:white; border-radius: 50px; height:80px; width:80px;">
                                                                                                <img src="<?php echo esc_url(plugins_url('../assets/images/facetryon.png', __FILE__)); ?>" style="width: 50px;" />
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-6 my-auto p-0">
                                                                                            <h4 class="type_text">Face Filter</h4>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- lipistic hide -->
                                                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 stretch-card">
                                                                        <div class="card choose_option" val="world_mapping">
                                                                            <div class="card-body" style="cursor: pointer; background-color: #d300ff; border-radius:5px; border:2px solid #C184FF;">
                                                                                <div class="container-fluid">
                                                                                    <div class="row">
                                                                                        <div class="col-4 pr-0">
                                                                                            <div class="p-3 text-center" style="background-color:white; border-radius: 50px; height:80px; width:80px;">
                                                                                                <img src="<?php echo esc_url(plugins_url('../assets/images/world-mapping.png', __FILE__)); ?>" style="width: 50px;" />
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-8 my-auto p-0">
                                                                                            <h4 class="type_text">World Mapping</h4>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- watch tryon -->
                                                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 stretch-card">
                                                                        <div class="card choose_option" val="3d_ads">
                                                                            <div class="card-body" style="cursor: pointer; background-color: #d300ff; border-radius:5px; border:2px solid #C184FF;">
                                                                                <div class="container-fluid">
                                                                                    <div class="row">
                                                                                        <div class="col-4 pr-0">
                                                                                            <div class="p-3 text-center" style="background-color:white; border-radius: 50px; height:80px; width:80px;">
                                                                                                <img src="<?php echo esc_url(plugins_url('../assets/images/3d_ads.png', __FILE__)); ?>" style="width: 50px;" />
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-8 my-auto p-0">
                                                                                            <h4 class="type_text">3D Ads</h4>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>

                                                            </div>
                                                            <div id="sub_home" class="d-none mt-3">
                                                                <div class="row mx-auto">
                                                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 stretch-card">
                                                                        <div class="card choose_option" val="toss" style="height: 150px;">
                                                                            <div class="card-body" style="cursor: pointer; background-color: #fcc65a; border-radius:10px; border-bottom:1px solid white;">
                                                                                <h4 class="m-0 p-0" style="color: white; font-weight: 800;">Toss</h4>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 stretch-card">
                                                                        <div class="card choose_option" val="dodge_collect" style="height: 150px;">
                                                                            <div class="card-body" style="cursor: pointer; background-color: blue; border-radius:10px; border-bottom:1px solid white;">
                                                                                <h4 class="m-0 p-0" style="color: white; font-weight: 800;">Collect</h4>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 stretch-card">
                                                                        <div class="card choose_option" val="shooter" style="height: 150px;">
                                                                            <div class="card-body" style="cursor: pointer; background-color: #5afc9a; border-radius:10px; border-bottom:1px solid white;">
                                                                                <h4 class="m-0 p-0" style="color: white; font-weight: 800;">Shooter</h4>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row mx-auto">
                                                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 stretch-card">
                                                                        <div class="card choose_option" val="face_game" style="height: 150px;">
                                                                            <div class="card-body" style="cursor: pointer; background-color: #ee46e7; border-radius:10px; border-bottom:1px solid white;">
                                                                                <h4 class="m-0 p-0" style="color: white; font-weight: 800;">Face Game</h4>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 stretch-card">
                                                                        <div class="card choose_option" val="treasure_hunt" style="height: 150px;">
                                                                            <div class="card-body" style="cursor: pointer; background-color: #5adcfc; border-radius:10px; border-bottom:1px solid white;">
                                                                                <h4 class="m-0 p-0" style="color: white; font-weight: 800;">Treasure Hunt</h4>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div id="setup" class="d-none">
                                                                <div class="alert alert-danger alert-dismissable mt-3" id="errmsgDiv" style="visibility:hidden">
                                                                    <button type="button" class="close mr-3" data-bs-dismiss="alert" aria-hidden="true">×</button>
                                                                    <p id="errmsg"></p>
                                                                </div>
                                                                <form class="forms-sample ar_form_create" name="Ar-form" method="post" action="<?php //echo base_url(); 
                                                                                                                                                ?>Ar/add" enctype="multipart/form-data" id="ar_form">
                                                                    <?php wp_nonce_field(Vossle::NONCE) ?>
                                                                    <div id="qrcode" style="display:none;"></div>

                                                                    <select class="exp_type_select" id="exp_type" name="exp_type" style="display:none;">
                                                                        <option value="0">Select Experience Type</option>
                                                                        <option id="markerbased" value="markerbased">Marker AR</option>
                                                                        <option id="markerless_3d_model" value="markerless_3d_model">Markerless AR</option>
                                                                        <option id="face_detection" value="facedetection">Face Detection</option>
                                                                        <option id="games" value="games">Games</option>
                                                                        <option id="ads" value="ads">Advertisement</option>
                                                                    </select>
                                                                    <?php if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
                                                                        global $wpdb;
                                                                        $querystr = "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'vossle_url'";
                                                                        $prod_vos_url = $wpdb->get_results($querystr, OBJECT);
                                                                        if (!$prod_vos_url) {
                                                                            $wpdb->print_error(); // Get the last error message for debugging
                                                                        } else {
                                                                            $postArry = array();
                                                                            foreach ($prod_vos_url as $pstkry) {
                                                                                $postArry[] = $pstkry->post_id;
                                                                            }
                                                                        }
                                                                        $args = array('post_type'   => 'product');
                                                                        $products = get_posts($args); ?>
                                                                        <div class="form-group row" id="product_list" style="margin-bottom:2rem;">
                                                                            <label for="product_list" id="product_list_label" class="col-sm-3 col-form-label" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Choose Product</label>

                                                                            <div class="col-sm-9">
                                                                                <select name="product_id" id="product_id" class="form-control">
                                                                                    <option value=" ">Select Product</option>
                                                                                    <?php foreach ($products as $prod => $prod_value) { ?>
                                                                                        <option value="<?php echo esc_html($prod_value->ID) ?>" <?php if (!empty($postArry) && isset($postArry)) {
                                                                                                                                                    if (in_array($prod_value->ID, $postArry)) {
                                                                                                                                                        echo esc_html('disabled');
                                                                                                                                                    }
                                                                                                                                                } ?>><?php echo esc_html($prod_value->post_title) ?></option>
                                                                                    <?php } ?>

                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row" id="exp_list" style="margin-bottom:2rem;">
                                                                            <label for="exp_list" id="exp_list_label" class="col-sm-3 col-form-label" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Choose AR Experience</label>
                                                                            <div class="col-sm-9">
                                                                                <select name="exp_id" id="exp_id" class="form-control">
                                                                                    <option value=" ">Select Ar Exp.</option>
                                                                                    <?php foreach ($list_array->data as $expkey => $exp_value) {
                                                                                        $qrCodeexist = $exp_value->qr_code; ?>
                                                                                        <option data-url="<?php echo esc_html($exp_value->qr_code) ?>" value="<?php echo esc_html($exp_value->slug_exp_name) ?>"><?php echo esc_html($exp_value->slug_exp_name) ?></option>
                                                                                    <?php } ?>

                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        <input type="hidden" id="qrCodeexist" name="qrCodeexist" value="" />
                                                                    <?php } ?>
                                                                    <input type="hidden" id="sub_category" name="sub_category" value="" />

                                                                    <input type="hidden" id="edit_form" value="add_form" />
                                                                    <div class="form-group row" id="detection_point_box" style="margin-bottom:2rem; display:none;">
                                                                        <label for="detection_point" id="detection_point_label" class="col-sm-3 col-form-label" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Face Detection Point</label>

                                                                        <div class="col-sm-9">
                                                                            <select name="detection_point" id="detection_point" class="form-control">
                                                                                <option></option>
                                                                                <option value="eye">Eye</option>
                                                                                <option value="head">Head</option>
                                                                                <!--<option value="mouth">mouth</option> -->
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row" id="head_section1" style="margin-bottom:2rem;display:none;">
                                                                        <label for="place_head" class="col-form-label col-sm-3" title="Place on head" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Place on Head</label>
                                                                        <div class="col-sm-3">
                                                                            <label class="switch">
                                                                                <input type="checkbox" id="place_head" value="place_head" name="place_head" checked />
                                                                                <span class="slider round"></span>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row" id="head_section2" style="margin-bottom:2rem;display:none;">
                                                                        <label for="above_head" class="col-form-label col-sm-3" title="Above head" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Above Head</label>
                                                                        <div class="col-sm-3">
                                                                            <label class="switch"><input type="checkbox" id="above_head" value="above_head" name="place_head" />
                                                                                <span class="slider round"></span></label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row exp_create" style="margin-bottom:2rem;">
                                                                        <label for="exp_name" class="col-sm-3 col-form-label exp_name_text" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Experience Name</label>

                                                                        <div class="col-sm-9">
                                                                            <input type="text" name="exp_name" id="exp_nm" class="form-control" placeholder="Please provide name">
                                                                            <span id="slugExpName" class="m-0 p-0" style="display:none;"></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row" id="enable_splash_section" style="margin-bottom:2rem;display:none;">
                                                                        <label for="enable_splash" class="col-form-label col-sm-3 label_design" title="Splash Setting">Enable Splash Image</label>
                                                                        <div class="col-sm-3">
                                                                            <label class="switch">
                                                                                <input type="checkbox" id="enable_splash" value="TRUE" name="enable_splash" data-bs-toggle="tooltip" data-bs-placement="left" title="Switch it on." />
                                                                                <span class="slider round"></span>
                                                                            </label>
                                                                        </div>

                                                                        <!-- <label for="use_gallery" class="col-form-label col-sm-3" title="Use Gallery" >Use Gallery</label> -->
                                                                        <!-- <div class="col-sm-6">
                                            <button type="button" class="form-control" id="use_gallery" >Use Gallery</button>
                                            <span id="use_gallery_status" class="m-0 p-0" style="display:none;"></span>
                                        </div> -->
                                                                    </div>
                                                                    <div class="form-group row" id="splash_screen" style="margin-bottom:2rem; display:none;">
                                                                        <label for="splash_screen_up" class="col-sm-3 col-form-label splash_label" title="Tossing Model" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Splash Image</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="file" name="animation_file" id="splash_screen_up" class="file-upload-default" />
                                                                            <div class="input-group col-xs-12">
                                                                                <input type="text" id="splash_screen_ip" class="form-control file-upload-info" disabled="" placeholder="Upload panel image in jpg / png format." />
                                                                                <span class="input-group-append">
                                                                                    <button class="file-upload-browse btn btn-info" type="button" id="up_6" style="border-radius: 10px; position: absolute; margin-left: -150px; height: 45px; width: 150px;" data-bs-toggle='tooltip' data-bs-placement='left' title='Upload Model'>Upload</button>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row" id="face_game_type_section" style="margin-bottom:2rem;display:none;">
                                                                        <label for="face_game_type" id="face_game_type_label" class="col-sm-3 col-form-label label_design">Face Game Type</label>
                                                                        <div class="col-sm-9">
                                                                            <select name="face_game_type" id="face_game_type" class="form-control">
                                                                                <option value="angry_pokemon">Angry Pokemon</option>
                                                                                <option value="favorite_food">Favorite Food</option>
                                                                                <option value="tarot_reading">Tarot Reading</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row countrowImage imagename_section1" style="margin-bottom:2rem;display:none;">
                                                                        <label for="imagename" class="col-sm-3 col-form-label label_design">Image Name</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" name="imagename" id="imagename" class="form-control" placeholder="Please provide image name for your AR Experience.">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row exp_create" id="content_file" style="margin-bottom:2rem;">
                                                                        <label for="3d_file_up" class="col-sm-3 col-form-label content_label" title="Content File" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Content File</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="file" name="three_d_model_file" id="3d_file_up" class="file-upload-default">
                                                                            <div class="input-group col-xs-12">
                                                                                <input type="text" id="three_d_model_file_ip" class="form-control file-upload-info" disabled="" placeholder="Upload content file in GLB, jpg, png and MP4 format." />
                                                                                <span class="input-group-append">
                                                                                    <button class="file-upload-browse btn btn-info" type="button" id="up_4" style="border-radius: 10px; position: absolute; margin-left: -150px; height: 45px; width: 150px;" data-bs-toggle='tooltip' data-bs-placement='left' title='Upload Model'>Upload</button>
                                                                                </span>
                                                                            </div>
                                                                            <span style="display:none" id="preview-6">&nbsp;<img id="blah_6" src="#" alt="" style="width:80px"><a href="javascript:void(0)" id="rmPreview_6">Remove</a><span>
                                                                        </div>
                                                                    </div>
                                                                    <div id="multiple_faceimage" style="display:block;"></div>

                                                                    <div class="form-group row add_fimage_section" style="margin-bottom:2rem;display:none;">
                                                                        <div class="col-sm-8"></div>
                                                                        <div class="col-sm-4"><span>All 11 images required.</span>
                                                                            <a id="add_image_btn" class="btn btn-info">Add Image</a>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row add_content_section" style="margin-bottom:2rem; display:none;">
                                                                        <div class="col-sm-8"></div>
                                                                        <div class="col-sm-4"><span>All 10 GLB Files required.</span>
                                                                            <button id="add_content_btn" class="btn btn-info">Add Content</button>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row" id="append_field">
                                                                        <input type="hidden" value="1" id="total_chq">
                                                                        <div class="start_field" style="width: 100%;">
                                                                            <div class="form-group row " style="margin-bottom:2rem;" id="lipstick_color">
                                                                                <label class="col-sm-3 col-form-label" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500; padding-left: 1.5em;">Choose Color</label>
                                                                                <div class="col-sm-9">
                                                                                    <input type="color" name="lipstick_colour_1" id="favcolor" class="form-control" value="#ff0000">
                                                                                </div>
                                                                            </div>

                                                                            <!--    <div class="form-group row" id="texture_setting_section" style="margin-bottom:2rem;">
                                                                                <label for="texture_setting" class="col-form-label col-sm-3 light_setting_label" title="sound File" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500; padding-left: 1.5em;">Texture</label>
                                                                                <div class="col-sm-1"> <label style="float:right" class="mt-1">
                                                                                        <h5>Glossy</h5>
                                                                                    </label></div>
                                                                                <div class="col-sm-1 text-center">
                                                                                    <label class="switch" style="width:68px;">
                                                                                        <input type="hidden" name="texture_1" value="0" class="get_texture">
                                                                                        <input type="checkbox" name="texture_1" class="light_setting_input" value="1" id="texture_setting" style="border: 1px solid #ffffff !important;" /><span class="slider round"></span>
                                                                                    </label>
                                                                                </div>
                                                                                <div class="col-sm-1"> <label class="mt-1">
                                                                                        <h5>Matte</h5>
                                                                                    </label></div>
                                                                            </div> -->
                                                                        </div>
                                                                    </div>

                                                                    <div class="d-flex justify-content-end col-11  mb-3" id="btn_container">
                                                                        <a href="javascript:void(0);" onclick="remove_clr()" class=" add_more_clr me-4 btn" style="background-color:red !important; color:white;">Remove</a>
                                                                        <a href="javascript:void(0);" onclick="add_clr()" class="btn btn-info">Add More</a>
                                                                    </div>
                                                                    <!--   <div class="form-group row" id="light_setting_section" style="margin-bottom:2rem; display:none;">
                                        <label for="light_setting" class="col-form-label col-sm-3 light_setting_label" title="sound File" >Light Setting Range</label>
                                        <div class="col-sm-9">
                                            <input type="range" name="light_range" class="form-range form-control light_setting_input" min="0" max="5" id="light_setting" value="2" style="border: 1px solid #ffffff !important;" />
                                            <span id="getvalue"></span>
                                        </div>
                                    </div> -->
                                                                    <div class="form-group row gacode_exp_field" style="margin-bottom:2rem;">
                                                                        <label for="gacode_exp" class="col-sm-3 col-form-label gacode_exp_text label_design">GA Code</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" name="ga_code_exp" id="ga_code_exp" maxlength="15" class="form-control" placeholder="Please provide GA Code">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row redirect_section" style="margin-bottom:2rem;">
                                                                        <label for="redirect_to_website" class="col-form-label col-sm-3 label_design" title="Redirect to Website Link">Enable Redirection</label>
                                                                        <div class="col-sm-3">
                                                                            <label class="switch"><input type="checkbox" id="redirect_to_website" value="TRUE" name="redirect_to_website" data-bs-toggle="tooltip" data-bs-placement="left" title="Redirect to website after experience complete" /><span class="slider round"></span></label>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row" id="redirect_input_section" style="margin-bottom:2rem; display:none;">
                                                                        <label for="redirect_time" class="col-form-label col-sm-3 redirect_time_label" title="Redirection Time">Redirection Time</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="range" name="redirection_time" class="form-range form-control redirect_time_input" min="10" max="60" id="redirect_time" value="10" step="5" style="border: 1px solid #ffffff !important;" />
                                                                            <span id="getvalue_redirect_time">10 Seconds</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row" id="action" style="margin-bottom:2rem; display:none;">
                                                                        <label for="action" id="action_label" class="col-sm-3 col-form-label" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Choose Action</label>

                                                                        <div class="col-sm-9">
                                                                            <select name="action" id="action" class="form-control">
                                                                                <option value="tap">Tap</option>
                                                                                <option value="shake">Shake</option>
                                                                                <option value="swipe">Swipe</option>
                                                                                <option value="parallel">Parallel</option>
                                                                                <option value="timed">Timed</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row" id="panel_image" style="margin-bottom:2rem; display:none;">
                                                                        <label for="panel_image_up" class="col-sm-3 col-form-label panel_label" title="Panel Image" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Panel Image</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="file" name="image_file" id="panel_image_up" class="file-upload-default" />
                                                                            <div class="input-group col-xs-12">
                                                                                <input type="text" id="panel_image_ip" class="form-control file-upload-info" disabled="" placeholder="Upload panel image in jpg / png format." />
                                                                                <span class="input-group-append">
                                                                                    <button class="file-upload-browse btn btn-info" type="button" id="up_7" style="border-radius: 10px; position: absolute; margin-left: -150px; height: 45px; width: 150px;" data-bs-toggle='tooltip' data-bs-placement='left' title='Upload Model'>Upload</button>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row" id="tossing_model" style="margin-bottom:2rem; display:none;">
                                                                        <label for="tossing_model_up" class="col-sm-3 col-form-label tossing_label" title="Tossing Model" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Tossing Model</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="file" name="tossing_model" id="tossing_model_up" class="file-upload-default" />
                                                                            <div class="input-group col-xs-12">
                                                                                <input type="text" id="tossing_model_ip" class="form-control file-upload-info" disabled="" placeholder="Tossing 3d model in GLB (Must less than 1 mb)" />
                                                                                <span class="input-group-append">
                                                                                    <button class="file-upload-browse btn btn-info" type="button" id="up_5" style="border-radius: 10px; position: absolute; margin-left: -150px; height: 45px; width: 150px;" data-bs-toggle='tooltip' data-bs-placement='left' title='Upload Model'>Upload</button>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row" id="tossing_model_pic" style="margin-bottom:2rem; display:none;">
                                                                        <label for="tossing_image_modal" class="col-sm-3 col-form-label splash_label" title="Tossing Model" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Tossing Model Image</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="file" name="tossing_image_modal" id="tossing_image_modal" class="file-upload-default" />
                                                                            <div class="input-group col-xs-12">
                                                                                <input type="text" id="tossing_model_imge_ip" class="form-control file-upload-info" disabled="" placeholder="Upload panel image in jpg / png format." />
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
                                                                                <input type="checkbox" id="transparent_background" value="Yes" name="transparent_background" data-bs-toggle='tooltip' data-bs-placement='left' title='Switch it on to transparent the background.' />
                                                                                <span class="slider round"></span>
                                                                            </label>
                                                                            <label for="transparent_background_label" style="color: #c2c2c2;font-size: 16px;margin-top: 9px;font-weight: 500;">Transparent Background</label>
                                                                        </div>
                                                                        <label class="col-form-label col-sm-3" for="background_color" style="color: #c2c2c2;font-size: 16px;margin-top: 9px;font-weight: 500;">Background Color</label>
                                                                        <div class="col-sm-3">
                                                                            <input class="form-control" type="color" id="background_color" name="button_color" placeholder="Please provide RGB color." data-bs-toggle='tooltip' data-bs-placement='left' title='Green Screen Background Color' />
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row" id="audio_file_section" style="margin-bottom:2rem; display:none;">
                                                                        <label for="audio_file_up" class="col-form-label col-sm-3 audio_label" title="Audio File" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Audio File</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="file" name="audio" id="audio_file_up" class="file-upload-default" accept="audio/*" />
                                                                            <div class="input-group col-xs-12">
                                                                                <input type="text" id="audio_file_ip" class="form-control file-upload-info" placeholder="Upload audio file in MP3 format." disabled />
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
                                                                                <input type="checkbox" id="audio_setting" value="on" name="startAudio" />
                                                                                <span class="slider round"></span>
                                                                            </label>
                                                                            <label for="audio_setting" class="audio_setting_desc" style="color: #c2c2c2;font-size: 16px;margin-top: 9px;font-weight: 500;">(Toggle the switch to start audio on deployment of model.)</label>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row" id="sound_file_section" style="margin-bottom:2rem; display:none;">
                                                                        <label for="sound_file_up" class="col-form-label col-sm-3 sound_label" title="sound File" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Target Audio</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="file" name="sound" id="sound_file_up" class="file-upload-default" />
                                                                            <div class="input-group col-xs-12">
                                                                                <input type="text" id="sound_file_ip" class="form-control file-upload-info" placeholder="Upload sound file in MP3 format." disabled />
                                                                                <span class="input-group-append">
                                                                                    <button class="file-upload-browse btn btn-info" type="button" id="up_3" style="border-radius: 10px; position: absolute; margin-left: -150px; height: 45px; width: 150px;">Upload</button>
                                                                                </span>
                                                                            </div>
                                                                            <span style="display:none;" id="preview-2">&nbsp;<img id="blah_2" src="#" alt="" style="width:80px"><a href="javascript:void(0)" id="rmPreview_2">Remove</a><span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row exp_create" id="light_setting_section" style="margin-bottom:2rem; display:none;">
                                                                        <label for="light_setting" class="col-form-label col-sm-3 light_setting_label" title="sound File" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Light Setting Range</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="range" name="light_range" class="form-range form-control light_setting_input" min="0" max="5" step="1" id="light_setting" value="2" />
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row label_for_hide exp_create" style="margin-bottom:2rem;">
                                                                        <label for="label" class="col-sm-3 col-form-label" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Label</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" name="label" id="label" maxlength="25" class="form-control" placeholder="Please provide label for your AR Experience.">
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

                                                                    <div class="form-group row" id="dodge_collect_section" style="margin-bottom:2rem; display:none;">
                                                                        <label for="submitToContest" class="col-form-label col-sm-3 dodge_collect_label" title="Collect Setting" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Setting</label>
                                                                        <div class="col-sm-9">
                                                                            <label class="switch">
                                                                                <input type="checkbox" id="submitToContest" value="TRUE" name="submitToContest" />
                                                                                <span class="slider round"></span>
                                                                            </label>
                                                                            <label for="submitToContest" class="dodge_collect_desc" style="color: #c2c2c2;font-size: 16px;margin-top: 9px;font-weight: 500;">(By Default Collect is selected, Toggle switch to make Dodge)</label>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row" id="getFeedback_section" style="margin-bottom:2rem; display:none;">
                                                                        <label for="getFeedback" class="col-form-label col-sm-3 getFeedback" title="Get Feedback" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Get Feedback</label>
                                                                        <div class="col-sm-9">
                                                                            <label class="switch">
                                                                                <input type="checkbox" id="getFeedback" value="TRUE" name="getFeedback" />
                                                                                <span class="slider round"></span>
                                                                            </label>
                                                                            <label for="getFeedback" class="getFeedback_text" style="color: #c2c2c2;font-size: 16px;margin-top: 9px;font-weight: 500;">(Toggle switch to Get Feedback)</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row d-none" id="enable_image_clr" style="margin-bottom:2rem;">
                                                                        <label for="enable_clr" class="col-form-label col-sm-3 label_design" title="Geo location Setting">Switch To Choose Colour as Background</label>
                                                                        <div class="col-sm-3">
                                                                            <label class="switch"><input type="checkbox" id="enable_clr" value="TRUE" name="enable_clr" data-bs-toggle="tooltip" data-bs-placement="left" title="Switch it on." /><span class="slider round"></span></label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row" id="background_image_section" style="margin-bottom:2rem; display:none;">
                                                                        <label for="background_image" class="col-sm-3 col-form-label splash_label" title="Tossing Model" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Background Image</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="file" name="background_image" id="background_image" class="file-upload-default" />
                                                                            <div class="input-group col-xs-12">
                                                                                <input type="text" id="background_image_ip" class="form-control file-upload-info" disabled="" placeholder="Upload panel image in jpg / png format." />
                                                                              <span class="input-group-append pt-1">
                                                                  <button class="file-upload-browse btn" type="button" id="up_11"><span class="material-icons-outlined">file_upload</span></button>
                                                                                 </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row" id="clr_background" style="margin-bottom:2rem;display:none;">
                                                                        <label for="clr_optional" class="col-sm-3 col-form-label label_design">Background Colour</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="color" name="background_colour" id="clr_optional" class="form-control" value="#e0dede">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row website_section exp_create" style="margin-bottom:2rem;">
                                                                        <label for="about_us" class="col-sm-3 col-form-label" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Website</label>
                                                                        <div class="col-sm-9">
                                                                            <input class="form-control" type="text" id="about_us" name="about" placeholder="Please provide websit URL." />
                                                                            <p style="color: red; font-size: 12px; margin-top: 5px; font-weight: 500;"><span id="display_count"></span></p>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row game_instruction_section" style="margin-bottom:2rem; display:none;">
                                                                        <label for="game_instruction" class="col-sm-3 col-form-label" style="color: #c2c2c2; font-size: 16px; margin-top: 5px; font-weight: 500;">Game Instruction</label>
                                                                        <div class="col-sm-9">
                                                                            <textarea row="5" minlength="50" maxlength="150" class="form-control" id="game_instruction" name="text_to_display" placeholder="Please provide Instruction for game."></textarea>
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
                                                                            <input type="number" name="game_duration" id="game_duration" class="form-control" min="30" max="60" placeholder="Please provide game duration for your AR Experience." value="30" onKeypress="event.preventDefault();">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row" id="feedback_game" style="margin-bottom:2rem;display:none;">
                                                                        <label for="feedback_game" id="feedback_game_label" class="col-sm-3 col-form-label label_design">Feedback Form</label>
                                                                        <div class="col-sm-9">
                                                                            <select name="feedbackForm_game" id="feedbackForm_game" class="form-control">
                                                                                <option value="visible">Visible</option>
                                                                                <option value="hidden">Hidden</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row game_speed_section" style="margin-bottom:2rem;display:none;">
                                                                        <label for="game_speed" class="col-sm-3 col-form-label label_design">Randomize Speed (seconds)</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="number" name="image_speed" id="image_speed" class="form-control" placeholder="Please provide randomize speed for your AR Experience." value="0.5" min="0.1" max="1" step="0.1">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row redirect_btn_clr" style="margin-bottom:2rem;display:none;">
                                                                        <label for="game_speed" class="col-sm-3 col-form-label label_design">Redirect Button Colour</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="color" name="redirection_button_colour" id="redir_btn_clr" class="form-control" value="#e0dede">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row" id="enable_geolocation_section" style="margin-bottom:2rem;display:none;">
                                                                        <label for="enable_geolocation" class="col-form-label col-sm-3 label_design" title="Geo location Setting">Enable Geo Location</label>
                                                                        <div class="col-sm-3">
                                                                            <label class="switch"><input type="checkbox" id="enable_geolocation" value="TRUE" name="enable_geolocation" data-bs-toggle="tooltip" data-bs-placement="left" title="Switch it on." /><span class="slider round"></span></label>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row geoLocation_field d-none" style="margin-bottom:2rem;">
                                                                        <label for="latitude_exp" class="col-sm-3 col-form-label geoLocation_exp_text label_design">Geo Location</label>
                                                                        <div class="col-sm-3">
                                                                            <input type="number" step="any" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="11" name="latitude" id="latitude_exp" class="form-control geo_number" placeholder="Please provide Latitude (°N or °S)">
                                                                        </div>
                                                                        <div class="col-sm-3">
                                                                            <input type="number" step="any" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="11" name="longitude" id="longitude_exp" class="form-control geo_number" placeholder="Please provide Longitude (°E or °W)">
                                                                        </div>
                                                                        <div class="col-sm-3">
                                                                            <input type="number" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="5" minlength="3" name="distance" id="distance_exp" class="form-control" placeholder="Please provide Distance (m)">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row text-center">
                                                                        <div class="col-sm-3"></div>
                                                                        <div class="col-sm-6">
                                                                            <button type="submit" class="btn btn-success mr-2" id="sv1" style="background-color: white !important; color: #2680eb; border: 2px solid #2680eb; width: 250px; height: 40px; border-radius: 10px;">Save Experience</button>
                                                                        </div>
                                                                        <input type="hidden" name="client_id" id="client_id" value="<?php echo esc_html(VOSSLE__USER_ID); ?>" />
                                                                        <div class="col-sm-3"></div>
                                                                    </div>
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
                                                                                <div class="qr_code_replacable"><img src="<?php echo esc_url(VOSSLE__SERVER_URL . 'dashboard/assets/qrCode/1606852311_qrcode.png'); ?>" height="150px"></div>
                                                                                <p class="m-0 p-0">or</p>
                                                                                <div class="container">
                                                                                    <div class="row mx-4 url_div" style=" background-color:#9a9a9a; min-height: 40px; border-radius: 10px;">
                                                                                        <div class="col-10 url_replacable" style="padding: 10px;"><input type="text" class="m-0 p-0 url_changeable w-100" id="url_changeable" value="google.com" style="color: white; background: none; border: 0px; text-align: center; font-size: 1rem; height: 100%;" readonly /></div>
                                                                                        <div class="col-2" style="padding: 10px;"><i class="far fa-copy copy_and_share_url" style="font-size:2rem; color:white;"></i></div>
                                                                                        <div class="col-12 copy_status text-white bg-warning" style="display:none;">URL copied successfully.</div>
                                                                                    </div>
                                                                                </div>
                                                                                <button onclick="DownloadImage('qrcode')" class="btn qr_code_btn_replacable mt-4" id="download_btn">Download</button>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-5 marker_image_section" style="display:none;">
                                                                            <div class="text-center">
                                                                                <div class="marker_image_replacable"><img src="<?php echo esc_url(plugins_url('../assets/images/markerbased.png', __FILE__)); ?>" height="150px" /></div>
                                                                                <!-- <p class="m-0 p-0 mb-4" style="font-size:0.8rem; color:#1a3b51;"><i class="fas fa-hand-point-right"></i> <b><u>Click here</u></b> to go back to setup page.</p> -->
                                                                                <button onclick="DownloadImage('markerimage')" class="marker_image_btn_replacable mt-4 border-0"><i class="fas fa-download px-4 py-2" style="font-size: 2rem; color: #1a3b51; background-color: #efefef; border-radius: 10px;"></i></a>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-2"></div>
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
                                                                    <canvas id="imageCanvas"></canvas>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
</div>

<!-- Trigger/Open The Modal -->
<button id="myBtn" style="display:none;">Open Modal</button>

<!-- The Modal -->


<div id="overlay"></div>
<div class="modal" id="addBookDialog" style="z-index: 9999;">
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