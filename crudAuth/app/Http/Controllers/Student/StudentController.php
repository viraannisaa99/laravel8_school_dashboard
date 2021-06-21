<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use App\Models\Student;
use DataTables;
use App\Http\Requests\StudentRequest;
use App\Http\Traits\ImageTrait;
use App\Http\Controllers\Response\ResponseController as ResponseController;

/**
 * For this class I tried to use datatables because I need the search bar
 */

class StudentController extends ResponseController
{
    use ImageTrait;

    function __construct()
    {
        $this->middleware('permission:student-list|student-create|student-edit', ['only' => ['index', 'show']]);
        $this->middleware('permission:student-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:student-edit', ['only' => ['edit', 'update', 'destroy']]);
    }

    /**
     * Display a listing of the students.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $rooms = Room::all();
        return view('students.index', compact('rooms'));
    }

    /**
     * Show the form for creating a new student.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created student in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(StudentRequest $request)
    {
        $input = $request->all();
        
        if ($photo = $request->file('photo')) {
            $input['photo'] = $this->saveImage($input['nim'], $photo);
        } else {
            unset($input['photo']);
        }

        $student = Student::updateOrCreate(['id' => $request->id], $input);
        if($student){
            return $this->sendResponse($student, 'Student retrieved successfully.');
        }else{
            return $this->sendError($student, 'Failed');
        }
    }

    /**
     * Display the student details.
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
     * Show the form for editing the specified student.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $student = Student::findOrFail($id);
        return response()->json($student);
    }

    /**
     * Update the specified student in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(StudentRequest $request, $id)
    {
        //
    }

    /**
     * Remove the specified student from storage.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Student::find($id)->delete();
        return $this->sendResponse([], 'Student deleted successfully.');
    }

    /**
     * Function to display datatable
     * Create action button, linked to /students/action.blade.php
     * Use query() instead of select / all, so we can use for many queries
     */
    public function dataTable(Request $request)
    {
        if ($request->ajax()) {
            $data = Student::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($student) {
                    return view('students.action', [
                        'student'       => $student,
                        'url_show'      => route('students.show', $student->id),
                        'url_edit'      => route('students.edit', $student->id),
                        'url_destroy'   => route('students.destroy', $student->id)
                    ]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
}
