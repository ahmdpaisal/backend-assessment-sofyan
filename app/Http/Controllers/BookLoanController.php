<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookLoanStoreRequest;
use App\Http\Resources\BookLoanResource;
use App\Models\Book;
use App\Models\BookLoan;
use App\Models\Member;
use Exception;
use Illuminate\Http\Request;

class BookLoanController extends Controller
{
    
    public function index() {
        try {

            //Get all list loan book
            $bookLoans = BookLoan::orderBy('loan_date', 'DESC')
                ->with([
                    'book:id,title',
                    'member:id,name',
                    'createdBy:id,name'
                ])
                ->paginate(10);

            //return data and send 200 code
            return (new BookLoanResource(true, 'Fetch loans list successfully', $bookLoans))
                ->response()
                ->setStatusCode(200);

        } catch (Exception $e) {
            //Handle when error and return status code 500
            return (new BookLoanResource(false, $e->getMessage(), []))
                ->response()
                ->setStatusCode(500);
        }
    }

    public function store(BookLoanStoreRequest $request) {
        try {
            $validated = $request->validated();

            $book = Book::find($validated['book_id']);

            //check if book is exists
            if (!$book) {
                return (new BookLoanResource(false, 'Book not found', []))
                    ->response()
                    ->setStatusCode(404);
            }

            $member = Member::find($validated['member_id']);
            
            //check if member is exists
            if (!$member) {
                return (new BookLoanResource(false, 'Member not found', []))
                    ->response()
                    ->setStatusCode(404);
            }

            //save new book loan
            $bookLoan = new BookLoan();
            $bookLoan->book_id = $validated['book_id'];
            $bookLoan->member_id = $validated['member_id'];
            $bookLoan->loan_date = $validated['loan_date'];
            $bookLoan->estimated_return = $validated['estimated_return'];
            $bookLoan->status = 'In Loan';
            $bookLoan->created_by = auth()->user()->id;
            $bookLoan->save();

            //Decrease stock
            $book->stock -= 1;
            $book->save();

            //return 201 created if successfully
            return (new BookLoanResource(true, 'New list book loan saved', $bookLoan))
                ->response()
                ->setStatusCode(201);

        } catch (Exception $e) {
            //Handle when error and return status code 500
            return (new BookLoanResource(false, $e->getMessage(), []))
                ->response()
                ->setStatusCode(500);
        }
    }

    public function show($id) {
        try {
            $bookLoan = BookLoan::with([
                'book:id,title',
                'member:id,name',
                'createdBy:id,name'
            ])
                ->find($id);

            //Check if list not found
            if (!$bookLoan) {
                return (new BookLoanResource(false, 'List not found', []))
                    ->response()
                    ->setStatusCode(404);
            }

            return (new BookLoanResource(true, 'Get book loan detail successfully', $bookLoan))
                ->response()
                ->setStatusCode(200);

        } catch (Exception $e) {
            //Handle when error and return status code 500
            return (new BookLoanResource(false, $e->getMessage(), []))
                ->response()
                ->setStatusCode(500);
        }
    }

    public function returnBook($id) {
        try {
            $bookLoan = BookLoan::with([
                'book:id,title',
                'member:id,name',
                'createdBy:id,name'
            ])
                ->find($id);

            //Check if list not found
            if (!$bookLoan) {
                return (new BookLoanResource(false, 'List not found', []))
                    ->response()
                    ->setStatusCode(404);
            }

            if ($bookLoan->status != 'In Loan') {
                return (new BookLoanResource(false, 'Book already returned', []))
                    ->response()
                    ->setStatusCode(200);
            }

            //return stock
            $book = Book::find($bookLoan->book_id);
            $book->stock += 1;
            $book->save();

            //updated book loan data
            $dateNow = date('Y-m-d');

            if ($dateNow > $bookLoan->estimated_return) {
                $bookLoan->forfeit = 5000;
            }

            $bookLoan->status = 'Returned';
            $bookLoan->return_date = $dateNow;
            $bookLoan->save();

            return (new BookLoanResource(true, 'Get book loan detail successfully', $bookLoan))
                ->response()
                ->setStatusCode(200);

        } catch (Exception $e) {
            //Handle when error and return status code 500
            return (new BookLoanResource(false, $e->getMessage(), []))
                ->response()
                ->setStatusCode(500);
        }
    }

    public function destroy($id) {
        try {
            $bookLoan = BookLoan::with([
                'book:id,title',
                'member:id,name',
                'createdBy:id,name'
            ])
                ->find($id);

            //Check if list not found
            if (!$bookLoan) {
                return (new BookLoanResource(false, 'List not found', []))
                    ->response()
                    ->setStatusCode(404);
            }

            if ($bookLoan->status != 'In Loan') {
                return (new BookLoanResource(false, 'Book already returned', []))
                    ->response()
                    ->setStatusCode(200);
            }

            //return stock
            $book = Book::find($bookLoan->book_id);
            $book->stock += 1;
            $book->save();

            //updated book loan data
            $bookLoan->status = 'Canceled';
            $bookLoan->save();

            return (new BookLoanResource(true, 'Book loan deleted successfully', []))
                ->response()
                ->setStatusCode(200);

        } catch (Exception $e) {
            //Handle when error and return status code 500
            return (new BookLoanResource(false, $e->getMessage(), []))
                ->response()
                ->setStatusCode(500);
        }
    }

}
