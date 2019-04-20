<?php

return [

    //ImageService generateThumbs configuration
    //set 0 to disabled

    'replaceImage' => 0, //replace image
    'aspectRatio' => 1,
    'aspectBase' => 'width', //width or height, ignore when aspectRatio 0
    'width' => 500, //new width after resize
    'height' => 375, //new height after resize
    'dirname' => 'thumbs'

];
