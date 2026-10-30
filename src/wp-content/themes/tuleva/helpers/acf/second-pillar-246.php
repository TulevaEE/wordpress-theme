<?php
/**
 * Editable copy for the II pillar 2-4-6 landing page (page_second-pillar-246.php).
 *
 * These fields let the text be edited in wp-admin without a theme deploy. The
 * template falls back to the original Estonian copy when a field is empty, so
 * the page always renders. The calculators, the hero lead and the slider hints
 * stay in the template because they carry markup (the yellow highlight).
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
			array(
				'key'           => 'field_sp246_cta_title',
				'label'         => 'Lõpuplokk — pealkiri',
				'name'          => 'cta_title',
				'type'          => 'text',
				'default_value' => 'Kogu uuest aastast rohkem',
			),
			array(
				'key'           => 'field_sp246_cta_intro',
				'label'         => 'Lõpuplokk — tekst enne pilti',
				'name'          => 'cta_intro',
				'type'          => 'wysiwyg',
				'tabs'          => 'visual',
				'toolbar'       => 'basic',
				'media_upload'  => 0,
				'default_value' => '<p>Me Tulevas oleme veendunud, et kõige kavalam on suunata II sambasse 6%. Ligi pool meiega koos kogujatest on sissemakset juba tõstnud.</p>',
			),
			array(
				'key'           => 'field_sp246_cta_outro',
				'label'         => 'Lõpuplokk — tekst pärast pilti',
				'name'          => 'cta_outro',
				'type'          => 'wysiwyg',
				'tabs'          => 'visual',
				'toolbar'       => 'basic',
				'media_upload'  => 0,
				'default_value' => '<p>Tee avaldus enne 30. novembrit ära. Siis hakkab suurem tükike palgast sinu II sambasse liikuma juba uuest aastast. Kui tähtaeg mööda lasta, saad uue võimaluse suuremat maksuvõitu kasutada alles aasta pärast.</p><p>Avalduse tegemine võtab paar minutit. Tee see kohe ära ja siis tunne õigustatult uhkust. Oled teinud oma tuleviku heaks midagi, mis töötab edasi iseenesest, iga kuu, märkamatult.</p>',
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
