<?php

namespace App\Notifications\Library;

use App\Notifications\BaseNotification;
use Illuminate\Mail\Mailable;


class BookRequestAcceptNotification extends BaseNotification
{

    public $data;

    public function __construct(String $branch_code, int $request_id, String $title, String $status, String $slug, String $image)
    {
        $this->data = [
            'branch_code' => $branch_code,
            'request_id' => $request_id,
            'title' => $title,
            'status' => $status,
            'slug' => $slug,
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
        return 'Your request to borrow ' . $this->data['title'] . ' has been ' . $this->data['status'];
    }

    public function getActionRoute(): ?string
    {
        return '/books/' . $this->data['slug'];
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
