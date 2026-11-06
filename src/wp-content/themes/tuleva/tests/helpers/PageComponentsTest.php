<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

$GLOBALS['page_components_meta'] = [];
$GLOBALS['page_components_query'] = ['singular' => true, 'id' => 0];

if (!function_exists('get_post_meta')) {
    function get_post_meta($post_id, $key = '', $single = false)
    {
        return $GLOBALS['page_components_meta'][$post_id][$key] ?? '';
    }
}

if (!function_exists('is_singular')) {
    function is_singular()
    {
        return $GLOBALS['page_components_query']['singular'];
    }
}

if (!function_exists('get_queried_object_id')) {
    function get_queried_object_id()
    {
        return $GLOBALS['page_components_query']['id'];
    }
}

if (!function_exists('acf_get_valid_post_id')) {
    function acf_get_valid_post_id($post_id)
    {
        return $GLOBALS['page_components_query']['acf_renders'] ?? $post_id;
    }
}

require_once __DIR__ . '/../../helpers/page-components.php';

final class PageComponentsTest extends TestCase
{
    protected function setUp(): void
    {
        $GLOBALS['page_components_meta'] = [];
        $GLOBALS['page_components_query'] = ['singular' => true, 'id' => 0];
    }

    #[Test]
    public function aPageUsesAComponentItsLayoutListHolds(): void
    {
        $GLOBALS['page_components_meta'][4]['page_components'] = ['notice', 'second_pillar_payment_rate_hero', 'credentials'];

        $this->assertTrue(page_uses_component(4, 'second_pillar_payment_rate_hero'));
    }

    #[Test]
    public function aPageDoesNotUseAComponentMissingFromItsLayoutList(): void
    {
        $GLOBALS['page_components_meta'][4]['page_components'] = ['notice', 'front_hero', 'credentials'];

        $this->assertFalse(page_uses_component(4, 'second_pillar_payment_rate_hero'));
    }

    #[Test]
    public function aPageWithoutComponentsUsesNone(): void
    {
        $this->assertFalse(page_uses_component(17, 'second_pillar_payment_rate_hero'));
    }

    #[Test]
    public function theCurrentPageUsesAComponentWhenItIsASinglePageHoldingIt(): void
    {
        $GLOBALS['page_components_meta'][4]['page_components'] = ['notice', 'second_pillar_payment_rate_hero'];
        $GLOBALS['page_components_query'] = ['singular' => true, 'id' => 4];

        $this->assertTrue(current_page_uses_component('second_pillar_payment_rate_hero'));
    }

    #[Test]
    public function anArchiveDoesNotUseThePageComponentsOfAPostSharingItsTermId(): void
    {
        $GLOBALS['page_components_meta'][4]['page_components'] = ['notice', 'second_pillar_payment_rate_hero'];
        $GLOBALS['page_components_query'] = ['singular' => false, 'id' => 4];

        $this->assertFalse(current_page_uses_component('second_pillar_payment_rate_hero'));
    }

    #[Test]
    public function aPreviewUsesTheComponentsOfTheRevisionAcfRenders(): void
    {
        $GLOBALS['page_components_meta'][27233]['page_components'] = ['rich_text_block'];
        $GLOBALS['page_components_meta'][900]['page_components'] = ['second_pillar_payment_rate_hero', 'rich_text_block'];
        $GLOBALS['page_components_query'] = ['singular' => true, 'id' => 27233, 'acf_renders' => 900];

        $this->assertTrue(current_page_uses_component('second_pillar_payment_rate_hero'));
    }

    #[Test]
    public function aPageAcfRendersAsPublishedUsesThePublishedComponents(): void
    {
        $GLOBALS['page_components_meta'][27233]['page_components'] = ['second_pillar_payment_rate_hero'];
        $GLOBALS['page_components_query'] = ['singular' => true, 'id' => 27233];

        $this->assertTrue(current_page_uses_component('second_pillar_payment_rate_hero'));
    }
}
