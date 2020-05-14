<?php

function imagelinethick($image, $x1, $y1, $x2, $y2, $color, $thick = 1)
{
    if ($thick == 1) {
        return imageline($image, $x1, $y1, $x2, $y2, $color);
    }
    $t = $thick / 2 - 0.5;
    if ($x1 == $x2 || $y1 == $y2) {
        return imagefilledrectangle($image, round(min($x1, $x2) - $t), round(min($y1, $y2) - $t), round(max($x1, $x2) + $t), round(max($y1, $y2) + $t), $color);
    }
    $k = ($y2 - $y1) / ($x2 - $x1); //y = kx + q
    $a = $t / sqrt(1 + pow($k, 2));
    $points = array(
        round($x1 - (1+$k)*$a), round($y1 + (1-$k)*$a),
        round($x1 - (1-$k)*$a), round($y1 - (1+$k)*$a),
        round($x2 + (1+$k)*$a), round($y2 - (1-$k)*$a),
        round($x2 + (1-$k)*$a), round($y2 + (1+$k)*$a),
    );
    imagefilledpolygon($image, $points, 4, $color);
    return imagepolygon($image, $points, 4, $color);
}

function flipImage($image, $vertical, $horizontal) {
    $w = imagesx($image);
    $h = imagesy($image);
    if (!$vertical && !$horizontal) return $image;
    $flipped = imagecreatetruecolor($w, $h);
    if ($vertical) {
      for ($y=0; $y<$h; $y++) {
        imagecopy($flipped, $image, 0, $y, 0, $h - $y - 1, $w, 1);
      }
    }
    if ($horizontal) {
      if ($vertical) {
        $image = $flipped;
        $flipped = imagecreatetruecolor($w, $h);
      }
      for ($x=0; $x<$w; $x++) {
        imagecopy($flipped, $image, $x, 0, $w - $x - 1, 0, 1, $h);
      }
    }
    return $flipped;
}

function maxHeight($id) {
    $handle = fopen("input_files/nodes_".$id.".csv", "r");

    $data = fgetcsv($handle, ",");

    $max_height = $data[2];

    while ($data = fgetcsv($handle, ",")) {
        if($data[2]>$max_height) { 
            $max_height = $data[2]; 
        }
    }   
    fclose($handle);
    
    return $max_height;
}

function maxWidth($id) {
    $handle = fopen("input_files/nodes_".$id.".csv", "r");

    $data = fgetcsv($handle, ",");

    $max_width = $data[1];

    while ($data = fgetcsv($handle, ",")) {
        if($data[1]>$max_width) { 
            $max_width = $data[1]; 
        }
    }   
    fclose($handle);

    return $max_width;
}
    /*
    echo "screen height: ".$_SESSION["screen_height"];
    echo "<br>";
    */
    

function coorTransY($id,$y_value) {
    //return (maxHeight($id)-$y_value);
    // optimizasyon çalışmaları başarısız :(    
    $expected =((maxHeight($id)-$y_value)*$_SESSION["screen_height"]/maxHeight($id)); 
    if($expected<=0) {
        $expected = 85;
    }     
    elseif(($_SESSION["screen_height"]-$expected)<100) {
        $expected = $expected - 85;
    }
    return $expected;
    
}
    
    /*
    echo "left panel: ".$_SESSION["leftpanel_width"];
    echo "<br>";
    echo "screen width: ".$_SESSION["screen_width"];
    echo "<br>";
    */
    

function coorTransX($id,$x_value) {
    //return ($x_value);    
    // optimizasyon çalışmaları başarısız :(
    $expected =($x_value*$_SESSION["leftpanel_width"])/maxWidth($id); 
    if($expected<=0) {
        $expected = 85;
    }        
    elseif(($_SESSION["leftpanel_width"]-$expected)<100) {
        $expected = $expected - 85;
    }
    return $expected;    
}

function nodeNum($coordinate) {
    return $coordinate-15;
}

function loadNum($coordinate,$coor) {
    if($coor == "+x") {
        return $coordinate-75;
    }
    if($coor == "-x") {
        return $coordinate+40;
    }
    if($coor == "+y") {
        return $coordinate+50;
    }
    if($coor == "-y") {
        return $coordinate-40;
    }
    if($coor == "cw") {
        return $coordinate+25;
    }
    if($coor == "ccw") {
        return $coordinate+25;
    }
}

function addPinSupport($x,$y,$id,$im) {
    
    $rotate180  = true;
    $rotate90   = false;
    $rotate270  = false;

    $handle = fopen("input_files/nodes_".$id.".csv", "r");
    $data = fgetcsv($handle, ",");
    while ($data = fgetcsv($handle, ",")) {
        if($data[2]>$y) {
            $rotate180  = false;
        }
    }   
    fclose($handle);    

    
    if($rotate180) {
        $pin         = imagecreatefrompng('img/pin180.png');          
        $pin_x       = coorTransX($id,$x)-14;
        $pin_y       = coorTransY($id,$y)-28;  
    }   
    else {
        $pin         = imagecreatefrompng('img/pin.png');          
        $pin_x       = coorTransX($id,$x)-15;
        $pin_y       = coorTransY($id,$y); 
    }

      
    $pin_width   = imagesx($pin);
    $pin_height  = imagesy($pin);  


    imagecopy($im, $pin, $pin_x, $pin_y, 0, 0, $pin_width, $pin_height);

}

function addFixedSupport($x,$y,$id,$im) {
    
    $rotate180  = true;
    $rotate90   = false;
    $rotate270  = false;

    $handle = fopen("input_files/nodes_".$id.".csv", "r");
    $data = fgetcsv($handle, ",");
    while ($data = fgetcsv($handle, ",")) {
        if($data[2]>$y) {
            $rotate180  = false;
        }
    }   
    fclose($handle);    

    
    if($rotate180) {
        $fixed         = imagecreatefrompng('img/fixed180.png');          
        $fixed_x       = coorTransX($id,$x)-14;
        $fixed_y       = coorTransY($id,$y)-5;  
    }   
    else {
        $fixed         = imagecreatefrompng('img/fixed.png');          
        $fixed_x       = coorTransX($id,$x)-15;
        $fixed_y       = coorTransY($id,$y); 
    }

      
    $fixed_width   = imagesx($fixed);
    $fixed_height  = imagesy($fixed);  


    imagecopy($im, $fixed, $fixed_x, $fixed_y, 0, 0, $fixed_width, $fixed_height);

}

function addRollerSupport($x,$y,$id,$im,$type) {


    if($type=="V") {

        $rotate180  = true;

        $handle = fopen("input_files/nodes_".$id.".csv", "r");
        $data = fgetcsv($handle, ",");
        while ($data = fgetcsv($handle, ",")) {
            if($data[2]>$y) {
                $rotate180  = false;
            }
        }   
        fclose($handle);    

        
        if($rotate180) {
            $hroller         = imagecreatefrompng('img/hroller180.png');          
            $hroller_x       = coorTransX($id,$x)-13;
            $hroller_y       = coorTransY($id,$y)-25;  
        }   
        else {
            $hroller         = imagecreatefrompng('img/hroller.png');          
            $hroller_x       = coorTransX($id,$x)-15;
            $hroller_y       = coorTransY($id,$y); 
        }

          
        $hroller_width   = imagesx($hroller);
        $hroller_height  = imagesy($hroller);  


        imagecopy($im, $hroller, $hroller_x, $hroller_y, 0, 0, $hroller_width, $hroller_height);


    }

    elseif($type=="H") {

        $rotate180  = true;

        $handle = fopen("input_files/nodes_".$id.".csv", "r");
        $data = fgetcsv($handle, ",");
        while ($data = fgetcsv($handle, ",")) {
            if($data[2]>$x) {
                $rotate180  = false;
            }
        }   
        fclose($handle);    

        
        if($rotate180) {
            $vroller         = imagecreatefrompng('img/vroller.png');          
            $vroller_x       = coorTransX($id,$x);
            $vroller_y       = coorTransY($id,$y)-14;  
        }   
        else {
            $vroller         = imagecreatefrompng('img/vroller180.png');          
            $vroller_x       = coorTransX($id,$x)-25;
            $vroller_y       = coorTransY($id,$y)-14; 
        }

          
        $vroller_width   = imagesx($vroller);
        $vroller_height  = imagesy($vroller);  


        imagecopy($im, $vroller, $vroller_x, $vroller_y, 0, 0, $vroller_width, $vroller_height);

    }

}

function addFx($x,$y,$forceMagnitude,$im,$id,$color,$font_size,$font_path) {

    if($forceMagnitude>0) {

        $forceMagnitude = $forceMagnitude."kN";
        $force  = imagecreatefrompng('img/force_right.png');  
        $width  = imagesx($force);
        $height = imagesy($force);  
        $force_x    = coorTransX($id,$x)-35;
        $force_y    = coorTransY($id,$y)-5;

        imagettftext($im, $font_size, 0, loadNum(coorTransX($id,$x),"+x"), coorTransY($id,$y)+5, $color, $font_path, $forceMagnitude);

        imagecopy($im, $force, $force_x, $force_y, 0, 0, $width, $height);

    }
    elseif($forceMagnitude<0) {

        $forceMagnitude = $forceMagnitude*(-1);
        $forceMagnitude = $forceMagnitude."kN";
        $force  = imagecreatefrompng('img/force_left.png');  
        $width  = imagesx($force);
        $height = imagesy($force);  
        $force_x    = coorTransX($id,$x)+5;
        $force_y    = coorTransY($id,$y)-5;

        imagettftext($im, $font_size, 0, loadNum(coorTransX($id,$x),"-x"), coorTransY($id,$y)+5, $color, $font_path, $forceMagnitude);

        imagecopy($im, $force, $force_x, $force_y, 0, 0, $width, $height);

    }

}

function addFy($x,$y,$forceMagnitude,$im,$id,$color,$font_size,$font_path) {

    if($forceMagnitude>0) {

        $forceMagnitude = $forceMagnitude."kN";
        $force  = imagecreatefrompng('img/force_up.png');  
        $width  = imagesx($force);
        $height = imagesy($force);  
        $force_x    = coorTransX($id,$x)-5;
        $force_y    = coorTransY($id,$y)+5;

        imagettftext($im, $font_size, 0, coorTransX($id,$x)-10, loadNum(coorTransY($id,$y),"+y"), $color, $font_path, $forceMagnitude);

        imagecopy($im, $force, $force_x, $force_y, 0, 0, $width, $height);

    }
    elseif($forceMagnitude<0) {

        $forceMagnitude = $forceMagnitude*(-1);
        $forceMagnitude = $forceMagnitude."kN";
        $force  = imagecreatefrompng('img/force_down.png');  
        $width  = imagesx($force);
        $height = imagesy($force);  
        $force_x    = coorTransX($id,$x)-5;
        $force_y    = coorTransY($id,$y)-35;

        imagettftext($im, $font_size, 0, coorTransX($id,$x)-10, loadNum(coorTransY($id,$y),"-y"), $color, $font_path, $forceMagnitude);

        imagecopy($im, $force, $force_x, $force_y, 0, 0, $width, $height);

    }

}

function addM($x,$y,$forceMagnitude,$im,$id,$color,$font_size,$font_path) {

    if($forceMagnitude>0) {

        $forceMagnitude = $forceMagnitude."kNm";
        $force  = imagecreatefrompng('img/momentcw.png');  
        $width  = imagesx($force);
        $height = imagesy($force);  
        $force_x    = coorTransX($id,$x)-5;
        $force_y    = coorTransY($id,$y)-15;

        imagettftext($im, $font_size, 0, coorTransX($id,$x)+7, loadNum(coorTransY($id,$y),"cw"), $color, $font_path, $forceMagnitude);

        imagecopy($im, $force, $force_x, $force_y, 0, 0, $width, $height);

    }
    elseif($forceMagnitude<0) {

        $forceMagnitude = $forceMagnitude*(-1);
        $forceMagnitude = $forceMagnitude."kNm";
        $force  = imagecreatefrompng('img/momentccw.png');  
        $width  = imagesx($force);
        $height = imagesy($force);  
        $force_x    = coorTransX($id,$x)-5;
        $force_y    = coorTransY($id,$y)-15;

        imagettftext($im, $font_size, 0, coorTransX($id,$x)-50, loadNum(coorTransY($id,$y),"ccw"), $color, $font_path, $forceMagnitude);

        imagecopy($im, $force, $force_x, $force_y, 0, 0, $width, $height);

    }
}

?>