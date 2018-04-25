<?php

namespace App\Http\Controllers;

use App\Http\Requests\TagControllerPostCreateTag;
use App\Http\Requests\TagControllerPostEditRequest;
use App\Mapping\RolesAndPermissions;
use App\Models\Tag;
use Illuminate\Auth\Access\AuthorizationException;

/**
 * Class TagController
 *
 * @package App\Http\Controllers
 */
class TagController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create() {
        return view('admin.tag.create');
    }

    /**
     * @param TagControllerPostCreateTag $request
     * @param Tag                        $tag_model
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add(TagControllerPostCreateTag $request, Tag $tag_model) {
        if(!$request->has('tag')) {
            return redirect()->back()->with(['message' => 'Invalid tag', 'alert_type' => 'danger']);
        }

        $tag = $request->get('tag');

        $tag_exists = $tag_model->where(['tag' => $tag])->first();

        if(null !== $tag_exists && $tag_exists->count() >= 1) {
            return redirect()->back()->with(['message' => 'That tag already exists', 'alert_type' => 'danger']);
        }

        $tag_model->create([
           'tag' => $tag
        ]);

        return redirect()->back()->with(['message' => 'Tag created successfully', 'alert_type' => 'success']);
    }

  /**
   * @param                                  $tag_id
   * @param \App\Mapping\RolesAndPermissions $roles_and_permissions
   * @param \App\Models\Tag                  $tag_model
   *
   * @return \Illuminate\Http\RedirectResponse
   * @throws \Illuminate\Auth\Access\AuthorizationException
   */
    public function delete($tag_id,RolesAndPermissions $roles_and_permissions, Tag $tag_model) {
      if(!$roles_and_permissions->hasPermissionTo(RolesAndPermissions::PERMISSION_DELETE_CHAPTER)) {
        throw new AuthorizationException();
      }
        $tag = $tag_model->where(['id' => $tag_id])->with('questions')->first();

        if(null === $tag) {
            return redirect()->route('tagsAll')->with(['message' => 'Unable to delete tag', 'alert_type' => 'danger']);
        }

        $tag->questions()->detach();

        if(!$tag->delete()) {
            return redirect()->route('tagsAll')->with(['message' => 'Unable to delete tag', 'alert_type' => 'danger']);
        }
        return redirect()->route('tagsAll')->with(['message' => 'Tag deleted successfully', 'alert_type' => 'success']);
    }

    /**
     * @param Tag $tag_model
     *
     * @return $this
     */
    public function all(Tag $tag_model) {
        return view('admin.tag.tags')->with(['tags' => $tag_model->all()]);
    }

    /**
     * @param     $tag_id
     * @param Tag $tag_model
     *
     * @return $this
     */
    public function edit($tag_id, Tag $tag_model) {
        return view('admin.tag.edit')->with(['tag' => $tag_model->where(['id' => $tag_id])->first()]);
    }

    /**
     * @param                              $tag_id
     * @param TagControllerPostEditRequest $request
     * @param Tag                          $tag_model
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editPost($tag_id, TagControllerPostEditRequest $request, Tag $tag_model) {
        $tag = $tag_model->where(['id' => $tag_id])->first();

        if(null === $tag) {
            return redirect()->back()->with(['message' => 'Unable to update tag', 'alert_type' => 'danger']);
        }

        if(!$tag->update([
            'tag' => $request->get('tag')
        ])) {
            return redirect()->back()->with(['message' => 'Unable to update tag', 'alert_type' => 'danger']);
        }
        return redirect()->back()->with(['message' => 'Tag updated successfully', 'alert_type' => 'success']);
    }
}
