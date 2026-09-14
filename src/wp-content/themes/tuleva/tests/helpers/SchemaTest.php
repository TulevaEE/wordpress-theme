<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

require_once __DIR__ . '/../../helpers/schema.php';

final class SchemaTest extends TestCase
{
    #[Test]
    public function organizationDetailsAreAddedToTheYoastOrganizationNode(): void
    {
        $organization = ['@type' => 'Organization', 'name' => 'Tuleva'];

        $result = add_organization_details($organization, [
            'legalName' => 'Tulundusühistu Tuleva',
            'foundingDate' => '2016-05-02',
            'description' => 'Eesti pensionikogujate ühistu.',
        ]);

        $this->assertSame([
            '@type' => 'Organization',
            'name' => 'Tuleva',
            'legalName' => 'Tulundusühistu Tuleva',
            'foundingDate' => '2016-05-02',
            'description' => 'Eesti pensionikogujate ühistu.',
        ], $result);
    }

    #[Test]
    public function emptyOrganizationDetailsAreLeftOut(): void
    {
        $organization = ['@type' => 'Organization', 'name' => 'Tuleva'];

        $result = add_organization_details($organization, [
            'legalName' => 'Tulundusühistu Tuleva',
            'foundingDate' => '',
            'description' => null,
        ]);

        $this->assertSame([
            '@type' => 'Organization',
            'name' => 'Tuleva',
            'legalName' => 'Tulundusühistu Tuleva',
        ], $result);
    }

    #[Test]
    public function faqSchemaListsEveryQuestionWithItsAnswer(): void
    {
        $json = faq_schema_json([
            ['question' => 'Mis on Tuleva?', 'answer' => '<p>Ühistu.</p>'],
            ['question' => '<strong>Kui palju</strong> maksab?', 'answer' => '<p>0,39% aastas.</p>'],
        ]);

        $this->assertSame([
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => [
                [
                    '@type' => 'Question',
                    'name' => 'Mis on Tuleva?',
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => '<p>Ühistu.</p>'],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'Kui palju maksab?',
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => '<p>0,39% aastas.</p>'],
                ],
            ],
        ], json_decode($json, true));
    }

    #[Test]
    public function faqSchemaCannotBreakOutOfTheScriptElement(): void
    {
        $json = faq_schema_json([
            ['question' => 'Kas?', 'answer' => '<p title="</script><b>x</b>">Jah.</p>'],
        ]);

        $this->assertStringNotContainsString('</script>', $json);
        $this->assertStringNotContainsString('<', $json);
        $this->assertSame(
            '<p title="</script><b>x</b>">Jah.</p>',
            json_decode($json, true)['mainEntity'][0]['acceptedAnswer']['text']
        );
    }

    #[Test]
    public function fundSchemaDescribesTheFundWithItsIsinFeesAndDetails(): void
    {
        $json = fund_schema_json([
            'name' => 'Tuleva Maailma Aktsiate Pensionifond',
            'url' => 'https://tuleva.ee/tuleva-maailma-aktsiate-pensionifond/',
            'isin' => 'EE3600109435',
            'provider' => 'Tuleva Fondid AS',
            'fees' => 'Valitsemistasu 0,205%, jooksvad tasud 0,28%',
            'description' => 'Võrdlusindeks: 100% MSCI ACWI (EUR). Riskiprofiil: Agressiivne.',
        ]);

        $this->assertSame([
            '@context' => 'https://schema.org',
            '@type' => 'InvestmentFund',
            'name' => 'Tuleva Maailma Aktsiate Pensionifond',
            'url' => 'https://tuleva.ee/tuleva-maailma-aktsiate-pensionifond/',
            'identifier' => ['@type' => 'PropertyValue', 'propertyID' => 'ISIN', 'value' => 'EE3600109435'],
            'provider' => ['@type' => 'Organization', 'name' => 'Tuleva Fondid AS'],
            'feesAndCommissionsSpecification' => 'Valitsemistasu 0,205%, jooksvad tasud 0,28%',
            'description' => 'Võrdlusindeks: 100% MSCI ACWI (EUR). Riskiprofiil: Agressiivne.',
        ], json_decode($json, true));
    }

    #[Test]
    public function fundSchemaFieldsHeadlineTheOngoingChargesPerYearAndDescribeTheRest(): void
    {
        $fields = fund_schema_fields([
            'ongoing_charges' => 'Jooksvad tasud',
            'per_year' => 'aastas',
            'management_fee' => 'Valitsemistasu',
            'redemption_fee' => 'Väljalasketasu ja tagasivõtmistasu',
            'inception_date' => 'Moodustamise kuupäev',
            'risk_profile' => 'Riskiprofiil',
            'comparison_index' => 'Võrdlusindeks',
        ], [
            'ongoing_charges' => '0,28%',
            'management_fee' => '0,205%',
            'redemption_fee' => '0%',
            'inception_date' => '27. märts 2017',
            'risk_profile' => 'Agressiivne',
            'comparison_index' => ['50% A', '50% B'],
        ]);

        $this->assertSame([
            'fees' => 'Jooksvad tasud 0,28% aastas',
            'description' => 'Valitsemistasu: 0,205%. Väljalasketasu ja tagasivõtmistasu: 0%. Moodustamise kuupäev: 27. märts 2017. Riskiprofiil: Agressiivne. Võrdlusindeks: 50% A, 50% B.',
        ], $fields);
    }

    #[Test]
    public function fundSchemaFieldsLeaveOutMissingDetails(): void
    {
        $fields = fund_schema_fields([
            'ongoing_charges' => 'Ongoing charges',
            'per_year' => 'per year',
            'management_fee' => 'Management fee',
            'redemption_fee' => 'Redemption fee and issue fee',
            'inception_date' => 'Date of inception',
            'risk_profile' => 'Risk profile',
            'comparison_index' => 'Comparison index',
        ], [
            'ongoing_charges' => '0,28%',
            'management_fee' => '',
            'redemption_fee' => '0%',
            'inception_date' => '',
            'risk_profile' => '',
            'comparison_index' => '',
        ]);

        $this->assertSame([
            'fees' => 'Ongoing charges 0,28% per year',
            'description' => 'Redemption fee and issue fee: 0%.',
        ], $fields);
    }

    #[Test]
    public function fundSchemaIsEmptyWithoutAnIsin(): void
    {
        $this->assertSame('', fund_schema_json(['name' => 'Fond', 'isin' => '']));
    }

    #[Test]
    public function faqSchemaIsEmptyWithoutQuestions(): void
    {
        $this->assertSame('', faq_schema_json([]));
    }
}
