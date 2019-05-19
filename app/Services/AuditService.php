<?php
/**
 * Created by PhpStorm.
 * User: snusnumr
 * Date: 15.05.19
 * Time: 2:04
 */

namespace App\Services;


use App\Http\Resources\Audit\AuditCollection;
use App\Models\Audit;

class AuditService
{
    public function store()
    {
        try {
            $audits = Audit::with('event')->latest('created_at')->paginate(20);
            return response()->json([
                'audits' => new AuditCollection($audits)
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg'   =>  $error->getMessage()
            ]);
        }
    }
}