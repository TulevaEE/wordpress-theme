<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class TemplatesTest extends TestCase
{
    #[Test]
    public function templatesDeclareNoFunctionsOrClassesBecauseOneRequestCanRenderThemTwice(): void
    {
        $declarations = [];
        foreach ($this->templateFiles() as $path => $source) {
            foreach ($this->namedDeclarations($source) as $name) {
                $declarations[] = $path . ': ' . $name;
            }
        }

        $this->assertSame([], $declarations);
    }

    private function templateFiles(): array
    {
        $directory = realpath(__DIR__ . '/../../templates');
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS));

        $sources = [];
        foreach ($files as $file) {
            if ($file->getExtension() === 'php') {
                $sources[substr($file->getPathname(), strlen($directory) + 1)] = file_get_contents($file->getPathname());
            }
        }
        ksort($sources);

        return $sources;
    }

    private function namedDeclarations(string $source): array
    {
        $tokens = array_values(array_filter(
            PhpToken::tokenize($source),
            fn(PhpToken $token) => !$token->isIgnorable()
        ));

        $names = [];
        foreach ($tokens as $i => $token) {
            $next = $tokens[$i + 1] ?? null;
            if ($token->is([T_FUNCTION, T_CLASS]) && $next !== null && $next->is(T_STRING)) {
                $names[] = $next->text;
            }
        }

        return $names;
    }
}
