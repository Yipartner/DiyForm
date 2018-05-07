<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class FormService{

    public function createForm($formCss){
        $formCss['attributes']=json_encode($formCss['attributes']);
        DB::table('forms')->insert($formCss);
    }

    public function isUrlAvailable(string $url){
        $res=DB::table('forms')->where([
            ['status','<',3],
            ['url','=',$url]
        ])->first();
        if (!$res){
            return true;
        }
        return false;
    }

    public function updateForm($formCss){
        $formCss['attributes']=json_encode($formCss['attributes']);
        DB::table('tables')->where('id',$formCss['id'])->update($formCss);
    }

    public function changeStatusZero($formId){
        $num=DB::table('tables')->where([
            ['id','=',$formId],
            ['status','<',3]
        ])->update([
            'status'=>0
        ]);
        return $num;
    }

    public function changeStatusOne($formId){
        $num=DB::table('tables')->where([
            ['id','=',$formId],
            ['status','<',3]
        ])->update([
            'status'=>1
        ]);
        return $num;
    }
    public function changeStatusTwo($formId){
        $num=DB::table('tables')->where([
            ['id','=',$formId],
            ['status','<',3]
        ])->update([
            'status'=>2
        ]);
        return $num;
    }
    public function deleteForm(int $formId){
        DB::transaction(function () use ($formId){
            DB::table('tables')->where('id',$formId)->delete();
            DB::table('datad')->where('form_id',$formId)->delete();
        });
    }

    public function softDeleteForm(int $formId){
        DB::table('tables')->where('id',$formId)->update([
            'status'=>3
        ]);
    }

    public function getFormByUrl(string $url){
        return DB::table('tables')->where([
            ['url','=',$url],
            ['status','<',3]
        ])->first();
    }
    public function isFormHasData(int $formId){
        $num=DB::table('datas')->where('form_id',$formId)->count();
        if ($num>0){
            return true;
        }
        return false;
    }

}