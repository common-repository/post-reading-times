<?php
/*
 * Plugin Name:     Post reading times
 * Version:         2.4.2
 * Text Domain:     post-reading-times
 * Plugin URI:      https://yrokiwp.ru
 * Description:    A plugin that allows you to easily display the reading time of any article. Reading time is calculated based on a person's standard reading speed. The value is displayed before the text.
 * Author:          dmitrylitvinov
 * Author URI:     https://yrokiwp.ru
 *
 *
 * License:           GNU General Public License v3.0
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 */

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly


define('PRT_VERSION', '2.4.2');
define('PRT_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PRT_PLUGIN_URL',  plugin_dir_url( __FILE__ ) );

/*------------Страница админки*------------*/
if (is_admin() || (defined('WP_CLI') && WP_CLI)) {
    require_once(PRT_PLUGIN_DIR . 'admin.php');
    add_action('init', array('PostReadingTimes', 'init'));
}
/*------------Страница админки------------*/

function num_word($value, $words, $show = true)
{/*функция склонения минут*/
    $num = $value % 100;
    if ($num > 19) {
        $num = $num % 10;
    }

    $out = ($show) ?  $value . ' ' : '';
    switch ($num) {
        case 1:  $out .= $words[0]; break;
        case 2:
        case 3:
        case 4:  $out .= $words[1]; break;
        default: $out .= $words[2]; break;
    }

    return $out;
}
$val = get_option( 'prt_fb_option' );
if(isset($val['only_shortcode'])){
    if($val['only_shortcode']!=1){
        add_filter('the_content', 'acort_prt_before_content');
    }
}else{
    add_filter('the_content', 'acort_prt_before_content');
}



add_shortcode( 'post_rt', 'acort_prt_before_content' );


function acort_prt_before_content($content) {
    $val = get_option( 'prt_fb_option' );
    $post_id = get_the_ID();
    $strwords = get_the_content( null, false, $post_id );

    $strwords = wp_strip_all_tags( $strwords );
    $strwords=count(preg_split('/\s+/', $strwords));
    if(isset( $val['count_words'])){ $count_words = $val['count_words'];}else{ $count_words= 130;}

if($strwords<$count_words){
    if(isset( $val['less-than-a-minute'])){ $timetoread = $val['less-than-a-minute'];}else{ $timetoread= 'less than a minute';}

}else{
    $count = $strwords/$count_words;
    $count=(int)$count;


    if(isset( $val['oneminute'])){ $oneminute = $val['oneminute'];}else{ $oneminute= ' minute';}
    if(isset( $val['twominute'])){ $twominute = $val['twominute'];}else{ $twominute= ' minutes';}
    if(isset( $val['manyminute'])){ $manyminute = $val['manyminute'];}else{ $manyminute= ' minutes';}
    $timetoread =  num_word($count, array($oneminute, $twominute, $manyminute));


}
    $clock='';
if(isset($val['clock'])){
    if($val['clock']==1){


        $stroke = isset($val['clock_color']) ? $val['clock_color'] : "#48576F";
        $clock='<svg width="19" height="18" viewBox="0 0 19 18" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M9.25488 16.5C13.397 16.5 16.7549 13.1421 16.7549 9C16.7549 4.85786 13.397 1.5 9.25488 1.5C5.11275 1.5 1.75488 4.85786 1.75488 9C1.75488 13.1421 5.11275 16.5 9.25488 16.5Z" stroke="'.$stroke.'" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
    <path d="M9.25488 4.5V9H12.6299" stroke="'.$stroke.'" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
</svg> ';
    }else{
        $clock='';
    }
}else{
    $clock='';
}

    if(is_single()) {
        if(isset( $val['title'])){ $reading_time = $val['title'];}else{ $reading_time= __('Reading time','post-reading-times');}
        $beforecontent = '<div class="acort-computy" style="display: flex;align-items: center;flex-wrap: wrap;">&nbsp;'.$clock.'&nbsp;'.$reading_time.'<span>&nbsp;'.$timetoread.'</span></div>';
        $fullcontent = $beforecontent . $content;
    } else {
        $fullcontent = $content;
    }

    return $fullcontent;
}