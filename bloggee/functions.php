<?php

function gee_getbaseurl(){
	//Base URL
	$baseurl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	return str_replace("/admin.php", "", $baseurl);
}

function gee_getextension($string){
	return substr(strrchr($string,'.'),1);
}

function gee_uploadAndResize($newimagename, $imageinputname, $uploaddirectory, $widthsize){
	
	if(!file_exists($uploaddirectory))
		mkdir($uploaddirectory);
	
	$maxsize = 224288;
	if($_FILES[$imageinputname]["size"] == 0){}
	else{
		if($_FILES[$imageinputname]['error'] > 0) {}
		$extsAllowed = array( 'jpg', 'jpeg', 'png' );
		$uploadedfile = $_FILES[$imageinputname]["name"];
		$extension = pathinfo($uploadedfile, PATHINFO_EXTENSION);
		if (in_array($extension, $extsAllowed) ) { 
			//Means extension is okay, 
			//Proceed storing new pic
			gee_resizeAndSave($_FILES[$imageinputname]['tmp_name'], $uploaddirectory . $newimagename . "." . $extension, $widthsize);
			
			return $newimagename . "." . $extension;
		}else{}
	}
}


// Link image type to correct image loader and saver
// - makes it easier to add additional types later on
// - makes the function easier to read
const IMAGE_HANDLERS = [
    IMAGETYPE_JPEG => [
        'load' => 'imagecreatefromjpeg',
        'save' => 'imagejpeg',
        'quality' => 100
    ],
    IMAGETYPE_PNG => [
        'load' => 'imagecreatefrompng',
        'save' => 'imagepng',
        'quality' => 0
    ],
    IMAGETYPE_GIF => [
        'load' => 'imagecreatefromgif',
        'save' => 'imagegif'
    ]
];

/**
 * @param $src - a valid file location
 * @param $dest - a valid file target
 * @param $targetWidth - desired output width
 * @param $targetHeight - desired output height or null
 */
function gee_resizeAndSave($src, $dest, $targetWidth, $targetHeight = null) {

    // 1. Load the image from the given $src
    // - see if the file actually exists
    // - check if it's of a valid image type
    // - load the image resource

    // get the type of the image
    // we need the type to determine the correct loader
    $type = exif_imagetype($src);

    // if no valid type or no handler found -> exit
    if (!$type || !IMAGE_HANDLERS[$type]) {
        return null;
    }

    // load the image with the correct loader
    $image = call_user_func(IMAGE_HANDLERS[$type]['load'], $src);

    // no image found at supplied location -> exit
    if (!$image) {
        return null;
    }


    // 2. Create a thumbnail and resize the loaded $image
    // - get the image dimensions
    // - define the output size appropriately
    // - create a thumbnail based on that size
    // - set alpha transparency for GIFs and PNGs
    // - draw the final thumbnail

    // get original image width and height
    $width = imagesx($image);
    $height = imagesy($image);

    // maintain aspect ratio when no height set
    if ($targetHeight == null) {

        // get width to height ratio
        $ratio = $width / $height;

        // if is portrait
        // use ratio to scale height to fit in square
        if ($width > $height) {
            $targetHeight = floor($targetWidth / $ratio);
        }
        // if is landscape
        // use ratio to scale width to fit in square
        else {
            $targetHeight = $targetWidth;
            $targetWidth = floor($targetWidth * $ratio);
        }
    }

    // create duplicate image based on calculated target size
    $thumbnail = imagecreatetruecolor($targetWidth, $targetHeight);

    // set transparency options for GIFs and PNGs
    if ($type == IMAGETYPE_GIF || $type == IMAGETYPE_PNG) {

        // make image transparent
        imagecolortransparent(
            $thumbnail,
            imagecolorallocate($thumbnail, 0, 0, 0)
        );

        // additional settings for PNGs
        if ($type == IMAGETYPE_PNG) {
            imagealphablending($thumbnail, false);
            imagesavealpha($thumbnail, true);
        }
    }

    // copy entire source image to duplicate image and resize
    imagecopyresampled(
        $thumbnail,
        $image,
        0, 0, 0, 0,
        $targetWidth, $targetHeight,
        $width, $height
    );


    // 3. Save the $thumbnail to disk
    // - call the correct save method
    // - set the correct quality level

    // save the duplicate version of the image to disk
    return call_user_func(
        IMAGE_HANDLERS[$type]['save'],
        $thumbnail,
        $dest,
        IMAGE_HANDLERS[$type]['quality']
    );
}

function gee_urlfriendly($string){
	$s = ' Zhongxiao Dunhua Sun ';
	$r = preg_replace('/\W+/', '-', strtolower(trim($s)));
	return $r;
}

function gee_say($text){
	
	global $bahasasitus;
	
	if($bahasasitus == "1"){
		switch($text){
			case "Daftar Tulisan" :	return "All Posts";
			case "Galeri Gambar" :	return "Image Gallery";
			case "Judul Website" :	return "Site Title";
			case "Keluar" :	return "Logout";
			case "Pengaturan" :	return "Settings";
			case "Tambah Tulisan" :	return "New Post";
			case "Tampilan" :	return "Themes";
			case "Bahasa" :	return "Language";
			case "Ya" :	return "Yes";
			case "Tidak" :	return "No";
			case "Link Dinamis (link tulisan berubah saat judul berubah)" :	return "Dynamic Links (change post link whenever post title is updated)";
			case "Teks Footer" :	return "Footer Text";
			case "URL Situs" :	return "Site URL";
			default : return "UNTRANSLATED (" . $text . ")";
		}
	}else{
		return $text;
	}
}
	
	
	
?>