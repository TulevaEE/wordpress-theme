<?php
/**
 * Editable copy for the II pillar 2-4-6 landing page (page_second-pillar-246.php).
 *
 * These fields let the text be edited in wp-admin without a theme deploy. The
 * template falls back to the original Estonian copy when a field is empty, so
 * the page always renders. The calculators, the hero lead, the slider hints and
 * the closing box stay in the template.
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
			array(
				'key'           => 'field_sp246_intro_1',
				'label'         => 'Sissejuhatus — 1. lõik',
				'name'          => 'intro_1',
				'type'          => 'wysiwyg',
				'tabs'          => 'visual',
				'toolbar'       => 'full',
				'media_upload'  => 0,
				'default_value' => '<p>See ei ole ühekordne, vaid läbi aastate püsiv ja kasvav maksuvõit.</p>',
			),
			array(
				'key'           => 'field_sp246_intro_2',
				'label'         => 'Sissejuhatus — 2. lõik',
				'name'          => 'intro_2',
				'type'          => 'wysiwyg',
				'tabs'          => 'visual',
				'toolbar'       => 'full',
				'media_upload'  => 0,
				'default_value' => '<p>II pensionisammas ei ole muud kui üks investeerimiskonto, kuhu sa kogud elu jooksul endale vara. See vara on sinu oma ja edasi pärandatav.</p>',
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
