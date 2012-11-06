<?php get_header(); ?>

<div class="container-fluid wider-page" id="page">
<?php
  while(have_posts()): the_post();
    $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
    $postParent = $post->post_parent;
    $parent = get_post($postParent);

    if ($parent->post_name == 'om-oss'):
      rps_print_secondary_navigation();
      rps_print_breadcrumbs();
    ?>
    <?php if (has_post_thumbnail()): ?>
    <div class="row-fluid">
      <div class="span12 image-border-container">
        <img src="<?php echo $image[0]; ?>" alt="<?php esc_attr(the_title()); ?>" />
      </div>
    </div>
    <?php endif; ?>
    <?php if (is_page('styrelse')): ?>
      <?php get_template_part('includes/commitee'); ?>
    <?php elseif (is_page('agarstruktur')): ?>
      <?php get_template_part('includes/owners'); ?>
    <?php else: ?>
    <div class="row-fluid introtext">
      <div class="span12">
        <h1 class="down"><?php the_title(); ?></h1>
        <?php the_content(); ?>
      </div>
    </div>
    <?php endif; ?>
  <?php else: ?>
    <div class="row-fluid standard-page-template">
      <div class="span3">
        <?php rps_print_main_navigation(true); ?>
      </div>
      <div class="span9">
        <?php if (has_post_thumbnail()): ?>
        <div class="image-border-container">
          <img src="<?php echo $image[0]; ?>" alt="<?php esc_attr(the_title()); ?>" />
        </div>
        <?php endif; ?>
        <section>
          <h1 <?php if (has_post_thumbnail()) { echo 'class="down"'; } ?>><?php the_title(); ?></h1>
          <?php the_content(); ?>
        </section>
      </div>
    </div>
  <?php endif; ?>

<?php endwhile; ?>
<?php
  if(is_page('detta-ar-crossborder')):
    get_repeater_field_images();
  endif;
 ?>
<?php get_footer(); ?>
