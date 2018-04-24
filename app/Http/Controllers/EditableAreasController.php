<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditableAreaControllerPostEditAreaPrestartTest;
use App\Models\EditableArea;
use App\Models\Test;

/**
 * Class EditableAreasController
 *
 * @package App\Http\Controllers
 */
class EditableAreasController extends Controller
{

  /**
   * @param \App\Models\Test $test
   *
   * @return $this
   */
  public function getEditAreaTestList(Test $test) {
    $tests = $test->with('editableArea')->paginate(10);

    return view('admin.editablearea.list')->with(['tests' => $tests]);
  }

  /**
   * @param                          $test_id
   * @param \App\Models\EditableArea $editable_area
   *
   * @return $this|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
   */
    public function getEditAreaPrestartTest($test_id, EditableArea $editable_area) {
      try{
        $area = $editable_area->where('test_id', $test_id)->first();
        if(null !== $area) {
          return view('admin.editablearea.prestarttest')->with(['area' => $area, 'test_id' => $test_id]);
        }

        return view('admin.editablearea.prestarttest')->with(['test_id' => $test_id]);

      } catch (\Exception $exception) {
        return redirect()->back()->with([
          'message'    => 'Database error: unable to access table',
          'alert_type' => 'danger'
        ]);
      }
    }

  /**
   * @param                                                                   $test_id
   * @param \App\Http\Requests\EditableAreaControllerPostEditAreaPrestartTest $request
   * @param \App\Models\EditableArea                                          $editable_area
   *
   * @return \Illuminate\Http\RedirectResponse
   */
    public function postEditAreaPrestartTest($test_id, EditableAreaControllerPostEditAreaPrestartTest $request, EditableArea $editable_area) {
      try{
        $area = $editable_area->where('test_id', $test_id)->first();

        if(null === $area) {
          $editable_area->create($request->except('_token'));
        } else {
          $area->update($request->except('_token'));
        }

        return redirect()->back()->with([
          'message'    => 'Content updated',
          'alert_type' => 'success'
        ]);


      } catch (\Exception $exception) {
        return redirect()->back()->with([
          'message'    => 'Database error: unable to access table',
          'alert_type' => 'danger'
        ]);
      }
    }
}
