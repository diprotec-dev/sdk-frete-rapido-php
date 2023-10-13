<?php

declare(strict_types=1);

use FreteRapido\Handlers\UriHandler;

test("should format the URI correctly for the Guzzle request", function () {
    $uri = "/api/v3/quote/simulate";
    
    $uriFormatted = UriHandler::format($uri);
    
    expect($uriFormatted)
        ->toBe("api/v3/quote/simulate");
});