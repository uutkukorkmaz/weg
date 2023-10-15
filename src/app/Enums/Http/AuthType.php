<?php

namespace app\Enums\Http;

enum AuthType: string
{
    case Basic = "Basic";
    case BearerToken = "Bearer";
    case Digest = "Digest";
    case HOBA = "HOBA";
    case Mutual = "Mutual";
    case None="None";

    public function is(AuthType $compare): bool
    {
        return $this->value === $compare->value;
    }
}
