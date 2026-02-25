<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
        ];

        if ($this->relationLoaded('wallets')) {
            $wallets = $this->wallets;
            $data['wallets'] = WalletResource::collection($wallets);
            $data['overall_balance'] = number_format((float) $wallets->sum(fn ($w) => (float) $w->balance), 2, '.', '');
        }

        return $data;
    }
}
