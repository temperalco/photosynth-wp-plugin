=== Temperal PhotoSynth ===
Contributors: temperal
Tags: photosynth, temperal, image, processing, management, optimization
Requires at least: 6.0
Tested up to: 6.4.1
Stable tag: 0.9
License: GPLv2 or later
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
* adaptivehistogram = 0.01 - 100.0
* blur = 0.2 - 20.0
* crop = Left,Top,Right,Bottom (comma separated int percentages 0-99)
* style: CSS style (defaults to 'display: block').
* gamma = 1.0 - 3.0
* greyscale = true / false
* height = 1 - 10000 (omit width to preserve aspect ratio)
* hue = 0.0 - 180.0
* lightness = 0.0 - 200.0
* normalize = Lower,Upper (comma separated ints 1-99)
* saturation = 0.0 - 20.0
* sharpen = 0.1 - 10.0
* url = URL of the source image
* width = 1 - 10000 (omit height to preserve aspect ratio)

= How to uninstall the plugin? =
 
Simply deactivate and delete the plugin. 
 
== Changelog ==
= 0.9 =
* Plugin released.
