<?php
/**
 * Editable copy for the II pillar 2-4-6 landing page (page_second-pillar-246.php).
 *
 * Only the hero heading is edited in wp-admin; the template falls back to the
 * Estonian copy when the field is empty. All other copy lives in the template.
 */
if ( function_exists( 'acf_add_local_field_group' ) ) :

	acf_add_local_field_group( array(
		'key'    => 'group_sp246_copy',
		'title'  => 'II samba 2-4-6 — tekstid',
		'fields' => array(
			array(
				'key'           => 'field_sp246_hero_title',
				'label'         => 'Hero pealkiri',
				'name'          => 'hero_title',
				'type'          => 'text',
				'default_value' => 'Tõsta II samba sissemakset',
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'page_template',
					'operator' => '==',
					'value'    => 'page_second-pillar-246.php',
				),
			),
		),
		'menu_order'      => 0,
		'position'        => 'normal',
		'style'           => 'default',
		'label_placement' => 'top',
	) );

endif;
