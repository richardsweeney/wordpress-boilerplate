<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<title></title>
		<title><?php echo bloginfo('name'); ?> <?php wp_title('-'); ?></title>
		<link rel="shortcut icon" href="<?php echo IMG; ?>/favicon.ico">
		<meta name="description" content="" />
		<meta name="author" content="Örestad Linux AB" />
		<meta name="viewport" content="initial-scale=1.0 width=device-width">

		<?php wp_head(); ?>
		<!--[if lt IE 9]>
			<style type="text/css">
			</style>
		<![endif]-->

	</head>
	<body <?php body_class(); ?>>
		<!--[if lt IE 9]><p class="chromeframe">Din webbläsare är <em>föråldrad!</em> <a href="http://browsehappy.com/">Uppgradera till en annan webbläsare</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->

		<div class="site-container">

			<header>
				<a class="brand" href="<?php echo URL; ?>" title="CrossBorder">
					<img src="<?php header_image(); ?>" alt="<?php echo bloginfo('name'); ?>" />
				</a>
				<?php rps_print_main_navigation() ?>
			</header>

			<div class="main-content-container">
