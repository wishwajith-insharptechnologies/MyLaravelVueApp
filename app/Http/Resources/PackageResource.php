<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'title' => $this->title, // Using the modified attribute name
            'rank' => $this->rank,
            'validity' => $this->validity,
            'price' => $this->price,
            'discount' => $this->discount,
            'images' => $this->images,
            'status' => $this->status,
            'stripeLink' => $this->stripe_link,
            'trial_period' => $this->trial_period,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'category_id' => $this->category_id,
            'product_id' => $this->projects_id,
            'limitation' => [
                'id' => $this->limitation->id,
                'projectsId' => $this->limitation->projects_id,
                'packagesId' => $this->limitation->packages_id,
                'defined' => $this->limitation->defined,
                'status' => $this->limitation->status,
                'limitation' => json_decode($this->limitation->limitation, true), // Assuming it's stored as JSON
                'createdAt' => $this->limitation->created_at,
                'updatedAt' => $this->limitation->updated_at,
            ],
        ];
    }
}
