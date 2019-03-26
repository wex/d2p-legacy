<?php
declare(strict_types=1);

namespace Wex\Controller;

class JsonResponse extends Response
{
    public function render(string $viewPath, string $viewFile, string $layout, array $data)
    {
        return json_encode($data, JSON_PRETTY_PRINT);
    }
}
