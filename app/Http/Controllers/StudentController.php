<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::get();
        return view('pages.student.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.student.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required|string|unique:students,nim',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'phone' => 'required|string|max:15',
            'address' => 'required|string',
            'gender' => 'required|in:l,p',
            'birth_date' => 'required|date',
        ], [
            'nim.required' => 'NIM harus diisi.',
            'nim.unique' => 'NIM sudah terdaftar.',
            'name.required' => 'Nama harus diisi.',
            'email.required' => 'Email harus diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'phone.required' => 'Nomor telepon harus diisi.',
            'address.required' => 'Alamat harus diisi.',
            'gender.required' => 'Jenis kelamin harus dipilih.',
            'birth_date.required' => 'Tanggal lahir harus diisi.',
            'birth_date.date' => 'Tanggal lahir harus berupa tanggal yang valid.',
        ]);

        $data = [
            'nim' => $request->nim,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
        ];

        $post = Student::create($data);

        activity()
            ->performedOn($post)
            ->event('create')
            ->causedBy(Auth::user())
            ->log('Membuat data mahasiswa baru: ' . $post->name);

        return redirect()->route('student.index')->with('success', 'Data mahasiswa berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $student = Student::findOrFail($id);
        return view('pages.student.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nim' => 'required|string|unique:students,nim,' . $id,
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:lecturers,email,' . $id,
            'phone' => 'required|string|max:15',
            'address' => 'required|string',
            'gender' => 'required|in:l,p',
            'birth_date' => 'required|date',
        ], [
            'nim.required' => 'NIM harus diisi.',
            'nim.unique' => 'NIM sudah terdaftar.',
            'name.required' => 'Nama harus diisi.',
            'email.required' => 'Email harus diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'phone.required' => 'Nomor telepon harus diisi.',
            'address.required' => 'Alamat harus diisi.',
            'gender.required' => 'Jenis kelamin harus dipilih.',
            'birth_date.required' => 'Tanggal lahir harus diisi.',
            'birth_date.date' => 'Tanggal lahir harus berupa tanggal yang valid.',
        ]);

        $data = [
            'nim' => $request->nim,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
        ];

        $student = Student::findOrFail($id);
        $beforeUpdate = $student->getOriginal();
        $student->update($data);

        $changes = [];
        foreach ($data as $key => $value) {
            if (array_key_exists($key, $beforeUpdate) &&  $beforeUpdate[$key] !== $value) {
                $changes[$key] = [
                    'old' => $beforeUpdate[$key],
                    'new' => $value,
                ];
            }
        }

        activity()
            ->performedOn($student)
            ->event('update')
            ->withProperties(['changes' => $changes])
            ->causedBy(Auth::user())
            ->log('Mahasiswa dengan ID ' . $student->id . ' berhasil diupdate');

        return redirect()->route('student.index')->with('success', 'Data mahasiswa berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        activity()
            ->performedOn($student)
            ->event('delete')
            ->causedBy(Auth::user())
            ->log('Mahasiswa dihapus: ' . $student->name);

        return redirect()->route('student.index')->with('success', 'Data mahasiswa berhasil dihapus.');
    }
}
