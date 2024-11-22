<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use App\Services\PackageService;
use Illuminate\Http\Request;
use App\Models\transactions;
use App\Models\expense_catagory;
use Carbon\Carbon;
use App\Models\Subject;
class SubjectController extends Controller
{
    protected $packageService;
    protected $orderService;

    public function __construct(PackageService $packageService, OrderService $orderService)
    {
        $this->middleware(['auth', 'Setting']);
        $this->packageService = $packageService;
        $this->orderService = $orderService;
    }



    public function Index(){
        $data['page_title'] = "Subject List";
        $data['subjects'] = Subject::all();

        return view('admin.subject.index',$data);
    }

    public function create(Request $request){
        $data['page_title'] = "Subject Create";
        return view('admin.subject.create', $data);
    }

    public function store(Request $request){

        $request->validate([
            'subject_name' => 'required|string|max:255',
        ]);

        // Save the subject
        Subject::create([
            'subject_name' => $request->subject_name,
        ]);

     return response()->json('subject added successfull');
    }
    public function edit($id)
    {
        $data['page_title'] = "Subject Edit";
        $data['package'] = $this->packageService->editPackage($id);
        return view('admin.package.edit', $data);
    }
    public function update(PackageRequest $request, $id)
    {
        $in = $request->all();
        $in['user_id'] = auth()->id();
        $this->packageService->updatePackage($id, $in); // store this package using services
        session()->flash('success', 'Successfully Created');
        return redirect()->route('package.index');
    }
    public function destroy($id)
    {
        $subject = Subject::find($id);
        if ($subject) {
            $subject->delete();
            return redirect()->back()
                ->with('success', 'Delete successfully');
        } else {
            return redirect()->back()->with('success', 'Some thing worng');
        }
    }

}