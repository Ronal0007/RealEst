<?php

namespace App\Http\Requests;

use App\Plotuse;
use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $data = [];
        $dataValidator = [
            'name'=>'required',
            'status_id'=>'required|min:1',
            'acqfactor'=>'required|min:1',
            'dmcfactor'=>'required'
        ];

        $plotuses = Plotuse::all();

        foreach ($plotuses as $plotuse){
            $name = str_replace('/','_',$plotuse->name);
            $name = str_replace(' ','_',$name);
            $data[$name] = $plotuse->id;
        }

        foreach ($data as $key=>$value){
            $dataValidator[$key.'_amount'] = 'required|min:0';
            $dataValidator[$key.'_rate'] = 'required|min:1|max:99';
        }

        return $dataValidator;
    }
}
