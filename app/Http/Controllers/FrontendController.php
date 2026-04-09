<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\ModuleSet;
use App\Models\Category;
use App\Models\TestType;
use App\Models\Test;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    /**
     * Return all test types as JSON for AJAX calls from the frontend.
     */
    public function getTestTypes()
    {
        $testTypes = TestType::select('id', 'name', 'slug')->get();
        return response()->json($testTypes);
    }

    /**
     * Return all levels as JSON for AJAX calls from the frontend.
     */
    public function getLevels()
    {
        $levels = Level::select('id', 'name', 'description')->get();
        return response()->json($levels);
    }

    /**
     * Return module sets for a given category slug (from navbar click),
     * test type name, and level id.
     */
    public function getModuleSets(Request $request)
    {
        $category = Category::where('slug', $request->get('category'))->first();
        $testType = TestType::where('name', $request->get('test_type'))->first();
        $levelId  = $request->get('level_id');

        if (!$category || !$testType) {
            return response()->json([]);
        }

        $moduleSets = ModuleSet::with(['tests'])
            ->where('category_id', $category->id)
            ->where('test_type_id', $testType->id)
            ->where('level_id', $levelId)
            ->where('status', 'active')
            ->get()
            ->map(function ($set) {
                return [
                    'id'         => $set->id,
                    'name'       => $set->name,
                    'tests_count'=> $set->tests->count(),
                ];
            });

        return response()->json($moduleSets);
    }

    public function getTests(Request $request)
    {
        $moduleSetId = $request->get('module_set_id');
        $studentId   = auth('student')->id();

        $query = Test::where('status', 'active')
            ->with(['attempts' => function($q) use ($studentId) {
                $q->where('student_id', $studentId);
            }]);

        if ($moduleSetId) {
            $query->where('module_set_id', $moduleSetId);
        } else {
            // Fetch directly by level, category, and type
            $levelId  = $request->get('level_id');
            $testType = TestType::where('name', $request->get('test_type'))->first();
            $category = Category::where('slug', $request->get('category'))->first();

            if ($levelId) $query->where('level_id', $levelId);
            if ($testType) $query->where('test_type_id', $testType->id);
            if ($category) $query->where('category_id', $category->id);
        }

        $tests = $query->get()
            ->map(function($test) {
                $attempt = $test->attempts->first();
                return [
                    'id' => $test->id,
                    'name' => $test->name,
                    'status' => $attempt ? $attempt->status : 'not_started'
                ];
            });

        return response()->json($tests);
    }
}
