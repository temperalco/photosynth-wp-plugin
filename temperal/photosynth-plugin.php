<?php
/**
* Plugin Name: photosynth
* Plugin URI: https://temperal.co/
* Description: Temperal PhotoSynth: no-code image processing
* Version: 1.0.1
* Author: temperal
* Author URI: https://github.com/temperalco/
* License: GPLv2
* License URI: http://www.gnu.org/licenses/gpl-2.0.html
* Tested up to: 6.4.3
**/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
define( 'PHOTOSYNTH_URL', 'https://ps.temperal.co/ps' );
define( 'PHOTOSYNTH_FORMATS', ["avif", "gif", "jpeg", "png", "tiff", "webp"]);

add_shortcode('photosynth-img', 'photosynth_img');

function photosynth_img($attributes, $content, $shortcode_name) {
  $psUrl = get_option('photosynth_url');
  $key = get_option('photosynth_key');
  if (empty($key)) {
    return "Please supply the PhotoSynth key";
  }
  $url = $attributes['url'];
  if (!isset($url)) {
    return "Please supply the image url";
  }
  $style = "display:block";
  if (isset($attributes['style'])) {
    $style = $attributes['style'];
  }
  $onerror = "this.onerror=null;this.src='{$url}'";
  $imageUrl = "<img style='{$style}' onerror=\"$onerror\" src='{$psUrl}/u={$url},k={$key}";

  if (isset($attributes['adaptivehistogram'])) {
    $adaptivehistogram = $attributes['adaptivehistogram'];
    if (!photosynth_validateRange($adaptivehistogram, 0, 100)) {
      return "Adaptive histogram {$adaptivehistogram} must be an int from 0-100";
    }
    $imageUrl .= ",ah={$adaptivehistogram}";
  }
  if (isset($attributes['blur'])) {
    $blur = $attributes['blur'];
    if (!photosynth_validateRange($blur, 0.2, 20, true)) {
      return "Blur {$blur} must be a float from 0.2-20";
    }
    $imageUrl .= ",b={$blur}";
  }
  if (isset($attributes['brightness'])) {
    $brightness = $attributes['brightness'];
    if (!photosynth_validateRange($brightness, 0, 20, true)) {
      return "Brightness {$brightness} must be a float from 0-20";
    }
    $imageUrl .= ",br={$brightness}";
  }
  if (isset($attributes['crop'])) {
    $crop = $attributes['crop'];
    if (!photosynth_validateMulti($crop, 0, 99)) {
      return "Crop {$crop} must be ints '#,#,#,#' from 0-99";
    }
    $imageUrl .= ",c={$crop}";
  }
  if (isset($attributes['gamma'])) {
    $gamma = $attributes['gamma'];
    if (!photosynth_validateRange($gamma, 1, 3, true)) {
      return "gamma {$gamma} must be a float from 1-3";
    }
    $imageUrl .= ",ga={$gamma}";
  }
  if (isset($attributes['greyscale'])) {
    $greyscale = $attributes['greyscale'];
    $greyscaleParsed = ($greyscale === 'true') ? 'true' : 'false';
    $imageUrl .= ",gr={$greyscaleParsed}";
  }
  if (isset($attributes['hue'])) {
    $hue = $attributes['hue'];
    if (!photosynth_validateRange($hue, 0, 180, true)) {
      return "Hue {$hue} must be a float from 0-180";
    }
    $imageUrl .= ",hu={$hue}";
  }
  if (isset($attributes['lightness'])) {
    $lightness = $attributes['lightness'];
    if (!photosynth_validateRange($lightness, 0, 200, true)) {
      return "Lightness {$lightness} must be a float from 0-200";
    }
    $imageUrl .= ",l={$lightness}";
  }
  if (isset($attributes['normalize'])) {
    $normalize = $attributes['normalize'];
    if (!photosynth_validateMulti($normalize, 1, 99)) {
      return "Normalize {$normalize} must be ints '#,#' from 1-99";
    }
    $imageUrl .= ",n={$normalize}";
  }
  if (isset($attributes['rotate'])) {
    $rotate = $attributes['rotate'];
    if (!photosynth_validateRange($rotate, -360, 360, true)) {
      return "Rotate {$rotate} must be a float from -360 to 360";
    }
    $imageUrl .= ",r={$rotate}";
  }
  if (isset($attributes['saturation'])) {
    $saturation = $attributes['saturation'];
    if (!photosynth_validateRange($saturation, 0, 20, true)) {
      return "Saturation {$saturation} must be a float from 0-20";
    }
    $imageUrl .= ",s={$saturation}";
  }
  if (isset($attributes['sharpen'])) {
    $sharpen = $attributes['sharpen'];
    if (!photosynth_validateRange($sharpen, 0.1, 10, true)) {
      return "Sharpen {$sharpen} must be a float from 0.1-10";
    }
    $imageUrl .= ",sh={$sharpen}";
  }
  if (isset($attributes['height'])) {
    $height = $attributes['height'];
    if (!photosynth_validateRange($height, 1, 5000)) {
      return "Height {$height} must be an int from 1-5000";
    }
    $imageUrl .= ",h={$height}";
  }
  if (isset($attributes['width'])) {
    $width = $attributes['width'];
    if (!photosynth_validateRange($width, 1, 5000)) {
      return "Width {$width} must be an int from 1-5000";
    }
    $imageUrl .= ",w={$width}";
  }

  if (isset($attributes['format'])) {
    $format = $attributes['format'];
    if (!in_array($format, PHOTOSYNTH_FORMATS)) {
      return "Format must be one of " . PHOTOSYNTH_FORMATS;
    }
    $imageUrl .= ",o={$format}";
  }

  return "{$imageUrl}'/>";
}

function photosynth_validateRange($value, $min, $max, $isFloat = false) {
  $parsedVal = 0;
  if ($isFloat) {
    $parsedVal = floatval($value);
    if (!is_float($parsedVal)) return false;
  }
  else {
    if ((int) $value != $value) return false;
    $parsedVal = intval($value);
  }
  if ($value < $min) return false;
  if ($value > $max) return false;
  return true;
}

function photosynth_validateMulti($values, $min, $max, $isFloat = false) {
  $val_arr = explode (",", $values);  
  foreach ($val_arr as $val) {
    if (!photosynth_validateRange($val, $min, $max, $isFloat)) return false;
  }
  return true;
}

// Settings page
function photosynth_register_settings() {
  add_option( 'photosynth_url', PHOTOSYNTH_URL);
  register_setting( 'photosynth_options_group', 'photosynth_url', 'photosynth_callback' );
  add_option( 'photosynth_key', '');
  register_setting( 'photosynth_options_group', 'photosynth_key', 'photosynth_callback' );
}
add_action( 'admin_init', 'photosynth_register_settings' );

function photosynth_register_options_page() {
  add_options_page('PhotoSynth Configuration', 'Plugin Menu', 'manage_options', 'photosynth-img', 'photosynth_options_page');
}
add_action('admin_menu', 'photosynth_register_options_page');

function photosynth_options_page()
{
  ?>
    <div>
      <h2><a href="https://www.temperal.co/">Temperal PhotoSynth</a> Settings</h2>
      <form method="post" action="options.php">
        <?php settings_fields( 'photosynth_options_group' ); ?>
        <table>
          <tr>
            <th scope="row"><label for="photosynth_url">PhotoSynth URL</label></th>
            <td><input type="text" id="photosynth_url" name="photosynth_url" value="<?php echo esc_url('photosynth_url'); ?>" /></td>
          </tr>
          <tr>
            <th scope="row"><label for="photosynth_key">PhotoSynth Key</label></th>
            <td><input type="text" id="photosynth_key" name="photosynth_key" value="<?php echo esc_attr('photosynth_key'); ?>" /></td>
          </tr>
        </table>
        <?php submit_button(); ?>
      </form>
    </div>
  <?php
}
