<?php

namespace App\Notifications\Library;

use App\Notifications\BaseNotification;
use Illuminate\Mail\Mailable;

class BookRequestNotification extends BaseNotification
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
        return 'fa fa-book';
    }

    public function getTitle(): string
    {
        return 'Book Request';
    }

    public function getDescription(): string
    {
        return $this->data['entity_name'] . ' requests to borrow ' . $this->data['title'];
    }

    public function getActionRoute(): ?string
    {
        return '/library/books?tab=book_requests&id=' . $this->data['request_id'];
    }

    public function getMailable(): ?Mailable
    {
        return null;
    }

    public function getData()
    {
        return parent::toArray();
    }

    public function getPicture(): ?string
    {
        return $this->data['image'];
    }
}
