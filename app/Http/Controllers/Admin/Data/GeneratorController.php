<?php

namespace App\Http\Controllers\Admin\Data;

use App\Http\Controllers\Controller;
use App\Models\Generator\RandomGenerator;
use App\Models\Generator\RandomObject;
use App\Services\GeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GeneratorController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Admin / Random Generator Controller
    |--------------------------------------------------------------------------
    |
    | Handles creation/editing of random generators and objects.
    |
    */

    /******************************************************************* GENERATORS */

    /**
     * Shows the main index.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getIndex() {
        return view('admin.generator.randoms', [
            'generators' => RandomGenerator::orderBy('sort', 'DESC')->get(),
        ]);
    }

    /**
     * Shows the index for a category.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getRandomGeneratorIndex($id) {
        $generator = RandomGenerator::find($id);

        return view('admin.generator.random_generator', [
            'generator' => $generator,
        ]);
    }

    /**
     * Shows the create generator page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getCreateRandomGenerator() {
        return view('admin.generator.create_edit_random_generator', [
            'generator' => new RandomGenerator,
        ]);
    }

    /**
     * Shows the edit generator page.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getEditRandomGenerator($id) {
        $generator = RandomGenerator::find($id);
        if (!$generator) {
            abort(404);
        }

        return view('admin.generator.create_edit_random_generator', [
            'generator' => $generator,
        ]);
    }

    /**
     * Creates or edits a generator.
     *
     * @param App\Services\GeneratorService $service
     * @param int|null                      $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreateEditRandomGenerator(Request $request, GeneratorService $service, $id = null) {
        $id ? $request->validate(RandomGenerator::$updateRules) : $request->validate(RandomGenerator::$createRules);
        $data = $request->only([
            'name', 'description', 'image', 'remove_image', 'is_active',
        ]);
        if ($id && $service->updateRandomGenerator(RandomGenerator::find($id), $data, Auth::user())) {
            flash('Generator updated successfully.')->success();
        } elseif (!$id && $category = $service->createRandomGenerator($data, Auth::user())) {
            flash('Generator created successfully.')->success();

            return redirect()->to('admin/data/random/generator/edit/'.$category->id);
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                flash($error)->error();
            }
        }

        return redirect()->back();
    }

    /**
     * Gets the generator deletion modal.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getDeleteRandomGenerator($id) {
        $generator = RandomGenerator::find($id);

        return view('admin.generator._delete_generator', [
            'generator' => $generator,

        ]);
    }

    /**
     * Deletes a generator.
     *
     * @param App\Services\GeneratorService $service
     * @param int                           $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeleteRandomGenerator(Request $request, GeneratorService $service, $id) {
        if ($id && $service->deleteRandomGenerator(RandomGenerator::find($id), Auth::user())) {
            flash('Category deleted successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                flash($error)->error();
            }
        }

        return redirect()->to('admin/data/random');
    }

    /**
     * Sorts generators.
     *
     * @param App\Services\GeneratorService $service
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postSortGenerator(Request $request, GeneratorService $service) {
        if ($service->sortGenerator($request->get('sort'))) {
            flash('Generator order updated successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                flash($error)->error();
            }
        }

        return redirect()->back();
    }

    /******************************************************************* OBJECTS */

    /**
     * Shows the create object page.
     *
     * @param mixed $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getCreateRandom($id) {
        $generator = RandomGenerator::find($id);
        if (!$generator) {
            abort(404);
        }

        return view('admin.generator.create_edit_random_object', [
            'object'    => new RandomObject(),
            'generator' => $generator,
        ]);
    }

    /**
     * Shows the edit object page.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getEditRandom($id) {
        $object = RandomObject::find($id);
        if (!$object) {
            abort(404);
        }

        return view('admin.generator.create_edit_random_object', [
            'object'    => $object,
            'generator' => $object->generator,
        ]);
    }

    /**
     * Creates or edits an object.
     *
     * @param App\Services\ItemService $service
     * @param int|null                 $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreateEditRandom(Request $request, GeneratorService $service, $id = null) {
        $id ? $request->validate(RandomObject::$rules) : $request->validate(RandomObject::$rules);
        $data = $request->only([
            'text', 'link', 'random_generator_id',
        ]);
        if ($id && $service->updateRandom(RandomObject::find($id), $data, Auth::user())) {
            flash('Object updated successfully.')->success();
        } elseif (!$id && $object = $service->createRandom($data, Auth::user())) {
            flash('Object created successfully.')->success();

            return redirect()->to('admin/data/random/generator/view/'.$data['random_generator_id']);
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                flash($error)->error();
            }
        }

        return redirect()->back();
    }

    /**
     * Gets the object deletion modal.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getDeleteRandom($id) {
        $object = RandomObject::find($id);

        return view('admin.generator._delete_object', [
            'object' => $object,
        ]);
    }

    /**
     * Deletes an object.
     *
     * @param App\Services\ItemService $service
     * @param int                      $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeleteRandom(Request $request, GeneratorService $service, $id) {
        $generator = RandomObject::find($id)->generator->id;
        if ($id && $service->deleteRandom(RandomObject::find($id), Auth::user())) {
            flash('Object deleted successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                flash($error)->error();
            }
        }

        return redirect()->to('admin/data/random/generator/view/'.$generator);
    }
}
