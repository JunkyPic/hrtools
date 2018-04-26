<?php

namespace App\Http\Controllers;

use App\Mapping\RolesAndPermissions;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Contracts\Role;

/**
 * Class RolesController
 *
 * @package App\Http\Controllers
 */
class RolesController extends Controller
{
    /**
     * @param Role $roles
     * @param User $user
     *
     * @return $this
     */
    public function getUserByRoles(Role $roles, User $user) {
        return view('admin.roles.users')
            ->with([
                'roles' => $roles->get(),
                'current_user' => \Auth::user(),
                'users' => $user->paginate(20)
            ]);
    }

    /**
     * @param Request $request
     * @param User    $user
     *
     * @return JsonResponse
     */
    public function assignRole(Request $request, User $user) {
        if(!\Auth::user()->hasRole(RolesAndPermissions::ROLE_ADMIN)) {
            return new JsonResponse([
                'message' => 'You don\'t have permission to do that',
            ]);
        }

        if(!$request->has('uid') || !$request->has('role_name')) {
            return new JsonResponse([
                'message' => 'Parameter not found in post variables',
            ]);
        }

        $user_id = $request->get('uid');
        $role_name = $request->get('role_name');

        try{
            $user = $user->where(['id' => $user_id])->first();
            if(!$user->hasRole($role_name)) {
                $user->assignRole($role_name);
                return new JsonResponse([
                    'message' => sprintf('Role %s assigned to %s', $role_name, $user->username),
                ]);
            }

            return new JsonResponse([
                'message' => sprintf('%s already has the role %s', $user->username, $role_name),
            ]);

        } catch (\Exception $exception) {
            return new JsonResponse([
                'message' => 'Unable to query database',
            ]);
        }
    }

    /**
     * @param Request $request
     * @param User    $user
     *
     * @return JsonResponse
     */
    public function revokeRole(Request $request, User $user) {
        if(!\Auth::user()->hasRole(RolesAndPermissions::ROLE_ADMIN)) {
            return new JsonResponse([
                'message' => 'You don\'t have permission to do that',
            ]);
        }

        if(!$request->has('uid') || !$request->has('role_name')) {
            return new JsonResponse([
                'message' => 'Parameter not found in post variables',
            ]);
        }

        $user_id = $request->get('uid');
        $role_name = $request->get('role_name');

        try{
            $user = $user->where(['id' => $user_id])->first();
            if($user->hasRole($role_name)) {
                $user->removeRole($role_name);
                return new JsonResponse([
                    'message' => sprintf('Role %s revoked from %s', $role_name, $user->username),
                ]);
            }

            return new JsonResponse([
                'message' => sprintf('%s does not have role %s', $user->username, $role_name),
            ]);

        } catch (\Exception $exception) {
            return new JsonResponse([
                'message' => 'Unable to query database',
            ]);
        }
    }

  /**
   * @param \App\Mapping\RolesAndPermissions $roles_and_permissions
   *
   * @return $this
   */
    public function getPermissionByRoles(RolesAndPermissions $roles_and_permissions) {
      return view('admin.roles.permission')->with(['roles_and_permissions' => $roles_and_permissions]);
    }
}
