<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Http\Requests\StudentsRequest;
use Illuminate\Support\Facades\Hash;

class StudentsController extends Controller
{
    /**
     * Display a listing of the students
     *
     * @param  \App\Students  $model
     * @return \Illuminate\View\View
     */
    public function index(Student $model)
    {
        return view('students.index', ['students' => $model->paginate(20)]);
    }
    /**
     * Show the form for creating a new students
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('students.create');
    }

    /**
     * Store a newly created students in storage
     *
     * @param  \App\Http\Requests\StudentsRequest  $request
     * @param  \App\Students  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StudentsRequest $request, Student $model)
    {
        $model->create($request->all());

        return redirect()->route('students.index')->withStatus(__('Students successfully created.'));
    }

    /**
     * Show the form for editing the specified students
     *
     * @param  \App\Students  $students
     * @return \Illuminate\View\View
     */
    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified students in storage
     *
     * @param  \App\Http\Requests\StudentsRequest  $request
     * @param  \App\Students  $students
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(StudentsRequest $request, Student  $student)
    {
        $student->update($request->all());
        return redirect()->route('students.index')->withStatus(__('Students successfully updated.'));
    }

    /**
     * Remove the specified students from storage
     *
     * @param  \App\Students  $students
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Student  $student)
    {
        $student->delete();

        return redirect()->route('students.index')->withStatus(__('Students successfully deleted.'));
    }
}
