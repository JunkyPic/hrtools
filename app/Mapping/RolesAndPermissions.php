<?php

namespace App\Mapping;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissions {

  const ROLE_ADMIN = 'admin';
  const ROLE_CONTENT_CREATOR = 'content creator';
  const ROLE_REVIEWER = 'reviewer';

  private $roles = [
    self::ROLE_ADMIN,
    self::ROLE_CONTENT_CREATOR,
    self::ROLE_REVIEWER,
  ];

  private $permissions = [
    'edit question',
    'add question',
    'delete question',
    'edit chapter',
    'add chapter',
    'delete chapter',
    'edit test',
    'add test',
    'delete test',
    'add review',
    'edit review',
    'invite user',
    'invite candidate',
  ];

  private $role_and_permissions = [
    self::ROLE_CONTENT_CREATOR => [
      'edit question',
      'add question',
      'delete question',
      'edit chapter',
      'add chapter',
      'delete chapter',
      'edit test',
      'add test',
      'delete test',
    ],
    self::ROLE_REVIEWER        => [
      'add review',
      'edit review',
    ],
    self::ROLE_ADMIN           => [
      'invite user',
      'invite candidate',
      'edit question',
      'add question',
      'delete question',
      'edit chapter',
      'add chapter',
      'delete chapter',
      'edit test',
      'add test',
      'delete test',
      'add review',
      'edit review',
    ],
  ];

  public function getAllRoles() {
    return $this->roles;
  }

  public function getRolesAndPermissions() {
    return $this->role_and_permissions;
  }

  public function createRolesAndPermissions() {
    foreach($this->roles as $role) {
      Role::create(['name' => $role]);
    }

    foreach($this->permissions as $permission) {
      Permission::create(['name' => $permission]);
    }

    foreach ($this->roles as $role) {
      $role_instance = Role::findByName($role);

      foreach($this->role_and_permissions[$role] as $permission) {
          $role_instance->givePermissionTo($permission);
      }
    }
  }

  public function getPermissionByRole($role) {
    if (!isset($this->role_and_permissions[$role])) {
      throw new \Exception('Unable to find role');
    }

    return ($this->role_and_permissions[$role]);
  }

  public function getRoleByPermission($permission) {
    foreach ($this->role_and_permissions as $role_and_permission) {
      if (in_array($permission, $role_and_permission)) {
        return $role_and_permission;
      }
    }

    throw new \Exception('Unable to find permissions');
  }

}
