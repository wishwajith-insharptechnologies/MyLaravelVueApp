<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            'projectName' => $this->project_name, // Using the modified attribute name
            'projectType' => $this->project_type,
            'environmentType' => $this->is_web_base,
            'description' => $this->description,
            'image' => $this->image,
            'link' => $this->link,
            'status' => $this->status,
            'secretCode' => $this->secret_code, // Assuming you also changed this attribute name
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
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
