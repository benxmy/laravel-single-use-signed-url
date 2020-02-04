<?php

namespace Benxmy\SingleUseSignedUrl;

use Closure;
use Illuminate\Routing\Exceptions\InvalidSignatureException;

class ValidateSingleUseSignedUrl
{
    public function handle($request, Closure $next)
    {
        $singleUseSignedUrl = SingleUseSignedUrl::where('key', $request->singleUseKey)
            ->whereNull('reaccessed_at')
            ->get()
            ->first();

        if(!$singleUseSignedUrl) {
            throw new InvalidSignatureException;
        }
        if($request->route()->getName() != $singleUseSignedUrl->route_name) {
            throw new InvalidSignatureException;
        }
        if(get_class($request->user)) {
            if($request->user->id != $singleUseSignedUrl->user_id) {
                throw new InvalidSignatureException;
            }
        } else {
            if($request->user != $singleUseSignedUrl->user_id) {
                throw new InvalidSignatureException;
            }
        }

        if($singleUseSignedUrl->expires_at && now() > $singleUseSignedUrl->expires_at) {
            throw new InvalidSignatureException;
        }

        if(!$singleUseSignedUrl->accessed_at) {
            $singleUseSignedUrl->update([
                'accessed_by_ip' => $request->ip(),
                'accessed_at' => now(),
            ]);
        }
        else {
            $singleUseSignedUrl->update([
                'reaccessed_by_ip' => $request->ip(),
                'reaccessed_at' => now(),
            ]);            
        }

        return $next($request);
    }
}
