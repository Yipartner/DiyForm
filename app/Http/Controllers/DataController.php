<?php

namespace App\Http\Controllers;

use App\Services\DataService;
use App\Tool\ValidationHelper;
use Illuminate\Http\Request;

class DataController extends Controller
{
    private $dataService;
    public function __construct(DataService $dataService)
    {
        $this->dataService = $dataService;
    }

    public function postData($formId,Request $request)
    {
        if(!$this->dataService->isFormExist($formId))
            return response()->json([
                'code' => 2001,
                'message' => '表单不存在'
            ]);
        $rules = $this->dataService->getValidatorRules($formId);
        $res = ValidationHelper::validateCheck($request->all(),$rules);
        if($res->fails())
            return response()->json([
                'code' => 2002,
                'message' => $res->errors()
            ]);
        $datas = ValidationHelper::getInputData($request,$rules);
        $this->dataService->createData($formId,$datas);
        return response()->json([
            'code' => 2000,
            'message' => '报名成功'
        ]);
    }

    public function getData($formId,Request $request)
    {
        // todo
        //$request->user->id;
        if(!(isset($formId) && $this->dataService->isFormExist($formId)))
            return response()->json([
                'code' => 2001,
                'message' => '表单不存在'
            ]);
        $datas = $this->dataService->getData($formId);
        return response()->json([
            'code' => 1000,
            'datas' => $datas
        ]);
    }

    public function exportData($formId,Request $request)
    {
        // todo
        //$request->user->id;
        if(!(isset($formId) && $this->dataService->isFormExist($formId)))
            return response()->json([
                'code' => 2001,
                'message' => '表单不存在'
            ]);
        $this->dataService->excelExport($formId);
    }
}
