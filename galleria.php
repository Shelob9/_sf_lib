<?php
/**
*
* @ package _sf
* @ since 1.1.0
*
*/

/**
* Add Galleria JS/CSS
*
*@since _sf-1.1.0
*/

function _sf_scripts_galleria() {
	wp_enqueue_script('galleria', get_template_directory_uri().'/lib/js/galleria-1.2.9.min.js', array('jquery'), false, true);
	wp_enqueue_style('galleria', get_template_directory_uri().'/lib/css/galleria.classic.css');
}
add_action('wp_enqueue_scripts', '_sf_scripts_galleria', 10);

/**
* Galleria CSS
*/
function _sf_galleriaCSS() {
	echo "<style> #galleria{ width: 100%; height: 500px; background: #000; } </style>";
}
add_action('wp_head', '_sf_galleriaCSS');

/**
* Setup galleria and data for it
*
*@since _sf-1.1.0
*
*@params $default (required) first category in main dataset. Only on if $combine == false.
*@params $cats (required) array of category IDs to create datasets for. NOT including the default category.
*@params $combine (optional) (default = true). If set to true all data sets will be combined into one, big one, which will be default.
*@params $switches (optional) (default = true) If set to true, will create the ability to switch categories via jQuery onClick("#switch-x")
* 
*@params $desc, $link, false see _sf_galleria_data below
* $combine = true, $switches = true, $title = false, $desc = false, $link = false
*/
add_action('wp_footer', '_sf_galleriaSetup', 10000);
//*this should be gotten from options*/
$default = 5;
$cats = array(8, 6);

function _sf_galleriaSetup($default, $cats = array()) {
$combine = true;
	/*Start and wrap script */
	echo "<script> \n ";
	echo "jQuery(document).ready(function($) { \n ";
	
	/*CONSTRUCT DATA SETS */
	//defualt first
		_sf_galleria_data($default);
	foreach ($cats as $CatID) {
		_sf_galleria_data($CatID);
	}
	
	//create var data with all of them (if combine = true) and set $defaultData to "data"
	//else skip concat and set $defaultData to data. the id of the default category.
	if ($combine == true) {
			echo "var data = data".$default.".concat(";
			
			foreach ($cats as $cat) {
				echo "data".$cat.", ";
			}
			
			echo ");";
			$defaultData = "data";
	}
	else {
		$defaultData = "data".$default;
	}
	/*Setup Galleria*/
	//but first put location of theme js in a $themejs
	$themejs = get_template_directory_uri().'/lib/js/galleria.classic.min.js';
	echo "
		//left and right arrows control from keyboard
		 Galleria.ready(function() {
			var gallery = this; 
			 gallery.attachKeyboard({
					left: gallery.prev,
					right: gallery.next,
				});
			 });

		//load theme   									            			
			Galleria.loadTheme('".$themejs."');

		//configure
			Galleria.configure({
				thumbnails:'lazy',
				lightbox: true,
				dataSource: ".$defaultData.",
				responsive:true,
				height:0.9,
				//debug:false
		
			});
		//on ready functions (lazy load, auto play)
			Galleria.ready(function(){
				this.lazyLoadChunks(3);
				this.play(3000);
			});
		";
		/*run and extend galleria + setup the switches*/
		//first run the thing and add play pause toggle.
		echo "
			//run and extend
			 Galleria.run('#galleria', {
				//play/pause toggle
					extend: function() {
						var gallery = this; 
						$('#galleria-play-pause').click(function() {
							gallery.playToggle()
						});
		";
		//create switches for filter categories
		$x=0;
		foreach ($cats as $CatID) {
			$switches = "
					 $('#switch-".$x."').click(function() {
						gallery.load(data".$CatID.");
						window.setTimeout(function(){
							gallery.lazyLoadChunks(3);
						},10);
					});
			";
			echo $switches;
			$x++;
		}
		//create #all switch for switch back to complete set (data) in main menu
		echo "
				 $('#all').click(function() {
					gallery.load(data);
					window.setTimeout(function(){
						gallery.lazyLoadChunks(3);
					},10);
				});
			";
	echo " 	}
			 });";
	/** tag on the play/pause color change **/
	echo "
		 $('#g-play-pause').click(function() {
			$('#g-play-pause').toggleClass('sldPause');
		});
	";
	/*Wrap Up and Get Out*/
	echo " \n }); //end no conflict wrapper for galleria init";
	echo " \n </script>";
}

/**
* Function to create the data sets.
*
*@since _sf-1.1.0
*
*@params $CatID (required) ID of categories to create data sets for.
*@params $title, $desc, $link (optional) (default false) If true add title (the_title()), description (the_excerpt()) and link (the_permalink()) to the datas
/* $title = false, $desc = false, $link = false
*/

function _sf_galleria_data($CatID) {
	//setup before and after for data sets
	$startData = "var data".$CatID." = [ \n ";
	$endData = "]; \n"; 

	//first start the data set
	echo $startData;

	//then get the posts in that are in the category and are image posts.
	$cat_posts = get_posts( array(
		'cat' => $CatID,
		'posts_per_page' => -1,
		'nopaging' => true,
	) );
	//setup post data
		global $post;
		setup_postdata( $post );
	//then loop through posts in the category putting together the data as needed.
	foreach( (array) $cat_posts as $post ) {
		//get all the shit we need together
		$ID = get_post_thumbnail_id();
		$thumb = wp_get_attachment_image_src($ID);
		$full = wp_get_attachment_image_src($ID, 'full');
		$large = wp_get_attachment_image_src($ID, 'large');
		$title = get_the_title();
		$desc = get_the_excerpt();
		//Kirk: Prepare to echo. Sulu: Preparing to echo.
		$out = "{ \n ";
		$out .= "image: '".$large[0]."', \n ";
		$out .= "thumb: '".$thumb[0]."', \n ";
		$out .= "big: '".$full[0]."', \n ";
		$out .= "title: '".$title."', \n ";
		$out .= "description: '".$desc."', \n ";
		//$out .= "link: '".$link."', 
		
	   $out .= "}, \n ";
	   //Kirk: ECHO!
	   echo $out;
	}
	//then end the data set.
	echo $endData;
	//then reset post data
	wp_reset_postdata();
}
