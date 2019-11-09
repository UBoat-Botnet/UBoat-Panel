<?php

/**
 * This file is part of UBoat - HTTP Botnet Project
 */

class capcha
{
    public function recap()
    {
        $characters = goat::$app->config['capcha']['char_pool'];
        $code = '';

        for ($i = 0; $i < rand(3, 5); ++$i) {
            $code .= $characters[mt_rand(0, strlen($characters) - 1)];
        }
        goat::$app->setFlash('_cap', $code, 1);

        $font = WEB_DIR.'/fonts/'.goat::$app->config['capcha']['fonts'][mt_rand(0, count(goat::$app->config['capcha']['fonts']) - 1)]; //todo: add more fonts
        //Set the font size.
        $font_size = mt_rand(goat::$app->config['capcha']['font_size'][0], goat::$app->config['capcha']['font_size'][1]);
        $angle = rand(goat::$app->config['capcha']['angle'][0], goat::$app->config['capcha']['angle'][1]);
        $text_box_size = imagettfbbox($font_size, $angle, $font, $code);
        $bg_width = 50;
        $bg_height = 24;

        $im = imagecreatetruecolor($bg_width, $bg_height);
        $bg = imagecolorallocate($im, 241, 243, 250); //background color gray #F1F3FA
        imagefill($im, 0, 0, $bg);
        // Determine text position
        $box_width = abs($text_box_size[6] - $text_box_size[2]);
        $box_height = abs($text_box_size[5] - $text_box_size[1]);
        $text_pos_x_min = 0;
        $text_pos_x_max = ($bg_width) - ($box_width);
        $text_pos_x = mt_rand($text_pos_x_min, ($text_pos_x_max < $text_pos_x_min) ? $text_pos_x_min : $text_pos_x_max);
        $text_pos_y_min = $box_height;
        $text_pos_y_max = ($bg_height) - ($box_height / 2);
        if ($text_pos_y_min > $text_pos_y_max) {
            $temp_text_pos_y = $text_pos_y_min;
            $text_pos_y_min = $text_pos_y_max;
            $text_pos_y_max = $temp_text_pos_y;
        }
        $text_pos_y = mt_rand($text_pos_y_min, $text_pos_y_max);

        //shadow
        $shadow_color = imagecolorallocate($im, 160, 160, 160);
        imagettftext($im, $font_size, $angle, $text_pos_x + -1, $text_pos_y + 1, $shadow_color, $font, $code);

        $text_color = ('true' == goat::$app->config['capcha']['random_color']) ? imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255)) : imagecolorallocate($im, 40, 40, 40);
        imagettftext($im, $font_size, $angle, $text_pos_x, $text_pos_y, $text_color, $font, $code);

        ob_start();
        imagepng($im);
        $imagedata = ob_get_contents();
        // Clear the output buffer
        ob_end_clean();
        imagedestroy($im);

        return base64_encode($imagedata);
    }
}
