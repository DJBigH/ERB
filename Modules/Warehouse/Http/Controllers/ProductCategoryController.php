<?php

namespace Modules\Warehouse\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Warehouse\Entities\ProductCategory;

class ProductCategoryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/product-categories",
     *     tags={"Product Categories"},
     *     summary="Lấy danh sách nhóm sản phẩm",
     *     description="Danh sách nhóm sản phẩm (cây danh mục). Dùng tree=1 để trả về dạng cây lồng nhau.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="search", in="query", required=false, description="Tìm theo mã/tên", @OA\Schema(type="string")),
     *     @OA\Parameter(name="parent_id", in="query", required=false, description="Lọc theo danh mục cha", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="status", in="query", required=false, @OA\Schema(type="integer", enum={0,1})),
     *     @OA\Parameter(name="tree", in="query", required=false, description="1: trả về dạng cây lồng nhau", @OA\Schema(type="integer", enum={0,1})),
     *     @OA\Response(response=200, description="Lấy danh sách thành công"),
     *     @OA\Response(response=401, description="Chưa xác thực tài khoản")
     * )
     */
    public function index(Request $request)
    {
        if ($request->boolean('tree')) {
            $all = ProductCategory::orderBy('sort_order')->orderBy('id')->get();
            $tree = $this->buildTree($all, null);

            return response()->json(['status' => 'success', 'data' => $tree]);
        }

        $query = ProductCategory::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }
        if ($request->filled('parent_id')) {
            $query->where('parent_id', $request->parent_id);
        }
        if ($request->has('status') && $request->status !== null && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $categories = $query->orderBy('sort_order')->orderBy('id')->paginate($request->get('limit', 50));

        return response()->json(['status' => 'success', 'data' => $categories]);
    }

    /** Dựng cây danh mục lồng nhau từ collection phẳng. */
    private function buildTree($all, $parentId)
    {
        return $all->where('parent_id', $parentId)->values()->map(function ($node) use ($all) {
            $node->children = $this->buildTree($all, $node->id);
            return $node;
        });
    }

    /**
     * @OA\Post(
     *     path="/product-categories",
     *     tags={"Product Categories"},
     *     summary="Tạo nhóm sản phẩm",
     *     description="depth được tính tự động theo danh mục cha. parent_id null = danh mục gốc.",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"code","name"},
     *             @OA\Property(property="code", type="string", example="DM001"),
     *             @OA\Property(property="name", type="string", example="Điện tử"),
     *             @OA\Property(property="parent_id", type="integer", nullable=true, example=null),
     *             @OA\Property(property="sort_order", type="integer", example=0),
     *             @OA\Property(property="status", type="integer", enum={0,1}, example=1)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Tạo thành công"),
     *     @OA\Response(response=400, description="Dữ liệu không hợp lệ"),
     *     @OA\Response(response=401, description="Chưa xác thực tài khoản")
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code'       => 'required|string|max:50|unique:product_categories,code',
            'name'       => 'required|string|max:255',
            'parent_id'  => 'nullable|integer|exists:product_categories,id',
            'sort_order' => 'nullable|integer',
            'status'     => 'nullable|integer|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Dữ liệu đầu vào không hợp lệ',
                'errors'  => $validator->errors(),
            ], 400);
        }

        $data = $validator->validated();
        $data['depth'] = $this->resolveDepth($data['parent_id'] ?? null);
        $data['sort_order'] = $request->get('sort_order', 0);
        $data['status'] = $request->get('status', 1);

        $category = ProductCategory::create($data);

        return response()->json([
            'status'  => 'success',
            'message' => 'Tạo nhóm sản phẩm thành công',
            'data'    => $category,
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/product-categories/{id}",
     *     tags={"Product Categories"},
     *     summary="Xem chi tiết nhóm sản phẩm",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Thành công"),
     *     @OA\Response(response=404, description="Không tìm thấy nhóm sản phẩm")
     * )
     */
    public function show($id)
    {
        $category = ProductCategory::with(['parent', 'children'])->find($id);

        if (!$category) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy nhóm sản phẩm'], 404);
        }

        return response()->json(['status' => 'success', 'data' => $category]);
    }

    /**
     * @OA\Put(
     *     path="/product-categories/{id}",
     *     tags={"Product Categories"},
     *     summary="Cập nhật nhóm sản phẩm",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="string", example="DM001"),
     *             @OA\Property(property="name", type="string", example="Điện tử (mới)"),
     *             @OA\Property(property="parent_id", type="integer", nullable=true),
     *             @OA\Property(property="sort_order", type="integer", example=1),
     *             @OA\Property(property="status", type="integer", enum={0,1}, example=1)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Cập nhật thành công"),
     *     @OA\Response(response=400, description="Dữ liệu không hợp lệ"),
     *     @OA\Response(response=404, description="Không tìm thấy nhóm sản phẩm"),
     *     @OA\Response(response=409, description="parent_id không hợp lệ (vòng lặp)")
     * )
     */
    public function update(Request $request, $id)
    {
        $category = ProductCategory::find($id);

        if (!$category) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy nhóm sản phẩm'], 404);
        }

        $validator = Validator::make($request->all(), [
            'code'       => 'sometimes|required|string|max:50|unique:product_categories,code,' . $id,
            'name'       => 'sometimes|required|string|max:255',
            'parent_id'  => 'nullable|integer|exists:product_categories,id',
            'sort_order' => 'nullable|integer',
            'status'     => 'sometimes|required|integer|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Dữ liệu chỉnh sửa không hợp lệ',
                'errors'  => $validator->errors(),
            ], 400);
        }

        $data = $validator->validated();

        if (array_key_exists('parent_id', $data)) {
            if ((int) $data['parent_id'] === (int) $id) {
                return response()->json(['status' => 'error', 'message' => 'Danh mục không thể là cha của chính nó'], 409);
            }
            if ($this->wouldCreateCycle($id, $data['parent_id'])) {
                return response()->json(['status' => 'error', 'message' => 'Không thể chọn danh mục con làm danh mục cha (vòng lặp)'], 409);
            }
            $data['depth'] = $this->resolveDepth($data['parent_id']);
        }

        $category->update($data);

        return response()->json([
            'status'  => 'success',
            'message' => 'Cập nhật nhóm sản phẩm thành công',
            'data'    => $category,
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/product-categories/{id}",
     *     tags={"Product Categories"},
     *     summary="Xóa nhóm sản phẩm",
     *     description="Chỉ xóa được khi không còn danh mục con.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Xóa thành công"),
     *     @OA\Response(response=404, description="Không tìm thấy nhóm sản phẩm"),
     *     @OA\Response(response=409, description="Danh mục đang có danh mục con")
     * )
     */
    public function destroy($id)
    {
        $category = ProductCategory::withCount('children')->find($id);

        if (!$category) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy nhóm sản phẩm'], 404);
        }

        if ($category->children_count > 0) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Không thể xóa nhóm sản phẩm đang có danh mục con',
            ], 409);
        }

        $category->delete();

        return response()->json(['status' => 'success', 'message' => 'Xóa nhóm sản phẩm thành công']);
    }

    /** Tính depth dựa vào danh mục cha (gốc = 0). */
    private function resolveDepth($parentId): int
    {
        if (!$parentId) {
            return 0;
        }
        $parent = ProductCategory::find($parentId);

        return $parent ? (int) $parent->depth + 1 : 0;
    }

    /** Kiểm tra việc gán parentId cho id có tạo vòng lặp không. */
    private function wouldCreateCycle($id, $parentId): bool
    {
        $cursor = $parentId;
        while ($cursor) {
            if ((int) $cursor === (int) $id) {
                return true;
            }
            $parent = ProductCategory::find($cursor);
            $cursor = $parent?->parent_id;
        }

        return false;
    }
}
