<?php

namespace App\Traits;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

trait EncryptsRouteKey
{
    public function getRouteKey()
    {
        return Crypt::encryptString((string) $this->getKey());
    }

    public function resolveRouteBinding($value, $field = null)
    {
        try {
            $decryptedId = Crypt::decryptString($value);
        } catch (DecryptException $e) {
            abort(404);
        }

        return $this->where($field ?? $this->getRouteKeyName(), $decryptedId)->firstOrFail();
    }
}