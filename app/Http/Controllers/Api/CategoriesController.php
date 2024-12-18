<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiHandler;
use Illuminate\Http\Request;
use App\Models\Category;
use Validator;

class CategoriesController extends Controller
{
    //


    use ApiHandler;

    public function createCat(Request $request) {


        $rules = [
            "name_ar" => "required",

            "name_en" => "required",
            "name_sp" => "required",

        ];


        $validator = Validator::make($request->all(), $rules);


        if($validator->fails()) {

            $code = $this->returnCodeAccordingToInput($validator);

            return $this->returnValidationError($validator,$code);
    
        }

        $cate = Category::create([

            "name_ar" => $request->name_ar,
            "name_en" => $request->name_en,
            "name_sp" => $request->name_sp,
        ]);


        if(!$cate) {

            return $this->returnError(404, "error creating cats");
            // $code = $this->returnCodeAccordingToInput($validator);

            // return $this->returnValidationError($code, $validator);
        } else {
            return $this->returnData("data", $cate, "success");
        }
    }


    public function getAllCats(Request $request) {

        $allCats = Category::select( 'name_' . app()->getlocale())->get();

        if(!$allCats) {
            return $this->returnError(404, "error getting cats");

        } else {
            return response()->json([
                "msg" => "success",
                "data" => $allCats
            
            
            ]);

        }


    }


    public function updateCat(Request $request) {

        $cat = Category::find( $request->id);

        

        if(!$cat) {
            return response()->json(["msg" => "error"]);

        } else {

            $cat->update([
                "name_ar" =>  $request->name_ar,
                "name_en" =>  $request->name_en,
            ]);
            return response()->json([
                "msg" => "success",
                "data" => $cat
            
            
            ]);

        }


    }



    public function deleteCat(Request $request) {

        $cat = Category::find( $request->id);

        

        if(!$cat) {
            return response()->json(["msg" => "error"]);

        } else {

            $cat->delete();
            return response()->json([
                "msg" => "success, deleted",
                
            
            
            ]);

        }


    }

    public function getSingleCat(Request $request) {

        if(Category::find($request->id)) {
            $cat = Category::find( $request->id)->select("name_" . app()->getLocale())->first();

        } else {
            return $this->returnError(404, "cat not found");

        }
        
        

        if(!$cat) {
            return response()->json(["msg" => "error"]);

        } else {

           
            return response()->json([
                "msg" => "success, deleted",
                "data" => $cat
            
            
            ]);

        }


    }



    
}
