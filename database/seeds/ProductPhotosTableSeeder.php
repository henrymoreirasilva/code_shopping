<?php

use CodeShopping\Models\Product;
use CodeShopping\Models\ProductPhotos;
use Illuminate\Database\Seeder;

class ProductPhotosTableSeeder extends Seeder
{
    private $allFakerPhotos;
    private $fakerPhotosPath = 'app/faker/product_photos';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->allFakerPhotos = $this->getFakerPhotos();
        $products = Product::all();
        $this->deleteAllPhotosInProductsPath();
        foreach ($products as $product) {
            $this->createPhotoDir($product);
            $this->createPhotosModels($product);
        }
    }

    private function deleteAllPhotosInProductsPath() {
        $path = ProductPhotos::PRODUCTS_PATH;
        \File::deleteDirectory(storage_path($path));
    }
    private function createPhotoDir(Product $product) {
        $path = ProductPhotos::photosPath($product->id);
        \File::makeDirectory($path, 0777, true);
    }
    private function createPhotosModels(Product $product) {
        foreach (range(1, 5) as $v) {
            $this->createPhotoModel($product);
        }
    }
    private function createPhotoModel(Product $product) {
        $photo = ProductPhotos::create(['photo_name' => "teste.jpg", 'product_id' => $product->id]);
        $this->generatePhoto($photo);
    }

    private function generatePhoto(ProductPhotos $photos) {
        $photos->photo_name = $this->uploadPhoto($photos->product_id);
        $photos->save();
    }

    private function uploadPhoto($productId) {
        /** @var SplFileInfo $photoFile */
        $photoFile = $this->allFakerPhotos->random();
        $uploadedFile = new \Illuminate\Http\UploadedFile(
            $photoFile->getRealPath(),
            str_random(16). '.'. $photoFile->getExtension()
        );

        ProductPhotos::uploadFiles($productId, [$uploadedFile]);

        return $uploadedFile->hashName();
    }

    private function getFakerPhotos() {
        $path = storage_path($this->fakerPhotosPath);
        return collect(\File::allFiles($path));
    }
}
