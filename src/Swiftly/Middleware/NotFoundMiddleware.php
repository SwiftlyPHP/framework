<?php

namespace Swiftly\Middleware;

use Swiftly\Middleware\MiddlewareInterface;
use Swiftly\Template\TemplateInterface;
use Swiftly\Http\Server\Request;
use Swiftly\Http\Server\Response;

use function in_array;
use function array_first;
use function is_file;

use const APP_VIEW;

/**
 * Middleware responsible for loading the custom 404 page template
 */
class NotFoundMiddleware implements MiddlewareInterface
{
    /** @var TemplateInterface $template */
    private $template;

    /**
     * Create a new 404 (not found) handler
     * 
     * @param TemplateInterface $template Template renderer
     */
    public function __construct(TemplateInterface $template)
    {
        $this->template = $template;
    }

    /**
     * {@inheritDoc}
     */
    public function run(Request $request, Response $response, callable $next): Response
    {
        if ($response->getStatus() !== 404) {
            return $next($request, $response);
        }

        $content_type = $response->headers->get('content-type');

        // Only do this for some resource types (i.e not JSON/XML)
        if (in_array($content_type, ['', 'text/html', 'text/plain'], true)) {
            return $next($request, $response);
        }

        $file = $this->get404File();

        // No dedicated 404 file
        if ($file === null) {
            return $next($request, $response);
        }

        // Render the template and return it to the client
        $html = $this->template->render($file);
        $response->setContent($html);

        return $next($request, $response);
    }

    /**
     * Attempts to find a 404 template in the views directory
     * 
     * @return string|null File path
     */
    private function get404File(): ?string
    {
        // NOTE: TemplateInterface respects the `APP_VIEW` constant
        $files = [
            '/404.html.php',
            '/404.php',
            '/404.html'
        ];    

        // Return the first that matches
        return array_first($files, function (string $file) {
            return is_file(APP_VIEW . $file);
        });
    }
}
