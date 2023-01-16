<?php
namespace DevDojo\Chatter\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

use App\Event;
use App\Ticket;
use Log;
use App\EventOption;
use DB;
use SpamScore;

use DevDojo\Chatter\Requests\PostRequest;

class DiscussionRequest extends FormRequest
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

    public function withValidator($validator)
    {
        $req = $this;
        $validator->after(function ($validator) use ($req) {
            $score = SpamScore::score("{$req->title}");
            if ($score > 99) {
                 return $validator
                    ->errors()
                    ->add('token', __('Hemos detectado que este post puediera ser spam, si no lo fuera por favor contáctanos'));
            }
        });
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'title'               => 'required|min:5|max:255',
            'body'                => 'required|min:10',
            'chatter_category_id' => 'required',
            'whitebrand_id'       => '',
            
        ];
        if (\Config::get('chatter.security.captcha')) {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'title.required' =>  trans('chatter::alert.danger.reason.title_required'),
            'title.min'     => [
                'string'  => trans('chatter::alert.danger.reason.title_min'),
            ],
            'title.max' => [
                'string'  => trans('chatter::alert.danger.reason.title_max'),
            ],
            'chatter_category_id.required' => trans('chatter::alert.danger.reason.category_required'),
        ];
    }
}
