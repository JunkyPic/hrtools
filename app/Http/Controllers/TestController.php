<?php

namespace App\Http\Controllers;

use App\Http\Requests\TestControllerPostCreateTest;
use App\Http\Requests\TestControllerPostEditTest;
use App\Models\Test;
use Illuminate\Http\Request;

/**
 * Class TestController
 *
 * @package App\Http\Controllers
 */
class TestController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCreate() {
        return view('admin.test.create');
    }

    /**
     * @param TestControllerPostCreateTest $request
     * @param Test                         $test_model
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreate(TestControllerPostCreateTest $request, Test $test_model) {
        try{
            $test_model->create([
                'name' => $request->get('name'),
                'information' => $request->has('information') ? $request->get('information') : null,
            ]);
        }catch (\Exception $exception) {
            return redirect()->back()->with(['message' => 'Unable to create test', 'alert_type' => 'danger']);
        }

        return redirect()->back()->with(['message' => 'Test created successfully', 'alert_type' => 'success']);
    }

    /**
     * @param Test $test_model
     *
     * @return $this
     */
    public function all(Test $test_model) {
        return view('admin.test.tests')->with(['tests' => $test_model->with(['chapters.questions'])->orderBy('created_at', 'DESC')->paginate(1)]);
    }

    /**
     * @param      $test_id
     * @param Test $test_model
     *
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function getEdit($test_id, Test $test_model) {
        try{
            $test = $test_model->find($test_id);
        }catch (\Exception $exception) {
            return redirect()->back()->with(['message' => 'Unable to find test', 'alert_type' => 'danger']);
        }

        return view('admin.test.edit')->with(['test' => $test]);
    }

    /**
     * @param                            $test_id
     * @param TestControllerPostEditTest $request
     * @param Test                       $test_model
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEdit($test_id, TestControllerPostEditTest $request, Test $test_model) {
        try{
            $test = $test_model->where(['id' => $test_id])->first();

            $test->update([
                'name' => $request->get('name'),
                'information' => $request->get('information'),
            ]);
        } catch (\Exception $exception) {
            return redirect()->back()->with(['message' => 'Unable to update test', 'alert_type' => 'danger']);
        }

        return redirect()->back()->with(['message' => 'Test updated successfully', 'alert_type' => 'success']);
    }

    /**
     * @param      $test_id
     * @param Test $test_model
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($test_id, Test $test_model) {
        try{
            $test = $test_model->where(['id' => $test_id])->first();

            $test->delete();
        } catch (\Exception $exception) {
            return redirect()->back()->with(['message' => 'Unable to delete test', 'alert_type' => 'danger']);
        }

        return redirect()->route('testAll')->with(['message' => 'Test deleted successfully', 'alert_type' => 'success']);
    }
}
