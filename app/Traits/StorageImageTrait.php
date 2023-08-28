<?php
namespace App\Traits;
use Storage;
use Illuminate\Support\Str;
trait StorageImageTrait{
    public function storageTraitUpload($request, $fieldName, $folderName ){
        if($request->hasFile($fieldName)){
            $file =  $request->$fieldName; 
            $fileNameOriginal =$file->getClientOriginalName(); //tên file gốc
            $fileNameHas = Str::random(20) . '.' . $file->getClientOriginalExtension();
            $filePath = $request->file($fieldName)->storeAs('public/' . $folderName ,$fileNameHas);
            $dataUploadTrait = [
                'file_name' => $fileNameOriginal,
                'file_path' => Storage::url($filePath) // thay vì public/foldername thì là storage/foldername trong public
            ];
            return $dataUploadTrait;
        }
        return null;    
        
    }

    public function storageTraitUploadMultiple($file, $folderName ){
        $fileNameOriginal =$file->getClientOriginalName(); //tên file gốc
        $fileNameHas = Str::random(20) . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('public/' . $folderName,$fileNameHas); 
        $dataUploadTrait = [
            'file_name' => $fileNameOriginal,
            'file_path' => Storage::url($filePath) 
        ];
        return $dataUploadTrait;
        
    }
}