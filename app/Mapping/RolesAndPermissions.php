<?php

namespace App\Mapping;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissions {

  const ROLE_ADMIN = 'admin';
  const ROLE_CONTENT_CREATOR = 'content creator';
  const ROLE_REVIEWER = 'reviewer';
  // questions
  const PERMISSION_EDIT_QUESTION = 'edit question';
  const PERMISSION_ADD_QUESTION = 'add question';
  const PERMISSION_DELETE_QUESTION = 'delete question';
  // tags
  const PERMISSION_EDIT_TAG = 'edit tag';
  const PERMISSION_ADD_TAG = 'add tag';
  const PERMISSION_DELETE_TAG = 'delete tag';
  // chapters
  const PERMISSION_EDIT_CHAPTER = 'edit chapter';
  const PERMISSION_ADD_CHAPTER = 'add chapter';
  const PERMISSION_DELETE_CHAPTER = 'delete chapter';
  // tests
  const PERMISSION_ADD_TEST = 'add test';
  const PERMISSION_EDIT_TEST = 'edit test';
  const PERMISSION_DELETE_TEST = 'delete test';
  //review
  const PERMISSION_ADD_REVIEW = 'add review';
  const PERMISSION_EDIT_REVIEW = 'edit review';
  //invites
  const PERMISSION_INVITE_USER = 'invite user';
  const PERMISSION_INVITE_CANDIDATE = 'invite candidate';

  private $roles = [
    self::ROLE_ADMIN,
    self::ROLE_CONTENT_CREATOR,
    self::ROLE_REVIEWER,
  ];

  private $permissions = [
    self::PERMISSION_EDIT_TAG,
    self::PERMISSION_ADD_TAG,
    self::PERMISSION_DELETE_TAG,
    self::PERMISSION_EDIT_QUESTION,
    self::PERMISSION_ADD_QUESTION,
    self::PERMISSION_DELETE_QUESTION,
    self::PERMISSION_EDIT_CHAPTER,
    self::PERMISSION_ADD_CHAPTER,
    self::PERMISSION_DELETE_CHAPTER,
    self::PERMISSION_EDIT_TEST,
    self::PERMISSION_ADD_TEST,
    self::PERMISSION_DELETE_TEST,
    self::PERMISSION_ADD_REVIEW,
    self::PERMISSION_EDIT_REVIEW,
    self::PERMISSION_INVITE_USER,
    self::PERMISSION_INVITE_CANDIDATE,
  ];

  private $role_and_permissions = [
    self::ROLE_CONTENT_CREATOR => [
      self::PERMISSION_EDIT_TAG,
      self::PERMISSION_ADD_TAG,
      self::PERMISSION_DELETE_TAG,
      self::PERMISSION_EDIT_QUESTION,
      self::PERMISSION_ADD_QUESTION,
      self::PERMISSION_DELETE_QUESTION,
      self::PERMISSION_EDIT_CHAPTER,
      self::PERMISSION_ADD_CHAPTER,
      self::PERMISSION_DELETE_CHAPTER,
      self::PERMISSION_EDIT_TEST,
      self::PERMISSION_ADD_TEST,
      self::PERMISSION_DELETE_TEST,
    ],
    self::ROLE_REVIEWER        => [
      self::PERMISSION_ADD_REVIEW,
      self::PERMISSION_EDIT_REVIEW,
    ],
    self::ROLE_ADMIN           => [
      self::PERMISSION_EDIT_TAG,
      self::PERMISSION_ADD_TAG,
      self::PERMISSION_DELETE_TAG,
      self::PERMISSION_EDIT_QUESTION,
      self::PERMISSION_ADD_QUESTION,
      self::PERMISSION_DELETE_QUESTION,
      self::PERMISSION_EDIT_CHAPTER,
      self::PERMISSION_ADD_CHAPTER,
      self::PERMISSION_DELETE_CHAPTER,
      self::PERMISSION_EDIT_TEST,
      self::PERMISSION_ADD_TEST,
      self::PERMISSION_DELETE_TEST,
      self::PERMISSION_ADD_REVIEW,
      self::PERMISSION_EDIT_REVIEW,
      self::PERMISSION_INVITE_USER,
      self::PERMISSION_INVITE_CANDIDATE,
    ],
  ];

  public function hasPermissionTo($permission) {
    return \Auth::user()->hasPermissionTo($permission);
  }

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
