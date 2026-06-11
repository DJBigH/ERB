<?php

namespace Modules\Warehouse\Services;

use Modules\Warehouse\Entities\AuditLog;

/**
 * Ghi nhật ký thao tác người dùng.
 *
 * MVP: ghi vào bảng audit_logs cục bộ. Theo BA v3 (Epic K), về sau Module Kho sẽ
 * KHÔNG lưu audit cục bộ mà gọi API "Audit Service" ngoài — chỉ cần thay phần thân
 * record() bằng HTTP client tới Audit Service, giao diện gọi giữ nguyên.
 */
class AuditLogger
{
    public static function record(
        string $action,
        string $objectType,
        ?int $objectId,
        ?int $userId,
        ?string $ip = null,
        array $detail = []
    ): void {
        AuditLog::create([
            'user_id'     => $userId,
            'action'      => $action,
            'object_type' => $objectType,
            'object_id'   => $objectId,
            'detail'      => $detail ?: null,
            'ip_address'  => $ip,
            'created_at'  => now(),
        ]);
    }
}
