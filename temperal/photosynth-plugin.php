<?php
/**
* Plugin Name: photosynth
* Plugin URI: https://temperal.co/
* Description: Temperal PhotoSynth: no-code image processing
* Version: 0.9
* Author: temperal
* Author URI: https://github.com/temperalco/
* License: GPLv2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html
**/

define( 'PHOTOSYNTH_URL', 'https://ps.temperal.co/ps' );

add_shortcode('photosynth-img', 'ps_img');

function ps_img($attributes, $content, $shortcode_name) {
  $psUrl = get_option('temperal_photosynth_url');
  $key = get_option('temperal_photosynth_key');
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
  $imageUrl = "<img style='{$style}' src='{$psUrl}?u={$url}&k={$key}";

  if (isset($attributes['adaptivehistogram'])) {
    $adaptivehistogram = $attributes['adaptivehistogram'];
    if (!validateRange($adaptivehistogram, 0.01, 100, true)) {
      return "Adaptive histogram {$adaptivehistogram} must be an int from 0.01-100";
    }
    $imageUrl .= "&ah={$adaptivehistogram}";
  }
  if (isset($attributes['blur'])) {
    $blur = $attributes['blur'];
    if (!validateRange($blur, 0.2, 20, true)) {
      return "Blur {$blur} must be a float from 0.2-20";
    }
    $imageUrl .= "&b={$blur}";
  }
  if (isset($attributes['crop'])) {
    $crop = $attributes['crop'];
    if (!validateMulti($crop, 0, 99)) {
      return "Crop {$crop} must be ints '#,#,#,#' from 0-99";
    }
    $imageUrl .= "&c={$crop}";
  }
  if (isset($attributes['gamma'])) {
    $gamma = $attributes['gamma'];
    if (!validateRange($gamma, 1, 3, true)) {
      return "gamma {$gamma} must be a float from 1-3";
    }
    $imageUrl .= "&ga={$gamma}";
  }
  if (isset($attributes['greyscale'])) {
    $greyscale = $attributes['greyscale'];
    $greyscaleParsed = ($greyscale === 'true') ? 'true' : 'false';
    $imageUrl .= "&gr={$greyscaleParsed}";
  }
  if (isset($attributes['hue'])) {
    $hue = $attributes['hue'];
    if (!validateRange($hue, 0, 180, true)) {
      return "Hue {$hue} must be a float from 0-180";
    }
    $imageUrl .= "&hu={$hue}";
  }
  if (isset($attributes['lightness'])) {
    $lightness = $attributes['lightness'];
    if (!validateRange($lightness, 0, 200, true)) {
      return "Lightness {$lightness} must be a float from 0-200";
    }
    $imageUrl .= "&l={$lightness}";
  }
  if (isset($attributes['normalize'])) {
    $normalize = $attributes['normalize'];
    if (!validateMulti($normalize, 1, 99)) {
      return "Normalize {$normalize} must be ints '#,#' from 1-99";
    }
    $imageUrl .= "&n={$normalize}";
  }
  if (isset($attributes['saturation'])) {
    $saturation = $attributes['saturation'];
    if (!validateRange($saturation, 0, 20, true)) {
      return "Saturation {$saturation} must be a float from 0-20";
    }
    $imageUrl .= "&s={$saturation}";
  }
  if (isset($attributes['sharpen'])) {
    $sharpen = $attributes['sharpen'];
    if (!validateRange($sharpen, 0.1, 10, true)) {
      return "Sharpen {$sharpen} must be a float from 0.1-10";
    }
    $imageUrl .= "&sh={$sharpen}";
  }
  if (isset($attributes['height'])) {
    $height = $attributes['height'];
    if (!validateRange($height, 1, 10000)) {
      return "Height {$height} must be an int from 1-10000";
    }
    $imageUrl .= "&h={$height}";
  }
  if (isset($attributes['width'])) {
    $width = $attributes['width'];
    if (!validateRange($width, 1, 10000)) {
      return "Width {$width} must be an int from 1-10000";
    }
    $imageUrl .= "&w={$width}";
  }

  return "{$imageUrl}'/>";
}

function validateRange($value, $min, $max, $isFloat = false) {
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

function validateMulti($values, $min, $max, $isFloat = false) {
  $val_arr = explode (",", $values);  
  foreach ($val_arr as $val) {
    if (!validateRange($val, $min, $max, $isFloat)) return false;
  }
  return true;
}

// Settings page
function temperal_photosynth_register_settings() {
  add_option( 'temperal_photosynth_url', PHOTOSYNTH_URL);
  register_setting( 'temperal_photosynth_options_group', 'temperal_photosynth_url', 'temperal_photosynth_callback' );
  add_option( 'temperal_photosynth_key', '');
  register_setting( 'temperal_photosynth_options_group', 'temperal_photosynth_key', 'temperal_photosynth_callback' );
}
add_action( 'admin_init', 'temperal_photosynth_register_settings' );

function photosynth_register_options_page() {
  add_options_page('PhotoSynth Configuration', 'Plugin Menu', 'manage_options', 'photosynth-img', 'photosynth_options_page');
}
add_action('admin_menu', 'photosynth_register_options_page');

function photosynth_options_page()
{
  ?>
    <div>
      <?php screen_icon(); ?>
      <h2><a href="https://www.temperal.co/">Temperal PhotoSynth</a> Settings</h2>
      <form method="post" action="options.php">
        <?php settings_fields( 'temperal_photosynth_options_group' ); ?>
        <table>
          <tr>
            <th scope="row"><label for="temperal_photosynth_url">PhotoSynth URL</label></th>
            <td><input type="text" id="temperal_photosynth_url" name="temperal_photosynth_url" value="<?php echo get_option('temperal_photosynth_url'); ?>" /></td>
          </tr>
          <tr>
            <th scope="row"><label for="temperal_photosynth_key">PhotoSynth Key</label></th>
            <td><input type="text" id="temperal_photosynth_key" name="temperal_photosynth_key" value="<?php echo get_option('temperal_photosynth_key'); ?>" /></td>
          </tr>
        </table>
        <?php submit_button(); ?>
      </form>
    </div>
  <?php
}
