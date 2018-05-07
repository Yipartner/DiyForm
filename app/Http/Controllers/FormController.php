<?php

namespace App\Http\Controllers;

use App\Services\FormService;
use App\Tool\ValidationHelper;
use Illuminate\Http\Request;

class FormController extends Controller
{
    //
    public $formService;
    public function __construct(FormService $formService)
    {
        $this->formService=$formService;
    }
    public function createForm(Request $request){
        //TODO user
        $rule=[
            'attributes'=>'required',
            'url'=>'required'
        ];
        $res=ValidationHelper::validateCheck($request->all(),$rule);
        if ($res->fails()){
            return response()->json([
                'code' => 1001,
                'message' => $res->errors()
            ]);
        }
        $css=ValidationHelper::getInputData($request,$rule);
        if (!$this->formService->isUrlAvailable($css['url'])){
            return response()->json([
                'code' => 1002,
                'message' => 'url重复'
            ]);
        }
        $css['user_id']=1;
        $css['status']=0;
        $this->formService->createForm($css);
        return response()->json([
            'code' => 1000,
            'message' => '创建成功'
        ]);
    }
    public function updateForm($formId,Request $request){
        //TODO 权限验证
        if ($this->formService->isFormHasData($formId)){
            return response()->json([
                'code' =>1003,
                'message' =>'表单已有数据，不可临时修改,如果想'
            ]);
        }
        $rule=['attributes'=>'required'];
        $res=ValidationHelper::validateCheck($request->all(),$rule);
        if ($res->fails()){
            return response()->json([
                'code' =>1001,
                'message' =>$res->errors()
            ]);
        }
        $css=ValidationHelper::getInputData($request,$rule);
        $this->formService->updateForm($css);
        return response()->json([
            'code' =>1000,
            'message' => '更新成功'
        ]);
    }
    public function changeStatus($formId,$status){
        //TODO 权限
        $res=0;
        switch ($status){
            case 0:
                $res=$this->formService->changeStatusZero($formId);
                break;
            case 1:
                $res=$this->formService->changeStatusOne($formId);
                break;
            case 2:
                $res=$this->formService->changeStatusTwo($formId);
                break;
        }
        if (!$res){
            return response()->json([
                'code' => 1004,
                'message' => '已废弃的表单收集不可重启'
            ]);
        }
        return response()->json([
            'code'=> 1000,
            'message' => '修改成功'
        ]);

    }
    public function trueDeleteForm($formId){
        //TODO 权限
        $this->formService->deleteForm($formId);
        return response()->json([
            'code' =>1000,
            'message' => '删除成功'
        ]);
    }
    public function softDeleteForm($formId){
        $this->formService->softDeleteForm($formId);
        return response()->json([
            'code' => 1000,
            'message' => '弃用表单成功'
        ]);
    }
    public function getFormByUrl($url){
        //如果url有误，form为空
        $form=$this->formService->getFormByUrl($url);
        if ($form){
            $form->attributes=json_decode($form->attributes);
        }
        return response()->json([
            'code' =>1000,
            'form' => $form
        ]);
    }
}
