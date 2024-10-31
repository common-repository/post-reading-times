<?php
/**
 * Class PostReadingTimes
 *
 * @version 2.4.0
 */
class PostReadingTimes
{

    public static function init() {
        /* инициализируем меню в админке*/
        add_action( 'admin_menu', array( 'PostReadingTimes', 'add_admin_menu' ));

        add_action( 'admin_init', array( 'PostReadingTimes', 'plugin_settings' ));

    }

    public static function plugin_settings() {
        register_setting( 'option_group_prt', 'prt_fb_option', 'sanitize_callback' );
        $trans1  = __( 'Plugin settings', 'post-reading-times' );
        $trans2  = __( 'Title', 'post-reading-times' );
        $count_words = __( 'Words per minute', 'post-reading-times' );


        // параметры: $id, $title, $callback, $page
        add_settings_section( 'section_id', $trans1, '', 'section_prt_1' );
        // параметры: $id, $title, $callback, $page, $section, $args

        add_settings_field( 'primer_field0', $count_words, array( 'PostReadingTimes', 'count_words' ), 'section_prt_1', 'section_id' );
        add_settings_field( 'primer_field2', $trans2, array( 'PostReadingTimes', 'title' ), 'section_prt_1', 'section_id' );


        add_settings_field( 'primer_field4', __('less than a minute','post-reading-times'), array( 'PostReadingTimes', 'ctc1' ), 'section_prt_1', 'section_id' );
        add_settings_field( 'primer_field5', __('1 minute','post-reading-times'), array( 'PostReadingTimes', 'ctc2' ), 'section_prt_1', 'section_id' );
        add_settings_field( 'primer_field6', __('2,3,4 minutes','post-reading-times'), array( 'PostReadingTimes', 'ctc3' ), 'section_prt_1', 'section_id' );
        add_settings_field( 'primer_field7', __('5, 6, 7 minutes','post-reading-times'), array( 'PostReadingTimes', 'ctc4' ), 'section_prt_1', 'section_id' );
        add_settings_field( 'primer_field8', __('Use only shortcode','post-reading-times'), array( 'PostReadingTimes', 'auto' ), 'section_prt_1', 'section_id' );
        add_settings_field( 'primer_field9', __('Add icon before text?','post-reading-times'), array( 'PostReadingTimes', 'clock' ), 'section_prt_1', 'section_id' );

        add_settings_field( 'primer_field10', __('Color icon','post-reading-times'), array( 'PostReadingTimes', 'clock_color' ), 'section_prt_1', 'section_id' );

    }


    /* инициализируем меню в админке*/
    public static function add_admin_menu() {

        $hello1 = __( 'Post reading times settings', 'post-reading-times' );
        add_options_page( ' ', $hello1, 'manage_options', 'prt-plugin-options', array( 'PostReadingTimes', 'prt_plugin_options' ) );
    }

    public static function prt_plugin_options() {
        ?>
        <div class="wrap">

            <h2><?php echo _e( 'Post Reading Times', 'post-reading-times' ), ' V', PRT_VERSION; ?></h2>

            <hr>


            <form action="options.php" method="POST">
                <?php
                settings_fields( 'option_group_prt' );     // скрытые защитные поля
                do_settings_sections( 'section_prt_1' ); // секции с настройками (опциями). У нас она всего одна 'section_id'
                submit_button();
                ?>
            </form>

        </div>
        <?php
    }


    public static function count_words() {
        $val = get_option( 'prt_fb_option' );
        if(isset( $val['count_words'])){ $val = $val['count_words'];}else{ $val= 130;}
        ?>
        <input type="number" placeholder="130" name="prt_fb_option[count_words]" value="<?php echo esc_attr( $val ) ?>" />
        <?php
    }

    /*Title*/
    public static function title() {
        $val = get_option( 'prt_fb_option' );
        if(isset( $val['title'])){ $val = $val['title'];}else{ $val= __('Reading time','post-reading-times');}
        ?>
        <input type="text" placeholder="<?php echo __('Reading time','post-reading-times'); ?>" name="prt_fb_option[title]" value="<?php echo esc_attr( $val ) ?>" />
        <?php
    }


    public static function ctc1(){
        $val = get_option( 'prt_fb_option' );
        if(isset( $val['less-than-a-minute'])){ $val = $val['less-than-a-minute'];}else{ $val= 'less than a minute';}
        ?>
        <input style="width:250px" type="text" placeholder="less than a minute" name="prt_fb_option[less-than-a-minute]" value="<?php echo esc_attr( $val ) ?>" />
        <?php
    }
    public static function ctc2(){
        $val = get_option( 'prt_fb_option' );
        if(isset( $val['oneminute'])){ $val = $val['oneminute'];}else{ $val= '';}
        ?>
        <input style="width:250px"  type="text" placeholder="minute" name="prt_fb_option[oneminute]" value="<?php echo esc_attr( $val ) ?>" />
        <?php
    }

    public static function ctc3(){
        $val = get_option( 'prt_fb_option' );
        if(isset( $val['twominute'])){ $val = $val['twominute'];}else{ $val= '';}
        ?>
        <input style="width:250px"  type="text" placeholder="minutes" name="prt_fb_option[twominute]" value="<?php echo esc_attr( $val ) ?>" />
        <?php
    }

    public static function ctc4(){
        $val = get_option( 'prt_fb_option' );
        if(isset( $val['manyminute'])){ $val = $val['manyminute'];}else{ $val= '';}
        ?>
        <input style="width:250px"  type="text" placeholder="minutes" name="prt_fb_option[manyminute]" value="<?php echo esc_attr( $val ) ?>" />
        <?php
    }

    ## убрать автоматический вывод
    public static function auto() {
        $val = get_option( 'prt_fb_option' );
        $checked = isset($val['only_shortcode']) ? "checked" : "";
        ?>
        <input name="prt_fb_option[only_shortcode]" type="checkbox" value="1" <?php echo $checked; ?>>
        <small style="color:#ff0000"><?php echo __( 'Attention! To display the time, use the shortcode: [post_rt]', 'post-reading-times' )?></small>
    <?php }


    ## Add icon before text?
    public static function clock() {
        $val = get_option( 'prt_fb_option' );
        $checked = isset($val['clock']) ? "checked" : "";
        ?>
        <input name="prt_fb_option[clock]" type="checkbox" value="1" <?php echo $checked; ?>>
        <img style="margin: 0 0 -6px 0;" src="<?php echo PRT_PLUGIN_URL; ?>clock.svg">
    <?php }
    public static function clock_color(){
$val = get_option( 'prt_fb_option' );
$clock_color = isset($val['clock_color']) ? $val['clock_color'] : "#48576F";
?>
<input name="prt_fb_option[clock_color]" type="color" value="<?php echo $clock_color; ?>" >
<?php    }



    ## Очистка данных
    public static function sanitize_callback( $options ) {
        // очищаем
        foreach ( $options as $name => & $val ) {
            $val = strip_tags( $val );
        }

        return $options;
    }

}