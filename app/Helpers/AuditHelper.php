<?php

use App\Models\AuditModel;

if (!function_exists('audit_log')) {
    function audit_log($action, $category, $entity_id = null, $extraData = null)
    {
        $description = is_array($extraData) || is_object($extraData)
            ? json_encode($extraData)
            : (string) $extraData;

        $audit = new AuditModel();
		$audit->user_id     = auth()->id();
		$audit->action      = $action;
		$audit->category    = $category;
		$audit->entity_id   = $entity_id;
		$audit->description = $description;
		$audit->ip_address  = request()->ip();
		$audit->user_agent  = null;
		$audit->save();

    }
}
