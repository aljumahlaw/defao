---
**Updated:** 2025-12-22 - Defao v1.0.1  
**Status:** ✅ Production Ready  
**Features:** Workflow, Reports link, Arabic toasts  
---

# مسارات المستخدمين التفصيلية (User Journeys)

## 📋 الفهرس

1. [مسار إنشاء ومتابعة وثيقة](#1-مسار-إنشاء-ومتابعة-وثيقة)
2. [مسار إدارة المهام](#2-مسار-إدارة-المهام)
3. [مسار مراقبة سير العمل](#3-مسار-مراقبة-سير-العمل)
4. [مسار الأرشفة والاستعادة](#4-مسار-الأرشفة-والاستعادة)

---

## 1. مسار إنشاء ومتابعة وثيقة

### 🎯 الهدف
من تسجيل الدخول إلى رفع وثيقة → إسنادها → متابعة مراحلها → أرشفتها

### 📊 المخطط التفصيلي

```mermaid
flowchart TD
    Start([تسجيل الدخول<br/>/login]) --> Login[صفحة تسجيل الدخول<br/>AuthenticatedSessionController]
    Login -->|POST /login| AuthCheck{التحقق من<br/>المصادقة}
    AuthCheck -->|نجح| Dashboard[/dashboard<br/>DashboardOverview]
    AuthCheck -->|فشل| Login
    
    Dashboard --> NavDocs[النقر على<br/>'الوثائق' في القائمة]
    NavDocs --> DocsIndex[/documents<br/>DocumentTable Component]
    
    DocsIndex --> UploadBtn[النقر على<br/>'رفع وثيقة جديدة']
    UploadBtn --> UploadPage[/documents/upload<br/>DocumentUpload Component]
    
    UploadPage --> FillForm[ملء النموذج:<br/>- اختيار الملف<br/>- العنوان<br/>- النوع وارد/صادر<br/>- الوصف]
    FillForm --> ValidateFile{التحقق من<br/>الملف}
    ValidateFile -->|خطأ| FillForm
    ValidateFile -->|صحيح| UploadProgress[عرض شريط التقدم<br/>uploadProgress]
    
    UploadProgress --> ProcessFile[معالجة الملف<br/>processingProgress]
    ProcessFile --> SaveBtn[النقر على 'حفظ']
    SaveBtn --> CreateDoc[Document::create<br/>user_id = auth()->id()<br/>assignee_id = auth()->id()<br/>current_stage = 'draft']
    
    CreateDoc --> CreateActivity1[DocumentActivity::create<br/>action_type = 'created']
    CreateActivity1 --> CreateActivity2[DocumentActivity::create<br/>action_type = 'uploaded']
    CreateActivity2 --> Redirect1[redirect → /documents<br/>DocumentTable]
    
    Redirect1 --> ViewDoc[النقر على وثيقة<br/>viewDocument]
    ViewDoc --> DocDetail[/documents/{id}<br/>DocumentDetail Component]
    
    DocDetail --> CheckView{DocumentPolicy@view<br/>منشئ أو مكلّف?}
    CheckView -->|لا| DenyView[403 Forbidden]
    CheckView -->|نعم| ShowDoc[عرض الوثيقة:<br/>- التفاصيل<br/>- الملف<br/>- المراحل<br/>- سجل الأنشطة]
    
    ShowDoc --> ViewTasks[عرض تبويب 'المهام'<br/>DocumentTasks Component]
    ViewTasks --> AddTask[إضافة مهمة جديدة<br/>addTask]
    AddTask --> TaskCreated[DocumentTask::create<br/>status = 'open']
    
    ShowDoc --> WorkflowActions{الإجراءات على<br/>سير العمل}
    WorkflowActions -->|المكلّف فقط| ApproveBtn[الموافقة<br/>approve]
    WorkflowActions -->|المكلّف فقط| RejectBtn[الرفض<br/>reject]
    WorkflowActions -->|المكلّف فقط| ForwardBtn[التحويل<br/>forward]
    
    ApproveBtn --> CheckUpdate1{DocumentPolicy@update<br/>مكلّف?}
    CheckUpdate1 -->|نعم| UpdateStage1[تحديث current_stage<br/>= 'finalapproval']
    CheckUpdate1 -->|لا| DenyUpdate1[403 Forbidden]
    
    RejectBtn --> CheckUpdate2{DocumentPolicy@update<br/>مكلّف?}
    CheckUpdate2 -->|نعم| UpdateStage2[تحديث current_stage<br/>= 'draft']
    CheckUpdate2 -->|لا| DenyUpdate2[403 Forbidden]
    
    ForwardBtn --> CheckUpdate3{DocumentPolicy@update<br/>مكلّف?}
    CheckUpdate3 -->|نعم| UpdateStage3[تحديث current_stage<br/>→ المرحلة التالية]
    CheckUpdate3 -->|لا| DenyUpdate3[403 Forbidden]
    
    UpdateStage1 --> ActivityLog1[DocumentActivity::create<br/>action_type = 'approved']
    UpdateStage2 --> ActivityLog2[DocumentActivity::create<br/>action_type = 'rejected']
    UpdateStage3 --> ActivityLog3[DocumentActivity::create<br/>action_type = 'forwarded']
    
    ShowDoc --> BackToList[العودة للقائمة<br/>/documents]
    BackToList --> SelectDoc[تحديد وثيقة/وثائق<br/>checkbox]
    SelectDoc --> BulkArchive[اختيار 'أرشفة'<br/>bulkAction]
    BulkArchive --> ArchiveDoc[تحديث is_archived<br/>= true]
    
    ArchiveDoc --> ArchivedView[عرض الوثائق المؤرشفة<br/>/documents/archive<br/>DocumentArchive Component]
    
    style DenyView fill:#ff6b6b
    style DenyUpdate1 fill:#ff6b6b
    style DenyUpdate2 fill:#ff6b6b
    style DenyUpdate3 fill:#ff6b6b
    style CheckView fill:#ffd93d
    style CheckUpdate1 fill:#ffd93d
    style CheckUpdate2 fill:#ffd93d
    style CheckUpdate3 fill:#ffd93d
```

### 🔍 نقاط الاحتكاك (Friction Points)

| # | النقطة | الوصف | التأثير | الحل المقترح |
|---|--------|-------|---------|--------------|
| **F1** | **عدم إمكانية تغيير assignee_id** | عند رفع الوثيقة، `assignee_id` يُسند تلقائياً للمنشئ. لا توجد واجهة لتغيير المكلّف | ⚠️ متوسط | إضافة dropdown في `DocumentUpload` لاختيار المكلّف |
| **F2** | **المنشئ لا يستطيع تحديث الوثيقة** | `DocumentPolicy@update` يسمح فقط للمكلّف. المنشئ لا يستطيع تعديل وثيقته | ⚠️ متوسط | السماح للمنشئ بالتحديث في مرحلة `draft` |
| **F3** | **عدم وجود تأكيد قبل الأرشفة** | `bulkAction` للأرشفة لا يطلب تأكيد | ⚠️ منخفض | إضافة modal تأكيد قبل الأرشفة |
| **F4** | **عدم وجود إشعارات** | لا توجد إشعارات عند إسناد وثيقة أو تغيير المرحلة | ⚠️ متوسط | إضافة نظام إشعارات |
| **F5** | **عدم وجود تعليقات** | زر "إضافة تعليق" في `DocumentDetail` لا يعمل (TODO) | ⚠️ منخفض | تنفيذ modal التعليقات |

---

## 2. مسار إدارة المهام

### 🎯 الهدف
من استقبال مهمة جديدة إلى معاينتها → تنفيذها → إغلاقها → إعادة فتحها (إن لزم)

### 📊 المخطط التفصيلي

```mermaid
flowchart TD
    Start([تسجيل الدخول]) --> Dashboard[/dashboard<br/>DashboardOverview]
    Dashboard --> NavTasks[النقر على<br/>'المهام' في القائمة]
    NavTasks --> TasksIndex[/tasks<br/>TaskList Component]
    
    TasksIndex --> ViewAllTasks[عرض جميع المهام:<br/>- pending<br/>- in_progress<br/>- completed<br/>- overdue]
    
    ViewAllTasks --> FilterTasks[تصفية المهام:<br/>- حسب الحالة<br/>- حسب التاريخ<br/>- البحث]
    
    ViewAllTasks --> CreateTaskBtn[النقر على<br/>'مهمة جديدة']
    CreateTaskBtn --> TaskFormModal[فتح Modal<br/>TaskForm Component]
    
    TaskFormModal --> FillTaskForm[ملء النموذج:<br/>- العنوان<br/>- الوصف<br/>- الأولوية<br/>- المعين له<br/>- تاريخ الاستحقاق<br/>- وثيقة مرتبطة]
    FillTaskForm --> ValidateTask{التحقق من<br/>النموذج}
    ValidateTask -->|خطأ| FillTaskForm
    ValidateTask -->|صحيح| SaveTask[Task::create<br/>user_id = auth()->id()<br/>status = 'pending']
    
    SaveTask --> TaskCreated[✅ تم إنشاء المهمة<br/>show-toast]
    TaskCreated --> RefreshList[تحديث القائمة<br/>task-saved event]
    
    ViewAllTasks --> ViewTask[النقر على مهمة<br/>viewTask]
    ViewTask --> TaskModal[فتح Modal<br/>عرض تفاصيل المهمة]
    
    TaskModal --> EditTask[النقر على 'تعديل'<br/>editTask]
    EditTask --> TaskFormEdit[فتح TaskForm Modal<br/>مع taskId]
    TaskFormEdit --> LoadTask[تحميل بيانات المهمة<br/>loadTask]
    LoadTask --> UpdateTaskForm[تعديل البيانات]
    UpdateTaskForm --> SaveUpdate[Task::update<br/>حفظ التعديلات]
    
    TaskModal --> DeleteTask[النقر على 'حذف'<br/>deleteTask]
    DeleteTask --> ConfirmDelete{⚠️ لا يوجد تأكيد!}
    ConfirmDelete --> DeleteConfirm[Task::delete<br/>soft delete]
    
    TasksIndex --> DocTasks[الانتقال لصفحة وثيقة<br/>/documents/{id}]
    DocTasks --> DocDetail[DocumentDetail Component]
    DocDetail --> TasksTab[النقر على تبويب<br/>'المهام']
    TasksTab --> DocTasksView[DocumentTasks Component<br/>عرض مهام الوثيقة]
    
    DocTasksView --> AddDocTask[إضافة مهمة للوثيقة<br/>addTask]
    AddDocTask --> FillDocTaskForm[ملء النموذج:<br/>- العنوان<br/>- الملاحظات<br/>- تاريخ الاستحقاق<br/>- المعين له]
    FillDocTaskForm --> CreateDocTask[DocumentTask::create<br/>document_id = $documentId<br/>status = 'open']
    
    CreateDocTask --> DocTaskCreated[✅ تم إضافة المهمة<br/>show-toast]
    
    DocTasksView --> ViewDocTask[النقر على مهمة<br/>viewTask]
    ViewDocTask --> ToggleDetails{selectedTaskId<br/>toggle}
    ToggleDetails -->|null| ShowDetails[عرض تفاصيل المهمة]
    ToggleDetails -->|taskId| HideDetails[إخفاء التفاصيل]
    
    DocTasksView --> MarkDone[النقر على 'إكمال'<br/>markDone]
    MarkDone --> UpdateStatus1[تحديث status<br/>= 'closed']
    
    DocTasksView --> Reopen[النقر على 'إعادة فتح'<br/>reopen]
    Reopen --> UpdateStatus2[تحديث status<br/>= 'open']
    
    DocTasksView --> DeleteDocTask[النقر على 'حذف'<br/>deleteTask]
    DeleteDocTask --> ConfirmDelete2{⚠️ لا يوجد تأكيد!}
    ConfirmDelete2 --> DeleteDocTaskConfirm[DocumentTask::delete]
    
    style ConfirmDelete fill:#ff6b6b
    style ConfirmDelete2 fill:#ff6b6b
    style DeleteConfirm fill:#ffd93d
    style DeleteDocTaskConfirm fill:#ffd93d
```

### 🔍 نقاط الاحتكاك (Friction Points)

| # | النقطة | الوصف | التأثير | الحل المقترح |
|---|--------|-------|---------|--------------|
| **F6** | **عدم وجود تأكيد قبل الحذف** | `deleteTask` في `TaskList` و `DocumentTasks` لا يطلب تأكيد | 🔴 عالي | إضافة modal تأكيد قبل الحذف |
| **F7** | **عدم وجود Policy للمهام** | أي مستخدم يمكنه حذف/تعديل أي مهمة | 🔴 عالي | إنشاء `TaskPolicy` |
| **F8** | **عدم وجود إشعارات للمهام** | لا توجد إشعارات عند إسناد مهمة أو تغيير حالتها | ⚠️ متوسط | إضافة نظام إشعارات |
| **F9** | **عدم وجود فلاتر متقدمة** | `TaskList` لا يحتوي على فلتر حسب الأولوية أو المعين | ⚠️ منخفض | إضافة فلاتر إضافية |
| **F10** | **عدم وجود بحث في DocumentTasks** | `DocumentTasks` لا يحتوي على بحث | ⚠️ منخفض | إضافة حقل بحث |
| **F11** | **عدم وجود تاريخ تعديل** | لا يوجد `updated_at` مرئي في تفاصيل المهمة | ⚠️ منخفض | إضافة عرض تاريخ التعديل |

---

## 3. مسار مراقبة سير العمل

### 🎯 الهدف
من لوحة `WorkflowOverview` إلى التنقل بين المراحل → تغيير حالة الوثائق

### 📊 المخطط التفصيلي

```mermaid
flowchart TD
    Start([تسجيل الدخول]) --> Dashboard[/dashboard<br/>DashboardOverview]
    Dashboard --> NavWorkflow[النقر على<br/>'سير العمل' في القائمة]
    NavWorkflow --> WorkflowPage[/workflow<br/>WorkflowOverview Component]
    
    WorkflowPage --> ShowStats[عرض الإحصائيات:<br/>- إجمالي الوثائق<br/>- الوثائق المتأخرة<br/>- عدد الوثائق لكل مرحلة]
    
    ShowStats --> StageCards[عرض 4 بطاقات مراحل:<br/>WorkflowStageCard Components]
    
    StageCards --> DraftCard[بطاقة 'مسودة'<br/>stage = 'draft']
    StageCards --> ReviewCard[بطاقة 'مراجعة أولى'<br/>stage = 'review1']
    StageCards --> ProofreadCard[بطاقة 'تدقيق'<br/>stage = 'proofread']
    StageCards --> ApprovalCard[بطاقة 'موافقة نهائية'<br/>stage = 'finalapproval']
    
    DraftCard --> ViewDraftDocs[عرض 3 وثائق حديثة<br/>recentDocuments]
    ReviewCard --> ViewReviewDocs[عرض 3 وثائق حديثة<br/>recentDocuments]
    ProofreadCard --> ViewProofreadDocs[عرض 3 وثائق حديثة<br/>recentDocuments]
    ApprovalCard --> ViewApprovalDocs[عرض 3 وثائق حديثة<br/>recentDocuments]
    
    ViewDraftDocs --> ClickDoc1[النقر على وثيقة]
    ClickDoc1 --> DocDetail1[/documents/{id}<br/>DocumentDetail]
    
    ViewReviewDocs --> ClickDoc2[النقر على وثيقة]
    ClickDoc2 --> DocDetail2[/documents/{id}<br/>DocumentDetail]
    
    ViewProofreadDocs --> ClickDoc3[النقر على وثيقة]
    ClickDoc3 --> DocDetail3[/documents/{id}<br/>DocumentDetail]
    
    ViewApprovalDocs --> ClickDoc4[النقر على وثيقة]
    ClickDoc4 --> DocDetail4[/documents/{id}<br/>DocumentDetail]
    
    DraftCard --> AdvanceBtn1[النقر على 'إرسال للمرحلة التالية'<br/>advanceStage]
    AdvanceBtn1 --> CheckVisible1{visibleTo scope<br/>يستطيع رؤية الوثيقة?}
    CheckVisible1 -->|لا| Deny1[403 Forbidden]
    CheckVisible1 -->|نعم| CheckArchived1{is_archived?}
    CheckArchived1 -->|نعم| DenyArchived1[⚠️ لا يمكن تغيير مرحلة وثيقة مؤرشفة]
    CheckArchived1 -->|لا| UpdateStage1[تحديث current_stage<br/>→ المرحلة التالية]
    UpdateStage1 --> Dispatch1[dispatch 'document-stage-changed']
    Dispatch1 --> Refresh1[تحديث WorkflowOverview<br/>$refresh]
    
    ReviewCard --> AdvanceBtn2[النقر على 'إرسال للمرحلة التالية'<br/>advanceStage]
    AdvanceBtn2 --> CheckVisible2{visibleTo scope}
    CheckVisible2 -->|نعم| CheckArchived2{is_archived?}
    CheckArchived2 -->|لا| UpdateStage2[تحديث current_stage<br/>→ proofread]
    
    ProofreadCard --> AdvanceBtn3[النقر على 'إرسال للمرحلة التالية'<br/>advanceStage]
    AdvanceBtn3 --> CheckVisible3{visibleTo scope}
    CheckVisible3 -->|نعم| CheckArchived3{is_archived?}
    CheckArchived3 -->|لا| UpdateStage3[تحديث current_stage<br/>→ finalapproval]
    
    DraftCard --> RejectBtn1[النقر على 'إرجاع للمسودة'<br/>rejectStage]
    ReviewCard --> RejectBtn2[النقر على 'إرجاع للمسودة'<br/>rejectStage]
    ProofreadCard --> RejectBtn3[النقر على 'إرجاع للمسودة'<br/>rejectStage]
    ApprovalCard --> RejectBtn4[النقر على 'إرجاع للمسودة'<br/>rejectStage]
    
    RejectBtn1 --> CheckVisible4{visibleTo scope}
    RejectBtn2 --> CheckVisible4
    RejectBtn3 --> CheckVisible4
    RejectBtn4 --> CheckVisible4
    
    CheckVisible4 -->|نعم| CheckArchived4{is_archived?}
    CheckArchived4 -->|لا| UpdateStage4[تحديث current_stage<br/>= 'draft']
    UpdateStage4 --> Dispatch2[dispatch 'document-stage-changed']
    Dispatch2 --> Refresh2[تحديث WorkflowOverview]
    
    WorkflowPage --> ExportBtn[النقر على 'تصدير تقرير'<br/>exportWorkflowReport]
    ExportBtn --> GeneratePDF[Pdf::loadView<br/>workflow-report]
    GeneratePDF --> DownloadPDF[تنزيل PDF<br/>workflow-report-YYYY-MM-DD.pdf]
    
    style Deny1 fill:#ff6b6b
    style DenyArchived1 fill:#ffd93d
    style CheckVisible1 fill:#ffd93d
    style CheckVisible2 fill:#ffd93d
    style CheckVisible3 fill:#ffd93d
    style CheckVisible4 fill:#ffd93d
```

### 🔍 نقاط الاحتكاك (Friction Points)

| # | النقطة | الوصف | التأثير | الحل المقترح |
|---|--------|-------|---------|--------------|
| **F12** | **عدم وجود Policy في WorkflowStageCard** | `advanceStage` و `rejectStage` يستخدمان `visibleTo` فقط، وليس `DocumentPolicy@update` | 🔴 عالي | استخدام `DocumentPolicy@update` قبل تغيير المرحلة |
| **F13** | **عدم وجود تأكيد قبل تغيير المرحلة** | تغيير المرحلة يحدث مباشرة بدون تأكيد | ⚠️ متوسط | إضافة modal تأكيد |
| **F14** | **عرض 3 وثائق فقط** | `recentDocuments` يعرض فقط 3 وثائق حديثة | ⚠️ منخفض | إضافة "عرض المزيد" أو pagination |
| **F15** | **عدم وجود فلترة في WorkflowOverview** | لا يمكن تصفية الوثائق حسب النوع أو التاريخ | ⚠️ منخفض | إضافة فلاتر |
| **F16** | **عدم وجود إشعارات عند تغيير المرحلة** | لا توجد إشعارات للمكلّف عند تغيير مرحلة وثيقته | ⚠️ متوسط | إضافة نظام إشعارات |
| **F17** | **عدم وجود تعليق عند الرفض** | عند رفض وثيقة، لا يوجد حقل لإدخال سبب الرفض | ⚠️ متوسط | إضافة modal للتعليق عند الرفض |

---

## 4. مسار الأرشفة والاستعادة

### 🎯 الهدف
من `DocumentTable` bulk actions إلى `/documents/archive` → restore/force delete

### 📊 المخطط التفصيلي

```mermaid
flowchart TD
    Start([تسجيل الدخول]) --> Dashboard[/dashboard]
    Dashboard --> NavDocs[النقر على 'الوثائق']
    NavDocs --> DocsIndex[/documents<br/>DocumentTable Component]
    
    DocsIndex --> SelectDocs[تحديد وثيقة/وثائق<br/>checkbox selection]
    SelectDocs --> BulkActions[اختيار إجراء جماعي:<br/>bulkAction dropdown]
    
    BulkActions --> BulkArchive[اختيار 'أرشفة'<br/>bulkAction = 'archive']
    BulkActions --> BulkDelete[اختيار 'حذف'<br/>bulkAction = 'delete']
    BulkActions --> BulkStage[اختيار 'تغيير المرحلة'<br/>bulkAction = 'stage_*']
    
    BulkArchive --> ValidateArchive{visibleTo scope<br/>الوثائق مرئية للمستخدم?}
    ValidateArchive -->|نعم| UpdateArchive[تحديث is_archived<br/>= true]
    ValidateArchive -->|لا| SkipArchive[تخطي الوثيقة]
    
    UpdateArchive --> ArchiveSuccess[✅ تم أرشفة X وثيقة<br/>show-toast]
    
    BulkDelete --> ValidateDelete{visibleTo scope<br/>الوثائق مرئية?}
    ValidateDelete -->|نعم| SoftDelete[Document::delete<br/>soft delete]
    ValidateDelete -->|لا| SkipDelete[تخطي الوثيقة]
    
    SoftDelete --> DeleteSuccess[✅ تم حذف X وثيقة<br/>show-toast]
    
    BulkStage --> ValidateStage{visibleTo scope}
    ValidateStage -->|نعم| UpdateStage[تحديث current_stage<br/>= selected stage]
    ValidateStage -->|لا| SkipStage[تخطي الوثيقة]
    
    UpdateStage --> StageSuccess[✅ تم تغيير مرحلة X وثيقة<br/>show-toast]
    
    DocsIndex --> SingleArchive[النقر على 'أرشفة'<br/>للوثيقة الواحدة]
    SingleArchive --> ArchiveSingle[archiveDocument<br/>تحديث is_archived = true]
    ArchiveSingle --> ArchiveSingleSuccess[✅ تم أرشفة الوثيقة<br/>show-toast]
    
    DocsIndex --> NavArchive[النقر على 'الأرشيف'<br/>في القائمة]
    NavArchive --> ArchivePage[/documents/archive<br/>DocumentArchive Component]
    
    ArchivePage --> ShowArchived[عرض الوثائق المؤرشفة:<br/>is_archived = true<br/>visibleTo scope]
    ShowArchived --> FilterArchive[تصفية الأرشيف:<br/>- البحث<br/>- التاريخ من/إلى]
    
    ShowArchived --> UnarchiveBtn[النقر على 'استعادة'<br/>unarchive]
    UnarchiveBtn --> CheckArchived{is_archived?}
    CheckArchived -->|لا| ErrorNotArchived[⚠️ هذه الوثيقة غير مؤرشفة<br/>show-toast error]
    CheckArchived -->|نعم| UnarchiveDoc[document.unarchive<br/>is_archived = false]
    UnarchiveDoc --> UnarchiveSuccess[✅ تم استعادة الوثيقة<br/>show-toast]
    
    ShowArchived --> ForceDeleteBtn[النقر على 'حذف نهائي'<br/>forceDelete]
    ForceDeleteBtn --> CheckArchived2{is_archived?}
    CheckArchived2 -->|لا| ErrorNotArchived2[⚠️ لا يمكن حذف وثيقة غير مؤرشفة<br/>show-toast error]
    CheckArchived2 -->|نعم| ConfirmForceDelete{⚠️ لا يوجد تأكيد!}
    ConfirmForceDelete --> ForceDeleteDoc[document.forceDelete<br/>حذف نهائي من DB]
    ForceDeleteDoc --> ForceDeleteSuccess[✅ تم حذف الوثيقة نهائياً<br/>show-toast]
    
    DocsIndex --> NavTrash[النقر على 'المحذوفات'<br/>في القائمة]
    NavTrash --> TrashPage[/archive<br/>ArchiveTable Component]
    
    TrashPage --> ShowTrashed[عرض الوثائق المحذوفة:<br/>onlyTrashed<br/>visibleTo scope]
    ShowTrashed --> FilterTrash[تصفية المحذوفات:<br/>- البحث]
    
    ShowTrashed --> RestoreBtn[النقر على 'استعادة'<br/>restoreDocument]
    RestoreBtn --> CheckTrashed{visibleTo scope}
    CheckTrashed -->|نعم| RestoreDoc[document.restore<br/>إلغاء soft delete]
    CheckTrashed -->|لا| DenyRestore[403 Forbidden]
    
    RestoreDoc --> RestoreSuccess[✅ تم استعادة الوثيقة<br/>show-toast]
    
    ShowTrashed --> ForceDeleteBtn2[النقر على 'حذف نهائي'<br/>forceDeleteDocument]
    ForceDeleteBtn2 --> CheckTrashed2{visibleTo scope}
    CheckTrashed2 -->|نعم| ConfirmForceDelete2{⚠️ لا يوجد تأكيد!}
    CheckTrashed2 -->|لا| DenyForceDelete[403 Forbidden]
    
    ConfirmForceDelete2 --> ForceDeleteDoc2[document.forceDelete<br/>حذف نهائي]
    ForceDeleteDoc2 --> ForceDeleteSuccess2[✅ تم حذف الوثيقة نهائياً<br/>show-toast]
    
    style ConfirmForceDelete fill:#ff6b6b
    style ConfirmForceDelete2 fill:#ff6b6b
    style ErrorNotArchived fill:#ffd93d
    style ErrorNotArchived2 fill:#ffd93d
    style DenyRestore fill:#ff6b6b
    style DenyForceDelete fill:#ff6b6b
    style ValidateArchive fill:#ffd93d
    style ValidateDelete fill:#ffd93d
    style ValidateStage fill:#ffd93d
```

### 🔍 نقاط الاحتكاك (Friction Points)

| # | النقطة | الوصف | التأثير | الحل المقترح |
|---|--------|-------|---------|--------------|
| **F18** | **عدم وجود تأكيد قبل الحذف النهائي** | `forceDelete` في `DocumentArchive` و `ArchiveTable` لا يطلب تأكيد | 🔴 عالي | إضافة modal تأكيد مع تحذير واضح |
| **F19** | **عدم وجود Policy للأرشفة** | `bulkAction` و `archiveDocument` لا يستخدمان Policy | 🔴 عالي | إضافة `archive` و `unarchive` في `DocumentPolicy` |
| **F20** | **عدم وجود Policy للحذف** | `bulkAction` للحذف لا يستخدم Policy | 🔴 عالي | إضافة `delete` في `DocumentPolicy` |
| **F21** | **عدم وجود Policy للاستعادة** | `restoreDocument` لا يستخدم Policy | ⚠️ متوسط | إضافة `restore` في `DocumentPolicy` |
| **F22** | **عدم وجود إشعار عند الأرشفة** | لا توجد إشعارات للمنشئ أو المكلّف عند أرشفة وثيقة | ⚠️ متوسط | إضافة نظام إشعارات |
| **F23** | **عدم وجود سجل للأرشفة** | `DocumentActivity` لا يسجل عملية الأرشفة تلقائياً | ⚠️ منخفض | إضافة `DocumentActivity::create` عند الأرشفة |
| **F24** | **عدم وجود فلترة متقدمة في الأرشيف** | `DocumentArchive` لا يحتوي على فلتر حسب المرحلة أو النوع | ⚠️ منخفض | إضافة فلاتر إضافية |

---

## 📊 ملخص نقاط الاحتكاك حسب الأولوية

### 🔴 حرجة (عاجلة)
- **F7**: عدم وجود Policy للمهام
- **F12**: عدم وجود Policy في WorkflowStageCard
- **F18**: عدم وجود تأكيد قبل الحذف النهائي
- **F19**: عدم وجود Policy للأرشفة
- **F20**: عدم وجود Policy للحذف

### ⚠️ متوسطة
- **F1**: عدم إمكانية تغيير assignee_id
- **F2**: المنشئ لا يستطيع تحديث الوثيقة
- **F4**: عدم وجود إشعارات
- **F8**: عدم وجود إشعارات للمهام
- **F13**: عدم وجود تأكيد قبل تغيير المرحلة
- **F16**: عدم وجود إشعارات عند تغيير المرحلة
- **F17**: عدم وجود تعليق عند الرفض
- **F21**: عدم وجود Policy للاستعادة
- **F22**: عدم وجود إشعار عند الأرشفة

### 🟡 منخفضة
- **F3**: عدم وجود تأكيد قبل الأرشفة
- **F5**: عدم وجود تعليقات
- **F6**: عدم وجود تأكيد قبل حذف المهام
- **F9**: عدم وجود فلاتر متقدمة
- **F10**: عدم وجود بحث في DocumentTasks
- **F11**: عدم وجود تاريخ تعديل
- **F14**: عرض 3 وثائق فقط
- **F15**: عدم وجود فلترة في WorkflowOverview
- **F23**: عدم وجود سجل للأرشفة
- **F24**: عدم وجود فلترة متقدمة في الأرشيف

---

## 🎯 التوصيات

### أولويات عاجلة:
1. ✅ إنشاء `TaskPolicy` لحماية المهام
2. ✅ إضافة `DocumentPolicy@archive`, `DocumentPolicy@delete`, `DocumentPolicy@restore`
3. ✅ استخدام `DocumentPolicy@update` في `WorkflowStageCard`
4. ✅ إضافة modals تأكيد قبل الحذف النهائي

### تحسينات UX:
1. ✅ إضافة نظام إشعارات شامل
2. ✅ إضافة واجهة لتغيير `assignee_id`
3. ✅ إضافة modals تأكيد قبل الإجراءات الحرجة
4. ✅ إضافة تعليقات عند الرفض

---

**تاريخ الإنشاء:** 2025-01-27  
**آخر تحديث:** 2025-01-27  
**الإصدار:** 1.0

