<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

// Stub the WordPress + theme functions that credentials.php depends on, so the
// template can be rendered in isolation and we can assert that it actually
// publishes the investor count on the page.
if (!function_exists('get_investor_count')) {
    function get_investor_count()
    {
        return $GLOBALS['__test_investor_count'] ?? 0;
    }
}
if (!function_exists('get_sub_field')) {
    function get_sub_field($name)
    {
        return $GLOBALS['__test_sub_fields'][$name] ?? '';
    }
}
if (!function_exists('the_sub_field')) {
    function the_sub_field($name)
    {
        echo $GLOBALS['__test_sub_fields'][$name] ?? '';
    }
}

final class CredentialsComponentTest extends TestCase
{
    private function render(int $investorCount): string
    {
        $GLOBALS['__test_investor_count'] = $investorCount;
        $GLOBALS['__test_sub_fields'] = [
            'component_id' => 'credentials',
            'members_count_description' => 'Eesti inimest kogub Tuleva indeksfondides',
            'security_text' => '',
            'security_link_text' => '',
            'security_link_url' => '',
        ];

        ob_start();
        include __DIR__ . '/../../templates/components/credentials.php';
        return ob_get_clean();
    }

    #[Test]
    public function publishesInvestorCountInMembercountSpan(): void
    {
        $html = $this->render(85224);

        $this->assertStringContainsString('class="membercount', $html);
        // number_format(85224, 0, '', ' ') => "85 224"
        $this->assertStringContainsString('85 224', $html);
        $this->assertStringContainsString('Eesti inimest kogub Tuleva indeksfondides', $html);
    }

    #[Test]
    public function hidesTheBlockWhenCountIsZero(): void
    {
        $html = $this->render(0);

        // When every fallback resolves to 0 the number must not be published at all,
        // rather than showing a misleading "0".
        $this->assertStringNotContainsString('class="membercount', $html);
    }
}
