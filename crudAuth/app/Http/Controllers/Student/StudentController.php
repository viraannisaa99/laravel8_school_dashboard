<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use App\Models\Student;
use DataTables;

/**
 * For this class I tried to use datatables because I need the search bar
 */

class StudentController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:student-list|student-create|student-edit|student-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:student-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:student-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:student-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        return view('students.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //select option from room table
        $rooms = Room::all();

        return view('students.create', compact('rooms'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'nim'  => 'required|min:10|max:10',
            'phone' => 'required',
            'email' => 'required|email',
            'roomId' => 'required',
            'photo' => 'required|mimes:jpg,bmp,png|max:1024',
        ]);

        $input = $request->all();

        //single file upload
        if ($photo = $request->file('photo')) {
            $fileName = Request()->nim . '.' . $photo->extension();
            $photo->move(public_path('student_photo'), $fileName);
            $input['photo'] = $fileName;
        }

        Student::create($input);

        return redirect()->route('students.index')
            ->with('success', 'Student created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $student = Student::findOrFail($id);
        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $student = Student::findOrFail($id);
        $rooms = Room::all();

        return view('students.edit', compact('student', 'rooms'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        request()->validate([
            'name' => 'required',
            'nim'  => 'required|min:10|max:10',
            'phone' => 'required',
            'email' => 'required|email',
            'roomId' => 'required',
        ]);

        $student = Student::findOrFail($id);
        $input = $request->all();

        //condition file upload when update
        if ($photo = $request->file('photo')) {
            $fileName = Request()->nim . '.' . $photo->extension();
            $photo->move(public_path('student_photo'), $fileName);
            $input['photo'] = $fileName;
        //if file updated, then empty the file target after deleting
        } else {
            unset($input['photo']);
        }

        $student->update($input);

        return redirect()->route('students.index')
            ->with('success', 'Student updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Student::find($id)->delete();
        return response()->json(['success'=>'Student deleted successfully.']);
    }

    /**
     * Function to display datatable
     * Create action button, linked to /students/action.blade.php
     * Use query() instead of select / all, so we can use for many queries
     */

    public function dataTable()
    {
        $student = Student::query();
        return Datatables::of($student)
                ->addIndexColumn()
                ->addColumn('action', function($student){
                    return view('students.action', [
                        'student' => $student,
                        'url_show' => route('students.show', $student->id),
                        'url_edit' => route('students.edit', $student->id),
                        'url_destroy' => route('students.destroy', $student->id)
                    ]);
                })
                ->rawColumns(['action'])
                ->make(true);
    }
}
