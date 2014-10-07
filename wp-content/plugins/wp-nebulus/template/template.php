<?php
$options = array_merge( get_option('nebulus_general_settings'), get_option('nebulus_social_settings'));
$options = array_merge( $options, get_option('nebulus_mailchimp_settings'));
$hidetimer = ( isset( $options["hidetimer"] ) && $options["hidetimer"] );
$hidefooter = ( isset( $options["hidefooter"] ) && $options["hidefooter"] );
$hidetwitter = ( isset( $options["hidetwitterfeed"] ) && $options["hidetwitterfeed"] );
$hideemailform = ( isset( $options["hide_email_form"] ) && $options["hide_email_form"] );
$hasBackgroundColor = isset( $options["backgroundcolor"]  ) && $options["backgroundcolor"];
$hasBackgroundImage = isset( $options["backgroundimage"] ) && $options["backgroundimage"];
if( isset( $options['tweet_consumerkey'] ) && isset( $options['tweet_consumersecret'] ) && isset( $options['tweet_accesstoken'] ) && isset( $options['tweet_accesssecret'] ) )
    $tweets = WP_Nebulus::get_tweets(); ?>
<!DOCTYPE html>
<!--[if IE 7 ]><html class="ie7 ieold"><![endif]-->
<!--[if IE 8 ]><html class="ie8 ieold"><![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html><!--<![endif]-->
<head>
    <meta charset="utf-8">

    <!-- Title -->
    <title><?php if(is_front_page()) { echo bloginfo("name"); echo " - "; echo bloginfo("description"); } else { echo wp_title(" - ", false, right); echo bloginfo("name"); } ?></title>

    <!-- Site Meta -->
    <meta name="description" content="<?php bloginfo('description') ?>">
    <meta name="viewport" content="width=device-width, user-scalable = no">

    <!-- Icons -->
    <link rel="shortcut icon" href="favicon.ico">
    <link rel="apple-touch-icon" href="img/touch-icon.png">

    <!-- Win 8 Tiles -->
    <meta name="application-name" content="<?php wp_title(''); ?> | <?php bloginfo('description') ?>"/>
    <meta name="msapplication-TileColor" content="#ffffff"/>
    <meta name="msapplication-TileImage" content="img/touch-icon.png"/>

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- CSS -->
    <link href='http://fonts.googleapis.com/css?family=Lato:400,400italic,700,900' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Fjalla+One' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="<?php echo plugins_url('css/font-awesome.min.css', __FILE__); ?>">
    <link rel="stylesheet" href="<?php echo plugins_url('css/reset.css', __FILE__); ?>">
    <link rel="stylesheet" href="<?php echo plugins_url('css/main.css', __FILE__); ?>">

    <?php if( isset( $options["theme"] ) && $options["theme"] && $options["theme"] != 'default'): ?>
    <link rel="stylesheet" href="<?php echo plugins_url('css/'. esc_attr( $options["theme"] ) .'.css', __FILE__); ?>">
    <?php endif; ?>

    <!--[if IE 7]>
    <link rel="stylesheet" href="<?php echo plugins_url('css/font-awesome-ie7.min.css', __FILE__); ?>">
    <![endif]-->

    <script type="text/javascript">
    window.WPNEB = {
        cssurl : "<?php echo plugins_url('css', __FILE__)?>"
    }
    </script>
    <style><?php if( isset( $options["logo"] ) && $options["logo"] ): ?>
        .brand{
            width: auto;
            height:auto;
            background: none;
            }
    <?php endif; ?>
    <?php if( isset( $options["footerlogo"] ) && $options["footerlogo"]): ?>
        footer .brand{
            width: auto;
            height:auto;
            background: none;
            opacity: 1;
            }
    <?php endif; ?>
    <?php if( isset( $options["textcolor"] ) && $options["textcolor"] ): ?>
        body .top-section{
            color: <?php echo esc_attr($options["textcolor"]) ?>;
            }
        .sub-hero-copy,.unodos .sep, .unodos .label {
                color: <?php echo WP_Nebulus::adjustBrightness($options["textcolor"], 80) ?>;
            }
        .notify-msg {
            color: <?php echo WP_Nebulus::adjustBrightness($options["textcolor"], -60) ?>;
        }
    <?php endif; ?>

    <?php if( $hasBackgroundImage ):
        // defaults to "Tiles";
        $repeat= 'repeat'; $size='auto'; $position= '0 0';

        switch($options['backgroundimage_options']){
            case 'centered':
                $repeat = 'no-repeat'; $size = 'auto'; $position = 'center center';
                break;
            case 'best_fit':
                $repeat = 'no-repeat'; $size = 'contain'; $position = 'center center';
                break;
            case 'responsive':
                $repeat = 'repeat'; $size = 'cover'; $position = 'center center';
                break;
            }
    ?>
        section.top-section{
            background-image: url('<?php echo plugins_url("/img/bottom_bg.png", __FILE__) ?>'), url('<?php echo esc_url($options["backgroundimage"]); ?>');
            background-repeat: repeat-x, <?php echo $repeat ?>;
            background-position: 0 bottom, <?php echo $position; ?>;
            background-size: auto, <?php echo $size; ?>;
            <?php if( $hasBackgroundColor ): ?>
            background-color: <?php echo esc_attr($options["backgroundcolor"]) ?>;
            <?php endif; ?>
            }
        .ieold section.top-section{
            background-image: url('<?php echo esc_url($options["backgroundimage"]); ?>');
            background-repeat: <?php echo $repeat ?>;
            background-position: <?php echo $position; ?>;
            background-size: <?php echo $size; ?>;
            <?php if( $hasBackgroundColor ): ?>
            background-color: <?php echo esc_attr($options["backgroundcolor"]) ?>;
            <?php endif; ?>
            }
    <?php endif; ?>
    <?php if( $hasBackgroundColor && !$hasBackgroundImage ): ?>
        body .top-section{
            background: <?php echo esc_attr($options["backgroundcolor"]) ?>;
            }
    <?php endif; ?>
    <?php if( isset( $options["buttoncolor"] ) && $options["buttoncolor"] ): ?>
        .notify-btn {
            background-color: <?php echo esc_url($options['buttoncolor']) ?>;
            background-image: -moz-linear-gradient(top, <?php echo WP_Nebulus::adjustBrightness( esc_url( $options["buttoncolor"] ), 40) ?>, <?php echo esc_url($options['buttoncolor']) ?>);
            background-image: -webkit-gradient(linear, 0 0, 0 100%, from(<?php echo WP_Nebulus::adjustBrightness( esc_url( $options["buttoncolor"] ), 40) ?>), to(<?php echo esc_url($options['buttoncolor']) ?>));
            background-image: -webkit-linear-gradient(top, <?php echo WP_Nebulus::adjustBrightness( esc_url( $options["buttoncolor"] ), 40) ?>, <?php echo esc_url($options['buttoncolor']) ?>);
            background-image: -o-linear-gradient(top, <?php echo WP_Nebulus::adjustBrightness( esc_url( $options["buttoncolor"] ), 40) ?>, <?php echo esc_url($options['buttoncolor']) ?>);
            background-image: linear-gradient(to bottom, <?php echo WP_Nebulus::adjustBrightness( esc_url( $options["buttoncolor"] ), 40) ?>, <?php echo esc_url($options['buttoncolor']) ?>);
            border-color: <?php echo esc_url($options['buttoncolor']) ?> <?php echo esc_url($options['buttoncolor']) ?> <?php echo WP_Nebulus::adjustBrightness( esc_url( $options["buttoncolor"] ), -40) ?>;
            border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
            }

        .notify-btn:hover,
        .notify-btn:focus,
        .notify-btn:active,
        .notify-btn.active,
        .notify-btn.disabled,
        .notify-btn[disabled] {
            background-color: <?php echo esc_url($options['buttoncolor']) ?>;
            }
    <?php endif; ?>
    <?php if( isset( $options["customcss"] ) && $options["customcss"] ) echo esc_html(trim($options["customcss"])); ?>
    </style>
</head><body <?php body_class('wp-nebulus coming-soon'); ?>>

    <section class="top-section">
        <div class="wrap">
            <p class="social pressed-box">
                <?php if(isset( $options['facebook'] ) && $options['facebook'] ): ?><a target="_blank" href="#" class="facebook"><i class="icon-facebook-sign"></i></a><?php endif; ?>
                <?php if(isset( $options['twitter'] ) && $options['twitter'] ): ?><a target="_blank" href="#" class="twitter"><i class="icon-twitter-sign"></i></a><?php endif; ?>
                <?php if(isset( $options['instagram'] ) && $options['instagram'] ): ?><a target="_blank" href="<?php echo esc_url( $options['instagram'] ); ?>" class="instagram"><i class="icon-instagram"></i></a><?php endif; ?>
                <?php if(isset( $options['pinterest'] ) && $options['pinterest'] ): ?><a target="_blank" href="<?php echo esc_url( $options['pinterest'] ); ?>" class="twitter"><i class="icon-pinterest-sign"></i></a><?php endif; ?>
                <?php if(isset( $options['linkedin'] ) && $options['linkedin'] ): ?><a target="_blank" href="<?php echo esc_url( $options['linkedin'] ); ?>" class="twitter"><i class="icon-linkedin-sign"></i></a><?php endif; ?>
                <?php if(isset( $options['google'] ) && $options['google'] ): ?><a target="_blank" href="#" class="googleplus"><i class="icon-google-plus-sign"></i></a><?php endif; ?>
                <?php if(isset( $options['flickr'] ) && $options['flickr'] ): ?><a target="_blank" href="<?php echo esc_url( $options['flickr'] ); ?>" class="flickr"><i class="icon-flickr"></i></a><?php endif; ?>
                <?php if(isset( $options['tumblr'] ) && $options['tumblr'] ): ?><a target="_blank" href="<?php echo esc_url( $options['tumblr'] ); ?>" class="tumblr"><i class="icon-tumblr-sign"></i></a><?php endif; ?>
                <?php if(isset( $options['youtube'] ) && $options['youtube'] ): ?><a target="_blank" href="<?php echo esc_url( $options['youtube'] ); ?>" class="youtube"><i class="icon-youtube-sign"></i></a><?php endif; ?>
                <?php if(isset( $options['foursquare'] ) && $options['foursquare'] ): ?><a target="_blank" href="<?php echo esc_url( $options['foursquare'] ); ?>" class="foursquare"><i class="icon-foursquare"></i></a><?php endif; ?>
                <?php if(isset( $options['dribbble'] ) && $options['dribbble'] ): ?><a target="_blank" href="<?php echo esc_url( $options['dribbble'] ); ?>" class="dribbble"><i class="icon-dribbble"></i></a><?php endif; ?>
            </p>

            <?php if( isset( $options["logo"] ) && $options["logo"] ): ?>
                <h1><img src="<?php echo esc_url($options["logo"]); ?>" alt="<?php bloginfo( 'name' ); ?>" class="brand"></h1>
            <?php else: ?>
                <h1 class="brand top ir">Nebulus</h1>
            <?php endif; ?>

            <h2 class="hero-copy"><?php echo apply_filters( 'the_title', $options["heading"] ) ?></h2>
            <h2 class="sub-hero-copy <?php if( $hidetimer ) echo 'bottom-margin' ?>"><?php echo apply_filters( 'the_title', $options["sub_heading"] ) ?></h2>

            <?php if( !$hidetimer ): ?>
            <section class="unodos">

                <div class="days">
                    <span class="digits">00</span>
                    <span class="label">days</span>
                </div>
                <span class="sep">&bull;</span>

                <div class="hours">
                    <span class="digits">00</span>
                    <span class="label">hours</span>
                </div>
                <span class="sep">&bull;</span>

                <div class="minutes">
                    <span class="digits">00</span>
                    <span class="label">mins</span>
                </div>
                <span class="sep">&bull;</span>

                <div class="seconds">
                    <span class="digits">00</span>
                    <span class="label">secs</span>
                </div>

            </section>
            <!-- /Counter -->
            <?php endif; ?>

            <?php if( !$hideemailform ): ?>
            <form id="signup" class="notify-form cf" action="inc/email/mailchimp.php" method="POST">
                <fieldset class="pressed-box">
                    <p class="input-cont">
                        <input class="email-input" type="email" name="email" placeholder="Enter your email address" required>
                        <i class="icon-ok-sign validate good"></i>
                        <i class="icon-remove-sign validate bad"></i>
                    </p>
                    <input type="hidden" name="action" value="nebulus_mailchimp">
                    <button id="notify-submit" type="submit" class="notify-btn btn" ><i class="icon-envelope-alt"></i> <?php echo apply_filters( 'the_title', $options["button_text"] ) ?></button>
                </fieldset>
                <div class="alert-cont">
                    <p class="alert hidden"></p>
                </div>
            </form>
            <!--<p class="notify-msg"><?php //echo apply_filters( 'the_title', $options["text_below"] ) ?></p>-->
            <p class="notify-msg">Want to get notified when we launch? Enter your email address above and we’ll send you a note.</p>
            <!-- /Notify Me -->
            <?php endif; ?>

        </div>
        <!-- /Wrap -->
    </section>
    <!-- /Top Section -->

    <section id="bottom" class="bottom-section">
        <div class="row bottom-cols wrap cf">
            <article class="col span4">
                <?php echo wp_kses_post( $options["content1"] ); ?>
            </article>

            <article class="col span4">
                <?php echo wp_kses_post( $options["content2"] ); ?>
            </article>

            <article class="col span4">

                <?php if ( isset($tweets) && !$hidetwitter): ?>
                <div class="twitter">
                    <i class="icon-twitter"></i>
                    <h3 class="twitter_title">Latest Tweets</h3>
                    <?php if( is_string($tweets) ):

                        echo '<p class="alert alert-error"><strong>Error:</strong> ' .$tweets . '</p>';

                    else: ?>
                    <div class="tweets-ticker">
                        <ul class="tweet_list">
                            <?php foreach ( $tweets as $tweet ): ?>
                            <li>
                                <a class="tweet_avatar" href="<?php echo $tweet->user->url; ?>">
                                    <img src="<?php echo $tweet->user->profile_image_url; ?>" alt="<?php echo $tweet->user->screen_name ?>'s avatar" title="<?php echo $tweet->user->screen_name ?>'s avatar">
                                </a>
                                <span class="tweet_text">
                                    <?php echo $tweet->text; ?>
                                </span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                </div>

                <?php if( !empty($options['tweet_user'])): ?>
                <p class="alignright follow-btn"><a class="btn" target="_blank" href="https://twitter.com/intent/user?screen_name=<?php echo $options['tweet_user']; ?>"><i class="icon-twitter"></i> Follow Us</a></p>
                <?php endif; ?>
                <?php else:

                echo wp_kses_post( $options["content3"] );

                endif; ?>
            </article>

        </div>
    </section>
    <!-- /Bottom Columns -->
    <?php if ( !$hidefooter ): ?>
    <footer>
        <section class="wrap">
            <?php if(isset( $options["footerlogo"] ) && $options["footerlogo"] ): ?>
                <img src="<?php echo esc_url($options["footerlogo"]); ?>" alt="<?php bloginfo( 'name' ); ?>" class="brand">
            <?php else: ?>
                <h1 class="brand ir"><?php bloginfo('name'); ?></h1>
            <?php endif; ?>

            <p class="copyright">&copy; <?php echo date('Y') ?> Scouping | <?php bloginfo('description'); ?></p>
        </section>
    </footer>
    <!-- /Footer -->
    <?php endif; ?>


<!-- JS (CDN jQuery with Local Fallback)-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script>!window.jQuery && document.write('<script src="<?php echo plugins_url("js/libs/jquery.min.js", __FILE__); ?>"><\/script>')</script>
<script>
    (function($){
        window.wp_ajax_url = '<?php echo admin_url( 'admin-ajax.php' );  ?>';
        window.launchDate = '<?php echo esc_js($options['launchdate']); ?>';
    })(jQuery);
</script>
<script src="<?php echo plugins_url('js/libs/jquery.tweet.js', __FILE__); ?>"></script>
<script src="<?php echo plugins_url('js/scripts.js', __FILE__); ?>"></script>

<?php if(isset($options['google_analytics']) && $options['google_analytics']){
    echo $options['google_analytics'];
} ?>

</body></html>
<?php exit(); ?>
