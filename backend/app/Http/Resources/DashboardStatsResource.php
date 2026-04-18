<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardStatsResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $payload = [
            'role' => $this['role'],
            'products_total' => $this['products_total'],
            'active_products_total' => $this['active_products_total'],
            'latest_products' => $this['latest_products'],
        ];

        if (array_key_exists('users_total', $this->resource)) {
            $payload['users_total'] = $this['users_total'];
        }

        if (array_key_exists('admins_total', $this->resource)) {
            $payload['admins_total'] = $this['admins_total'];
        }

        return $payload;
    }
}
