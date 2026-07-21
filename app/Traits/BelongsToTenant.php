<?php

namespace App\Traits;

use App\Models\Scopes\TenantScope;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant(): void
    {
        static::addGlobalScope(new TenantScope);

        static::creating(function ($model) {
            if (! $model->tenant_id) {
                $tenantId = null;

                if (auth()->check()) {
                    $user = auth()->user();
                    $tenantId = $user->current_tenant_id;

                    if (! $tenantId) {
                        $defaultTenant = Tenant::first();
                        if ($defaultTenant) {
                            $tenantId = $defaultTenant->id;
                            try {
                                $user->forceFill(['current_tenant_id' => $tenantId])->save();
                            } catch (\Throwable $e) {
                                // Ignore save errors during quiet assignment
                            }
                        }
                    }
                }

                if (! $tenantId) {
                    $tenantId = Tenant::first()?->id;
                }

                $model->tenant_id = $tenantId;
            }
        });
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
