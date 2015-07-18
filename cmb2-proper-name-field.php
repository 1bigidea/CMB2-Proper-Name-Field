<?php
/*
 * Plugin Name: CMB2 Proper Name Field
 * Description: Makes available a 'Proper Name' CMB2 Custom Field Type. Based on https://github.com/WebDevStudios/CMB2/wiki/Adding-your-own-field-types#example-4-multiple-inputs-one-field-lets-create-an-address-field
 * Author: transom
 * Author URI: http://1bigidea.com
 * Version: 1.0
 *
 * Heavily borrowed from Justin Sternberg's sample code for addresses
 * Name types:
 *	name_full - separate fields for salutation, first, middle, last, suffix
 *	name_short - separate fields for first and last
 *	name_simple - single field for name ( same as text_middle )
 */

/**
 * Template tag for displaying a name from the CMB2 proper name field type (on the front-end)
 *
 * @since  1.0.0
 *
 * @param  string  $metakey The 'id' of the 'address' field (the metakey for get_post_meta)
 * @param  integer $post_id (optional) post ID. If using in the loop, it is not necessary
 */
function onebigidea_cmb2_proper_name_field( $metakey, $post_id = 0 ) {
	echo onebigidea_cmb2_get_proper_name_field( $metakey, $post_id );
}

/**
 * Template tag for returning an address from the CMB2 address field type (on the front-end)
 *
 * @since  1.0.0
 *
 * @param  string  $metakey The 'id' of the 'address' field (the metakey for get_post_meta)
 * @param  integer $post_id (optional) post ID. If using in the loop, it is not necessary
 */
function onebigidea_cmb2_get_proper_name_field( $metakey, $post_id = 0 ) {

	$post_id = $post_id ? $post_id : get_the_ID();
	$full_name = get_post_meta( $post_id, $metakey, true );

	// Check to see if is compound name
	if( is_array( $full_name) ) {

		// Set default values for each name key
		$full_name = wp_parse_args( $full_name, array(
			'salutation'		=> '',
			'first_name' 		=> '',
			'middle_name'    	=> '',
			'last_name'			=> '',
			'name_suffix'		=> '',
		) );

		$output = sprintf( '%s %s %s %s %s',
			$full_name['salutation'],
			$full_name['first_name'],
			$full_name['middle_name'],
			$full_name['last_name'],
			$full_name['name_suffix']
		);
		$output = preg_replace ( '/ +/', ' ', $output );

	} else {
		$output = $full_name;
	}
	return apply_filters( 'onebigidea_cmb2_get_proper_name_field', $output, $full_name );
}

/**
 * Render full 'proper name' custom field type
 *
 * @since 1.0.0
 *
 * @param array  $field              The passed in `CMB2_Field` object
 * @param mixed  $value              The value of this field escaped.
 *                                   It defaults to `sanitize_text_field`.
 *                                   If you need the unescaped value, you can access it
 *                                   via `$field->value()`
 * @param int    $object_id          The ID of the current object
 * @param string $object_type        The type of object you are working with.
 *                                   Most commonly, `post` (this applies to all post-types),
 *                                   but could also be `comment`, `user` or `options-page`.
 * @param object $field_type_object  The `CMB2_Types` object
 */
function onebigidea_cmb2_render_proper_name_full_field_callback( $field, $value, $object_id, $object_type, $field_type_object ) {

	// Set default values for each name key
	$value = wp_parse_args( $value, array(
		'salutation'	=> '',
		'first_name' 		=> '',
		'middle_name'    	=> '',
		'last_name'			=> '',
		'name_suffix'		=> '',
	) );

	?>
	<div class="group clearfix">
		<div class="alignleft cmb2-text-10">
			<p>
				<label for="<?php echo $field_type_object->_id( '_salutation' ); ?>'">
					<?php echo esc_html( $field_type_object->_text( 'proper_name_salutation_text', 'Salutation' ) ); ?>
				</label>
			</p>
			<?php echo $field_type_object->input( array(
				'class' => 'cmb2-text-small',
				'name'  => $field_type_object->_name( '[salutation]' ),
				'id'    => $field_type_object->_id( '_salutation' ),
				'value' => $value['salutation'],
				'desc'	=> false,
			) ); ?>
		</div>
		<div class="alignleft cmb2-text-30">
			<p>
				<label for="<?php echo $field_type_object->_id( '_first_name' ); ?>'">
					<?php echo esc_html( $field_type_object->_text( 'proper_name_first_text', 'First Name' ) ); ?>
				</label>
			</p>
			<?php echo $field_type_object->input( array(
				'class' => 'cmb2-text-medium',
				'name'  => $field_type_object->_name( '[first_name]' ),
				'id'    => $field_type_object->_id( '_first_name' ),
				'value' => $value['first_name'],
				'desc'	=> false,
			) ); ?>
		</div>
		<div class="alignleft cmb2-text-10">
			<p>
				<label for="<?php echo $field_type_object->_id( '_middle_name' ); ?>'">
					<?php echo esc_html( $field_type_object->_text( 'proper_name_middle_text', 'Middle' ) ); ?>
				</label>
			</p>
			<?php echo $field_type_object->input( array(
				'class' => 'cmb2-text-small',
				'name'  => $field_type_object->_name( '[middle_name]' ),
				'id'    => $field_type_object->_id( '_middle_name' ),
				'value' => $value['middle_name'],
				'desc'	=> false,
			) ); ?>
		</div>
		<div class="alignleft cmb2-text-30">
			<p>
				<label for="<?php echo $field_type_object->_id( '_last_name' ); ?>'">
					<?php echo esc_html( $field_type_object->_text( 'proper_name_last_name', 'Last Name' ) ); ?>
				</label>
			</p>
			<?php echo $field_type_object->input( array(
				'class' => 'cmb2-text-medium',
				'name'  => $field_type_object->_name( '[last_name]' ),
				'id'    => $field_type_object->_id( '_last_name' ),
				'value' => $value['last_name'],
				'desc'	=> false,
			) ); ?>
		</div>
		<div class="alignleft cmb2-text-10">
			<p>
				<label for="<?php echo $field_type_object->_id( '_name_suffix' ); ?>'">
					<?php echo esc_html( $field_type_object->_text( 'proper_name_suffix_text', 'Suffix' ) ); ?>
				</label>
			</p>
			<?php echo $field_type_object->input( array(
				'class' => 'cmb2-text-small',
				'name'  => $field_type_object->_name( '[name_suffix]' ),
				'id'    => $field_type_object->_id( '_name_suffix' ),
				'value' => $value['name_suffix'],
				'desc'	=> false,
			) ); ?>
		</div>
	</div>
<?php

}
add_filter( 'cmb2_render_name_full',   'onebigidea_cmb2_render_proper_name_full_field_callback', 10, 5 );

/**
 * Render short 'proper name' custom field type
 *
 * @since 1.0.0
 *
 * @param array  $field              The passed in `CMB2_Field` object
 * @param mixed  $value              The value of this field escaped.
 *                                   It defaults to `sanitize_text_field`.
 *                                   If you need the unescaped value, you can access it
 *                                   via `$field->value()`
 * @param int    $object_id          The ID of the current object
 * @param string $object_type        The type of object you are working with.
 *                                   Most commonly, `post` (this applies to all post-types),
 *                                   but could also be `comment`, `user` or `options-page`.
 * @param object $field_type_object  The `CMB2_Types` object
 */
function onebigidea_cmb2_render_proper_name_short_field_callback( $field, $value, $object_id, $object_type, $field_type_object ) {

	// Set default values for each name key
	$value = wp_parse_args( $value, array(
		'salutation'	=> '',
		'first_name' 		=> '',
		'middle_name'    	=> '',
		'last_name'			=> '',
		'name_suffix'		=> '',
	) );

	?>
	<div class="group clearfix">
		<div class="alignleft">
			<p>
				<label for="<?php echo $field_type_object->_id( '_first_name' ); ?>'">
					<?php echo esc_html( $field_type_object->_text( 'proper_name_first_text', 'First Name' ) ); ?>
				</label>
			</p>
			<?php echo $field_type_object->input( array(
				'class' => 'cmb2-text-medium',
				'name'  => $field_type_object->_name( '[first_name]' ),
				'id'    => $field_type_object->_id( '_first_name' ),
				'value' => $value['first_name'],
				'desc'	=> false,
			) ); ?>
		</div>
		<div class="alignleft">
			<p>
				<label for="<?php echo $field_type_object->_id( '_last_name' ); ?>'">
					<?php echo esc_html( $field_type_object->_text( 'proper_name_last_name', 'Last Name' ) ); ?>
				</label>
			</p>
			<?php echo $field_type_object->input( array(
				'class' => 'cmb2-text-medium',
				'name'  => $field_type_object->_name( '[last_name]' ),
				'id'    => $field_type_object->_id( '_last_name' ),
				'value' => $value['last_name'],
				'desc'	=> false,
			) ); ?>
			<?php echo $field_type_object->hidden( array(
				'name'  => $field_type_object->_name( '[salutation]' ),
				'id'    => $field_type_object->_id( '_salutation' ),
				'value' => '',
			) ); ?>
			<?php echo $field_type_object->hidden( array(
				'name'  => $field_type_object->_name( '[middle_name]' ),
				'id'    => $field_type_object->_id( '_middle_name' ),
				'value' => '',
			) ); ?>
			<?php echo $field_type_object->hidden( array(
				'name'  => $field_type_object->_name( '[name_suffix]' ),
				'id'    => $field_type_object->_id( '_name_suffix' ),
				'value' => '',
			) ); ?>
		</div>
	</div>
<?php

}
add_filter( 'cmb2_render_name_short',  'onebigidea_cmb2_render_proper_name_short_field_callback', 10, 5 );

/**
 * Render short 'proper name' custom field type
 *
 * @since 1.0.0
 *
 * @param array  $field              The passed in `CMB2_Field` object
 * @param mixed  $value              The value of this field escaped.
 *                                   It defaults to `sanitize_text_field`.
 *                                   If you need the unescaped value, you can access it
 *                                   via `$field->value()`
 * @param int    $object_id          The ID of the current object
 * @param string $object_type        The type of object you are working with.
 *                                   Most commonly, `post` (this applies to all post-types),
 *                                   but could also be `comment`, `user` or `options-page`.
 * @param object $field_type_object  The `CMB2_Types` object
 */
function onebigidea_cmb2_render_proper_name_simple_field_callback( $field, $value, $object_id, $object_type, $field_type_object ) {

	// Set default values for each name key
	$value = wp_parse_args( $value, array(
		'salutation'	=> '',
		'first_name' 		=> '',
		'middle_name'    	=> '',
		'last_name'			=> '',
		'name_suffix'		=> '',
	) );

	?>
	<div class="group clearfix">
		<div class="alignleft">
			<p>
				<label for="<?php echo $field_type_object->_id( '_first_name' ); ?>'">
					<?php echo esc_html( $field_type_object->_text( 'proper_name_first_text', 'First Name' ) ); ?>
				</label>
			</p>
			<?php echo $field_type_object->input( array(
				'class' => 'cmb2-text-medium',
				'name'  => $field_type_object->_name( '[first_name]' ),
				'id'    => $field_type_object->_id( '_first_name' ),
				'value' => $value['first_name'],
				'desc'	=> false,
			) ); ?>
		</div>
		<div class="alignleft">
			<p>
				<label for="<?php echo $field_type_object->_id( '_last_name' ); ?>'">
					<?php echo esc_html( $field_type_object->_text( 'proper_name_last_name', 'Last Name' ) ); ?>
				</label>
			</p>
			<?php echo $field_type_object->input( array(
				'class' => 'cmb2-text-medium',
				'name'  => $field_type_object->_name( '[last_name]' ),
				'id'    => $field_type_object->_id( '_last_name' ),
				'value' => $value['last_name'],
				'desc'	=> false,
			) ); ?>
			<?php echo $field_type_object->hidden( array(
				'name'  => $field_type_object->_name( '[salutation]' ),
				'id'    => $field_type_object->_id( '_salutation' ),
				'value' => '',
			) ); ?>
			<?php echo $field_type_object->hidden( array(
				'name'  => $field_type_object->_name( '[middle_name]' ),
				'id'    => $field_type_object->_id( '_middle_name' ),
				'value' => '',
			) ); ?>
			<?php echo $field_type_object->hidden( array(
				'name'  => $field_type_object->_name( '[name_suffix]' ),
				'id'    => $field_type_object->_id( '_name_suffix' ),
				'value' => '',
			) ); ?>
		</div>
	</div>
<?php

}
add_filter( 'cmb2_render_name_simple', 'onebigidea_cmb2_render_proper_name_simple_field_callback', 10, 5 );

/**
 * Optionally save the Proper Name values into separate fields
 */
function cmb2_split_proper_name_values( $override_value, $value, $object_id, $field_args ) {

kickout('split', $override_value, $value, $object_id, $field_args );

	if ( ! isset( $field_args['split_values'] ) || ! $field_args['split_values'] ) {
		// Don't do the override
		return $override_value;
	}

	$name_keys = array( 'salutation', 'first_name', 'middle_name', 'last_name', 'name_suffix' );

	foreach ( $address_keys as $key ) {
		if ( ! empty( $value[ $key ] ) ) {
			update_post_meta( $object_id, $field_args['id'] . 'name_'. $key, $value[ $key ] );
		}
	}

	// Tell CMB2 we already did the update
	return true;
}
add_filter( 'cmb2_sanitize_proper_name_field', 'cmb2_split_proper_name_values', 12, 4 );

/**
 * The following snippets are required for allowing the address field
 * to work as a repeatable field, or in a repeatable group
 */

function cmb2_sanitize_proper_name_field( $check, $meta_value, $object_id, $field_args, $sanitize_object ) {

	// if not repeatable, bail out.
	if ( ! is_array( $meta_value ) || ! $field_args['repeatable'] ) {
		return $check;
	}

	foreach ( $meta_value as $key => $val ) {
		$meta_value[ $key ] = array_map( 'sanitize_text_field', $val );
	}

	return $meta_value;
}
add_filter( 'cmb2_sanitize_proper_name_field', 'cmb2_sanitize_proper_name_field', 10, 5 );

function cmb2_types_esc_proper_name_field( $check, $meta_value, $field_args, $field_object ) {
	// if not repeatable, bail out.
	if ( ! is_array( $meta_value ) || ! $field_args['repeatable'] ) {
		return $check;
	}

	foreach ( $meta_value as $key => $val ) {
		$meta_value[ $key ] = array_map( 'esc_attr', $val );
	}

	return $meta_value;
}
add_filter( 'cmb2_types_esc_proper_name_field', 'cmb2_types_esc_proper_name_field', 10, 4 );
