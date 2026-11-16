<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

require_once __DIR__ . '/../../helpers/page-components.php';

final class PageComponentsTest extends TestCase
{
    #[Test]
    public function findsTheLayoutAmongThePageComponents(): void
    {
        $components = [['acf_fc_layout' => 'notice'], ['acf_fc_layout' => 'second_pillar_payment_rate_hero']];

        $this->assertTrue(components_include_layout($components, 'second_pillar_payment_rate_hero'));
    }

    #[Test]
    public function doesNotFindALayoutThePageDoesNotUse(): void
    {
        $components = [['acf_fc_layout' => 'notice'], ['acf_fc_layout' => 'front_hero']];

        $this->assertFalse(components_include_layout($components, 'second_pillar_payment_rate_hero'));
    }

    #[Test]
    public function findsNothingOnAPageWithoutComponents(): void
    {
        $this->assertFalse(components_include_layout(null, 'second_pillar_payment_rate_hero'));
        $this->assertFalse(components_include_layout(false, 'second_pillar_payment_rate_hero'));
    }
}
