<?php

/*
 
Plugin Name: Obsidian custom products post type
 
Description: Custom post type for looping/on action products 
 
Version: 1

*/

// Our custom post type function
function ob_create_post_type()
{

    register_post_type(
        'custom_product',
        // CPT Options
        array(
            'labels' => array(
                'name' => 'Custom Products',
                'singular_name' => 'Custom Product'
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'custom_product'),
            'show_in_rest' => true,
            'publicly_queryable'  => false

        )
    );
}
// Hooking up our function to theme setup
add_action('init', 'ob_create_post_type');


/*
* Creating a function to create our CPT
*/

function ob_custom_post_type()
{

    // Set UI labels for Custom Post Type
    $labels = array(
        'name'                => array('Custom Products', 'Post Type General Name'),
        'singular_name'       => array('Custom Product', 'Post Type Singular Name'),
        'menu_name'           => 'Custom Products',
        'parent_item_colon'   => 'Parent Custom Product',
        'all_items'           => 'All Custom Products',
        'view_item'           => 'View Custom Product',
        'add_new_item'        => 'Add New Custom Product',
        'add_new'             => 'Add New',
        'edit_item'           => 'Edit Custom Product',
        'update_item'         => 'Update Custom Product',
        'search_items'        => 'Search Custom Product',
        'not_found'           => 'Not Found',
        'not_found_in_trash'  => 'Not found in Trash',
    );

    // Set other options for Custom Post Type

    $args = array(
        'label'               => 'custom_product',
        'description'         => 'Custom Products',
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array('title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields',),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
        'taxonomies'          => array('genres'),
        /* A hierarchical CPT is like Pages and can have
            * Parent and child items. A non-hierarchical CPT
            * is like Posts.
            */
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'show_in_rest' => true,

    );

    // Registering your Custom Post Type
    register_post_type('custom_product', $args);
}

/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/

add_action('init', 'ob_custom_post_type', 0);

/* Register required ACF fields */

function ob_custom_product_acf()
{
    acf_add_local_field_group(array(
        'key' => 'group_61b070dbc2278',
        'title' => 'Custom product fields',
        'fields' => array(
            array(
                'key' => 'field_61b07179cc5c2',
                'label' => 'Secondary text',
                'name' => 'secondary_text',
                'type' => 'wysiwyg',
                'instructions' => 'Second part of product text',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 1,
                'delay' => 0,
            ),
            array(
                'key' => 'field_61b07664bd220',
                'label' => 'Afp id',
                'name' => 'afp_id',
                'type' => 'text',
                'instructions' => 'Pure afp id, the number example, 1650',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_61b076c4bd221',
                'label' => 'product group',
                'name' => 'product_group',
                'type' => 'text',
                'instructions' => 'A product group to add this product too for example nordic_dream_beds	etc.',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_61b0973fbb7be',
                'label' => 'Product index',
                'name' => 'product_index',
                'type' => 'number',
                'instructions' => 'This is the index of the product calendar for amongst other nordic dream.',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'min' => '',
                'max' => '',
                'step' => '',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'custom_product',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => 'Secondary text field',
        'show_in_rest' => 0,
    ));
}

/* Only fire if ACF is active */
add_action('acf/init', 'ob_custom_product_acf');

function show_custom_product($atts)
{
    $dow = 'friday';
    $step = 2;
    $unit = 'W';

    $start = new DateTime('2022-01-21');
    $end = clone $start;

    $offer_index = [1, 2, 3, 4];
    $current_offer_index = 2;

    $start->modify($dow); // Move to first occurence
    $end->add(new DateInterval('P4Y')); // Move to x years from start

    $interval = new DateInterval("P{$step}{$unit}");
    $period = new DatePeriod($start, $interval, $end);

    foreach ($period as $date) {
        $date2 = clone $date;
        $date2 = date_add($date2, date_interval_create_from_date_string('14 days'));

        if (time() >= $date->format('U') && time() <= $date2->format('U')) {
            break;
        }
        $current_offer_index++;
        //fix to count()$offer indeX?
        if ($current_offer_index > 3) $current_offer_index = 0;
    }

    //echo "index is ".$current_offer_index;


    $value = shortcode_atts(array(
        'product_group' => 'test',
        'part' => 'title',
    ), $atts);


    $args = array(
        'post_type' => 'custom_product',
        'posts_per_page' => '1',
        'meta_query' => array(

            'relation' => 'AND',
            array(
                'key' => 'product_group',
                'value' => $value['product_group'],
                'compare' => '=',
            ),

            array(
                'key' => 'product_index',
                'value' => $offer_index[$current_offer_index],
                'compare' => 'AND',
            )
        )

    );

    $query = new WP_Query($args);

    if (!$query->have_posts()) {
        $args = array(
            'post_type' => 'custom_product',
            'posts_per_page' => '1',
            'meta_query' => array(

                'relation' => 'AND',
                array(
                    'key' => 'product_group',
                    'value' => $value['product_group'],
                    'compare' => '=',
                ),

                array(
                    'key' => 'product_index',
                    'value' => '1',
                    'compare' => 'AND',
                )
            ),
        );
        $query = new WP_Query($args);
    }

    if ($query->have_posts()) :
        while ($query->have_posts()) :

            $query->the_post();
            $post_id = get_the_ID();
            //$result .= get_the_content();
            $result = '';
            if ($value['part'] == 'title') {
                $result .= '<h2>' . get_the_title() . '</h2>';
            } elseif ($value['part'] == 'body') {
                $afp = get_post_meta($post_id, 'afp_id', true);
                $result .= do_shortcode(get_the_content());
                $result .= "<div style='margin-bottom:20px;'>" . do_shortcode('[afp id="' . $afp . '"]') . "</div>";
            } elseif ($value['part'] == 'secondary_text') {
                //$result .= the_field('secondary_text', false, false);
                $result .= nl2br(get_post_meta($post_id, 'secondary_text', true));
            }
            return $result;


        endwhile;

        wp_reset_postdata();

    endif;
}

add_shortcode('nordic_dream_bed', 'show_custom_product');
