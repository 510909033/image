<?php
class Demo{
    public function captcha(){
        
        /*
         A very primitive captcha implementation
         */
        
        /* Create Imagick object */
        $Imagick = new Imagick();
        
        /* Create the ImagickPixel object (used to set the background color on image) */
        $bg = new ImagickPixel();
        
        /* Set the pixel color to white */
        $bg->setColor( 'white' );
        
        /* Create a drawing object and set the font size */
        $ImagickDraw = new ImagickDraw();
        
        /* Set font and font size. You can also specify /path/to/font.ttf */
//         $ImagickDraw->setFont( 'Helvetica Regular' );
//         $ImagickDraw->setFontSize( 20 );
        
        /* Create the text */
        $alphanum = 'ABXZRMHTL23456789';
        $string = substr( str_shuffle( $alphanum ), 2, 6 );
        
        /* Create new empty image */
        $Imagick->newImage( 85, 30, $bg );
        
        /* Write the text on the image */
        $Imagick->annotateImage( $ImagickDraw, 4, 20, 0, $string );
        
        /* Add some swirl */
        $Imagick->swirlImage( 20 );
        
        /* Create a few random lines */
        $ImagickDraw->line( rand( 0, 70 ), rand( 0, 30 ), rand( 0, 70 ), rand( 0, 30 ) );
        $ImagickDraw->line( rand( 0, 70 ), rand( 0, 30 ), rand( 0, 70 ), rand( 0, 30 ) );
        $ImagickDraw->line( rand( 0, 70 ), rand( 0, 30 ), rand( 0, 70 ), rand( 0, 30 ) );
        $ImagickDraw->line( rand( 0, 70 ), rand( 0, 30 ), rand( 0, 70 ), rand( 0, 30 ) );
        $ImagickDraw->line( rand( 0, 70 ), rand( 0, 30 ), rand( 0, 70 ), rand( 0, 30 ) );
        
        /* Draw the ImagickDraw object contents to the image. */
        $Imagick->drawImage( $ImagickDraw );
        
        /* Give the image a format */
        $Imagick->setImageFormat( 'png' );
        
        /* Send headers and output the image */
        header( "Content-Type: image/{$Imagick->getImageFormat()}" );
        echo $Imagick->getImageBlob( );
    }
    
    public function test2(){
        // 输入的gif图片
        $gif_file = "./file/2.gif";
        // 输出的mp4文件
        $mp4_file = $gif_file.".out.mp4";
        
        print_r(Imagick::queryFormats());
        
        try{
            if (!file_exists($gif_file)){
                exit('file not exists');
            }
            $image = new Imagick($gif_file);
        }catch (Exception $e) {
            $image = false;
            echo $e->getMessage();
            return ;
        }
        
        if(false != $image && "GIF" == $image->getImageFormat()){
            $image = $image->coalesceimages();
            
            $width = $image->getImageWidth();
            $height = $image->getImageHeight();
            
            foreach($image as $gif){
                $width = max($gif->getImageWidth(), $width);
                $height = max($gif->getImageHeight(), $height);
            }
            if($height % 2 == 1)$height = $height - 1;
            if($width % 2 == 1)$width = $width - 1;
            
            $command = "ffmpeg -i ".$gif_file." -s ".$width.":".$height;
            $command .= " -r 24 -c:v libx264 -c:a copy -pix_fmt yuv420p -f mp4 ";
            $command .= $mp4_file." 2>&1";
            
            exec($command, $output);
            echo print_r($output, true);
        }else{
            echo "Not gif image!";
        }
    }
}


$image = new Demo();
$image->captcha();


