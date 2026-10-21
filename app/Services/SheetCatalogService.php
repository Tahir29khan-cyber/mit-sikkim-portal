<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class SheetCatalogService
{
    protected array $fieldAliases = [
        'courseCode'     => ['Course code'],
        'syllabus'       => ['Syllabus'],
        'syllabusLink'   => ['Syllabus Link'],
        'programme'      => ['Programme'],
        'course'         => ['Course'],
        'specialisation' => ['Specialisation'],
        'semester'       => ['Semester'],
        'subjectCode'    => ['Subject Code - 1', 'Subject Code'],
        'majorI'         => ['Major Subject-I (Core)', 'Major Subject-I'],
        'file1'          => ['Ebooks 1', 'Theory Paper 1'],
        'file1Link'      => ['Ebooks 1 Link', 'Theory Paper 1 Link'],
        'majorII'        => ['Major Subject-II (Core/DSC)', 'Major Subject-II'],
        'file2'          => ['Ebooks 2', 'Theory Paper 2'],
        'file2Link'      => ['Ebooks 2 Link', 'Theory Paper 2 Link'],
        'minorIII'       => ['Minor Subject-III'],
        'file3'          => ['Ebooks 3', 'Theory Paper 3'],
        'file3Link'      => ['Ebooks 3 Link', 'Theory Paper 3 Link'],
    ];

    /**
     * Returns ['syllabusDocs' => [...], 'subjectDocs' => [...]] for a sheet ID.
     * Cached for 10 minutes so every page view doesn't re-hit Google.
     */
    public function catalog(string $sheetId): array
    {
        return Cache::remember("sheet_catalog_{$sheetId}", now()->addMinutes(10), function () use ($sheetId) {
            return $this->fetchAndParse($sheetId);
        });
    }

    protected function fetchAndParse(string $sheetId): array
    {
        $url = "https://docs.google.com/spreadsheets/d/{$sheetId}/gviz/tq?tqx=out:json";
        $body = Http::timeout(10)->get($url)->body();

        $start = strpos($body, '{');
        $end   = strrpos($body, '}');
        $json  = json_decode(substr($body, $start, $end - $start + 1), true);

        $cols = array_map(fn ($c) => $c['label'] ?? '', $json['table']['cols'] ?? []);
        $idx  = [];
        foreach ($this->fieldAliases as $key => $names) {
            $idx[$key] = $this->findCol($cols, $names);
        }

        $rows = array_map(
            fn ($row) => array_map(fn ($c) => $c['v'] ?? '', $row['c'] ?? []),
            $json['table']['rows'] ?? []
        );

        $lastSyllabus = '';
        $lastSyllabusLink = '';
        $syllabusDocs = [];
        $subjectDocs = [];
        $slots = [
            ['majorI', 'file1', 'file1Link'],
            ['majorII', 'file2', 'file2Link'],
            ['minorIII', 'file3', 'file3Link'],
        ];

        foreach ($rows as $row) {
            $programme = $this->cell($row, $idx, 'programme');
            if ($programme === '') {
                continue;
            }

            $course = $this->cell($row, $idx, 'course');
            $specialisation = $this->cell($row, $idx, 'specialisation');
            $semester = $this->cell($row, $idx, 'semester');

            $rowSyllabus = $this->cell($row, $idx, 'syllabus');
            $rowSyllabusLink = $this->cell($row, $idx, 'syllabusLink');
            if ($rowSyllabus !== '') {
                $lastSyllabus = $rowSyllabus;
            }
            if ($rowSyllabusLink !== '') {
                $lastSyllabusLink = $rowSyllabusLink;
            }
            $syllabus = $rowSyllabus !== '' ? $rowSyllabus : $lastSyllabus;
            $syllabusLink = $rowSyllabusLink !== '' ? $rowSyllabusLink : $lastSyllabusLink;

            if ($syllabus !== '') {
                $exists = false;
                foreach ($syllabusDocs as $d) {
                    if ($d['programme'] === $programme && $d['specialisation'] === $specialisation && $d['file'] === $syllabus) {
                        $exists = true;
                        break;
                    }
                }
                if (!$exists) {
                    $syllabusDocs[] = [
                        'programme' => $programme, 'course' => $course, 'specialisation' => $specialisation,
                        'semester' => '', 'subject' => 'Syllabus', 'file' => $syllabus, 'link' => $syllabusLink,
                    ];
                }
            }

            foreach ($slots as [$subjKey, $fileKey, $linkKey]) {
                $subject = $this->cell($row, $idx, $subjKey);
                $file = $this->cell($row, $idx, $fileKey);
                if ($subject === '' || $file === '') {
                    continue;
                }
                $subjectDocs[] = [
                    'programme' => $programme, 'course' => $course, 'specialisation' => $specialisation,
                    'semester' => $semester, 'subjectCode' => $this->cell($row, $idx, 'subjectCode'),
                    'subject' => $subject, 'file' => $file, 'link' => $this->cell($row, $idx, $linkKey),
                ];
            }
        }

        return compact('syllabusDocs', 'subjectDocs');
    }

    protected function cell(array $row, array $idx, string $key): string
    {
        $i = $idx[$key] ?? -1;
        return $i >= 0 ? (string) ($row[$i] ?? '') : '';
    }

    protected function findCol(array $cols, array $names): int
    {
        foreach ($names as $name) {
            foreach ($cols as $i => $c) {
                if (strtolower(trim($c)) === strtolower(trim($name))) {
                    return $i;
                }
            }
        }
        return -1;
    }
}
