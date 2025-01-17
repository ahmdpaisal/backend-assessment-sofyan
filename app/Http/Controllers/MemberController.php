<?php

namespace App\Http\Controllers;

use App\Http\Requests\MemberStoreRequest;
use App\Http\Requests\MemberUpdateRequest;
use App\Http\Resources\MemberResource;
use App\Models\Member;
use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{

    public function index() {
        try {

            //get members sort by name
            $members = Member::orderBy('name', 'ASC')
                ->with([
                    'createdBy:id,name', 
                    'updatedBy:id,name',
                    'deletedBy:id,name'])
                ->paginate(10);

            //return 200 using memberresource
            return (new MemberResource(true, 'Fetch members successfully', $members))
                ->response()
                ->setStatusCode(200);
                
        } catch (Exception $e) {
            //Handle when error and return status code 500
            return (new MemberResource(false, $e->getMessage(), []))
                ->response()
                ->setStatusCode(500);
        }
    }

    public function store(MemberStoreRequest $request) {
        try {
            $validated = $request->validated();

            $member = Member::create($validated);

            // $member->created_by = Auth::user()->id;
            // $member->save();

            return (new MemberResource(true, 'New member successfully saved', $member))
                ->response()
                ->setStatusCode(201);

        } catch (Exception $e) {
            //Handle when error and return status code 500
            return (new MemberResource(false, $e->getMessage(), []))
                ->response()
                ->setStatusCode(500);
        }
    }

    public function show($id) {
        try {

            $member = Member::with([
                'createdBy:id,name', 
                'updatedBy:id,name',
                'deletedBy:id,name'
            ])
                ->find($id);

            if (!$member) {
                //return 404 when member not found
                return (new MemberResource(true, 'Member not found', []))
                    ->response()
                    ->setStatusCode(404);
            }

            //return 200 success
            return (new MemberResource(true, 'Member fetch successfully', $member))
                ->response()
                ->setStatusCode(200);

        } catch (Exception $e) {
            //Handle when error and return status code 500
            return (new MemberResource(false, $e->getMessage(), []))
                ->response()
                ->setStatusCode(500);
        }
    }

    public function update(MemberUpdateRequest $request, $id) {
        try {
            $validated = $request->validated();
            
            $member = Member::with([
                'createdBy:id,name', 
                'updatedBy:id,name',
                'deletedBy:id,name'
            ])
                ->find($id);

            if (!$member) {
                //return 404 when member not found
                return (new MemberResource(true, 'Member not found', []))
                    ->response()
                    ->setStatusCode(404);
            }

            $member->update($validated);

            //return 200 success
            return (new MemberResource(true, 'Member successfully updated', $member))
                ->response()
                ->setStatusCode(200);

        } catch (Exception $e) {
            //Handle when error and return status code 500
            return (new MemberResource(false, $e->getMessage(), []))
                ->response()
                ->setStatusCode(500);
        }
    }

    public function destroy($id) {
        try {
            
            $member = Member::find($id);

            if (!$member) {
                //return 404 when member not found
                return (new MemberResource(true, 'Member not found', []))
                    ->response()
                    ->setStatusCode(404);
            }

            $member->delete();

            //return 200 success
            return (new MemberResource(true, 'Member successfully deleted', []))
                ->response()
                ->setStatusCode(200);

        } catch (Exception $e) {
            //Handle when error and return status code 500
            return (new MemberResource(false, $e->getMessage(), []))
                ->response()
                ->setStatusCode(500);
        }
    }

}
