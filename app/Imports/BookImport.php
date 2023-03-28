<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Services\Library\Book;
use App\Services\Library\BookAuthor;
use App\Services\Library\BookSeries;
use App\Services\Library\BookCategory;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BookImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $category_id = null;
        $book_series_id = null;
        $author_ids = [];
        if ($row['authors']){
            $authors = explode(', ', $row['authors']);
            foreach ($authors as $author){
                $exists = BookAuthor::where('name', $author)->first();
                if (!$exists){
                    $book_author = BookAuthor::create([
                        'name' => $author,
                    ]);
                    array_push($author_ids, $book_author->id);
                } else {
                    array_push($author_ids, $exists->id);
                }
            }
        }
        $book_category = BookCategory::where('description', $row['category'])->first();
        if (!$book_category){
            $book_category = BookCategory::create([
                'description' => $row['category'],
            ]);
            $book_category_id = $book_category->id;
        }
        $book_series = BookSeries::where('title', $row['series_name'])->first();
        if (!$book_series){
            $book_series = BookSeries::create([
                'title' => $row['series_name'],
            ]);
            $book_series_id = $book_series->id;
        }
        $book = Book::create([
            'title' => $row['title'],
            'place_of_publication' => $row['place_of_publication'],
            'publisher' => $row['publisher'],
            'copyright_year' => $row['copyright_year_ddmmyyyy'],
            'subject' => $row['subject'],
            'available_quantity' => $row['available_quantity'],
            'accession' => $row['accession'],
            'call_no' => $row['call_no'],
            'borrowing_days' => $row['days_for_borrow'],
            'penalty' => $row['penalty_ddmmyyyy'],
            'edition' => $row['edition'],
            'description' => $row['description'],
            'series_no' => $row['series_no'],
            'author_ids' => implode(',', $author_ids),
            'book_series_id' => $book_series_id,
            'book_category_id' => $category_id,
        ]);
    }
}
