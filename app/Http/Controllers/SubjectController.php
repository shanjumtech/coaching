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
    public function expenseCategoryStore(Request $request){

        $validatedData = $request->validate([
            'expense_cat' => 'required',
        ]);
        expense_catagory::insert([
            'name' => $request->expense_cat,
            'created_at' => Carbon::now(),
        ]);

        return back()->with('success', 'expense category successfull');
    }

    public function expenseIndex(){
        $data['page_title'] = "expense";
        $data['category'] = expense_catagory::all();
       return view('admin.financial.expense.index', $data);
    }

    public function expenseStore(Request $request)
    {
        $validatedData = $request->validate([
            'expense_cat' => 'required',
            'amount' => 'required',
        ]);
        transactions::insert([
            'debit' => $request->amount,
            'category_id' => $request->expense_cat,
            'type' => "expense",
            'created_at' => Carbon::now(),
        ]);
        return back()->with('success', 'Expense added successfull');
    }


    public function expenseCategoryList(){

        $data['page_title'] = "expense";
        $data['category'] = expense_catagory::all();
        return view('admin.financial.expense.expense_cat_list', $data);
    }

    public function expenseCategoryEdit($id){

        $data['page_title'] = "Expense Catagory Edit";
        $data['category'] = expense_catagory::find($id);
        return view('admin.financial.expense.expense_cat_edit', $data);
    }




}