<?php

namespace App\Http\Controllers;


use App\Http\Requests\BookRequest;
use App\Http\Resources\BookCollection;
use App\Models\Book;
use Illuminate\Support\Carbon;



class BookController extends Controller
{
    private function generateCode()
    {
        $code = 0;
        for($i = 0; $i <= Book::query()->count('id'); $i++){
            $code += 1 ;
        }
        return $code;
    }

    public function store(BookRequest $request): BookCollection
    {
        $data = $request->validated();
        $code = 'BK-00'. $this->generateCode();
        $book = new Book($data);
        $book->code = $code;
        $book->save();
        return new BookCollection($book->toArray());
    }
}
