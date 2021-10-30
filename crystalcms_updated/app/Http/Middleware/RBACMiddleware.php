<?php

namespace App\Http\Middleware;

use Closure;
use Log;

use App\Models\RBACGroups;
use App\Models\RBACRanks;

class RBACMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $rank_id = null;

        if ($request->userdata)
        {
            $rank_id = $request->userdata->rbac_rank;
        }
        else
        {
            if (!is_numeric($request->settings->get('guest_rbac_rank_id')))
            {
                Log::critical("RBACMiddleware: GlobalSettings: guest_rbac_rank_id isn't numeric!");
                abort(503);
            }

            $rank_id = intval($request->settings->get('guest_rbac_rank_id'));
        }

        $rank = RBACRanks::find($rank_id);

        if (!$rank)
        {
            Log::critical("RBACMiddleware: rank doesn't eists! rank_id: " . $rank_id);
            abort(503);
        }

        $data = RBACGroups::where('rank_id', $rank_id)
            ->leftJoin('rbac_permissions', 'rbac_groups.permission_id', '=', 'rbac_permissions.id')
            ->select('rbac_groups.*', 'rbac_permissions.id AS permission_id','rbac_permissions.permissions')
            ->orderBy('sort_order', 'asc')
            ->get();

        if (!count($data))
        {
            Log::critical("RBACMiddleware: rank_id doesn't eists! rank_id: " . $rank_id);
            abort(503);
        }

        $relevant = null;
        foreach ($data as $row)
        {
            if ($row->url == "/")
            {
                $relevant[] = $row;
                continue;
            }

            if ($request->path() == "/")
            {
                continue;
            }

            $segments = explode('/', $request->path());

            $first = true;
            $s = "";
            for ($i = 0; $i < count($segments); $i++)
            {
                if (!$first)
                {
                    $s .= '/';
                }

                $s .= $segments[$i];

                //we need to collect every match
                if ($row->url == $s)
                {
                    $relevant[] = $row;
                }

                $first = false;
            }
        }

        $permissions = new \App\Data\Permissions();

        foreach ($relevant as $r)
        {
            $permissions->applyPermissions($r);
        }

        //var_dump($permissions);

        if (!$permissions->get('view'))
        {
            if ($permissions->get('redirect'))
            {
                $redirect_to = $request->settings->get('redirect_to_when_no_view_permission', '/');

                //jst to be safe
                if ($request->is($redirect_to))
                {
                    Log::critical("RBACMiddleware: User has no permission to view the page, they are redirected to by default! rank_id: " . $rank_id);
                    abort(503);
                }

                return redirect($redirect_to);
            }
            else
            {
                abort(404);
            }
        }

        $ranksettings = new \App\Data\RBACRankSettings($rank->settings);

        $request->rank_settings = $ranksettings;
        view()->share('rank_settings', $ranksettings);

        $request->permissions = $permissions;
        view()->share('permissions', $permissions);

        return $next($request);
    }
}
