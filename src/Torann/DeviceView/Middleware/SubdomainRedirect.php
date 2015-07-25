<?php namespace Torann\DeviceView\Middleware;

use Closure;
use Illuminate\Contracts\View\Factory;

class SubdomainRedirect {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! $request->ajax())
        {
            $subdomain = $this->getSubdomain($request->getHost());
            //return redirect()->guest('auth/login');
        }

        return $next($request);
    }

    /**
     * Returns the subdomain portion of provided host.
     *
     * @param string $host host
     *
     * @return mixed
     */
    private function getSubdomain($host)
    {
        $registerableDomain = $this->getRegisterableDomain($host);

        if ($registerableDomain === null || $host === $registerableDomain) {
            return;
        }

        $registerableDomainParts = array_reverse(explode('.', $registerableDomain));
        $hostParts = array_reverse(explode('.', $host));
        $subdomainParts = array_slice($hostParts, count($registerableDomainParts));

        return implode('.', array_reverse($subdomainParts));
    }

    /**
     * Returns registerable domain portion of provided host.
     *
     * @param  string $host
     *
     * @return mixed
     */
    private function getRegisterableDomain($host)
    {
        $parts = array_reverse(explode('.', $host));
        $publicSuffix = array_shift($parts);

        if ($publicSuffix === null || $host == $publicSuffix) {
            return;
        }

        $publicSuffixParts = array_reverse(explode('.', $publicSuffix));
        $hostParts = array_reverse(explode('.', $host));
        $registerableDomainParts = $publicSuffixParts + array_slice($hostParts, 0, count($publicSuffixParts) + 1);

        return implode('.', array_reverse($registerableDomainParts));
    }

}
