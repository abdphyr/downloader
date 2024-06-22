<?php

namespace App\Http\Requests;


class FileRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'type' => ['nullable'],
            'files' => ['nullable', 'array'],
            'files.*' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,ppt,pptx,mp4,avi,mov,mpg,mpeg,flv,mp3,wav,ogg,aac,flac,wma,m4a',
                'max:4096'
            ],
            'file' => [
                'nullable',
                // 'sometimes',
                // 'required',
                'file',
                'mimes:jpg,jpeg,png,gif,svg,pdf,doc,docx,xls,xlsx,ppt,pptx,mp4,avi,mov,mpg,mpeg,flv,mp3,wav,ogg,aac,flac,wma,m4a',
            ],
        ];
    }
}
