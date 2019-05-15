<?php

namespace App\Http\Controllers;

use App\Services\AuditService;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public $audit;

    public function __construct(AuditService $audit)
    {
        $this->audit = $audit;
    }

    public function store()
    {
        return $this->audit->store();
    }
}
