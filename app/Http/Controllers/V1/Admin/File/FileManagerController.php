<?php

namespace App\Http\Controllers\V1\Admin\File;
use App\Http\Controllers\Controller;

use App\Http\Resources\Admin\CouponCollection;
use App\Http\Resources\Admin\CouponResource;
use App\Http\Resources\Admin\FileResource;
use App\Http\Resources\Panel\CurrencyCollection;
use App\Http\Resources\Panel\CurrencyResource;
use App\Models\Currency;
use App\Models\File;
use App\Models\FinancialAccount;
use App\Models\User;
// use Illuminate\Support\Facades\MimeType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File as FileFacades;
class FileManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:fileManager.index'])->only('index');
        $this->middleware(['permission:fileManager.cut'])->only('cut');
        $this->middleware(['permission:fileManager.copy'])->only('copy');
        $this->middleware(['permission:fileManager.newDirectory'])->only('newDirectory');

        $this->middleware(['permission:fileManager.rename'])->only('rename');
        $this->middleware(['permission:fileManager.upload'])->only('upload');
    }

    public function index(Request $request){
        $file=File::where("id",$request->file_id)->get();
        // dd($file);
        return FileResource::collection($file);
    }
        public function cut(Request $request){
        $validated = $request->validate([
            'file_id'=> 'required',
            'parent_id_cut'=> 'required',
           ]);
           $file=File::where("id",$request->file_id)->first();
        
           $directory=File::where("id",$request->parent_id_cut)->first();
           //check erorr
           if($directory->type == 'file'){
               return response()->json(['success' => false, 'message' => 'this file and not directory','data'=>null], 200);
   
           }
   
           if($file->type == 'directory'){
               $this->cutDirectory($request);
   
           }
           if($file->type == 'file'){
              $this->cutFile($request);
   
           }
           return response()->json(['success' => true, 'message' => 'cut success','data'=>null], 200);

    }

    public function cutFile(Request $request){
        $validated = $request->validate([
         'file_id'=> 'required',
         'parent_id_cut'=> 'required',
        ]);
        $file=File::where("id",$request->file_id)->first();
     
        $directory=File::where("id",$request->parent_id_cut)->first();
        if($directory->type == 'file'){
            return response()->json(['success' => false, 'message' => 'this file and not directory','data'=>null], 200);

        }

        if($file->type == 'directory'){
            return response()->json(['success' => false, 'message' => 'this not file','data'=>null], 200);

        }

        $filename = $file->name;
        $filename = 'cut'.$filename;
        // dd($filename);
        $src=$directory->src.'/'.$filename;


        $sourcePath = public_path($file->src);

        if(FileFacades::exists($sourcePath)){
            $destinationPath=public_path($src);
            FileFacades::move($sourcePath, $destinationPath);
        }else{
            return response()->json(['success' => false, 'message' => 'this file not exist','data'=>null], 200);

     }
 
     $file->update([
        'parent_id'=>$request['parent_id_cut'],
        'type'=>'file',
        'name'=>$filename,
        'src'=>$src,

     ]);
     return response()->json(['success' => true, 'message' => 'copy success','data'=>null], 200);

        }

    public function cutDirectory(Request $request){
        $validated = $request->validate([
            'file_id'=> 'required',
            'parent_id_cut'=> 'required',
        ]);
    
        $directory = File::where("id", $request->file_id)->first();
        $destinationDirectory = File::where("id", $request->parent_id_cut)->first();
        
        if($destinationDirectory->type == 'file'){
            return response()->json(['success' => false, 'message' => 'Destination is not a directory', 'data' => null], 200);
        }
    
        if($directory->type != 'directory'){
            return response()->json(['success' => false, 'message' => 'Cannot cut a non-directory using this method', 'data' => null], 200);
        }
    
        $sourcePath = public_path($directory->src);
        $destinationPath = public_path($destinationDirectory->src . '/' . $directory->name);
    
        if(FileFacades::isDirectory($sourcePath)){
            FileFacades::moveDirectory($sourcePath, $destinationPath);
        } else {
            return response()->json(['success' => false, 'message' => 'The directory does not exist', 'data' => null], 200);
        }
    
        // Create entry for the moved directory in the database
        $cutDirectory=$directory->update([
            'parent_id' => $request['parent_id_cut'],
            'type' => 'directory',
            'name' => $directory->name,
            'src' => $destinationDirectory->src . '/' . $directory->name,
        ]);
    
        // Copy all files and subdirectories inside the moved directory
        foreach ($directory->children as $child) {
            if ($child->type == 'directory') {
                $this->cutDirectoryRecursively($child, $directory);
            } else {
                // Copy files
                // dd($cutDirectory);
                $this->cutFiles($child, $directory);
            }
        }
    
        return response()->json(['success' => true, 'message' => 'Directory moved successfully', 'data' => null], 200);
    }
    
    private function cutDirectoryRecursively($directory, $parentDirectory){
        $sourcePath = public_path($directory->src);
        $destinationPath = public_path($parentDirectory->src . '/' . $directory->name);
    
        if(FileFacades::isDirectory($sourcePath)){
            FileFacades::moveDirectory($sourcePath, $destinationPath);
        }
    
        // Create entry for the cut directory in the database
        $cutDirectory =$directory->update([
            'parent_id' => $parentDirectory->id,
            'type' => 'directory',
            'name' => $directory->name,
            'src' => $parentDirectory->src . '/' . $directory->name,
        ]);
    
        // Copy all files and subdirectories inside the cut directory
        foreach ($directory->children as $child) {
            if ($child->type == 'directory') {
                $this->cutDirectoryRecursively($child, $directory);
            } else {
                // Copy files
                // dd($cutDirectory);

                $this->cutFiles($child, $directory);
            }
        }
    }
    
    private function cutFiles($file, $parentDirectory){
        // dd($$parentDirectory );
        $sourcePath = public_path($file->src);
        $destinationPath = public_path($parentDirectory->src . '/' . $file->name);
    
        if(FileFacades::exists($sourcePath)){
            FileFacades::move($sourcePath, $destinationPath);

        }
               $file->update([
            'parent_id' => $parentDirectory->parent_id,
            'type' => 'file',
            'name' => $file->name,
            'src' => $parentDirectory->src . '/' . $file->name,
        ]);
    }
    

    public function copy(Request $request){
        $validated = $request->validate([
         'file_id'=> 'required',
         'parent_id_copy'=> 'required',
        ]);
        $file=File::where("id",$request->file_id)->first();
     
        $directory=File::where("id",$request->parent_id_copy)->first();
        //check erorr
        if($directory->type == 'file'){
            return response()->json(['success' => false, 'message' => 'this file and not directory','data'=>null], 200);

        }

        if($file->type == 'directory'){
            $this->copyDirectory($request);

        }
        if($file->type == 'file'){
           $this->copyFile($request);

        }
        return response()->json(['success' => true, 'message' => 'copy success','data'=>null], 200);
      
        }
    public function copyFile(Request $request){
        $validated = $request->validate([
         'file_id'=> 'required',
         'parent_id_copy'=> 'required',
        ]);
        $file=File::where("id",$request->file_id)->first();
     
        $directory=File::where("id",$request->parent_id_copy)->first();
        if($directory->type == 'file'){
            return response()->json(['success' => false, 'message' => 'this file and not directory','data'=>null], 200);

        }

        if($file->type == 'directory'){
            return response()->json(['success' => false, 'message' => 'this not file','data'=>null], 200);

        }

        $filename = $file->name;
        $filename = 'copy'.$filename;
        // dd($filename);
        $src=$directory->src.'/'.$filename;


        $sourcePath = public_path($file->src);

        if(FileFacades::exists($sourcePath)){
            $destinationPath=public_path($src);
            FileFacades::copy($sourcePath, $destinationPath);
        }else{
            return response()->json(['success' => false, 'message' => 'this file not exist','data'=>null], 200);

     }
 
     File::create([
        'parent_id'=>$request['parent_id_copy'],
        'type'=>'file',
        'name'=>$filename,
        'src'=>$src,

     ]);
     return response()->json(['success' => true, 'message' => 'copy success','data'=>null], 200);

        }

    public function copyDirectory(Request $request){
        $validated = $request->validate([
            'file_id'=> 'required',
            'parent_id_copy'=> 'required',
        ]);
    
        $directory = File::where("id", $request->file_id)->first();
        $destinationDirectory = File::where("id", $request->parent_id_copy)->first();
        
        if($destinationDirectory->type == 'file'){
            return response()->json(['success' => false, 'message' => 'Destination is not a directory', 'data' => null], 200);
        }
    
        if($directory->type != 'directory'){
            return response()->json(['success' => false, 'message' => 'Cannot copy a non-directory using this method', 'data' => null], 200);
        }
    
        $sourcePath = public_path($directory->src);
        $destinationPath = public_path($destinationDirectory->src . '/' . $directory->name);
    
        if(FileFacades::isDirectory($sourcePath)){
            FileFacades::copyDirectory($sourcePath, $destinationPath);
        } else {
            return response()->json(['success' => false, 'message' => 'The directory does not exist', 'data' => null], 200);
        }
    
        // Create entry for the copied directory in the database
        $copiedDirectory = File::create([
            'parent_id' => $request['parent_id_copy'],
            'type' => 'directory',
            'name' => $directory->name,
            'src' => $destinationDirectory->src . '/' . $directory->name,
        ]);
    
        // Copy all files and subdirectories inside the copied directory
        foreach ($directory->children as $child) {
            if ($child->type == 'directory') {
                $this->copyDirectoryRecursively($child, $copiedDirectory);
            } else {
                // Copy files
                $this->copyFiles($child, $copiedDirectory);
            }
        }
    
        return response()->json(['success' => true, 'message' => 'Directory copied successfully', 'data' => null], 200);
    }
    
    private function copyDirectoryRecursively($directory, $parentDirectory){
        $sourcePath = public_path($directory->src);
        $destinationPath = public_path($parentDirectory->src . '/' . $directory->name);
    
        if(FileFacades::isDirectory($sourcePath)){
            FileFacades::copyDirectory($sourcePath, $destinationPath);
        }
    
        // Create entry for the copied directory in the database
        $copiedDirectory = File::create([
            'parent_id' => $parentDirectory->id,
            'type' => 'directory',
            'name' => $directory->name,
            'src' => $parentDirectory->src . '/' . $directory->name,
        ]);
    
        // Copy all files and subdirectories inside the copied directory
        foreach ($directory->children as $child) {
            if ($child->type == 'directory') {
                $this->copyDirectoryRecursively($child, $copiedDirectory);
            } else {
                // Copy files
                $this->copyFiles($child, $copiedDirectory);
            }
        }
    }
    
    private function copyFiles($file, $parentDirectory){
        $sourcePath = public_path($file->src);
        $destinationPath = public_path($parentDirectory->src . '/' . $file->name);
    
        if(FileFacades::exists($sourcePath)){
            FileFacades::copy($sourcePath, $destinationPath);

        }
                File::create([
            'parent_id' => $parentDirectory->id,
            'type' => 'file',
            'name' => $file->name,
            'src' => $parentDirectory->src . '/' . $file->name,
        ]);
    }
    
       
        public function delete(Request $request){
            $validated = $request->validate([
            'file_id'=> 'required',
        ]);
        $file=File::where("id",$request->file_id)->first();
        if($file->type == 'directory'){
            $sourcePath = public_path($file->src);

            if(FileFacades::exists($sourcePath)){
                 FileFacades::deleteDirectory($sourcePath);
             }else{
                 return response()->json(['success' => false, 'message' => 'this file not exist','data'=>null], 200);
        
             }
             $file->children()->delete();
             $file->delete();
        //  

        }
        
        if($file->type == 'file'){
            $sourcePath = public_path($file->src);

            if(FileFacades::exists($sourcePath)){
                 FileFacades::delete($sourcePath);
             }else{
                 return response()->json(['success' => false, 'message' => 'this file not exist','data'=>null], 200);
        
             }
             $file->delete();
        }
           
   
      
        return response()->json(['success' => true, 'message' => 'delete success','data'=>null], 200);

        }


        public function newDirectory(Request $request){
            // dd(8);  
            $validated = $request->validate([
             'parent_id'=> 'required',
             'name'=> 'required',

            ]);
        $file=File::where("id",$request->parent_id)->first();
          
     if(!$file){
        return response()->json(['success' => false, 'message' => 'not exist directory','data'=>null], 200);

     }
     if($file->type == 'file'){
        return response()->json(['success' => false, 'message' => 'not directory','data'=>null], 200);

     }
     $src=$file->src.'/'.$request->name;
    //  dd($src);  
    //  $src='/storage/rrrrrrr';
     $src = Str::replaceFirst('/', '', $src);

     FileFacades::makeDirectory($src, 0755, true);
         File::create([
            'parent_id'=>$request['parent_id'],
            'type'=>'directory',
            'src'=>$file->src.'/'.$request->name,
            'name'=>$request->name,

    
         ]);
         return response()->json(['success' => true, 'message' => 'copy success','data'=>null], 200);
    
            }

    public function rename(Request $request){
        $validated = $request->validate([
            'file_id'=> 'required',
            'name'=> 'required',
        ]);
        $file=File::where("id",$request->file_id)->first();
        // dd($file->type);

        if($file->type == 'directory'){
            $src = Str::replaceFirst($file->name, $request->name, $file->src);
            $sourcePath = public_path($file->src);
            // dd($src);
    
            if(FileFacades::exists($sourcePath)){
                $destinationPath=public_path($src);
                FileFacades::moveDirectory($sourcePath, $destinationPath);
            }else{
                return response()->json(['success' => false, 'message' => 'this file not exist','data'=>null], 200);
    
         };
    
                $file=$file->update([
                    'type'=>'directory',
                    'name'=>$request->name,
                    'src'=>$src,
                ]);
          
                foreach ($file->children as $child) {
                    if ($child->type == 'directory') {
                        $this->renameDirectory($child, $file);
                    } else {
                        // Copy files
                        $this->renameFilesDirectory($child, $file);
                    }
                }
            }
                    if($file->type == 'file'){

                        $src = Str::replaceFirst($file->name, $request->name, $file->src);
                        $format=pathinfo($file->name, PATHINFO_EXTENSION);
                        $src =$src.'.'.$format;
                        $sourcePath = public_path($file->src);
                
                        if(FileFacades::exists($sourcePath)){
                            $destinationPath=public_path($src);
                            FileFacades::move($sourcePath, $destinationPath);
                        }else{
                            return response()->json(['success' => false, 'message' => 'this file not exist','data'=>null], 200);
                
                    }
                
                    $file->update([
                        'type'=>'file',
                        'name'=>$request->name.'.'.$format,
                        'src'=>$src,
                
                    ]);

                    }
        return response()->json(['success' => true, 'message' => 'copy success','data'=>null], 200);
        
        }
        public function renameDirectory($directory, $parentDirectory){
            // Create entry for the cut directory in the database
            $cutDirectory =$directory->update([
                'parent_id' => $parentDirectory->id,
                'type' => 'directory',
                'name' => $directory->name,
                'src' => $parentDirectory->src . '/' . $directory->name,
            ]);
        
            // Copy all files and subdirectories inside the cut directory
            foreach ($directory->children as $child) {
                if ($child->type == 'directory') {
                    $this->renameDirectory($child, $directory);
                } else {
                    // Copy files
                    // dd($cutDirectory);
    
                    $this->renameFilesDirectory($child, $directory);
                }
            }
        }
        private function renameFilesDirectory($file, $parentDirectory){
                    $file->update([
                'parent_id' => $parentDirectory->id,
                'type' => 'file',
                'name' => $file->name,
                'src' => $parentDirectory->src . '/' . $file->name,
            ]);
        }
         public function upload(Request $request){
        $validated = $request->validate([
            'file'=> 'required',
            'parent_id'=> 'required',
        ]);
        $file=File::where("id",$request->parent_id)->first();
        if($file->type == 'file'){
            return response()->json(['success' => false, 'message' => 'this not directory','data'=>null], 200);

        }
        $src = Str::replaceFirst('/storage', '', $file->src);

        if($request->file('file')) {
            $file=$request->file('file');   
                $document = new File;

                $file_name = time().$file->getClientOriginalName();
                $file_path = $file->storeAs($src, $file_name, 'public');
                $path='storage/'.$file_path;
                $document->src=$path;
                $document->name=$file_name;
                $document->parent_id=$request->parent_id;
                $document->type='file';
                $document->format=$file->getClientOriginalName();


                $document->save();
              
        }
        return response()->json(['success' => true, 'message' => 'upload success','data'=>null], 200);
        
        }
}







