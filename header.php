<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Evaflor</title>
    <script>
        var baseWordpressThemeUrl = "<?php echo get_stylesheet_directory_uri(); ?>";
    </script>
    <script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/jquery.js"></script>
    <script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/jquery-easing.js"></script>
    
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy"
        crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js" integrity="sha384-a5N7Y/aK3qNeh15eJKGWxsqtnX/wWdSZSKp+81YjTmS15nvnvxKHuzaWwXHDli+4"
        crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed|Yellowtail" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src='https://www.google.com/recaptcha/api.js'></script>
    
	<link href="<?php echo get_bloginfo('template_directory'); ?>/style.css" rel="stylesheet">
    <?php wp_head(); ?>

</head>

<body>

<nav class="navbar navbar-expand-lg fixed-top">
    <a class="navbar-brand" href="#">
        <img src="<?php echo get_bloginfo('template_directory'); ?>/images/evaflor-logo-white.png" alt="">
    </a>
    <div class="languages-container">
        <a href="javascript:setDefaultLanguage('en')" id="en" class="language-select">
            <img src="<?php echo get_bloginfo('template_directory'); ?>/images/english.png">
        </a>
        <a href="javascript:setDefaultLanguage('fr')" id="fr" class="language-select active">
            <img src="<?php echo get_bloginfo('template_directory'); ?>/images/french.png">
        </a>
    </div>
    <a href="#" class="navbar-toggler" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false"
        aria-label="Toggle navigation">
        <i class="fa fa-bars" aria-hidden="true"></i>
    </a>
    <div class="collapse navbar-collapse" id="navbarText">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link wordings wordings-navbar-aboutus" href="#">About us
                    <span class="sr-only">(current)</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link wordings wordings-navbar-whiskycollection" href="#">Whisky collection</a>
            </li>
            <li class="nav-item">
                <a class="nav-link wordings wordings-navbar-allproducts" href="#">Our products</a>
            </li>
            <li class="nav-item">
                <a class="nav-link wordings wordings-navbar-allproducts" href="#">Our products</a>
            </li>
            <li class="nav-item language-select-li">
                <a href="javascript:setDefaultLanguage('fr')">
                    <img src="<?php echo get_bloginfo('template_directory'); ?>/images/french.png">
                </a>
                <a class="active" href="javascript:setDefaultLanguage('en')">
                    <img src="<?php echo get_bloginfo('template_directory'); ?>/images/english.png">
                </a>
            </li>
            <li class="nav-item social-icons">
                <a href="https://www.facebook.com/evaflor.paris">
                    <img src="<?php echo get_bloginfo('template_directory'); ?>/images/facebook.png">
                </a>
                <a href="#">
                    <img src="<?php echo get_bloginfo('template_directory'); ?>/images/twitter.png">
                </a>
                <a href="https://www.instagram.com/evaflorparis">
                    <img src="<?php echo get_bloginfo('template_directory'); ?>/images/insta.png">
                </a>
                <a href="contact.html">
                    <img src="<?php echo get_bloginfo('template_directory'); ?>/images/mail.png">
                </a>
            </li>
        </ul>
    </div>
</nav>

<div class="main-bg"></div>
    <!-- You write code for this content block in another file -->
