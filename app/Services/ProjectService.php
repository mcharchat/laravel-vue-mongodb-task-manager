<?php
namespace App\Services;

use App\Events\ProjectEvent;
use App\Models\Project;

class ProjectService
{
    public function store(array $validated) : bool
    {
        // create a new project with the validated data
        $project = Project::create($validated);
        $project->load('user');
        // fire the ProjectEvent event with the squad_id and the validated data
        broadcast(new ProjectEvent($project->squad_id, $project, 'create'));

        return true;
    }

    public function update(Project $project, array $validated) : bool
    {
        // update the project with the validated data
        $project->update($validated);
        $project->load('user');
        // fire the ProjectEvent event with the squad_id and the validated data
        broadcast(new ProjectEvent($project->squad_id, $project, 'update'));

        return true;
    }

    public function destroy(Project $project) : bool
    {
        $thisProject = $project->load('user');
        $squad_id = $project->squad_id;
        // delete the project
        $project->delete();
        // fire the ProjectEvent event with the squad_id and the project
        broadcast(new ProjectEvent($squad_id, $thisProject, 'delete'));

        return true;
    }
}