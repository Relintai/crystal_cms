<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Log;
use Theme;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use App\Models\RBACRanks;
use App\Models\RBACGroups;
use App\Models\RBACPermissions;


class RBACEditor extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $ranks = RBACRanks::all();
        $info = $request->session()->get('info', false);

        return Theme::AdminView('rbac_editor/list', ['ranks' => $ranks, 'info' => $info]);
    }

    public function rankEditor($id = 0)
    {
        $rankentry = null;
        if ($id)
        {
            $rankentry = RBACRanks::find($id);

            if (!$rankentry)
            {
                Log::warning('RBACEditor->showRankForm id was bad! id: ' . $id);

                $rankentry = null;
                $id = 0;
            }
        }

        return Theme::AdminView('rbac_editor/edit_create_rank', ['rankentry' => $rankentry, 'id' => $id]);
    }

    public function doRankEdit(Request $request)
    {
        $this->validate($request, [
            'id' => 'integer',
            'name' => 'required|max:30',
            'name:internal' => 'max:40',
        ]);


        $id = $request->input('id');
        $rank = null;

        if ($id)
        {
            $rank = RBACRanks::findOrFail($id);
        }
        else
        {
            $rank = new RBACRanks();
        }

        $rank->name = $request->input('name');
        $rank->name_internal = $request->input('name_internal');
        $rank->save();

        return redirect('admin/rbac_editor')->with('info', trans('admin.success'));
    }

    public function show($rank_id, Request $request)
    {
        $info = $request->session()->get('info', false);

        $data = RBACGroups::where('rank_id', $rank_id)
            ->leftJoin('rbac_permissions', 'rbac_groups.permission_id', '=', 'rbac_permissions.id')
            ->select('rbac_groups.*', 'rbac_permissions.id AS permission_id','rbac_permissions.permissions')
            ->orderBy('sort_order', 'asc')
            ->get();

        $rank = RBACRanks::find($rank_id);

        return Theme::AdminView('rbac_editor/rbac_entry', [
                    'info' => $info, 
                    'data' => $data, 
                    'rank_id' => $rank_id,
                    'rank' => $rank]);
    }

    public function saveRankSettings(Request $request)
    {
        $rank_id = $request->input('rank_id');

        if (!$rank_id || !is_numeric($rank_id))
        {
            Log::warning('RBACEditor->saveRankSettings: rank_id from form was bad! rank_id: ' . $rank_id);
            return redirect()->withErrors(trans('errors.internal_error_try_again'));
        }

        $rank = RBACRanks::find($rank_id);

        if (!$rank)
        {
            Log::warning('RBACEditor->saveRankSettings: rank cannot be found! rank_id: ' . $rank_id);
            return redirect()->withErrors(trans('errors.internal_error_try_again'));
        }


        $first = true;
        $settings = "";
        foreach (RBACRanks::$settings as $s)
        {
            if ($request->input($s, false))
            {
                if (!$first)
                {
                    $settings .= ',';
                }

                $settings .= $s;
                $first = false;
            }
        }

        $rank->settings = $settings;
        $rank->save();

        return redirect()->back()->with('info', trans('admin.success'));
    }

    public function groupEditor($rank_id, $group_id = 0)
    {
        //let's check if rank exists
        RBACRanks::findOrFail($rank_id);

        $groupentry = null;
        if ($group_id)
        {
            $groupentry = RBACGroups::find($group_id);

            if (!$groupentry)
            {
                Log::warning('RBACEditor->groupEditor group_id was bad! group_id: ' . $group_id);

                $groupentry = null;
                $group_id = 0;
            }
        }

        return Theme::AdminView('rbac_editor/edit_create_group', ['groupentry' => $groupentry, 'group_id' => $group_id, 'rank_id' => $rank_id]);
    }

    public function groupEditorPost(Request $request)
    {
        $this->validate($request, [
            'rank_id' => 'integer',
            'group_id' => 'integer',
            'url' => 'required|max:200',
        ]);

        $rank_id = $request->input('rank_id');
        $group_id = $request->input('group_id');

        //Let's see if they exist
        $rank = RBACRanks::findOrFail($rank_id);

        $created = false;
        $group = null;
        if ($group_id)
        {
            $group = RBACGroups::findOrFail($group_id);
        }
        else
        {
            $group = new RBACGroups();
            $created = true;
        }

        //Let's see if we aren't edition the correct one
        if ($group_id && ($group->rank_id != $rank->id))
        {
            Log::error("RBACEditor->groupEditorPost: group doesn't belong to rank! rank_id: " . $rank_id . " group_id: " . $group_id);
            return redirect()->back();
        }

        if ($created)
        {
            //Let's create a new rbac_permissions entry
            $permissions = new RBACPermissions();
            $permissions->save();
            $group->permission_id = $permissions->id;

            //also add it to the bottom
            $bottomGroup = RBACGroups::where('rank_id', $rank_id)->orderBy('sort_order', 'desc')->first();

            if ($bottomGroup)
            {
                $group->sort_order = $bottomGroup->sort_order + 1;
            }
            else
            {
                $group->sort_order = 0;
            }
        }

        $group->rank_id = $rank_id;
        $group->name = $request->input('name');
        $group->url = $request->input('url');

        $revoke = $request->input('revoke') ? true : false;

        $group->revoke = $revoke;

        $group->save();

        return redirect('admin/rbac_editor/show/' . $rank_id)->with('info', trans('admin.success'));
    }

    public function up(Request $request) 
    {
        $id = $request->input('id', false);

        if (!$id || !is_numeric($id))
        {
            Log::critical('RBACEditor->up: id is bad, id: ' . $id);
            return redirect()->back()->withErrors(trans('errors.internal_error_try_again'));
        }

        $current = RBACGroups::findOrFail($id);

        if ($current->sort_order == 0)
        {
            Log::critical('RBACEditor->up: up is pressed, white sort order is 0!');
            return redirect()->back()->withErrors(trans('errors.internal_error_try_again'));
        }

        $above = RBACGroups::where('rank_id', $current->rank_id)->where('sort_order', $current->sort_order - 1)->first();
        
        $above->sort_order += 1;
        $current->sort_order -= 1;

        $above->save();
        $current->save();

        return redirect()->back();
    }

    public function down(Request $request)
    {
        $id = $request->input('id', false);

        if (!$id || !is_numeric($id))
        {
            Log::critical('RBACEditor->down: id is bad, id: ' . $id);
            return redirect()->back()->withErrors(trans('errors.internal_error_try_again'));
        }

        $current = RBACGroups::findOrFail($id);

        $pc = RBACGroups::where('rank_id', $current->rank_id)->get();

        $max = $pc[0]->sort_order;
        for ($i = 1; $i < count($pc); $i++)
        {
            if ($pc[$i]->sort_order > $max)
            {
                $max = $pc[$i]->sort_order;
            }
        }

        if ($current->sort_order == $max)
        {
            Log::critical('RBACEditor->down: Down is pressed, white sort order is the max (the entry is at the bottom)!');
            return redirect()->back()->withErrors(trans('errors.internal_error_try_again'));
        }

        $below = RBACGroups::where('rank_id', $current->rank_id)->where('sort_order', $current->sort_order + 1)->first();
        
        $below->sort_order -= 1;
        $current->sort_order += 1;

        $below->save();
        $current->save();

        return redirect()->back();
    }

    public function delete(Request $request)
    {
        $id = $request->input('id', false);

        if (!$id || !is_numeric($id))
        {
            Log::critical('RBACEditor->delete: id is bad, id: ' . $id);
            return redirect()->back()->withErrors(trans('errors.internal_error_try_again'));
        }

        $current = RBACGroups::find($id);

        if (!$current)
        {
            Log::critical('RBACEditor->delete: id is bad, id: ' . $id);
            return redirect()->back()->withErrors(trans('errors.internal_error_try_again'));
        }

        $currentPermission = RBACPermissions::find($current->permission_id);

        if (!$currentPermission)
        {
            Log::critical('RBACEditor->delete: permission_id is bad in the db, (group)id: ' . $id . ' permissions_id: ' . $currentPermission->id);
            return redirect()->back()->withErrors(trans('errors.internal_error_try_again'));
        }

        $current->delete();
        $currentPermission->delete();

        return redirect()->back()->with('info', trans('admin.success'));
    }

    public function updatePermissions(Request $request)
    {
        $id = $request->input('id', false);

        if (!$id || !is_numeric($id))
        {
            Log::critical('RBACEditor->updatePermissions: id is bad, id: ' . $id);
            return redirect()->back()->withErrors(trans('errors.internal_error_try_again'));
        }

        $current = RBACPermissions::find($id);

        if (!$current)
        {
            Log::critical('RBACEditor->updatePermissions: id is bad, id: ' . $id);
            return redirect()->back()->withErrors(trans('errors.internal_error_try_again'));
        }

        $first = true;
        $permissions = "";
        foreach (RBACPermissions::$permissions as $p)
        {
            if ($request->input($p, false))
            {
                if (!$first)
                {
                    $permissions .= ',';
                }

                $permissions .= $p;
                $first = false;
            }
        }

        $current->permissions = $permissions;
        $current->save();

        return redirect()->back()->with('info', trans('admin.success'));
    }
}
