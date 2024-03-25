=== Temperal PhotoSynth ===
Contributors: temperal
Tags: photosynth, temperal, image, optimization, transform
Requires at least: 5.0
Tested up to: 6.4.3
Stable tag: 1.0.1
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Temperal PhotoSynth is the easiest no-code solution to optimize and transform images directly in markup.
 
== Description ==
 
This is a WordPress plugin for the Temperal PhotoSynth service that provides an easy to use short code. 
The plugin may be placed in any page 
 
== Installation ==
 
1. Upload the plugin folder to your /wp-content/plugins/ folder.
1. Go to the **Plugins** page and activate photosynth.
 
== Frequently Asked Questions ==
 
= How do I get started? =
 
Visit https://www.temperal.co/ to obtain a free key. Paste the key in Settings -> Plugin Menu -> PhotoSynth Key

Use the shortcode `[photosynth-img url="..." other_attribs...]`

The following optional attributes are available:
* adaptivehistogram = 0 - 100
* blur = 0.2 - 20.0
* brightness = 0.0 - 20.0
* crop = Left,Top,Right,Bottom (comma separated int percentages 0-99)
* format: "avif", "gif", "jpeg", "png", "tiff", "webp" (default: webp)
* gamma = 1.0 - 3.0
* greyscale = true / false
* height = 1 - 5000 (omit width to preserve aspect ratio)
* hue = 0.0 - 180.0
* lightness = 0.0 - 200.0
* normalize = Lower,Upper (comma separated ints 1-99)
* saturation = 0.0 - 20.0
* sharpen = 0.1 - 10.0
* style: CSS style (defaults to 'display: block').
* url = URL of the source image
* width = 1 - 5000 (omit height to preserve aspect ratio)

= How to uninstall the plugin? =
 
Simply deactivate and delete the plugin. 
 
== Changelog ==
= 0.9 =
* Plugin released.
= 1.0 =
* Add img fallback.
= 1.0.1 =
* Add rotate param.