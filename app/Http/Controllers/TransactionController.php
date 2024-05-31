<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Transaction;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function borrowed(Request $request): JsonResponse
    {
        $user = Auth::user();
        $borrowed = Transaction::query()->where('user_id', '=', $request->user_id)->get();
        if(Book::query()->where('id', $request->book_id)->count() == 0) {
            throw new HttpResponseException(response()
                ->json(['message' => 'book not found'], 401));
        }elseif ($borrowed->contains('book_id', '=', $request->book_id) == 1){
            throw new HttpResponseException(response()->json(
                ['message'=>'book found'],400));
        }elseif (Transaction::query()->where('user_id', $user->id)->count() == 2) {
            throw new HttpResponseException(response()
                ->json(['message' => 'user can only borrow 2 books'], 401));
        }elseif ($borrowed->contains('penalty' ,'=', true)) {
            throw new HttpResponseException(response()
                ->json(['message' => 'you getting penalty'], 401));
        }
        $book = Book::query()->first();
        $request->user_id = $user->id;
        $request->book_id = $book->id;
        $transaction = new Transaction($request->all());
        $transaction->save();
        return response()->json([
            'data' => $transaction
        ], 201);

    }

    public function returned(Request $request): JsonResponse
    {
        $user = Auth::user();
        $book = Transaction::query()->where('user_id','=', $user->id)->get('book_id');
        if ($book->contains('book_id', '!=', $request->book_id)) {
            throw new HttpResponseException(response()->json(
                ['message' => 'you not borrowed this book'],400
            ));
        }
        $transaction = Transaction::query()->where('user_id','=', $user->id);
        $transaction->update([
            'returned_date' => now('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'status' => 'returned',
        ]);
        return response()->json([
            'data' => $transaction
        ], 201);

    }

    public function get()
    {
        return Transaction::query()->get();
    }
}
