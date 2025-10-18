<?php

namespace Tests\Unit;

use Tests\TestCase;

class ViteConfigTest extends TestCase
{
    /**
     * Test that vite.config.js file exists.
     */
    public function test_vite_config_file_exists(): void
    {
        $configPath = base_path('vite.config.js');
        $this->assertFileExists($configPath);
    }

    /**
     * Test that vite.config.js contains valid JavaScript.
     */
    public function test_vite_config_contains_valid_javascript(): void
    {
        $configPath = base_path('vite.config.js');
        $content = file_get_contents($configPath);
        
        // Check for basic structure
        $this->assertStringContainsString('export default defineConfig', $content);
        $this->assertStringContainsString('laravel(', $content);
    }

    /**
     * Test that vite.config.js contains esbuild configuration.
     */
    public function test_vite_config_contains_esbuild_config(): void
    {
        $configPath = base_path('vite.config.js');
        $content = file_get_contents($configPath);
        
        $this->assertStringContainsString('esbuild:', $content);
        $this->assertStringContainsString("drop:", $content);
        $this->assertStringContainsString("'console'", $content);
        $this->assertStringContainsString("'debugger'", $content);
    }

    /**
     * Test that vite.config.js contains CSS Lightning CSS configuration.
     */
    public function test_vite_config_contains_lightningcss_config(): void
    {
        $configPath = base_path('vite.config.js');
        $content = file_get_contents($configPath);
        
        $this->assertStringContainsString('css:', $content);
        $this->assertStringContainsString("minify: 'lightningcss'", $content);
        $this->assertStringContainsString('lightningcss:', $content);
        $this->assertStringContainsString('targets:', $content);
    }

    /**
     * Test that vite.config.js contains build configuration.
     */
    public function test_vite_config_contains_build_config(): void
    {
        $configPath = base_path('vite.config.js');
        $content = file_get_contents($configPath);
        
        $this->assertStringContainsString('build:', $content);
        $this->assertStringContainsString("target: 'es2019'", $content);
        $this->assertStringContainsString('sourcemap: false', $content);
        $this->assertStringContainsString('rollupOptions:', $content);
    }

    /**
     * Test that vite.config.js contains manual chunks configuration.
     */
    public function test_vite_config_contains_manual_chunks(): void
    {
        $configPath = base_path('vite.config.js');
        $content = file_get_contents($configPath);
        
        $this->assertStringContainsString('manualChunks:', $content);
        $this->assertStringContainsString("vendor:", $content);
        $this->assertStringContainsString("'axios'", $content);
    }

    /**
     * Test that vite.config.js contains server CORS configuration.
     */
    public function test_vite_config_contains_server_cors(): void
    {
        $configPath = base_path('vite.config.js');
        $content = file_get_contents($configPath);
        
        $this->assertStringContainsString('server:', $content);
        $this->assertStringContainsString('cors: true', $content);
    }

    /**
     * Test that vite.config.js has proper file structure.
     */
    public function test_vite_config_has_proper_structure(): void
    {
        $configPath = base_path('vite.config.js');
        $content = file_get_contents($configPath);
        
        // Count braces to ensure they're balanced
        $openBraces = substr_count($content, '{');
        $closeBraces = substr_count($content, '}');
        $this->assertEquals($openBraces, $closeBraces, 'Braces should be balanced');
        
        // Count parentheses
        $openParens = substr_count($content, '(');
        $closeParens = substr_count($content, ')');
        $this->assertEquals($openParens, $closeParens, 'Parentheses should be balanced');
    }

    /**
     * Test that vite.config.js does not contain syntax errors.
     */
    public function test_vite_config_does_not_contain_common_syntax_errors(): void
    {
        $configPath = base_path('vite.config.js');
        $content = file_get_contents($configPath);
        
        // Check for common syntax issues
        $this->assertStringNotContainsString('undefined', $content);
        $this->assertStringNotContainsString('null,]', $content);
        $this->assertStringNotContainsString(',,', $content);
    }

    /**
     * Test that app.js file exists.
     */
    public function test_app_js_file_exists(): void
    {
        $appJsPath = base_path('resources/js/app.js');
        $this->assertFileExists($appJsPath);
    }

    /**
     * Test that app.js contains at least minimal content or comment.
     */
    public function test_app_js_contains_content_or_comment(): void
    {
        $appJsPath = base_path('resources/js/app.js');
        $content = file_get_contents($appJsPath);
        
        // Should not be completely empty
        $this->assertNotEmpty(trim($content));
    }

    /**
     * Test that Lightning CSS target is valid.
     */
    public function test_lightningcss_target_is_valid(): void
    {
        $configPath = base_path('vite.config.js');
        $content = file_get_contents($configPath);
        
        // Verify the target format is reasonable
        $this->assertMatchesRegularExpression('/targets:\s*[\'"]>=?\s*[\d.]+%[\'"]/', $content);
    }

    /**
     * Test that ES target is a valid ES version.
     */
    public function test_es_target_is_valid(): void
    {
        $configPath = base_path('vite.config.js');
        $content = file_get_contents($configPath);
        
        // Verify ES target format
        $this->assertMatchesRegularExpression("/target:\s*['\"]es\d{4}['\"]/", $content);
    }

    /**
     * Test that vendor chunk includes axios.
     */
    public function test_vendor_chunk_includes_axios(): void
    {
        $configPath = base_path('vite.config.js');
        $content = file_get_contents($configPath);
        
        // Verify axios is in the vendor chunk
        $this->assertMatchesRegularExpression("/vendor:\s*\[[^\]]*['\"]axios['\"]/", $content);
    }

    /**
     * Test that esbuild drops console and debugger statements.
     */
    public function test_esbuild_drops_console_and_debugger(): void
    {
        $configPath = base_path('vite.config.js');
        $content = file_get_contents($configPath);
        
        // Verify both console and debugger are in the drop array
        $this->assertMatchesRegularExpression("/drop:\s*\[[^\]]*['\"]console['\"]/", $content);
        $this->assertMatchesRegularExpression("/drop:\s*\[[^\]]*['\"]debugger['\"]/", $content);
    }

    /**
     * Test that sourcemap is disabled in build.
     */
    public function test_sourcemap_is_disabled(): void
    {
        $configPath = base_path('vite.config.js');
        $content = file_get_contents($configPath);
        
        $this->assertStringContainsString('sourcemap: false', $content);
    }

    /**
     * Test vite config import statements.
     */
    public function test_vite_config_has_required_imports(): void
    {
        $configPath = base_path('vite.config.js');
        $content = file_get_contents($configPath);
        
        $this->assertStringContainsString('import', $content);
        $this->assertStringContainsString('laravel', $content);
        $this->assertStringContainsString('vite', $content);
    }
}