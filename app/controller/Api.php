<?php declare(strict_types=1);

namespace App\Controller;

use Swiftly\Core\Controller;
use Swiftly\Http\Response\JsonResponse;

use function ucfirst;

/**
 * Example controller that returns JSON responses.
 */
class Api extends Controller
{
    /**
     * Returns a JSON response for a URL with a variable component.
     */
    public function hello(string $name): JsonResponse
    {
        return new JsonResponse([
            'message' => 'Welcome to Swiftly ' . ucfirst($name) . '!'
        ]);
    }
}