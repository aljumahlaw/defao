# 🧪 Browser Testing Guide - Document Deletion

## ⚠️ ملاحظة

**لا يمكن الوصول للـ Browser Console مباشرة من Cursor AI**
لكن هذا الدليل يوضح **كيفية الاختبار يدوياً** + **ما الذي يجب البحث عنه**

---

## 📋 Test 1: Browser Console

### الخطوات:

1. **افتح صفحة الوثائق:**
   - URL: `/documents`
   - Login كـ admin

2. **افتح DevTools:**
   - `F12` أو `Ctrl+Shift+I` (Windows/Linux)
   - `Cmd+Option+I` (Mac)

3. **افتح Console Tab**

4. **احذف وثيقة:**
   - اضغط زر الحذف 🗑️ لأي وثيقة
   - اضغط OK في confirm dialog

5. **راقب Console:**

**✅ المتوقع (بعد الإصلاح):**
```
(لا errors)
```

**❌ المشاكل المحتملة (قبل الإصلاح):**
```
Uncaught Error: Livewire: Cannot find element with wire:id="..."
TypeError: Cannot read property 'id' of undefined
Livewire: Component not found
```

**السبب:**
- ❌ بدون `wire:key`, Livewire DOM diff يربط الأزرار بصفوف خاطئة بعد حذف صف

---

## 📋 Test 2: Network Tab

### الخطوات:

1. **DevTools → Network Tab**

2. **Filter:**
   - اختر "XHR" فقط (لتقليل الضوضاء)

3. **Clear Network Log** (🚫 icon)

4. **احذف وثيقة:**
   - اضغط زر الحذف 🗑️
   - اضغط OK

5. **راقب Request:**

**✅ المتوقع:**
```
Request URL: /livewire/message/documents.document-table
Request Method: POST
Status Code: 200 OK

Request Payload:
{
  "fingerprint": {...},
  "serverMemo": {...},
  "updates": [
    {
      "type": "callMethod",
      "payload": {
        "method": "deleteDocument",
        "params": [5]  // ✅ ID صحيح
      }
    }
  ]
}

Response:
{
  "effects": {
    "html": "...",  // ✅ HTML updated
    "dispatches": [
      {
        "event": "show-toast",
        "params": {
          "message": "تم حذف الوثيقة بنجاح",
          "type": "success"
        }
      }
    ]
  }
}
```

**❌ المشاكل المحتملة:**
```
Status Code: 500 Internal Server Error
Status Code: 422 Unprocessable Entity
Status Code: 404 Not Found

Response:
{
  "message": "Document not found",
  "errors": {...}
}
```

---

## 📋 Test 3: Tinker Tests

### تشغيل الاختبارات:

```bash
# Option 1: Run test file
php artisan tinker < test_deletion.tinker.php

# Option 2: Manual commands
php artisan tinker
```

### Test Commands:

```php
// Test 1: Sequential Delete
$docs = App\Models\Document::limit(5)->get();
$doc1 = $docs[0];
$doc2 = $docs[1];

echo "Deleting doc1 ID: {$doc1->id}\n";
$result1 = $doc1->delete(); // ✅ true
echo "Result: " . ($result1 ? 'SUCCESS' : 'FAILED') . "\n";

echo "Deleting doc2 ID: {$doc2->id}\n";
$result2 = $doc2->delete(); // ✅ true (يجب أن ينجح)
echo "Result: " . ($result2 ? 'SUCCESS' : 'FAILED') . "\n";

// Verify soft deleted
$deleted1 = App\Models\Document::onlyTrashed()->find($doc1->id);
$deleted2 = App\Models\Document::onlyTrashed()->find($doc2->id);
echo "Doc1 soft deleted: " . ($deleted1 ? 'YES' : 'NO') . "\n";
echo "Doc2 soft deleted: " . ($deleted2 ? 'YES' : 'NO') . "\n";

// Test 2: Bulk Delete
$testDocs = App\Models\Document::limit(3)->get();
$ids = $testDocs->pluck('id')->toArray();
echo "Bulk deleting: " . implode(', ', $ids) . "\n";

try {
    $count = App\Models\Document::whereIn('id', $ids)->delete();
    echo "Deleted: {$count}\n";
    echo "SUCCESS\n";
} catch (\Exception $e) {
    echo "FAILED: " . $e->getMessage() . "\n";
}
```

---

## 📋 Test 4: Livewire Component Test

### اختبار مباشر للـ Component:

```php
// في Tinker
use App\Livewire\Documents\DocumentTable;
use App\Models\Document;
use App\Models\User;
use Livewire\Livewire;

$admin = User::where('role', 'admin')->first();
$docs = Document::limit(3)->get();

// Test deleteDocument
Livewire::actingAs($admin)
    ->test(DocumentTable::class)
    ->assertSee($docs[0]->title)
    ->call('deleteDocument', $docs[0]->id)
    ->assertDispatched('show-toast')
    ->assertDontSee($docs[0]->title) // ✅ يجب أن تختفي
    ->call('deleteDocument', $docs[1]->id) // ✅ يجب أن يعمل
    ->assertDispatched('show-toast')
    ->assertDontSee($docs[1]->title);
```

---

## 📋 Test 5: Logs Verification

### التحقق من Logs:

```bash
# Watch logs in real-time
tail -f storage/logs/laravel.log

# Or check last 50 lines
tail -n 50 storage/logs/laravel.log | grep "Document delete"
```

**✅ المتوقع بعد الحذف:**

```
[2025-01-XX XX:XX:XX] local.INFO: Document delete attempt {"document_id":5,"document_title":"Test","user_id":1,"user_role":"admin","timestamp":"2025-01-XXTXX:XX:XX.XXXXXXZ"}
[2025-01-XX XX:XX:XX] local.INFO: Document deleted successfully {"document_id":5,"duration_ms":45.23,"timestamp":"2025-01-XXTXX:XX:XX.XXXXXXZ"}
```

**❌ المشاكل:**
```
[2025-01-XX XX:XX:XX] local.ERROR: Document delete failed {"document_id":5,"error":"SQLSTATE[23000]: Integrity constraint violation..."}
```

---

## 📋 Test 6: Race Condition Test

### اختبار Race Condition (يدوي):

1. **افتح صفحة `/documents` في Tab 1**
2. **افتح نفس الصفحة في Tab 2** (نفس المستخدم)
3. **في Tab 1:** احذف وثيقة ID=5
4. **في Tab 2 (بسرعة):** احذف نفس الوثيقة ID=5

**✅ المتوقع (بعد الإصلاح):**
- Tab 1: ✅ يحذف بنجاح
- Tab 2: ✅ يظهر "الوثيقة غير موجودة أو تم حذفها مسبقاً" (warning toast)

**❌ المشاكل (قبل الإصلاح):**
- Tab 2: ❌ 404 error أو exception

---

## 📋 Test 7: Bulk Delete Test

### اختبار الحذف الجماعي:

1. **حدد 5 وثائق** (checkboxes)
2. **اختر "حذف نهائي"** من القائمة
3. **اضغط "تنفيذ"**

**✅ المتوقع:**
- ✅ Toast: "تمت العملية على 5 وثائق بنجاح"
- ✅ الوثائق تختفي من القائمة
- ✅ Network: Status 200

**❌ المشاكل:**
- ❌ Toast: "تمت العملية على 0 وثائق بنجاح" (مضلل)
- ❌ Network: Status 500
- ❌ الوثائق لا تختفي

---

## 📋 Test 8: Validation Test

### اختبار Validation:

**Test 1: Selected Empty**
```javascript
// في Browser Console (بعد تطبيق الإصلاح)
@this.selected = [];
@this.bulkActionValue = 'delete';
@this.bulkAction();
// ✅ يجب أن يظهر: "لم يتم تحديد أي وثائق"
```

**Test 2: Invalid Selected IDs**
```javascript
@this.selected = [99999, 99998]; // IDs غير موجودة
@this.bulkActionValue = 'delete';
@this.bulkAction();
// ✅ يجب أن يظهر validation error
```

---

## 📋 Checklist النهائي

**بعد تطبيق جميع الإصلاحات:**

- [ ] ✅ الحذف الفردي يعمل كل مرة (لا يتوقف بعد أول حذف)
- [ ] ✅ الحذف الجماعي من Toolbar يعمل
- [ ] ✅ الحذف الجماعي من Dropdown يعمل
- [ ] ✅ لا JavaScript errors في Console
- [ ] ✅ Network requests: Status 200
- [ ] ✅ Toast messages صحيحة (success/error/warning)
- [ ] ✅ Logs تُكتب بشكل صحيح
- [ ] ✅ Race conditions لا تحدث (حذف متسلسل يعمل)
- [ ] ✅ Validation تعمل (selected array)
- [ ] ✅ Performance مقبول (حذف 100+ وثيقة)
- [ ] ✅ List refresh بعد الحذف (الوثيقة تختفي فوراً)
- [ ] ✅ wire:key يعمل (لا DOM binding errors)

---

**✅ هذا الدليل جاهز للاستخدام في الاختبار اليدوي**


