<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookStoreRequest;
use App\Http\Requests\BookUpdateRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Exception;
use Illuminate\Http\Request;

class BookController extends Controller
{
    
    public function index() {
        try {
            //get books sort by title
            $books = Book::orderBy('title', 'ASC')->paginate(10);

            //return with bookresource
            return (new BookResource(true, 'Fetch books successfully', $books))
                ->response()
                ->setStatusCode(200);

        } catch (Exception $e) {
            //Handle when error
            return (new BookResource(false, $e->getMessage(), []))
                ->response()
                ->setStatusCode(500);
        }
    }

    public function store(BookStoreRequest $request) {
        try {
            $validated = $request->validated(); //validate input with BookStoreRequest

            $book = Book::create($validated);
            
            return (new BookResource(true, 'New book successfully saved', $book))
                ->response()
                ->setStatusCode(201);

        } catch (Exception $e) {
            //Handle when error
            return (new BookResource(false, $e->getMessage(), []))
                ->response()
                ->setStatusCode(500);
        }
    }

    public function show($id) {
        try {
            //get book by id
            $book = Book::find($id);

            if (!$book) {
                //return 404 when book not found
                return (new BookResource(true, 'Book not found', []))
                    ->response()
                    ->setStatusCode(404);
            }

            //return 200 success
            return (new BookResource(true, 'Book fetch successfully', $book))
                ->response()
                ->setStatusCode(200);

        } catch (Exception $e) {
            //Handle when error and return 500
            return (new BookResource(false, $e->getMessage(), []))
                ->response()
                ->setStatusCode(500);
        }
    }

    public function update(BookUpdateRequest $request, $id) {
        try {
            $validated = $request->validated(); //validate input with BookUpdateRequest

            //get book by id
            $book = Book::find($id);

            if (!$book) {
                //return 404 when book not found
                return (new BookResource(true, 'Book not found', []))
                    ->response()
                    ->setStatusCode(404);
            }

            $book->update($validated);

            //return 200 success
            return (new BookResource(true, 'Book successfully updated', $book))
                ->response()
                ->setStatusCode(200);

        } catch (Exception $e) {
            //Handle when error and return 500
            return (new BookResource(false, $e->getMessage(), []))
                ->response()
                ->setStatusCode(500);
        }
    }

    public function destroy($id) {
        try {
            //get book by id
            $book = Book::find($id);

            if (!$book) {
                //return 404 when book not found
                return (new BookResource(true, 'Book not found', []))
                    ->response()
                    ->setStatusCode(404);
            }

            $book->delete();

            //return 200 success
            return (new BookResource(true, 'Book successfully deleted', []))
                ->response()
                ->setStatusCode(200);

        } catch (Exception $e) {
            //Handle when error and return 500
            return (new BookResource(false, $e->getMessage(), []))
                ->response()
                ->setStatusCode(500);
        }
    }

}
