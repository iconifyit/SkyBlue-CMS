<?php

require_once('wym.base.php');

$imgs = FileSystem::list_files(SB_MEDIA_DIR, 1);

$tmp = $imgs;
$imgs = array();
for ($i=0; $i<count($tmp); $i++) {
    $dir = basename(dirname($tmp[$i]));
    if (strcasecmp($dir, "videos") === 0) continue;
    array_push($imgs, $tmp[$i]);
}

define('THUMB_SIZE',70);
define('IMAGE_HTML',
'<a href="javascript: void(0);" style="{style}"
     onclick="SBC.InsertImage(\'{src}\',{width},{height},{index}); SBC.hideOverlay({index});">
     <img width="{w}"
           height="{h}"
           src="{src}"
           title="{img.title}"
           />
  </a>');

$index = Filter::get($_GET, 'index', '0');

$rows = "";

$colCount = 6;

for ($i=0; $i<ceil(count($imgs)/$colCount); $i++) {
    $rows .= "<tr>\n";
    for ($j=0; $j<$colCount; $j++) {
        $img = isset($imgs[$j+($i*$colCount)]) ? $imgs[$j+($i*$colCount)] : null ;
        
        if (empty($img)) {
            $rows .= "<td>&nbsp;</td>\n";
        }
        else {
            $src = str_replace(_SBC_WWW_, null, $img);
        
            list($width, $height) = ImageUtils::dimensions($img);
            list($w,$h) = ImageUtils::ImageDimsToMaxDim(
                array($width, $height), 
                THUMB_SIZE, 
                THUMB_SIZE
            );
                
            $w = (THUMB_SIZE - (THUMB_SIZE - $w));
            $h = (THUMB_SIZE - (THUMB_SIZE - $h));
            
            $mTop = floor((THUMB_SIZE - $h)/2);
            $mLeft = floor((THUMB_SIZE - $w)/2);
            
            $mTop += 4;
            $mLeft += 4;
            
            $sWidth  = 82 - $mLeft;
            $sHeight = 82 - $mTop;
            
            $style = "padding: {$mTop}px {$mLeft}px; width: $sWidth; height: $sHeight;";
            
            $title = basename(dirname($src)) . '/' . basename($src);
                   
            $image = str_replace('{src}',       $src, IMAGE_HTML);
            $image = str_replace('{w}',         $w, $image);
            $image = str_replace('{h}',         $h, $image);
            $image = str_replace('{img.title}', basename(dirname($src)) . '/' . basename($src), $image);
            $image = str_replace('{width}',     $width,  $image);
            $image = str_replace('{height}',    $height, $image);
            $image = str_replace('{style}',     $style,  $image);
            $image = str_replace('{index}',     $index,  $image);
            $rows .= "<td>$image</td>\n";
        }
    }
    $rows .= "</tr>\n";
}

?>
<?php if (empty($rows)) : ?>
    <form id="urlform" method="get" action="javascript:return void(0);">
      <div>
          <p><?php __('WYM.IMGS.NO_IMAGES'); ?></p>
      </div>
    </form>
<?php else : ?>
    <div id="imagetable" style="height: 350px; width: 520px; overflow: auto;">
    <table id="ImgSelectorThumbList" cellpadding="0" cellspacing="0">
      <?php echo $rows; ?>
    </table>
    </div>
    <style>
      #image-controls {
        width: 500px; 
        height: 100px; 
        overflow: hidden;
      }
      #image-controls button {
        display: block;
        cursor: pointer;
      }
      #image-controls iframe {
        width: 500px;
        height: 100px;
        border-top: 1px solid #CCC;
        overflow: hidden;
      }
    </style>
    <div id="image-controls">
        <iframe id="uplaod_iframe" src="resources/editors/wymeditor/php/image.upload.php?wym=<?php echo $index; ?>" frameborder="0"></iframe>
    </div>
<?php endif; ?>
