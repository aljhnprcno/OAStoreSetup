<?php

namespace App\Notifications\Library;

use App\Notifications\BaseNotification;
use Illuminate\Mail\Mailable;

class SyllabusRequestNotification extends BaseNotification
{

    public $data;

    public function __construct(String $branch_code, int $request_id, String $title, String $entity_name, String $image)
    {
        $this->data = [
            'branch_code' => $branch_code,
            'request_id' => $request_id,
            'title' => $title,
            'entity_name' => $entity_name,
            'image' => $image,
        ];
    }

    public function getIconClass(): ?string
    {
        return 'fa fa-file-text-o';
    }

    public function getTitle(): string
    {
        return 'Syllabus';
    }

    public function getDescription(): string
    {
        return $this->data['entity_name'] . ' requests to download ' . $this->data['title'];
    }

    public function getActionRoute(): ?string
    {
        return null;
    }

    public function getActionParams(): ?array
    {
        return null;
    }

    public function getPicture(): ?string
    {
        return $this->data['image'];
    }

    public function getMailable(): ?Mailable
    {
        return null;
    }

    public function getData()
    {
        return parent::toArray();
    }
}
