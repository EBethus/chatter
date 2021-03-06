<?php

namespace DevDojo\Chatter\Events;

use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

class ChatterBeforeNewResponse
{
    /**
     * @var Request
     */
    public $request;


    /**
     * Constructor.
     *
     * @param Request   $request
     * @param Validator $validator
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}
