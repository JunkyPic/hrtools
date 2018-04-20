<?php

namespace App\Repository;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use App\Models\Image as ImageModel;

/**
 * Class ImageRepository
 *
 * @package App\Repository
 */
class ImageRepository
{

    /**
     * @var \App\Models\Image
     */
    private $model = ImageModel::class;

    /**
     * @var \App\Models\Image
     */
    private $mode_instance;

    /**
     * @var string
     */
    private $default_storage_path;

    /**
     * ImageRepository constructor.
     */
    public function __construct()
    {
        $this->mode_instance = new $this->model;
        $this->default_storage_path = public_path('img').DIRECTORY_SEPARATOR;
    }

    /**
     * @param      $images
     * @param null $path
     *
     * @return array
     */
    public function store($images, $path = null)
    {
        if (null === $path) {
            $path = $this->default_storage_path;
        }

        $images_return = [];

        foreach ($images as $image) {
            $imageName = Str::random(10).'.'.$image->getClientOriginalExtension();
            $img = Image::make($image->getRealPath());
            $images_return[] = $this->mode_instance::create(
                [
                    'name'  => null,
                    'alias' => $imageName,
                ]
            );

            $img->save($path.$imageName, 100);
            $img->destroy();
        }

        return $images_return;
    }

    /**
     * @param $images
     */
    public function remove($images)
    {
        if(!$images instanceof Collection) {
            return;
        }

        foreach ($images as $image) {
            if (\File::exists(\Config::get('image.upload_path') . $image->alias)) {
                \File::delete(\Config::get('image.upload_path') . $image->alias);
            }
        }
    }
}