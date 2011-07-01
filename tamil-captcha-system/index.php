<?
   /*
	* name: tamil-captcha-system
	*
	* version: 0.2
	*
	* author: CS Shyam Sundar [ csshyamsundar AT gmail DOT com ]
	*
	* based on: Simon Jarvis's Captcha Implementation [ http://www.white-hat-web-design.co.uk/articles/php-captcha.php ]
	*
	* requirements: PHP 4/5 with GD and FreeType libraries
	*
	* licensed under: The MIT License ( http://www.opensource.org/licenses/mit-license.php )
	*	
	* (C) C S Shyam Sundar
	*	
	* Permission is hereby granted, free of charge, to any person obtaining a copy
	* of this software and associated documentation files (the "Software"), to deal
	* in the Software without restriction, including without limitation the rights
	* to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	* copies of the Software, and to permit persons to whom the Software is
	* furnished to do so, subject to the following conditions:
	*	
	* The above copyright notice and this permission notice shall be included in
	* all copies or substantial portions of the Software.
	*	
	* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	* AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	* OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
	* THE SOFTWARE.
	*
	* changelog: 
	* 6 / 7 / 2007 -- version 0.2
	* 	- modified the script from staged approach to a single source.
	*	- optimized the script for performance.
	* 5 / 7 / 2007 -- version 0.1	
	*	- initial version
	*	- used staged approach to generate captcha; due to bug in generation of random strings.
	*
	* usage:
	* [ basic usage ]
	* <img src="tamil-captcha-system/" />Security Code: <input id="security_code" name="security_code" type="text" />
	* [ advanced usage ]
	* <img src="tamil-captcha-system/?width=100&height=40&characters=5" />Security Code: <input id="security_code" name="security_code" type="text" />
	* [ checking the captcha ]
	*	<?php 
	*	   session_start();
	*	   if(($_SESSION['security_code'] == $_POST['security_code']) && (!empty($_SESSION['security_code'])) ) {
	*	      // Insert you code for processing the form here, e.g emailing the submission, entering it into a database. 
	*	      unset($_SESSION['security_code']); // this is very, very important! 
	*	   } else {
	*	      // Insert your code for showing an error message here
	*	   }
	*	?>
	*/
	
	/* charset should be specified as we are dealing with unicode fonts */
	header('Content-Type: image/jpeg; charset=UTF-8');
	session_start();
	
	/* unset, if session['security_code'] is set */
	unset($_SESSION['security_code']);
	
	/* get the variables, width, height and num. of characters */	
	$width = isset($_GET['width']) && $_GET['height'] < 600 ? $_GET['width'] : '350';
	$height = isset($_GET['height']) && $_GET['height'] < 200 ? $_GET['height'] : '60';
	$characters = isset($_GET['characters']) && $_GET['characters'] > 2 ? $_GET['characters'] : '5';

	/* characters array */
	$chars = array("அ","க","ப","ம","ல","ர","வ","1","2","3","4","5","6","7","8","9");
	
	/* pickup some random characters and create session['security_code'] */
	for ($i=0; $i<$characters; $i++){
	   $_SESSION['security_code'] .= $chars[rand(0, count($chars)-1)];
	}		
	
	$code = $_SESSION['security_code'];
	
	/* the unicode tamil font; must be present in script dir. */
	$font = 'TSCu_Veeravel_volted.ttf';	
	
	/* font size will be 60% of the image height */
	$font_size = $height * 0.60;
	$image = imagecreate($width, $height) or die('Cannot initialize new GD image stream');
	
	/* set the colours */
	$background_color = imagecolorallocate($image, 255, 255, 255);
	$text_color = imagecolorallocate($image, 20, 40, 100);
	$noise_color = imagecolorallocate($image, 100, 120, 180);
	
	/* generate random dots in background */
	for( $i=0; $i<($width*$height)/3; $i++ ) {
	 imagefilledellipse($image, mt_rand(0,$width), mt_rand(0,$height), 1, 1, $noise_color);
	}
	
	/* generate random lines in background */
	for( $i=0; $i<($width*$height)/150; $i++ ) {
	 imageline($image, mt_rand(0,$width), mt_rand(0,$height), mt_rand(0,$width), mt_rand(0,$height), $noise_color);
	}
	
	/* create textbox and add text */
	$textbox = imagettfbbox($font_size, 0, $font, $code) or die('Error in imagettfbbox function');
	$x = ($width - $textbox[4])/2;
	$y = ($height - $textbox[5])/2;
	imagettftext($image, $font_size, 0, $x, $y, $text_color, $font , $code) or die('Error in imagettftext function');
	
	/* output captcha image to browser */
	imagejpeg($image);
	imagedestroy($image);
?>