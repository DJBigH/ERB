<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bổ sung constraint/index còn thiếu cho 9 bảng Module 2 (additive, idempotent).
     *
     * - FK trong InnoDB đã tự tạo index cho cột tham chiếu -> không lặp lại ở đây.
     * - Chỉ thêm index cho các cột LỌC/SẮP XẾP thường dùng nhưng chưa được phủ.
     * - Thêm CHECK constraint bảo toàn số lượng (>= 0 / > 0) theo business rule.
     */
    public function up(): void
    {
        // ---- Index bổ sung (chỉ tạo nếu chưa có) ----
        $this->addIndex('suppliers', ['status'], 'suppliers_status_index');
        $this->addIndex('product_categories', ['status'], 'product_categories_status_index');
        $this->addIndex('safe_stock_configs', ['effective_from', 'effective_to'], 'safe_stock_effective_index');
        $this->addIndex('inventory_reservations', ['reference_type', 'reference_id'], 'inventory_reservations_reference_index');
        $this->addIndex('idempotency_keys', ['status'], 'idempotency_keys_status_index');

        // ---- CHECK constraint bảo toàn số lượng ----
        $this->addCheck('inventory_reservations', 'chk_ir_qty_positive', 'quantity > 0');
        $this->addCheck('safe_stock_configs', 'chk_ssc_min_nonneg', '(min_qty IS NULL OR min_qty >= 0)');
        $this->addCheck('safe_stock_configs', 'chk_ssc_max_nonneg', '(max_qty IS NULL OR max_qty >= 0)');
        $this->addCheck('safe_stock_configs', 'chk_ssc_min_le_max', '(min_qty IS NULL OR max_qty IS NULL OR max_qty >= min_qty)');
        $this->addCheck('in_transit_ledger', 'chk_itl_dispatched_nonneg', 'qty_dispatched >= 0');
        $this->addCheck('in_transit_ledger', 'chk_itl_received_nonneg', 'qty_received >= 0');
        $this->addCheck('in_transit_ledger', 'chk_itl_returned_nonneg', 'qty_returned >= 0');
        $this->addCheck('inventory_report_snapshots', 'chk_irs_available_nonneg', 'available_qty >= 0');
        $this->addCheck('inventory_report_snapshots', 'chk_irs_in_transit_nonneg', 'in_transit_qty >= 0');
        $this->addCheck('inventory_report_snapshots', 'chk_irs_reserved_nonneg', 'reserved_qty >= 0');
    }

    public function down(): void
    {
        $this->dropIndex('suppliers', 'suppliers_status_index');
        $this->dropIndex('product_categories', 'product_categories_status_index');
        $this->dropIndex('safe_stock_configs', 'safe_stock_effective_index');
        $this->dropIndex('inventory_reservations', 'inventory_reservations_reference_index');
        $this->dropIndex('idempotency_keys', 'idempotency_keys_status_index');

        $this->dropCheck('inventory_reservations', 'chk_ir_qty_positive');
        $this->dropCheck('safe_stock_configs', 'chk_ssc_min_nonneg');
        $this->dropCheck('safe_stock_configs', 'chk_ssc_max_nonneg');
        $this->dropCheck('safe_stock_configs', 'chk_ssc_min_le_max');
        $this->dropCheck('in_transit_ledger', 'chk_itl_dispatched_nonneg');
        $this->dropCheck('in_transit_ledger', 'chk_itl_received_nonneg');
        $this->dropCheck('in_transit_ledger', 'chk_itl_returned_nonneg');
        $this->dropCheck('inventory_report_snapshots', 'chk_irs_available_nonneg');
        $this->dropCheck('inventory_report_snapshots', 'chk_irs_in_transit_nonneg');
        $this->dropCheck('inventory_report_snapshots', 'chk_irs_reserved_nonneg');
    }

    /** Tạo index nếu bảng/cột tồn tại và index chưa có. */
    private function addIndex(string $table, array $columns, string $name): void
    {
        if (!Schema::hasTable($table) || !Schema::hasColumns($table, $columns)) {
            return;
        }
        if ($this->indexExists($table, $name)) {
            return;
        }
        $cols = implode(', ', array_map(fn ($c) => "`{$c}`", $columns));
        DB::statement("CREATE INDEX `{$name}` ON `{$table}` ({$cols})");
    }

    private function dropIndex(string $table, string $name): void
    {
        if (Schema::hasTable($table) && $this->indexExists($table, $name)) {
            DB::statement("DROP INDEX `{$name}` ON `{$table}`");
        }
    }

    /** Thêm CHECK constraint nếu bảng tồn tại và chưa có constraint cùng tên. */
    private function addCheck(string $table, string $name, string $expr): void
    {
        if (!Schema::hasTable($table) || $this->constraintExists($name)) {
            return;
        }
        try {
            DB::statement("ALTER TABLE `{$table}` ADD CONSTRAINT `{$name}` CHECK ({$expr})");
        } catch (\Throwable $e) {
            // Bỏ qua nếu phiên bản MySQL không hỗ trợ CHECK hoặc dữ liệu hiện tại vi phạm.
        }
    }

    private function dropCheck(string $table, string $name): void
    {
        if (Schema::hasTable($table) && $this->constraintExists($name)) {
            try {
                DB::statement("ALTER TABLE `{$table}` DROP CHECK `{$name}`");
            } catch (\Throwable $e) {
                // ignore
            }
        }
    }

    private function indexExists(string $table, string $name): bool
    {
        $db = DB::getDatabaseName();
        $rows = DB::select(
            'SELECT 1 FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND INDEX_NAME = ? LIMIT 1',
            [$db, $table, $name]
        );

        return count($rows) > 0;
    }

    private function constraintExists(string $name): bool
    {
        $db = DB::getDatabaseName();
        $rows = DB::select(
            'SELECT 1 FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = ? AND CONSTRAINT_NAME = ? LIMIT 1',
            [$db, $name]
        );

        return count($rows) > 0;
    }
};
