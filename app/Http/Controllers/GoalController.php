<?php namespace Bdgt\Http\Controllers;

use Bdgt\Resources\Goal;

use Input;

class GoalController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index()
    {
        $goals = Goal::all();

        $goals->sortByDesc(function ($goal) {
            return $goal->progress;
        });

        $c['goals'] = $goals;

        return view('goal/index', $c);
    }

    /**
     * Show an individual goal to the user.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        try {
            $goal = Goal::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect('/goals');
        }

        $c['goal'] = $goal;

        return view('goal/show', $c);
    }

    /**
     * Create and store a new goal.
     *
     * @return Redirect
     */
    public function store()
    {
        if ($goal = Goal::create(Input::all())) {
            session()->flash('alerts.success', 'Goal created');
            return redirect("/goals/{$goal->id}");
        } else {
            session()->flash('alerts.danger', 'Goal creation failed');
            return redirect()->back();
        }
    }

    /**
     * Update an existing goal with new data.
     *
     * @param  int $id
     *
     * @return Redirect
     */
    public function update($id)
    {
        if (Goal::where('id', '=', $id)->update(Input::except(['_token', '_method']))) {
            session()->flash('alerts.success', 'Goal updated');
            return redirect("/goals/{$id}");
        } else {
            session()->flash('alerts.danger', 'Goal update failed');
            return redirect()->back();
        }
    }

    /**
     * Delete a goal by ID.
     *
     * @param  int $id
     *
     * @return Redirect
     */
    public function destroy($id)
    {
        if (Goal::where('id', '=', $id)->delete()) {
            session()->flash('alerts.success', 'Goal deleted');
            return redirect("/goals");
        } else {
            session()->flash('alerts.danger', 'Goal deletion failed');
            return redirect()->back();
        }
    }
}
