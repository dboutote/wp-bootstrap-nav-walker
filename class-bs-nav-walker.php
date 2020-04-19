<?php
/**
 *  Class Name:  BS_Nav_Walker
 *  GitHub URI:  https://github.com/dupkey/bs4navwalker
 *  Description: A custom WordPress nav walker class for Bootstrap v4 nav menus.
 *  Version:     1.0.0
 *  Author:      Darrin Boutote - @darrinb
 *  License:     GPL-2.0+
 *  License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 *  @link https://developer.wordpress.org/reference/classes/walker_nav_menu/
 *
 *  @note: Based on https://github.com/dupkey/bs4navwalker
 */

namespace DBDB;

class BS_Nav_Walker extends \Walker_Nav_Menu
{


	/**
	 *  Starts the list before the elements are added.
	 *
	 *  @since WP 3.0.0
	 *
	 *  @see Walker::start_lvl()
	 *
	 *  @param string   $output Used to append additional content (passed by reference).
	 *  @param int      $depth  Depth of menu item. Used for padding.
	 *  @param stdClass $args   An object of wp_nav_menu() arguments.
	 */
    public function start_lvl( &$output, $depth = 0, $args = null ) {

        if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = str_repeat( $t, $depth );

        // Default class.
        $classes = [ 'sub-menu', 'dropdown-menu' ];

        /**
         *  Filters the CSS class(es) applied to a menu list element.
         *
         *  @since WP 4.8.0
         *
         *  @param string[] $classes Array of the CSS classes that are applied to the menu `<ul>` element.
         *  @param stdClass $args    An object of `wp_nav_menu()` arguments.
         *  @param int      $depth   Depth of menu item. Used for padding.
         */
        $class_names = join( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$output .= "{$n}{$indent}<div role=\"menu\"$class_names>{$n}";

    }


    /**
     *  Ends the list of after the elements are added.
     *
     *  @since WP 3.0.0
     *
     *  @see Walker::end_lvl()
     *
     *  @param string   $output Used to append additional content (passed by reference).
     *  @param int      $depth  Depth of menu item. Used for padding.
     *  @param stdClass $args   An object of wp_nav_menu() arguments.
     */
    public function end_lvl( &$output, $depth = 0, $args = null ) {

        if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent  = str_repeat( $t, $depth );
        $output .= "$indent</div>{$n}";

    }


	/**
	 *  Starts the element output.
	 *
	 *  @since WP 3.0.0
	 *  @since WP 4.4.0 The {@see 'nav_menu_item_args'} filter was added.
	 *
	 *  @see Walker::start_el()
	 *
	 *  @param string   $output Used to append additional content (passed by reference).
	 *  @param WP_Post  $item   Menu item data object.
	 *  @param int      $depth  Depth of menu item. Used for padding.
	 *  @param stdClass $args   An object of wp_nav_menu() arguments.
	 *  @param int      $id     Current item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = [], $id = 0 )
	{

        if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

        /**
         *  Filters the arguments for a single nav menu item.
         *
         *  @since WP 4.4.0
         *
         *  @param stdClass $args  An object of wp_nav_menu() arguments.
         *  @param WP_Post  $item  Menu item data object.
         *  @param int      $depth Depth of menu item. Used for padding.
         */
        $args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

        /**
         *  Filters the CSS classes applied to a menu item's list item element.
         *
         *  @since WP 3.0.0
         *  @since WP 4.1.0 The `$depth` parameter was added.
         *
         *  @param string[] $classes Array of the CSS classes that are applied to the menu item's `<li>` element.
         *  @param WP_Post  $item    The current menu item.
         *  @param stdClass $args    An object of wp_nav_menu() arguments.
         *  @param int      $depth   Depth of menu item. Used for padding.
         */
        $classes   = ( empty( $item->classes ) ) ? [] : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

		$classes[] = 'nav-item';

		if ( $args->has_children || in_array( 'menu-item-has-children', $classes ) ){
			$classes[] = 'dropdown';
		}

		if ( in_array( 'current-menu-item', $classes ) ){
			$classes[] = 'active';
		}

		if( strcasecmp( $item->attr_title, 'disabled' ) == 0 ){
			$classes[] = 'disabled';
		}

		if ( $depth > 0 && ( strcasecmp( $item->attr_title, 'divider' ) == 0 || strcasecmp( $item->title, 'divider' ) == 0 ) ) {
			$classes = [ 'dropdown-divider' ];
		}

		if ( $depth > 0 && strcasecmp( $item->attr_title, 'dropdown-header') == 0 ) {
			$classes = [ 'dropdown-header' ];
		}

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = ( $class_names ) ? ' class="' . esc_attr( $class_names ) . '"' : '';

		/**
		 *  Filters the ID applied to a menu item's list item element.
		 *
		 *  @since WP 3.0.1
		 *  @since WP 4.1.0 The `$depth` parameter was added.
		 *
		 *  @param string   $menu_id The ID that is applied to the menu item's `<li>` element.
		 *  @param WP_Post  $item    The current menu item.
		 *  @param stdClass $args    An object of wp_nav_menu() arguments.
		 *  @param int      $depth   Depth of menu item. Used for padding.
		 */
		$id = 'menu-item-' . $item->ID;

		if ( $depth > 0 && ( strcasecmp( $item->attr_title, 'divider' ) == 0 || strcasecmp( $item->title, 'divider' ) == 0 ) ) {
			$id = '';
		}

		if ( $depth > 0 && strcasecmp( $item->attr_title, 'dropdown-header') == 0 ) {
			$id = '';
		}

		$id = apply_filters( 'nav_menu_item_id', $id, $item, $args, $depth );
		$id = ( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';

		/**
		 *  Dividers, Headers or Disabled
		 *
		 *  @since 1.0.0
		 *
		 *  Determine whether the item is a Divider, Header, Disabled or regular menu item.
		 *  To prevent errors, we use the strcasecmp() function to do a case-insensitive comparison.
		 */
		if ( $depth > 0 && ( strcasecmp( $item->attr_title, 'divider' ) == 0 || strcasecmp( $item->title, 'divider' ) == 0 ) ) {
			$output .= $indent . '<div role="presentation"' . $id . $class_names . '>';
		} else if ( $depth > 0 && strcasecmp( $item->attr_title, 'dropdown-header') == 0 ) {
			$output .= $indent . '<h6 role="presentation"' . $id . $class_names . '>' . esc_attr( $item->title );
		} else if ( $depth === 0 && strcasecmp( $item->attr_title, 'disabled' ) == 0 ) {
			$output .= $indent . '<li role="presentation"' . $id . $class_names . '><a href="#">' . esc_attr( $item->title ) . '</a>';
		} else {

			if( $depth === 0 ) {
				$output .= $indent . '<li' . $id . $class_names .'>';
			}

			$atts           = [];
			$atts['title']  = ( ! empty( $item->attr_title ) ) ? $item->attr_title : '';
			$atts['target'] = ( ! empty( $item->target ) ) ? $item->target	: '' ;
			if ( '_blank' === $item->target && empty( $item->xfn ) ) {
				$atts['rel'] = 'noopener noreferrer';
			} else {
				$atts['rel'] = $item->xfn;
			}
			$href = ( ! empty( $item->url ) ) ? $item->url : '';

			if ( $depth === 0 && ( $args->has_children || in_array( 'menu-item-has-children', $classes ) ) ){
				$href = '#';
			}

			$atts['href']         = $href;
			$atts['aria-current'] = $item->current ? 'page' : '';
			$atts['class']        = [];

			if( in_array( 'current-menu-item', $item->classes ) ) {
				$atts['class'][] = 'active';
			}

			if( $depth === 0 ) {
				$atts['class'][] = 'nav-link';

				if ( $args->has_children || in_array( 'menu-item-has-children', $classes ) ){
					$atts['class'][]       = 'dropdown-toggle';
					$atts['data-toggle']   = 'dropdown';
					$atts['aria-haspopup'] = 'true';

					if( isset( $args->dropdown_hover ) && ! empty( $args->dropdown_hover ) ){
						$atts['data-hover'] = 'dropdown';
					}

				}
			}

			if( $depth > 0 ) {

				$dropdown_classes = [ 'dropdown-item' ];

				if( strcasecmp( $item->attr_title, 'disabled' ) == 0 ){
					$dropdown_classes[] = 'disabled';
				}

				$atts ['class'] = $dropdown_classes;
			}


			$atts_classes = join( ' ', array_filter( $atts['class'] ) );
			$atts['class'] = $atts_classes;

			/**
			 *  Filters the HTML attributes applied to a menu item's anchor element.
			 *
			 *  @since WP 3.6.0
			 *  @since WP 4.1.0 The `$depth` parameter was added.
			 *
			 *  @param array $atts {
			 *      The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
			 *
			 *      @type string $title        Title attribute.
			 *      @type string $target       Target attribute.
			 *      @type string $rel          The rel attribute.
			 *      @type string $href         The href attribute.
			 *      @type string $aria_current The aria-current attribute.
			 *  }
			 *  @param WP_Post  $item  The current menu item.
			 *  @param stdClass $args  An object of wp_nav_menu() arguments.
			 *  @param int      $depth Depth of menu item. Used for padding.
			 */
			$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

			$attributes = '';
			foreach ( $atts as $attr => $value ) {
				if ( is_scalar( $value ) && '' !== $value && false !== $value ) {
					$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}

			/**
			 *  Filters a menu item's title.
			 *
			 *  @since WP 4.4.0
			 *
			 *  @param string   $title The menu item's title.
			 *  @param WP_Post  $item  The current menu item.
			 *  @param stdClass $args  An object of wp_nav_menu() arguments.
			 *  @param int      $depth Depth of menu item. Used for padding.
			 */

			/** This filter is documented in wp-includes/post-template.php */
			$title = apply_filters( 'the_title', $item->title, $item->ID );

			$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

			$item_output  = $args->before;
			$item_output .= '<a' . $attributes . '>';
			$item_output .= $args->link_before . $title . $args->link_after;
			$item_output .= '</a>';
			$item_output .= $args->after;

			/**
			 *  Filters a menu item's starting output.
			 *
			 *  The menu item's starting output only includes `$args->before`, the opening `<a>`,
			 *  the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
			 *  no filter for modifying the opening and closing `<li>` for a menu item.
			 *
			 *  @since WP 3.0.0
			 *
			 *  @param string   $item_output The menu item's starting HTML output.
			 *  @param WP_Post  $item        Menu item data object.
			 *  @param int      $depth       Depth of menu item. Used for padding.
			 *  @param stdClass $args        An object of wp_nav_menu() arguments.
			 */
			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );

		}

	}

    /**
     *  Ends the element output, if needed.
     *
     *  @since WP 3.0.0
     *
     *  @see Walker::end_el()
     *
     *  @param string $output Passed by reference. Used to append additional content.
     *  @param object $item   Page data object. Not used.
     *  @param int    $depth  Depth of page. Not Used.
     *  @param array  $args   An array of arguments. @see wp_nav_menu()
     */
    public function end_el( &$output, $item, $depth = 0, $args = null )
	{
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}

		if ( $depth > 0 && ( strcasecmp( $item->attr_title, 'divider' ) == 0 || strcasecmp( $item->title, 'divider' ) == 0 ) ) {
			$output .= "</div>{$n}";
		} else if ( $depth > 0 && strcasecmp( $item->attr_title, 'dropdown-header') == 0 ) {
			$output .= "</h6>{$n}";
		} else {
			if( $depth === 0 ) {
				$output .= "</li>{$n}";
			}
		}

    }


	/**
	 *
	 *  Traverse elements to create list from elements.
	 *
	 *  Display one element if the element doesn't have any children otherwise,
	 *  display the element and its children. Will only traverse up to the max
	 *  depth and no ignore elements under that depth. It is possible to set the
	 *  max depth to include all depths, see walk() method.
	 *
	 *  This method should not be called directly, use the walk() method instead.
	 *
	 *  @since WP 2.5.0
	 *
	 *  @see Walker::display_element();
	 *
	 *  @param object $element           Data object.
	 *  @param array  $children_elements List of elements to continue traversing (passed by reference).
	 *  @param int    $max_depth         Max depth to traverse.
	 *  @param int    $depth             Depth of current element.
	 *  @param array  $args              An array of arguments.
	 *  @param string $output            Used to append additional content (passed by reference).
	 */
	public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output )
	{
		if ( ! $element ) {
			return;
		}

		$id_field = $this->db_fields['id'];
		$id       = $element->$id_field;

		$has_children = ! empty( $children_elements[ $id ] );

		if ( isset( $args[0] ) ) {

			if ( is_array( $args[0] ) ) {
				$args[0]['has_children'] = $has_children;
			}

			if ( is_object( $args[0] ) ) {
				$args[0]->has_children = $has_children;
			}
		}

        parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );

    }

}
