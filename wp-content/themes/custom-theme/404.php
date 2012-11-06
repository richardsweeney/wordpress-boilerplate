<?php get_header(); ?>

<div class="container-fluid wider-page" id="page">
  <div class="row-fluid introtext">
  </div>
  <div class="row-fluid standard-page-template">
  <div class="span3">
    <?php rps_print_main_navigation(true); ?>
  </div>
  <div class="span9">
    <section>
      <h1><?php _e('404 Sidan hittades inte.', 'xbrdr'); ?></h1>
      <p><?php _e('Sidan du letade efter kunde inte hittas!', 'xbrdr'); ?></p>
    </section>
  </div>
</div>

<?php get_footer(); ?>
