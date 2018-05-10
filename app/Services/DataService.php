<?php
/**
 * Created by PhpStorm.
 * User: yz
 * Date: 18/5/7
 * Time: 下午11:47
 */

namespace App\Services;


use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Excel;


class DataService
{
    public function isFormExist(int $formId): bool
    {
        $form = DB::table('forms')->where('id', $formId)->first();
        if ($form)
            return true;
        else
            return false;
    }

    public function getForm(int $formId)
    {
        $attributes = DB::table('forms')->where('id', $formId)->value('attributes');
        return json_decode($attributes);
    }

    public function getValidatorRules(int $formId): array
    {
        $formData = $this->getForm($formId);
        $rules = [];
        foreach ($formData as $key => $value) {
            $rule = '';
            if (isset($value->required) && $value->required == 1) {
                $rule .= 'required|';
            }
            if (isset($value->min)) {
                $rule .= ('min:' . $value->min . '|');

            }
            if (isset($value->max)) {
                $rule .= ('max:' . $value->max . '|');
            }
            if ($rule != '')
                $rule = substr($rule, 0, -1);
            $rules[(string)$key] = $rule;
        }
        return $rules;
    }

    public function createData(int $formId, $body)
    {
        $data = [
            'form_id' => $formId,
            'data' => json_encode($body),
            'created_at' => new Carbon()
        ];
        DB::table('datas')->insert($data);
    }

    public function getData(int $formId)
    {
        $formData = $this->getForm($formId);
        $sourceData = [];
        foreach ($formData as $key => $value) {
            $sourceData[$key] = $value->name;
        }
        $datas = DB::table('datas')->where('form_id', $formId)->select('data')->get();
        $resData = [];
        foreach ($datas as $data) {
            $rowData = [];
            $data = json_decode($data->data);
            foreach ($data as $key => $value) {
                $rowData[$sourceData[$key]] = $value;
            }
            $resData[] = $rowData;
        }
        return $resData;
    }

    public function excelExport($formId)
    {
        $datas = DB::table('datas')->where('form_id', $formId)->select('data')->get();
        $formData = $this->getForm($formId);
        $title = [];
        $sourceData = [];
        foreach ($formData as $key => $value) {
            $title[] = $value->name;
            $sourceData[$key] =$value->name;
        }
        $tableData[] = $title;

        foreach ($datas as $data) {
            $rowdata = [];
            $data = json_decode($data->data);
            foreach ($data as $key => $value) {
                $rowdata[] = $value;
            }
            $tableData[] = $rowdata;
        }
        Excel::create('组队信息表', function ($excel) use ($tableData) {
            $excel->sheet('teams', function ($sheet) use ($tableData) {
                $sheet->rows($tableData);
            });
        })->export('xls');
    }
}