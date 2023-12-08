<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectFilterRequest;
use App\Http\Requests\ProjectRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Models\Item;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $project = Project::all();
        return response()->json($project);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ProjectRequest $request)
    {
        $project = Project::create($request->all());

        return response()->json(['message' => 'Project added successfully', 'project' => $project], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $project = Project::with(['items','company'])->find($id);


        return response()->json(['project' => $project]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ProjectUpdateRequest $request, Project $project)
    {
//        todo:update company id return exception
        $project->update($request->all());
        return response()->json(['message' => 'Project updated successfully', 'project' => $project], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        Project::destroy($id);
        return response()->json(['message' => 'Project deleted successfully'], 200);
    }

    public function filter_project_details($id, ProjectFilterRequest $request)
    {
        $date_from = $request->date_from;
        $date_to = $request->date_to;
        $project = Project::findOrFail($id);


        $items = $project->items()->when(filled($date_from) && filled($date_to), function ($q) use ($date_from, $date_to) {
            return $q->whereBetween('created_at', [$date_from, $date_to]);
        })->when(filled($request->project_id), function ($q) use ($request) {
            return $q->where('project_id', $request->project_id);
        })->when(filled($request->company_id), function ($q) use ($request) {
            return $q->where('company_id', $request->company_id);
        })->when(filled($request->priority), function ($q) use ($request) {
            return $q->where('priority_level', $request->priority);
        }) ->when(filled($request->id), function ($q) use ($request) {
            return $q->where('id', $request->id);

        })->when(filled($request->name), function ($q) use ($request) {
            return $q->where('name', $request->name);
        })->with('logs')->get();


        return response()->json(['message' => 'items retrieved successfully','items'=>$items], 200);

    }
}
