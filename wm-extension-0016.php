<?php
/*
 * Plugin Name: Woomelly Extension 016 Add ons 
 * Version: 1.1.0
 * Plugin URI: https://woomelly.com
 * Description: Woomelly custom extension.
 * Author: Team MakePlugins
 * Author URI: https://woomelly.com
 * Requires at least: 4.0
 * Tested up to: 5.1.1
 * WC requires at least: 3.0.0
 * WC tested up to: 3.5.6
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'wm_clean_attributes_variation_ext_016' ) ) {
	add_filter( 'wmfilter_woomelly_clean_variation_field', 'wm_clean_attributes_variation_ext_016', 10, 3 );
	add_filter( 'wmfilter_woomelly_clean_attributes_variation_field', 'wm_clean_attributes_variation_ext_016', 10, 3 );
	function wm_clean_attributes_variation_ext_016 ( $wm_attributes_variation_temp, $available_variation, $_product ) {
		$variations = $wm_attributes_variation_temp;
		if ( !empty($variations) ) {
			if ( is_object($_product) ) {
				$is_ok = false;
				$wm_printdesign = get_option( 'wm_printdesign_ext_016', '');
				if ( $wm_printdesign != "" ) {
					$list_printdesign = explode(',', $wm_printdesign);
					if ( !empty($list_printdesign) && in_array($_product->get_id(), $list_printdesign) ) {
						$variations[] = array( 'id' => 'PRINT_DESIGN', 'value_name' => '-', 'attribute_name' => '', 'attribute_value' => '-' );
						$is_ok = true;
					}
				}
				if ( $is_ok == false ) {
					$wm_fabricdesign = get_option( 'wm_fabricdesign_ext_016', '');
					if ( $wm_fabricdesign != "" ) {
						$list_fabricdesign = explode(',', $wm_fabricdesign);
						if ( !empty($list_fabricdesign) && in_array($_product->get_id(), $list_fabricdesign) ) {
							$variations[] = array( 'id' => 'FABRIC_DESIGN', 'value_name' => '-', 'attribute_name' => '', 'attribute_value' => '-' );
							$is_ok = true;
						}
					}					
				}
			}
		}
		return $variations;
	}
}

if ( ! function_exists( 'wm_filter_fix_errors_ext_016' ) ) {
	add_filter( 'woomelly_filter_fix_errors', 'wm_filter_fix_errors_ext_016', 10, 5 );
	function wm_filter_fix_errors_ext_016 ( $validate, $item_meli, $errors, $result_meli, $code ) {
		if ( in_array('164', $errors) ) {
			if ( isset($item_meli['variations']) && is_array($item_meli['variations']) && !empty($item_meli['variations']) ) {
				$new_variations = array();
				foreach ( $item_meli['variations'] as $value ) {
					if ( isset($value['id']) ) {
						unset($value['id']);
					}
					$new_variations[] = $value;
				}
				unset($item_meli['variations']);
				$item_meli['variations'] = $new_variations;
			}
			$r = WMeli::put_item( $code, $item_meli, true );
			return $r;
		}
	}
}

if ( ! function_exists( 'woomelly_admin_menu_ext_016' ) ) {
	add_action( 'admin_menu', 'woomelly_admin_menu_ext_016', 10 );
	function woomelly_admin_menu_ext_016() {
		add_menu_page( 'WM Extesion 016', 'WM Extesion 016', 'manage_options', 'wm-extension016-menu', 'wm_extension016_menu', '', 72 );
	}
	function wm_extension016_menu() {
        $wm_printdesign = "";
        $wm_fabricdesign = "";

        if ( isset($_POST['wm_submit_strings']) ) {
            if ( isset($_POST['wm_printdesign']) ) {
            	$wm_printdesign = trim( $_POST['wm_printdesign'] );
            	if ( $wm_printdesign != "" ) {
            		$list_printdesign = explode(',', $wm_printdesign);
            		if ( !empty($list_printdesign) ) {
            			$new_list = array();
            			foreach ( $list_printdesign as $value ) {
            				if ( !in_array($value, $new_list) ) {
            					$new_list[] = $value;
            				}
            			}
            			$wm_printdesign = implode(',', $new_list);
            			unset($new_list);
            		} else {
            			$wm_printdesign = "";
            		}
            	}
            }
            if ( isset($_POST['wm_fabricdesign']) ) {
            	$wm_fabricdesign = trim( $_POST['wm_fabricdesign'] );
            	if ( $wm_fabricdesign != "" ) {
            		$list_fabricdesign = explode(',', $wm_fabricdesign);
            		if ( !empty($list_fabricdesign) ) {
            			$new_list = array();
            			foreach ( $list_fabricdesign as $value ) {
            				if ( !in_array($value, $new_list) ) {
            					$new_list[] = $value;
            				}
            			}
            			$wm_fabricdesign = implode(',', $new_list);
            		} else {
            			$wm_fabricdesign = "";
            		}
            	}
            }
            update_option( 'wm_printdesign_ext_016', $wm_printdesign);
            update_option( 'wm_fabricdesign_ext_016', $wm_fabricdesign);
            echo '<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible"><p><strong>Ajustes guardados.</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Descartar este aviso.</span></button></div>';
		}
        $wm_printdesign = get_option( 'wm_printdesign_ext_016', '');
        $wm_fabricdesign = get_option( 'wm_fabricdesign_ext_016', '');
    	?>
    	<div class="wrap">
    		<h2 class="uk-heading-divider"><?php echo __("WM Extesion 016", "woomelly"); ?></h2><br>
			<div style="padding-top: 15px;">
                <form action="" method="post">
                    <table class="form-table">
                        <tbody>
                            <tr>
                                <th scope="row"><label for="blogname">PRINT DESIGN</label></th>
                                    <td><input name="wm_printdesign" type="text" id="wm_printdesign" value="<?php echo $wm_printdesign; ?>" class="regular-text"></td>
                                </tr>
                            <tr>
                            <tr>
                                <th scope="row"><label for="blogname">FABRIC DESIGN</label></th>
                                    <td><input name="wm_fabricdesign" type="text" id="wm_fabricdesign" value="<?php echo $wm_fabricdesign; ?>" class="regular-text"></td>
                                </tr>
                            <tr>
                        </tbody>
                    </table>
                    <p class="submit"><input type="submit" name="wm_submit_strings" id="wm_submit_strings" class="button button-primary" value="Guardar cambios"></p>
                </form>
            </div>
    	<?php
	}
}

if ( ! function_exists( 'woomelly_sync_first_variation_img_ext_016' ) ) {
    add_filter( 'woomelly_sync_first_variation_img', 'woomelly_sync_first_variation_img_ext_016', 10, 1 );
    function woomelly_sync_first_variation_img_ext_016( $value ) {
        return false;
    }
}

?>