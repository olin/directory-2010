<?php
	function getGDimage($file,$contenttype){
        switch($contenttype){
        	case 'image/jpg':
        	case 'image/jpeg':
			case 'image/pjpeg':
			case 'image/pjpg':
			case 'image/x-jpeg':
			case 'image/x-jpg':
        		if(!(@imagetypes()&@IMG_JPG)){ return false; }
        		return imagecreatefromjpeg($file);
        	case 'image/gif':
        	case 'image/x-gif':
        		if(!(@imagetypes()&@IMG_GIF)){ return false; }
        		return imagecreatefromgif($file);
        	case 'image/png':
			case 'image/x-png':
        		if(!(@imagetypes()&@IMG_PNG)){ return false; }
        		return imagecreatefrompng($file);
        	}
        return false;
        }
	function saveGDimage($ir, $file){
        $ext = @strtolower(@strrchr($file,'.'));
        if(!$ext){ return false; }
        switch($ext){
        	case '.jpg':
        	case '.jpeg':
        		if(!(@imagetypes()&@IMG_JPG)){ return false; }
        		return imagejpeg($ir,$file,80	);
        	case '.gif':
        		if(!(@imagetypes()&@IMG_GIF)){ return false; }
        		return imagegif($ir,$file);
        	case '.png':
        		if(!(@imagetypes()&@IMG_PNG)){ return false; }
        		return imagepng($ir,$file);
        	}
        return false;
        }

	function mkthumb($input, $contenttype, $output, $dh, $dar=0.75){ /*533.0/800.0*/
		$im = getGDimage($input,$contenttype);

		if(!$im){ print "bad image"; return false; }

		$w = imagesx($im);
		$h = imagesy($im);
		$ar = $w/$h;

		if($w<1||$h<1||$dh<1||$dar<0.01||$dar>100){ print "bad params, try again!"; return false; }

		if($ar>$dar){ //too wide
			$sw = $h*$dar;
			$sh = $h;
		}else{ //too tall
			$sw = $w;
			$sh = $w/$dar;
			}

		$sx = intval(($w-$sw)/2);
		$sy = intval(($h-$sh)/2);

		$resized = imagecreatetruecolor($dar*$dh, $dh);
		//imageantialias($resized,true);
		imagecopyresampled($resized, $im, 0, 0, $sx, $sy, $dar*$dh, $dh, $sw, $sh);

		@unlink($output);
		return saveGDimage($resized,$output);
		}

	?>




