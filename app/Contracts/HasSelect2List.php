<?php

namespace App\Contracts;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

interface HasSelect2List
{
    /**
     * @param  array<string, mixed>  $context
     */
    public static function getSelectList(?string $search = null, array $context = []): AnonymousResourceCollection;
}
