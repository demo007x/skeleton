<?php

declare(strict_types=1);

namespace Bclfp\Skeleton\Controller\Backend;

use Bclfp\Skeleton\Model\Carousel;
use Bclfp\Skeleton\Request\CarouselRequest;
use Bclfp\Skeleton\Controller\AbstractController;
use Bclfp\Skeleton\Util\ExceptionCode;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;



//  @Controller(prefix="/backend/carousel")
/**
 * 轮播控制器
 * Class CarouselController
 * @package App\Controller\Admin
 *
 */
class CarouselController extends AbstractController
{

    /**
     * @Inject()
     * @var ValidatorFactoryInterface
     */
    protected $validationFactory;

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @GetMapping(path="/backend/carousels")
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function carousels(RequestInterface $request, ResponseInterface $response)
    {
        try {
            $carousels = Carousel::query()->orderBy('sort')->get();
            return successResponse($carousels);
        } catch (\Throwable $exception) {
            return errorResponse($exception);
        }
    }

    /**
     * @param CarouselRequest $request
     * @return \Psr\Http\Message\ResponseInterface
     * @PostMapping(path="")
     */
    public function create(CarouselRequest $request)
    {
        try {
            $data = $request->all();
            $validator = $this->validationFactory->make(
                $data,
                [
                    'img_path' => 'required',
                    'url' => 'required'
                ],
                [
                    'img_path.*' => '图片地址不能为空',
                    'url' => '图片链接不能为空'
                ]
            );
            if ($validator->fails()) {
                throw new \Exception($validator->getMessageBag()->first());
            }

            $carousel = Carousel::create($data);

            return successResponse($carousel);
        } catch (\Throwable $exception) {
            return errorResponse($exception);
        }
    }

    /**
     * @param CarouselRequest $request
     * @param int $id
     * @return \Psr\Http\Message\ResponseInterface
     * @PutMapping(path="{id}")
     */
    public function update(CarouselRequest $request, int $id)
    {
        try {
            $data = $request->all();
            $carousel = Carousel::query()->find($id);
            if (!$carousel) {
                throw new \Exception(ExceptionCode::HTTP['NOT_FOUND_HTTP']['message'], ExceptionCode::HTTP['NOT_FOUND_HTTP']['code']);
            }
            $carousel->fill($data)->save();

            return successResponse($carousel);
        } catch (\Throwable $exception) {
            return errorResponse($exception);
        }
    }

    /**
     * @param RequestInterface $request
     * @param int $id
     * @DeleteMapping(path="{id}")
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function destroy(RequestInterface $request, int $id)
    {
        try {
            $carousel = Carousel::query()->find($id);
            if (!$carousel) {
                throw new \Exception(ExceptionCode::HTTP['NOT_FOUND_HTTP']['message'], ExceptionCode::HTTP['NOT_FOUND_HTTP']['code']);
            }

            return successResponse($carousel->delete());
        } catch (\Throwable $exception) {
            return errorResponse($exception);
        }
    }

}
