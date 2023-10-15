<?php

namespace app\Enums\Http;

use App\Concerns\ComparesInstances;

enum AuthType: string
{
    use ComparesInstances;

    case Basic = "Basic";
    case BearerToken = "Bearer";
    case Digest = "Digest";
    case HOBA = "HOBA";
    case Mutual = "Mutual";
    case None="None";
}
