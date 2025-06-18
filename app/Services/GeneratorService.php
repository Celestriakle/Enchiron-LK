<?php

namespace App\Services;

use App\Models\Generator\RandomGenerator;
use App\Models\Generator\RandomObject;
use Illuminate\Support\Facades\DB;

class GeneratorService extends Service {
    /*
    |--------------------------------------------------------------------------
    | Random Generator Service
    |--------------------------------------------------------------------------
    |
    | Handles the creation and editing of random generator categories and objects.
    |
    */

    /**********************************************************************************************

        ITEM CATEGORIES

    **********************************************************************************************/

    /**
     * Create a generator.
     *
     * @param array                 $data
     * @param \App\Models\User\User $user
     *
     * @return \App\Models\Item\RandomGenerator|bool
     */
    public function createRandomGenerator($data, $user) {
        DB::beginTransaction();

        try {
            $data = $this->populateGeneratorData($data);

            $image = null;
            if (isset($data['image']) && $data['image']) {
                $data['has_image'] = 1;
                $data['hash'] = randomString(10);
                $image = $data['image'];
                unset($data['image']);
            } else {
                $data['has_image'] = 0;
            }

            $generator = RandomGenerator::create($data);

            if ($image) {
                $this->handleImage($image, $generator->generatorImagePath, $generator->generatorImageFileName);
            }

            return $this->commitReturn($generator);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Update a generator.
     *
     * @param array                 $data
     * @param \App\Models\User\User $user
     * @param mixed                 $generator
     *
     * @return \App\Models\Item\RandomGenerator|bool
     */
    public function updateRandomGenerator($generator, $data, $user) {
        DB::beginTransaction();

        try {
            $data = $this->populateGeneratorData($data);

            $image = null;
            if (isset($data['image']) && $data['image']) {
                $data['has_image'] = 1;
                $data['hash'] = randomString(10);
                $image = $data['image'];
                unset($data['image']);
            }

            $generator->update($data);

            if ($image) {
                $this->handleImage($image, $generator->generatorImagePath, $generator->generatorImageFileName);
            }

            return $this->commitReturn($generator);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Deletes a category.
     *
     * @param mixed $user
     * @param mixed $generator
     *
     * @return bool
     */
    public function deleteRandomGenerator($generator, $user) {
        DB::beginTransaction();

        try {
            if (DB::table('random_objects')->where('random_generator_id', '=', $generator->id)->exists()) {
                throw new \Exception('This generator has at least one object. Please delete the objects before deleting it.');
            }

            $generator->delete();

            return $this->commitReturn(true);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Sorts generator order.
     *
     * @param array $data
     *
     * @return bool
     */
    public function sortGenerator($data) {
        DB::beginTransaction();

        try {
            // explode the sort array and reverse it since the order is inverted
            $sort = array_reverse(explode(',', $data));

            foreach ($sort as $key => $s) {
                RandomGenerator::where('id', $s)->update(['sort' => $key]);
            }

            return $this->commitReturn(true);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /*********** OBJECTS */

    /**
     * Create an object.
     *
     * @param array                 $data
     * @param \App\Models\User\User $user
     *
     * @return \App\Models\Item\RandomObject|bool
     */
    public function createRandom($data, $user) {
        DB::beginTransaction();

        try {
            $object = RandomObject::create($data);

            return $this->commitReturn($object);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Update an object.
     *
     * @param array                 $data
     * @param \App\Models\User\User $user
     * @param mixed                 $object
     *
     * @return \App\Models\Item\RandomObject|bool
     */
    public function updateRandom($object, $data, $user) {
        DB::beginTransaction();

        try {
            $object->update($data);

            return $this->commitReturn($object);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Deletes an object.
     *
     * @param mixed $object
     * @param mixed $user
     *
     * @return bool
     */
    public function deleteRandom($object, $user) {
        DB::beginTransaction();

        try {
            $object->delete();

            return $this->commitReturn(true);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Processes user input for creating/updating a generator.
     *
     * @param array                                 $data
     * @param \App\Models\Generator\RandomGenerator $generator
     *
     * @return array
     */
    private function populateGeneratorData($data, $generator = null) {
        if (isset($data['description']) && $data['description']) {
            $data['parsed_description'] = parse($data['description']);
        } else {
            $data['parsed_description'] = null;
        }
        $data['is_active'] = isset($data['is_active']);

        if (isset($data['remove_image'])) {
            if ($generator && $generator->has_image && $data['remove_image']) {
                $data['has_image'] = 0;
                $this->deleteImage($generator->generatorImagePath, $generator->generatorImageFileName);
            }
            unset($data['remove_image']);
        }

        return $data;
    }
}
