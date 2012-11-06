<?php

/**
 * Class to create new custom post types
 */
class RPS_CreateCustomPostType {

  public
    $postOrPage = 'post',
    $hierarchicalPost = false,
    $menuPos = 5,
    $supports = array();

  public function __construct( $nameArray ) {
    $this->cptName = $nameArray['cptName'];
    $this->singularName = $nameArray['singularName'];
    $this->pluralName = ( $nameArray['pluralName'] == null ) ? $nameArray['singularName'] : $nameArray['pluralName'];
    $this->slug = ( $nameArray['slug'] == null ) ? $nameArray['cptName'] : $nameArray['slug'];
    $this->supports = ( $nameArray['supports'] != null ) ? $nameArray['supports'] : array('title', 'editor', 'thumbnail');
    $this->menuName = ( $nameArray['menuName'] != null ) ? $nameArray['menuName'] : $this->pluralName;
  }

  public function createPostType(){
    $cptArgs = array(
      'labels' => array(
        'name' => __( ucfirst( $this->pluralName ), 'xbrdr' ),
        'singular_name' => __( $this->singularName, 'xbrdr' ),
        'add_new' => sprintf( __( 'Lägg till ny %s', 'xbrdr'), $this->singularName),
        'add_new_item' => sprintf( __( 'Lägg till ny %s', 'xbrdr'), ucfirst($this->singularName) ),
        'edit_item' => sprintf( __( 'Redigera %s', 'xbrdr '), $this->singularName ),
        'new_item' => sprintf( __( 'Ny %s', 'xbrdr'), $this->singularName ),
        'view_item' => sprintf( __( 'Kolla %s', 'xbrdr'), $this->singularName ),
        'search_items' => sprintf( __( 'Sök %s', 'xbrdr'), ucfirst($this->pluralName) ),
        'not_found' =>  sprintf( __( 'Inga %s hittades', 'xbrdr'), $this->pluralName ),
        'not_found_in_trash' => sprintf( __( 'Inga %s hittades i soptunnan!', 'xbrdr' ), $this->pluralName),
        'menu_name' => ucfirst( $this->menuName ),
      ),
      'hierarchical' => $this->hierarchicalPost,
      'public' => true,
      'rewrite' => array( 'slug' => $this->slug ),
      'query_var' => true,
      'supports' => $this->supports,
      'menu_position' => $this->menuPos,
    );
    register_post_type( $this->cptName, $cptArgs );
  }
}

/**
* Create a custom post type with a custom taxonomy
*/
class RPS_CreateCustomPostTypeWithTaxonomy extends RPS_CreateCustomPostType {
  public $customTax = array(
    'heirarchical' => true, // like categories
    'name' => '',
    'nameSingular' => '',
    'namePlural' => '',
    'taxSlug' => ''
  );
  public function createTaxonomy(){

    $name = $this->customTax['name'];
    $nameSingular = ( !empty( $this->customTax['nameSingular'] ) ) ? $this->customTax['nameSingular'] : $this->customTax['name'];
    $namePlural = ( !empty( $this->customTax['namePlural'] ) ) ? $this->customTax['namePlural'] : $this->customTax['name'];
    $taxSlug = ( !empty( $this->customTax['taxSlug'] ) ) ? $this->customTax['taxSlug'] : $this->customTax['name'];
    $labels = array(
      'name' => _x( ucfirst( $name ), 'taxonomy general name' ),
      'singular_name' => __( $nameSingular, 'taxonomy singular name' ),
      'search_items' =>  __( 'Sök ' . $namePlural ),
      'popular_items' => __( 'Populära ' . $namePlural ),
      'all_items' => __( 'Alla ' . $namePlural ),
      'parent_item' => __( 'Parent ' . $namePlural ),
      'parent_item_colon' => __( 'Forälder ' . $namePlural ),
      'edit_item' => __( 'Redigera ' . $nameSingular ),
      'update_item' => __( 'Uppdatera ' . $nameSingular ),
      'add_new_item' => __( 'Lägg till ny ' . $nameSingular )
    );
    register_taxonomy(
      $name,
      array( $this->cptName ),
      array(
        'hierarchical' => $this->customTax['heirarchical'],
        'labels' => $labels,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => $taxSlug )
      )
    );
  }
}
