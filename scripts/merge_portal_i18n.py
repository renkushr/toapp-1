#!/usr/bin/env python3
"""Merge portal @lang/__ keys into all locale JSON files and fill translations."""

from __future__ import annotations

import json
import re
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
LANG_DIR = ROOT / "core" / "resources" / "lang"
VIEW_DIRS = [
    ROOT / "core" / "resources" / "views" / "toapp_admin",
    ROOT / "core" / "resources" / "views" / "toapp_agent",
]

KEY_RE = re.compile(
    r"""(?:@lang|__|trans)\(\s*['"]([^'"]+)['"]""",
    re.M,
)

# High-quality translations for common portal phrases.
# Missing keys fall back to English in non-en locales (still valid UX).
TRANSLATIONS: dict[str, dict[str, str]] = {
    "Any": {"th": "ทั้งหมด", "ja": "すべて", "zh": "全部", "pt": "Qualquer", "ru": "Любой", "es": "Cualquiera", "vi": "Bất kỳ"},
    "Export CSV": {"th": "ส่งออก CSV", "ja": "CSV書き出し", "zh": "导出 CSV", "pt": "Exportar CSV", "ru": "Экспорт CSV", "es": "Exportar CSV", "vi": "Xuất CSV"},
    "Approved": {"th": "อนุมัติแล้ว", "ja": "承認済み", "zh": "已批准", "pt": "Aprovado", "ru": "Одобрено", "es": "Aprobado", "vi": "Đã duyệt"},
    "Clear": {"th": "ล้าง", "ja": "クリア", "zh": "清除", "pt": "Limpar", "ru": "Очистить", "es": "Limpiar", "vi": "Xóa"},
    "Date": {"th": "วันที่", "ja": "日付", "zh": "日期", "pt": "Data", "ru": "Дата", "es": "Fecha", "vi": "Ngày"},
    "User": {"th": "ผู้ใช้", "ja": "ユーザー", "zh": "用户", "pt": "Usuário", "ru": "Пользователь", "es": "Usuario", "vi": "Người dùng"},
    "Agent": {"th": "เอเจนต์", "ja": "エージェント", "zh": "代理", "pt": "Agente", "ru": "Агент", "es": "Agente", "vi": "Đại lý"},
    "Close": {"th": "ปิด", "ja": "閉じる", "zh": "关闭", "pt": "Fechar", "ru": "Закрыть", "es": "Cerrar", "vi": "Đóng"},
    "Toggle navigation": {"th": "เปิด/ปิดเมนู", "ja": "ナビゲーション切替", "zh": "切换导航", "pt": "Alternar navegação", "ru": "Переключить меню", "es": "Alternar navegación", "vi": "Bật/tắt điều hướng"},
    "No results found": {"th": "ไม่พบผลลัพธ์", "ja": "結果がありません", "zh": "未找到结果", "pt": "Nenhum resultado", "ru": "Ничего не найдено", "es": "Sin resultados", "vi": "Không có kết quả"},
    "Actions": {"th": "การดำเนินการ", "ja": "操作", "zh": "操作", "pt": "Ações", "ru": "Действия", "es": "Acciones", "vi": "Thao tác"},
    "Name": {"th": "ชื่อ", "ja": "名前", "zh": "姓名", "pt": "Nome", "ru": "Имя", "es": "Nombre", "vi": "Tên"},
    "Phone": {"th": "โทรศัพท์", "ja": "電話", "zh": "电话", "pt": "Telefone", "ru": "Телефон", "es": "Teléfono", "vi": "Điện thoại"},
    "ZIP code": {"th": "รหัสไปรษณีย์", "ja": "郵便番号", "zh": "邮编", "pt": "CEP", "ru": "ZIP", "es": "Código postal", "vi": "Mã ZIP"},
    "Created": {"th": "สร้างเมื่อ", "ja": "作成日", "zh": "创建时间", "pt": "Criado", "ru": "Создано", "es": "Creado", "vi": "Đã tạo"},
    "Updated": {"th": "อัปเดตเมื่อ", "ja": "更新日", "zh": "更新时间", "pt": "Atualizado", "ru": "Обновлено", "es": "Actualizado", "vi": "Đã cập nhật"},
    "Notes": {"th": "หมายเหตุ", "ja": "メモ", "zh": "备注", "pt": "Notas", "ru": "Заметки", "es": "Notas", "vi": "Ghi chú"},
    "Confirm": {"th": "ยืนยัน", "ja": "確認", "zh": "确认", "pt": "Confirmar", "ru": "Подтвердить", "es": "Confirmar", "vi": "Xác nhận"},
    "Reject": {"th": "ปฏิเสธ", "ja": "拒否", "zh": "拒绝", "pt": "Rejeitar", "ru": "Отклонить", "es": "Rechazar", "vi": "Từ chối"},
    "Approve": {"th": "อนุมัติ", "ja": "承認", "zh": "批准", "pt": "Aprovar", "ru": "Одобрить", "es": "Aprobar", "vi": "Duyệt"},
    "Ban": {"th": "แบน", "ja": "禁止", "zh": "封禁", "pt": "Banir", "ru": "Заблокировать", "es": "Bloquear", "vi": "Cấm"},
    "Restore": {"th": "กู้คืน", "ja": "復元", "zh": "恢复", "pt": "Restaurar", "ru": "Восстановить", "es": "Restaurar", "vi": "Khôi phục"},
    "Details": {"th": "รายละเอียด", "ja": "詳細", "zh": "详情", "pt": "Detalhes", "ru": "Подробности", "es": "Detalles", "vi": "Chi tiết"},
    "Message": {"th": "ข้อความ", "ja": "メッセージ", "zh": "消息", "pt": "Mensagem", "ru": "Сообщение", "es": "Mensaje", "vi": "Tin nhắn"},
    "Subject": {"th": "หัวข้อ", "ja": "件名", "zh": "主题", "pt": "Assunto", "ru": "Тема", "es": "Asunto", "vi": "Chủ đề"},
    "Type": {"th": "ประเภท", "ja": "種類", "zh": "类型", "pt": "Tipo", "ru": "Тип", "es": "Tipo", "vi": "Loại"},
    "Method": {"th": "ช่องทาง", "ja": "方法", "zh": "方式", "pt": "Método", "ru": "Метод", "es": "Método", "vi": "Phương thức"},
    "Reference": {"th": "อ้างอิง", "ja": "参照", "zh": "参考号", "pt": "Referência", "ru": "Ссылка", "es": "Referencia", "vi": "Tham chiếu"},
    "Balance": {"th": "ยอดคงเหลือ", "ja": "残高", "zh": "余额", "pt": "Saldo", "ru": "Баланс", "es": "Saldo", "vi": "Số dư"},
    "Total": {"th": "รวม", "ja": "合計", "zh": "合计", "pt": "Total", "ru": "Итого", "es": "Total", "vi": "Tổng"},
    "From": {"th": "จาก", "ja": "開始", "zh": "从", "pt": "De", "ru": "С", "es": "Desde", "vi": "Từ"},
    "To": {"th": "ถึง", "ja": "終了", "zh": "至", "pt": "Até", "ru": "По", "es": "Hasta", "vi": "Đến"},
    "Last 7 days": {"th": "7 วันล่าสุด", "ja": "過去7日", "zh": "近 7 天", "pt": "Últimos 7 dias", "ru": "Последние 7 дней", "es": "Últimos 7 días", "vi": "7 ngày gần nhất"},
    "Last 30 days": {"th": "30 วันล่าสุด", "ja": "過去30日", "zh": "近 30 天", "pt": "Últimos 30 dias", "ru": "Последние 30 дней", "es": "Últimos 30 días", "vi": "30 ngày gần nhất"},
    "Last 90 days": {"th": "90 วันล่าสุด", "ja": "過去90日", "zh": "近 90 天", "pt": "Últimos 90 dias", "ru": "Последние 90 дней", "es": "Últimos 90 días", "vi": "90 ngày gần nhất"},
    "All time": {"th": "ทั้งหมด", "ja": "全期間", "zh": "全部时间", "pt": "Todo o período", "ru": "За всё время", "es": "Todo el tiempo", "vi": "Toàn thời gian"},
    "Open chat": {"th": "เปิดแชท", "ja": "チャットを開く", "zh": "打开聊天", "pt": "Abrir chat", "ru": "Открыть чат", "es": "Abrir chat", "vi": "Mở chat"},
    "Conversations": {"th": "การสนทนา", "ja": "会話", "zh": "会话", "pt": "Conversas", "ru": "Диалоги", "es": "Conversaciones", "vi": "Hội thoại"},
    "No data": {"th": "ไม่มีข้อมูล", "ja": "データなし", "zh": "无数据", "pt": "Sem dados", "ru": "Нет данных", "es": "Sin datos", "vi": "Không có dữ liệu"},
    "Banned": {"th": "ถูกแบน", "ja": "禁止中", "zh": "已封禁", "pt": "Banido", "ru": "Заблокирован", "es": "Bloqueado", "vi": "Đã bị cấm"},
    "Inactive": {"th": "ไม่ใช้งาน", "ja": "無効", "zh": "未启用", "pt": "Inativo", "ru": "Неактивен", "es": "Inactivo", "vi": "Không hoạt động"},
    "Enabled": {"th": "เปิดใช้งาน", "ja": "有効", "zh": "已启用", "pt": "Ativado", "ru": "Включено", "es": "Activado", "vi": "Đã bật"},
    "Disabled": {"th": "ปิดใช้งาน", "ja": "無効", "zh": "已禁用", "pt": "Desativado", "ru": "Отключено", "es": "Desactivado", "vi": "Đã tắt"},
    "Required": {"th": "จำเป็น", "ja": "必須", "zh": "必填", "pt": "Obrigatório", "ru": "Обязательно", "es": "Obligatorio", "vi": "Bắt buộc"},
    "Optional": {"th": "ไม่บังคับ", "ja": "任意", "zh": "可选", "pt": "Opcional", "ru": "Необязательно", "es": "Opcional", "vi": "Tùy chọn"},
    "Yes": {"th": "ใช่", "ja": "はい", "zh": "是", "pt": "Sim", "ru": "Да", "es": "Sí", "vi": "Có"},
    "No": {"th": "ไม่", "ja": "いいえ", "zh": "否", "pt": "Não", "ru": "Нет", "es": "No", "vi": "Không"},
    "Loading…": {"th": "กำลังโหลด…", "ja": "読み込み中…", "zh": "加载中…", "pt": "Carregando…", "ru": "Загрузка…", "es": "Cargando…", "vi": "Đang tải…"},
    "Upload failed": {"th": "อัปโหลดไม่สำเร็จ", "ja": "アップロード失敗", "zh": "上传失败", "pt": "Falha no upload", "ru": "Ошибка загрузки", "es": "Error al subir", "vi": "Tải lên thất bại"},
    "Select all USA": {"th": "เลือกทั้งสหรัฐฯ", "ja": "全米を選択", "zh": "选择全美", "pt": "Selecionar todos EUA", "ru": "Выбрать все США", "es": "Seleccionar todo EE. UU.", "vi": "Chọn toàn Mỹ"},
    "Clear all": {"th": "ล้างทั้งหมด", "ja": "すべてクリア", "zh": "全部清除", "pt": "Limpar tudo", "ru": "Очистить всё", "es": "Borrar todo", "vi": "Xóa tất cả"},
    "New client message": {"th": "ข้อความลูกค้าใหม่", "ja": "新しい顧客メッセージ", "zh": "新客户消息", "pt": "Nova mensagem do cliente", "ru": "Новое сообщение клиента", "es": "Nuevo mensaje del cliente", "vi": "Tin nhắn khách mới"},
    "Chat notifications": {"th": "การแจ้งเตือนแชท", "ja": "チャット通知", "zh": "聊天通知", "pt": "Notificações de chat", "ru": "Уведомления чата", "es": "Notificaciones de chat", "vi": "Thông báo chat"},
}


def extract_keys() -> set[str]:
    keys: set[str] = set()
    for d in VIEW_DIRS:
        for path in d.rglob("*.blade.php"):
            text = path.read_text(encoding="utf-8", errors="ignore")
            for m in KEY_RE.finditer(text):
                key = m.group(1).strip()
                if key and not key.startswith("validation."):
                    keys.add(key)
    return keys


def load_json(path: Path) -> dict:
    return json.loads(path.read_text(encoding="utf-8"))


def save_json(path: Path, data: dict) -> None:
    path.write_text(json.dumps(data, ensure_ascii=False, indent=2) + "\n", encoding="utf-8")


def main() -> None:
    keys = extract_keys()
    en_path = LANG_DIR / "en.json"
    en = load_json(en_path)
    added = []
    for key in sorted(keys):
        if key not in en:
            en[key] = key
            added.append(key)
    save_json(en_path, en)

    locales = ["th", "ja", "zh", "pt", "ru", "es", "vi"]
    for code in locales:
        path = LANG_DIR / f"{code}.json"
        data = load_json(path)
        for key in en:
            if key in data:
                continue
            if key in TRANSLATIONS and code in TRANSLATIONS[key]:
                data[key] = TRANSLATIONS[key][code]
            else:
                # Fallback: English until curated translation exists
                data[key] = en[key]
        # Ensure known curated translations overwrite English fallbacks
        for key, langs in TRANSLATIONS.items():
            if key in data and code in langs:
                data[key] = langs[code]
        save_json(path, data)

    print(f"portal keys found: {len(keys)}")
    print(f"new en keys added: {len(added)}")
    print(f"en total: {len(en)}")


if __name__ == "__main__":
    main()
