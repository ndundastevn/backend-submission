<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'balance' => $this->balance,
        ];

        if ($this->relationLoaded('transactions')) {
            $data['transactions'] = TransactionResource::collection($this->transactions);
        }

        return $data;
    }
}
