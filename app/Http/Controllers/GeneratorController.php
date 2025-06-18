<?php

namespace App\Http\Controllers;

use App\Models\Generator\RandomGenerator;

class GeneratorController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Gallery Controller
    |--------------------------------------------------------------------------
    |
    | Displays galleries and gallery submissions.
    |
    */

    /**
     * Shows the index page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getGenerators() {
        return view('generators.index', [
            'generators' => RandomGenerator::where('is_active', 1)->orderBy('sort', 'DESC')->get(),
        ]);
    }

    /**
     * Shows one generator.
     *
     * @param mixed $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getGenerator($id) {
        $generator = RandomGenerator::where('id', $id)->where('is_active', 1)->first();
        if (!$generator) {
            abort(404);
        }
        $objects = $generator->objects->toArray();

        return view('generators.generator', [
            'generator' => $generator,
            'objects'   => $objects,
        ]);
    }
}
