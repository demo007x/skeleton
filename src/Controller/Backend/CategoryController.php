<?php

declare(strict_types=1);
namespace Bclfp\Skeleton\Controller\Backend;

use Bclfp\Skeleton\Controller\AbstractController;
use App\Model\Category;
use Bclfp\Skeleton\Util\ExceptionCode;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;
use Hyperf\Validation\Rule;
use Bclfp\Skeleton\Middleware\AdminAuthMiddleware;
use Bclfp\Skeleton\Middleware\PermissionMiddleware;

/**
 * Class CategoryController
 * @Controller(prefix="/backend/category")
 * @Middlewares({
 *    @Middleware(AdminAuthMiddleware::class),
 *    @Middleware(PermissionMiddleware::class),
 * })
 * @package App\Controller\Admin
 */
class CategoryController extends AbstractController
{

    /**
     * @Inject()
     * @var ValidatorFactoryInterface
     */
    protected $validationFactory;

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @GetMapping(path="/backend/categories")
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function list(RequestInterface $request, ResponseInterface $response)
    {
        try {
            $categories = Category::query()->orderByDesc('sort')->get()->toArray();
            $categories = trees($categories);

            return successResponse($categories);
        } catch (\Throwable $exception) {
            return errorResponse($exception);
        }
    }

    /**
     * 创建分类
     * @PostMapping(path="")
     * @param RequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function create(RequestInterface $request)
    {
        try {
            $data = $request->all();
            $validate = $this->validationFactory->make(
                $data,
                [
                    'title' => [
                        'required',
                        Rule::unique('categories', 'title')->ignore($request->post('id'))
                    ],
                ],
                [
                    'title.required' => '标题不能为空',
                    'title.unique'   => '此名称的分类已存在'
                ]
            );
            if ($validate->fails()) {
                throw new \Exception($validate->getMessageBag()->first());
            }
            $category = Category::create($data);

            return successResponse($category);
        } catch (\Throwable $exception) {
            return errorResponse($exception);
        }
    }

    /**
     * @param RequestInterface $request
     * @param int $id
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Exception
     * @PutMapping(path="{id}")
     */
    public function update(RequestInterface $request, int $id)
    {
        try {
            $data = $request->all();
            $validate = $this->validationFactory->make(
                $data,
                [
                    'title' => [
                        'required',
                        Rule::unique('categories', 'title')->ignore($id)
                    ],
                ],
                [
                    'title.required' => '标题不能为空',
                    'title.unique'   => '此名称的分类已存在'
                ]
            );
            if ($validate->fails()) {
                throw new \Exception($validate->getMessageBag()->first());
            }
            $category = Category::query()->find($id);
            if (!$category) {
                throw new \Exception('未找到此分类');
            }
            $category->save($data);
            return successResponse($category);
        } catch (\Throwable $exception) {
            return errorResponse($exception);
        }
    }

    /**
     * 删除分类
     *
     * @DeleteMapping(path="{id}")
     * @param int $id
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function destroy(int $id)
    {
        try {
            $category = Category::query()->find($id);
            if (!$category) {
                throw new \Exception(ExceptionCode::HTTP['NOT_FOUND_HTTP']['message'], ExceptionCode::HTTP['NOT_FOUND_HTTP']['code']);
            }
            $result = $category->delete();
            return successResponse($result);
        } catch (\Throwable $exception) {
            return errorResponse($exception);
        }
    }

    /**
     * 获取详情
     * @GetMapping(path="{id}")
     * @param int $id
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function detail(int $id)
    {
        try {
            $category = Category::query()->find($id);
            if (!$category) {
                throw new \Exception(ExceptionCode::HTTP['NOT_FOUND_HTTP']['message'], ExceptionCode::HTTP['NOT_FOUND_HTTP']['code']);
            }
            return successResponse($category);
        } catch (\Throwable $exception) {
            return errorResponse($exception);
        }
    }
}
