<?php

namespace DreamFactory\Core\Informix\Tests\Security;

use PHPUnit\Framework\TestCase;

/**
 * Security: InformixSchema must parameterize getTableConstraints schema lookup.
 */
class SchemaInterpolationTest extends TestCase
{
    private string $contents;

    protected function setUp(): void
    {
        $sourcePath = __DIR__ . '/../../src/Database/Schema/InformixSchema.php';
        $this->assertFileExists($sourcePath);
        $this->contents = file_get_contents($sourcePath);
    }

    public function testNoSchemaInterpolation(): void
    {
        $this->assertDoesNotMatchRegularExpression(
            "/IN\s*\(\s*'\{\\\$schema\}'\s*\)/",
            $this->contents,
            'getTableConstraints must not interpolate \$schema into IN clause'
        );
    }

    public function testInClauseUsesPlaceholders(): void
    {
        $this->assertMatchesRegularExpression(
            '/array_fill\s*\(\s*0\s*,\s*count\s*\(\s*\$schemas\s*\)/',
            $this->contents
        );
    }
}
