<?php

namespace CodeShopping\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class ProductPhotos extends Model
{
    const BASE_DIR = 'app/public';
    const DIR_PRODUCTS = 'products';
    const PRODUCTS_PATH = self::BASE_DIR. '/'. self::DIR_PRODUCTS;


    protected $fillable = ['photo_name', 'product_id'];

    public static function photosPath($productId) {
        return storage_path(self::PRODUCTS_PATH. "/{$productId}");
    }

    public static function createWithPhotosFiles(int $productId, array $files) {
        try {
            self::uploadFiles($productId, $files);
            \DB::beginTransaction();
            $photos = self::createPhotosModels($productId, $files);
            \DB::commit();
            return new Collection($photos);
        } catch (\Exception $e) {
            \DB::rollBack();
            self::deleteFiles($productId, $files);
            throw $e;
        }
    }

    public static function deleteFiles($productId, $files) {
        $path = self::photosPath($productId);
        foreach($files as $file) {
            $pathFile = $path. '/'. $file->hashName();
            if (file_exists($pathFile)) {
                \File::delete($pathFile);
            }
        }
    }

    public static function deleteFile($productId, $fileName) {
        $path = self::photosPath($productId);
        $pathFile = $path. '/'. $fileName;

        if (file_exists($pathFile)) {
            \File::delete($pathFile);
        }

    }

    /**
     * @param UploadedFile $newPhoto
     */
    public function updateFile(UploadedFile $newPhoto) {

        // salvar a nova imagem
        self::uploadFiles($this->product_id, [$newPhoto]);

        // atualizar o nome da nova imagem
        $oldName = $this->photo_name;
        $this->photo_name = $newPhoto->hashName();
        $this->save();

        // remover a imagem anterior
        self::deleteFile($this->product_id, $oldName);
    }

    public static function uploadFiles($productId, array $files) {
        $dir = self::photosDir($productId);
        /** @var UploadedFile $file */
        foreach ($files as $file) {
            $file->store($dir, ['disk' => 'public']);
        }
    }

    public static function createPhotosModels($productId, $files) {
        $photos = [];
        foreach($files as $file) {
            $photos[] = self::create([
                'photo_name' => $file->hashName(),
                'product_id' => $productId
            ]);
        }
        return $photos;
    }
    public static function photosDir($productId) {
        return self::DIR_PRODUCTS. "/{$productId}";
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function getPhotoUrlAttribute(): string
    {     //  accessors and mutators
        $path = self::photosDir($this->product_id);
        return asset("storage/{$path}/{$this->photo_name}");
    }
}
