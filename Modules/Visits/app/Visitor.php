<?php

namespace Modules\Visits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Modules\Visits\Contracts\UserAgentParser;
use Modules\Visits\Exceptions\DriverNotFoundException;
use Modules\Visits\Models\Visit;
use Stevebauman\Location\Facades\Location;

class Visitor
{
    protected $except;

    protected $driver;

    protected $driverInstance;

    protected $visitor;

    public function __construct(protected Request $request, protected $config)
    {

        $this->except = $this->config['except'];
        $this->via($this->config['default']);
        $this->setVisitor($this->request->user());
    }

    public function via($driver): static
    {
        $this->driver = $driver;
        $this->validateDriver();

        return $this;
    }

    public function request(): array
    {
        return $this->request->all();
    }

    public function ip(): ?string
    {
        return $this->request->ip();
    }

    public function url(): string
    {
        return $this->request->fullUrl();
    }

    public function referer(): ?string
    {
        return \Illuminate\Support\Facades\Request::server('HTTP_REFERER') ?? null;
    }

    public function method(): string
    {
        return $this->request->getMethod();
    }

    public function httpHeaders(): array
    {
        return $this->request->headers->all();
    }

    public function userAgent(): string
    {
        return $this->request->userAgent() ?? '';
    }

    public function device(): string
    {
        return $this->getDriverInstance()->device();
    }

    public function platform(): string
    {
        return $this->getDriverInstance()->platform();
    }

    public function browser(): string
    {
        return $this->getDriverInstance()->browser();
    }

    public function languages(): array
    {
        return $this->getDriverInstance()->languages();
    }

    public function setVisitor(?Model $model): static
    {
        $this->visitor = $model;

        return $this;
    }

    public function getVisitor(): ?Model
    {
        return $this->visitor;
    }

    public function visit(?Model $model = null)
    {
        foreach ($this->except as $path) {
            if ($this->request->is($path)) {
                return;
            }
        }

        $data = $this->prepareLog();

        // check if there's been a visit from this ip address
        // in the last 5 mins and do not log again
        if (Visit::query()->where('request_ip', $this->ip())
            ->where('url', $this->url())
            ->where('device', $this->device())
            ->where('browser', $this->browser())
            ->where('platform', $this->platform())
            ->where('created_at', '>=', Date::now()->subMinutes($this->config['wait_minutes']))
            ->exists()
        ) {
            return;
        }

        if ($model instanceof Model && method_exists($model, 'visitLogs')) {
            return $model->visitLogs()->create($data);
        }

        return Visit::create($data);
    }

    public function onlineVisitors(string $model, $seconds = 180)
    {
        return resolve($model)->online()->get();
    }

    public function isOnline(?Model $model = null, $seconds = 180)
    {
        $time = now()->subSeconds($seconds);

        $model ??= $this->getVisitor();

        if (! $model instanceof Model) {
            return false;
        }

        return Visit::whereHasMorph('visitor', $model::class, function ($query) use ($model): void {
            $query->where('visitor_id', $model->id);
        })->whereDate('created_at', '>=', $time)->count() > 0;
    }

    protected function prepareLog(): array
    {

        $base_data = [
            'method' => $this->method(),
            'request' => $this->request(),
            'url' => $this->url(),
            'referer' => $this->referer(),
            'languages' => $this->languages(),
            'useragent' => $this->userAgent(),
            'headers' => $this->httpHeaders(),
            'device' => $this->device(),
            'platform' => $this->platform(),
            'browser' => $this->browser(),
            'visitor_id' => $this->getVisitor() instanceof Model ? $this->getVisitor()->id : null,
            'visitor_type' => $this->getVisitor() instanceof Model ? $this->getVisitor()::class : null,
            'request_ip' => $this->ip(),
        ];

        if ($location = Location::get()) {
            return [
                ...$base_data,
                'country_name' => $location->countryName,
                'country_code' => $location->countryCode,
                'region_name' => $location->regionName,
                'region_code' => $location->regionCode,
                'city_name' => $location->cityName,
                'zip_code' => $location->zipCode,
                'latitude' => $location->latitude,
                'longitude' => $location->longitude,
                'timezone' => $location->timezone,
                'location_ip' => $location->ip,
            ];
        }

        return $base_data;
    }

    protected function getDriverInstance()
    {
        if (! empty($this->driverInstance)) {
            return $this->driverInstance;
        }

        return $this->getFreshDriverInstance();
    }

    protected function getFreshDriverInstance()
    {
        $this->validateDriver();

        $driverClass = $this->config['drivers'][$this->driver];

        return resolve($driverClass);
    }

    protected function validateDriver()
    {
        if (empty($this->driver)) {
            throw new DriverNotFoundException('Driver not selected or default driver does not exist.');
        }

        $driverClass = $this->config['drivers'][$this->driver];

        if (empty($driverClass) || ! class_exists($driverClass)) {
            throw new DriverNotFoundException('Driver not found in config file. Try updating the package.');
        }

        $reflectionClass = new \ReflectionClass($driverClass);

        if (! $reflectionClass->implementsInterface(UserAgentParser::class)) {
            throw new \Exception("Driver must be an instance of Contracts\Driver.");
        }
    }
}
