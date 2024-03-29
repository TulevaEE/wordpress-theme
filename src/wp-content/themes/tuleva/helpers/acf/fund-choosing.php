<?php
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
	'key' => 'group_63b73cd6e9f6c',
	'title' => 'Instructions — Step 1',
	'fields' => array(
		array(
			'key' => 'field_63b73cd6e9f80',
			'label' => 'Left box',
			'name' => 'left_box',
			'type' => 'group',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'wpml_cf_preferences' => 0,
			'layout' => 'row',
			'sub_fields' => array(
				array(
					'key' => 'field_63b73cd6ed09e',
					'label' => 'Subtitle',
					'name' => 'subtitle',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'wpml_cf_preferences' => 0,
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_63b73cd6ed0a6',
					'label' => 'Title',
					'name' => 'title',
					'type' => 'group',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'wpml_cf_preferences' => 0,
					'layout' => 'table',
					'sub_fields' => array(
						array(
							'key' => 'field_63b73cd6f2dc0',
							'label' => 'Text',
							'name' => 'text',
							'type' => 'text',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'wpml_cf_preferences' => 0,
							'default_value' => '',
							'placeholder' => '',
							'prepend' => '',
							'append' => '',
							'maxlength' => '',
						),
						array(
							'key' => 'field_63b73cd6f2dc8',
							'label' => 'Link',
							'name' => 'link',
							'type' => 'page_link',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'post_type' => array(
								0 => 'page',
							),
							'taxonomy' => '',
							'allow_null' => 0,
							'allow_archives' => 1,
							'multiple' => 0,
							'wpml_cf_preferences' => 0,
						),
					),
				),
				array(
					'key' => 'field_63b73cd6ed0ac',
					'label' => 'Image',
					'name' => 'image',
					'type' => 'image',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'return_format' => 'url',
					'preview_size' => 'medium',
					'library' => 'all',
					'min_width' => '',
					'min_height' => '',
					'min_size' => '',
					'max_width' => '',
					'max_height' => '',
					'max_size' => '',
					'mime_types' => '',
					'wpml_cf_preferences' => 0,
				),
				array(
					'key' => 'field_63b73cd6ed0b3',
					'label' => 'Link',
					'name' => 'link',
					'type' => 'group',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'wpml_cf_preferences' => 0,
					'layout' => 'table',
					'sub_fields' => array(
						array(
							'key' => 'field_63b73cd70c23c',
							'label' => 'Text',
							'name' => 'text',
							'type' => 'text',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'wpml_cf_preferences' => 0,
							'default_value' => '',
							'placeholder' => '',
							'prepend' => '',
							'append' => '',
							'maxlength' => '',
						),
						array(
							'key' => 'field_63b73cd70c244',
							'label' => 'Link',
							'name' => 'link',
							'type' => 'page_link',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'post_type' => array(
								0 => 'page',
							),
							'taxonomy' => '',
							'allow_null' => 0,
							'allow_archives' => 1,
							'multiple' => 0,
							'wpml_cf_preferences' => 0,
						),
					),
				),
				array(
					'key' => 'field_63b73cd6ed0b9',
					'label' => 'Button',
					'name' => 'button',
					'type' => 'group',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'wpml_cf_preferences' => 0,
					'layout' => 'table',
					'sub_fields' => array(
						array(
							'key' => 'field_63b73cd716761',
							'label' => 'Text',
							'name' => 'text',
							'type' => 'text',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'wpml_cf_preferences' => 0,
							'default_value' => '',
							'placeholder' => '',
							'prepend' => '',
							'append' => '',
							'maxlength' => '',
						),
						array(
							'key' => 'field_63b73cd716769',
							'label' => 'Link',
							'name' => 'link',
							'type' => 'page_link',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'post_type' => array(
								0 => 'page',
							),
							'taxonomy' => '',
							'allow_null' => 0,
							'allow_archives' => 1,
							'multiple' => 0,
							'wpml_cf_preferences' => 0,
						),
					),
				),
				array(
					'key' => 'field_63b73cd6ed0c0',
					'label' => 'Text',
					'name' => 'text',
					'type' => 'wysiwyg',
					'instructions' => 'Use class="text-highlight" to highlight text and class="list-style-checkmark" for list with checkmarks.',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'wpml_cf_preferences' => 0,
					'default_value' => '',
					'tabs' => 'all',
					'toolbar' => 'full',
					'media_upload' => 0,
					'delay' => 0,
				),
			),
		),
		array(
			'key' => 'field_63b73cd6e9f88',
			'label' => 'Right box',
			'name' => 'right_box',
			'type' => 'group',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'wpml_cf_preferences' => 0,
			'layout' => 'row',
			'sub_fields' => array(
				array(
					'key' => 'field_63b73cd725622',
					'label' => 'Subtitle',
					'name' => 'subtitle',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'wpml_cf_preferences' => 0,
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_63b73cd72562a',
					'label' => 'Title',
					'name' => 'title',
					'type' => 'group',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'wpml_cf_preferences' => 0,
					'layout' => 'table',
					'sub_fields' => array(
						array(
							'key' => 'field_63b73cd72b020',
							'label' => 'Text',
							'name' => 'text',
							'type' => 'text',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'wpml_cf_preferences' => 0,
							'default_value' => '',
							'placeholder' => '',
							'prepend' => '',
							'append' => '',
							'maxlength' => '',
						),
						array(
							'key' => 'field_63b73cd72b028',
							'label' => 'Link',
							'name' => 'link',
							'type' => 'page_link',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'wpml_cf_preferences' => 0,
							'post_type' => array(
								0 => 'page',
							),
							'taxonomy' => '',
							'allow_null' => 0,
							'allow_archives' => 1,
							'multiple' => 0,
						),
					),
				),
				array(
					'key' => 'field_63b73cd725631',
					'label' => 'Image',
					'name' => 'image',
					'type' => 'image',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'wpml_cf_preferences' => 0,
					'return_format' => 'url',
					'preview_size' => 'medium',
					'library' => 'all',
					'min_width' => '',
					'min_height' => '',
					'min_size' => '',
					'max_width' => '',
					'max_height' => '',
					'max_size' => '',
					'mime_types' => '',
				),
				array(
					'key' => 'field_63b73cd725637',
					'label' => 'Link',
					'name' => 'link',
					'type' => 'group',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'wpml_cf_preferences' => 0,
					'layout' => 'table',
					'sub_fields' => array(
						array(
							'key' => 'field_63b73cd73836f',
							'label' => 'Text',
							'name' => 'text',
							'type' => 'text',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'wpml_cf_preferences' => 0,
							'default_value' => '',
							'placeholder' => '',
							'prepend' => '',
							'append' => '',
							'maxlength' => '',
						),
						array(
							'key' => 'field_63b73cd738377',
							'label' => 'Link',
							'name' => 'link',
							'type' => 'page_link',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'wpml_cf_preferences' => 0,
							'post_type' => array(
								0 => 'page',
							),
							'taxonomy' => '',
							'allow_null' => 0,
							'allow_archives' => 1,
							'multiple' => 0,
						),
					),
				),
				array(
					'key' => 'field_63b73cd72563e',
					'label' => 'Button',
					'name' => 'button',
					'type' => 'group',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'wpml_cf_preferences' => 0,
					'layout' => 'table',
					'sub_fields' => array(
						array(
							'key' => 'field_63b73cd74292b',
							'label' => 'Text',
							'name' => 'text',
							'type' => 'text',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'wpml_cf_preferences' => 0,
							'default_value' => '',
							'placeholder' => '',
							'prepend' => '',
							'append' => '',
							'maxlength' => '',
						),
						array(
							'key' => 'field_63b73cd742933',
							'label' => 'Link',
							'name' => 'link',
							'type' => 'page_link',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'wpml_cf_preferences' => 0,
							'post_type' => array(
								0 => 'page',
							),
							'taxonomy' => '',
							'allow_null' => 0,
							'allow_archives' => 1,
							'multiple' => 0,
						),
					),
				),
				array(
					'key' => 'field_63b73cd725645',
					'label' => 'Text',
					'name' => 'text',
					'type' => 'wysiwyg',
					'instructions' => 'Use class="text-highlight" to highlight text and class="list-style-checkmark" for list with checkmarks.',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'wpml_cf_preferences' => 0,
					'default_value' => '',
					'tabs' => 'all',
					'toolbar' => 'full',
					'media_upload' => 0,
					'delay' => 0,
				),
			),
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'page_template',
				'operator' => '==',
				'value' => 'page_instructions1.php',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'seamless',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => array(
		0 => 'the_content',
		1 => 'discussion',
		2 => 'comments',
		3 => 'featured_image',
		4 => 'categories',
		5 => 'tags',
		6 => 'send-trackbacks',
	),
	'active' => true,
	'description' => '',
));

endif;
