<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Services\SheetCatalogService;
use Illuminate\Http\Request;

class PortalController extends Controller
{
    // Paste your real sheet IDs here
    protected array $sheets = [
        'ebooks' => '11xn-R6bgzglfI7MNs3k4v9depN_6MBV3qylNJ8KQQ0c',
        'theory' => '13E6FNYE7WDRP1GUbYx-N7Tm-nYxtZ1RvTjyEROFZF20',
    ];

    public function __construct(protected SheetCatalogService $catalog)
    {
    }

    public function syllabus(Request $request)
    {
        $data = $this->catalog->catalog($this->sheets['ebooks']);
        $all = array_merge($data['syllabusDocs'], $data['subjectDocs']);

        return view('portal.syllabus', [
            'docs' => $this->lockBySemester($this->applyFilters($all, $request)),
            'programmes' => $this->distinct($all, 'programme'),
            'specialisations' => $this->distinct($all, 'specialisation'),
            'semesters' => $this->distinct($data['subjectDocs'], 'semester'),
        ]);
    }

    public function theory(Request $request)
    {
        // CHANGED: Now checks the Database Setting instead of config
        if (!Setting::bool('theory_open')) {
            return view('portal.closed', ['name' => 'Theory Paper']);
        }

        $data = $this->catalog->catalog($this->sheets['theory']);

        return view('portal.theory', [
            'docs' => $this->lockBySemester($this->applyFilters($data['subjectDocs'], $request)),
            'programmes' => $this->distinct($data['subjectDocs'], 'programme'),
            'specialisations' => $this->distinct($data['subjectDocs'], 'specialisation'),
            'semesters' => $this->distinct($data['subjectDocs'], 'semester'),
        ]);
    }

       public function mcq(Request $request)
    {
        if (!Setting::bool('mcq_open')) {
            return view('portal.closed', ['name' => 'MCQ Practice']);
        }

        // Sample MCQ Data (since you don't have an MCQ Google Sheet yet)
        // Once you have a sheet, you can replace this with: $data = $this->catalog->catalog($this->sheets['mcq']);
        $all = [
            ['programme' => 'BSC', 'specialisation' => 'Computer Science & IT', 'semester' => '1', 'subject' => 'Fundamentals of Computer Science - MCQ', 'file' => 'MCQ_Set_1.pdf', 'link' => '#'],
            ['programme' => 'BSC', 'specialisation' => 'Computer Science & IT', 'semester' => '2', 'subject' => 'Computer Organisation - MCQ', 'file' => 'MCQ_Set_2.pdf', 'link' => '#'],
            ['programme' => 'BSC', 'specialisation' => 'Computer Science & IT', 'semester' => '4', 'subject' => 'Database Management - MCQ', 'file' => 'MCQ_Set_4.pdf', 'link' => '#'],
            ['programme' => 'BLIB', 'specialisation' => 'Library Science', 'semester' => '1', 'subject' => 'Foundations of LIS - MCQ', 'file' => 'MCQ_Set_3.pdf', 'link' => '#'],
        ];

        return view('portal.mcq', [
            'docs' => $this->lockBySemester($this->applyFilters($all, $request)),
            'programmes' => $this->distinct($all, 'programme'),
            'specialisations' => $this->distinct($all, 'specialisation'),
            'semesters' => $this->distinct($all, 'semester'),
        ]);
    }

    public function contact()
    {
        return view('portal.contact');
    }

    public function about()
    {
        return view('portal.about');
    }

    protected function applyFilters(array $docs, Request $request): array
    {
        return array_values(array_filter($docs, function ($d) use ($request) {
            if ($request->filled('programme') && ($d['programme'] ?? '') !== $request->get('programme')) {
                return false;
            }
            if ($request->filled('specialisation') && ($d['specialisation'] ?? '') !== $request->get('specialisation')) {
                return false;
            }
            if ($request->filled('semester') && (string) ($d['semester'] ?? '') !== (string) $request->get('semester')) {
                return false;
            }
            return true;
        }));
    }

    protected function lockBySemester(array $docs): array
    {
        // Default to semester 1 if session is somehow missing
        $studentSemester = (int) session('student_semester', 1);

        return array_map(function ($d) use ($studentSemester) {
            $sem = (int) preg_replace('/\D/', '', (string) ($d['semester'] ?? ''));
            
            // Lock if the document's semester is strictly greater than the student's semester
            $d['locked'] = $sem > 0 && $sem > $studentSemester;
            return $d;
        }, $docs);
    }

    protected function distinct(array $docs, string $field): array
    {
        $values = array_unique(array_filter(array_map(fn ($d) => $d[$field] ?? '', $docs)));
        sort($values);
        return array_values($values);
    }
}