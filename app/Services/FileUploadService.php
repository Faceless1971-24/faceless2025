<?php
namespace App\Services;

use Exception;
use Illuminate\Support\Str;

class FileUploadService
{
    public function __construct()
    {
        //
    }

    public function upload($fileName, $path = '')
    {
        try {
            return request()->file($fileName)->store("{$path}", 'public');
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    // TODO: Add thumbnail creation for each image
    public function uploadImage($fileName, $path = '')
    {

        try {
            $file = request()->file($fileName);
            $extension = $file->extension();

            // $name = Str::slug($file->getClientOriginalName()).'_'.time().'.'.$extension;
            // return request()->file($fileName)->storeAs("images/{$path}", 'public');
            return request()->file($fileName)->store("images/{$path}", 'public');
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        // $image = $request->file($fileNmae);
        // $reImage = time().'.'.$image->getClientOriginalExtension();
        // $dest = public_path($publicPath);
        // $image->move($dest,$reImage);

        // // return $reImage;
        // dd($reImage);
    }

    //Single File Upload
    public function uploadFiles($request, $fileNmae, $publicPath)
    {

        $image = $request->file($fileNmae);
        $reImage = time() . '.' . $image->getClientOriginalExtension();
        $dest = public_path($publicPath);
        $image->move($dest, $reImage);
        return $reImage;
    }


    public function uploadFilesMultiple($request, $fieldName, $directory)
    {
        $uploaded_files = [];
        if ($request->hasFile($fieldName)) {
            foreach ($request->file($fieldName) as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/' . $directory, $filename);
                $uploaded_files[] = $filename;
            }
        }
        return $uploaded_files;
    }


    // Multiple File Upload 
    // public function uploadFilesMultiple($request, $fileName, $publicPath)
    // {
    //     $files = $request->file($fileName);

    //     $filePaths = [];
    //     foreach ($files as $file) {
    //         $fileName = $file->getClientOriginalName();
    //         $file->move(public_path($publicPath), $fileName);
    //         $filePaths[] = $fileName;
    //     }

    //     return $filePaths;
    // }
}
