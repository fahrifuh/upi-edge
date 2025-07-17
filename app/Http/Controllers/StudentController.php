<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Services\ImageService;
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
            'address' => 'required|string',
            'gender' => 'required|in:l,p',
            'major' => 'required|string',
            'semester' => 'required|integer|min:1',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'nim.required' => 'NIM harus diisi.',
            'nim.unique' => 'NIM sudah terdaftar.',
            'name.required' => 'Nama harus diisi.',
            'email.required' => 'Email harus diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'address.required' => 'Alamat harus diisi.',
            'gender.required' => 'Jenis kelamin harus dipilih.',
            'major.required' => 'Jurusan harus diisi.',
            'semester.required' => 'Semester harus diisi.',
            'photo.required' => 'Foto harus diunggah.',
            'photo.image' => 'File yang diunggah harus berupa gambar.',
            'photo.mimes' => 'Format gambar harus jpeg, png, atau jpg.',
            'photo.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
        ]);

        $data = [
            'nim' => $request->nim,
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'gender' => $request->gender,
            'major' => $request->major,
            'semester' => $request->semester,
        ];

        $photo = ImageService::image_intervention($request->file('photo'), 'uploads/students/', 1 / 1);

        $post = Student::create($data + ['photo' => $photo]);

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
            'email' => 'required|email|unique:students,email,' . $id,
            'address' => 'required|string',
            'gender' => 'required|in:l,p',
            'major' => 'required|string',
            'semester' => 'required|integer|min:1',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'nim.required' => 'NIM harus diisi.',
            'nim.unique' => 'NIM sudah terdaftar.',
            'name.required' => 'Nama harus diisi.',
            'email.required' => 'Email harus diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'address.required' => 'Alamat harus diisi.',
            'gender.required' => 'Jenis kelamin harus dipilih.',
            'major.required' => 'Jurusan harus diisi.',
            'semester.required' => 'Semester harus diisi.',
            'photo.image' => 'File yang diunggah harus berupa gambar.',
            'photo.mimes' => 'Format gambar harus jpeg, png, atau jpg.',
            'photo.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
        ]);

        $data = [
            'nim' => $request->nim,
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'gender' => $request->gender,
            'major' => $request->major,
            'semester' => $request->semester,
        ];

        $student = Student::findOrFail($id);
        $beforeUpdate = $student->getOriginal();
        $student->update($data);

        $changes = [];

        if ($request->hasFile('photo')) {
            ImageService::deleteImage($student->photo);
            $photo = ImageService::image_intervention($request->file('photo'), 'uploads/students/', 1 / 1);
            $student->update(['photo' => $photo]);
            $changes['photo'] = [
                'old' => $beforeUpdate['photo'],
                'new' => $photo,
            ];
        }

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

        if ($student->photo) {
            ImageService::deleteImage($student->photo);
        }

        activity()
            ->performedOn($student)
            ->event('delete')
            ->causedBy(Auth::user())
            ->log('Mahasiswa dihapus: ' . $student->name);

        return redirect()->route('student.index')->with('success', 'Data mahasiswa berhasil dihapus.');
    }
}
