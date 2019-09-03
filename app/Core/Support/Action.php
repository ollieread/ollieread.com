<?php

namespace Ollieread\Core\Support;

use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection as ResourceCollection;
use League\Fractal\Resource\Item as ResourceItem;

abstract class Action
{
    private $responseFactory;

    protected function back(): RedirectResponse
    {
        return $this->response()->redirectTo($this->url()->previous());
    }

    protected function handleRedirectAfter(Request $request)
    {
        if (! $request->session()->has('url.intended')) {
            $redirect = $request->query('redirect_to') ?? $this->url()->previous();

            if ($redirect) {
                $request->session()->put('url.intended', $redirect);
            }
        }
    }

    /**
     * @return \Illuminate\Routing\ResponseFactory|mixed
     */
    protected function response(): ResponseFactory
    {
        if (! ($this->responseFactory instanceof ResponseFactory)) {
            try {
                $this->responseFactory = Container::getInstance()->make(ResponseFactory::class);
            } catch (BindingResolutionException $e) {
            }
        }

        return $this->responseFactory;
    }

    /**
     * @param        $data
     * @param string $transformer
     * @param array  $meta
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function transform($data, string $transformer, array $meta = []): JsonResponse
    {
        $manager = Container::getInstance()->make(Manager::class);
        $manager->parseIncludes(request()->query('include', ''));
        $manager->parseExcludes(request()->query('exclude', ''));

        $transformer = new $transformer;
        $manager->getSerializer()->meta($meta);

        if ($data instanceof Collection) {
            $resource = new ResourceCollection($data, $transformer);

            return $this->response()->json($manager->createData($resource)->toArray());
        }

        $resource = new ResourceItem($data, $transformer);

        return $this->response()->json($manager->createData($resource)->toArray());
    }

    /**
     * @return \Illuminate\Routing\UrlGenerator|mixed
     */
    protected function url(): UrlGenerator
    {
        try {
            return Container::getInstance()->make(UrlGenerator::class);
        } catch (BindingResolutionException $e) {
        }
    }
}