<?php

namespace App\Http\Resources\Admin;

use App\Api\Acl\Resources\RoleCollection;
use App\Api\Acl\Resources\RoleResource;
use App\Api\Shared\Resources\StatusResource;
use App\Models\File;
use Domain\Users\Models\Role;
use Illuminate\Http\Resources\Json\JsonResource;

class  FileResource extends JsonResource
{
    public function toArray($request): array
    {
        if ($this->type == 'directory') {
            $children=File::where("parent_id", $this->id)->get();
        }
        else{
            $children=null;
        }
        return [
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'src' => $this->src,
            'type'=> $this->type,
            'format'=> $this->format,
            'children'=>$children,
            'FileRequest'=>$this->FileRequest ?? null,
            'FileReport'=>$this->FileReport ?? null,            
        ];
    }
}
