---
**Updated:** 2025-12-22 - Defao v1.0.3  
**Status:** ✅ Production Ready  
**Features:** Workflow, Reports link, Arabic toasts  
---

# مخططات Swimlane Diagram - Mermaid Diagrams

## 1. Swimlane Diagram - السيناريو الكامل

```mermaid
flowchart TB
    subgraph Creator["👤 منشئ الوثيقة (Creator)"]
        C1[إنشاء وثيقة]
        C2[رفع الملف]
        C3[إضافة مهام فرعية]
        C4[متابعة التقدم]
        C5[أرشفة الوثيقة]
    end
    
    subgraph Assignee["👤 مكلّف الوثيقة (Assignee)"]
        A1[استقبال الوثيقة]
        A2[مراجعة الوثيقة]
        A3{قرار المراجعة}
        A4[موافقة/تحويل/رفض]
        A5[إضافة مهام فرعية]
        A6[إكمال مهام]
    end
    
    subgraph System["⚙️ النظام"]
        S1[التحقق من الصلاحيات]
        S2[تحديث المرحلة]
        S3[تسجيل الأنشطة]
        S4[إشعارات]
        S5[فحص التأخير]
    end
    
    C1 --> C2
    C2 --> S1
    S1 -->|✅ منشئ أو مكلّف| C3
    C3 --> A1
    A1 --> A2
    A2 --> A3
    A3 -->|موافقة| A4
    A3 -->|تحويل| A4
    A3 -->|رفض| A4
    A4 --> S1
    S1 -->|✅ المكلّف فقط| S2
    S2 --> S3
    S3 --> S4
    S4 --> C4
    C4 --> C5
    A2 --> A5
    A5 --> A6
    A6 --> S5
```

## 2. Swimlane Diagram - مراحل سير العمل

```mermaid
flowchart LR
    subgraph Draft["📝 Draft - المسودة"]
        D1[منشئ: إنشاء وثيقة]
        D2[منشئ: إضافة مهام]
        D3[مكلّف: مراجعة]
    end
    
    subgraph Review1["🔍 Review1 - مراجعة أولى"]
        R1[مكلّف: مراجعة]
        R2[مكلّف: قرار]
        R3{قرار}
    end
    
    subgraph Proofread["✏️ Proofread - تدقيق"]
        P1[مكلّف: تدقيق]
        P2[مكلّف: قرار]
        P3{قرار}
    end
    
    subgraph Final["✅ FinalApproval - موافقة نهائية"]
        F1[مكلّف: موافقة نهائية]
        F2[أي مستخدم: أرشفة]
    end
    
    D1 --> D2
    D2 --> D3
    D3 -->|forward| R1
    R1 --> R2
    R2 --> R3
    R3 -->|forward| P1
    R3 -->|approve| F1
    R3 -->|reject| D1
    P1 --> P2
    P2 --> P3
    P3 -->|forward| F1
    P3 -->|approve| F1
    P3 -->|reject| D1
    F1 --> F2
```

## 3. Sequence Diagram - تفاعل الأدوار

```mermaid
sequenceDiagram
    participant C as منشئ الوثيقة
    participant S as النظام
    participant A as مكلّف الوثيقة
    participant W as لوحة سير العمل
    
    C->>S: DocumentUpload::save
    S->>S: Document::create (draft)
    S->>S: DocumentActivity::create
    S->>C: redirect /documents
    
    C->>S: DocumentDetail::mount
    S->>S: DocumentPolicy@view
    S->>C: عرض الوثيقة
    
    C->>S: DocumentTasks::addTask
    S->>S: DocumentTask::create
    
    Note over A: ⚠️ لا يوجد إشعار!
    
    A->>S: DocumentDetail::mount
    S->>S: DocumentPolicy@view
    S->>A: عرض الوثيقة
    
    A->>S: forward() / approve() / reject()
    S->>S: DocumentPolicy@update
    alt المكلّف
        S->>S: update(['current_stage'])
        S->>S: DocumentActivity::create
        S->>A: Toast: 'تم التحويل'
        S->>W: dispatch('document-stage-changed')
    else غير مكلّف
        S->>A: 403 Forbidden
    end
```

## 4. Decision Tree - قرارات المراجعة

```mermaid
flowchart TD
    Start[الوثيقة في مرحلة Review1/Proofread] --> Review[المكلّف يراجع]
    Review --> Check{الوثيقة صحيحة?}
    
    Check -->|✅ نعم| Next{المرحلة التالية?}
    Check -->|❌ لا| Reject[reject → draft]
    
    Next -->|نعم| Forward[forward → next stage]
    Next -->|لا - موافقة نهائية| Approve[approve → finalapproval]
    
    Forward --> Activity1[DocumentActivity::create<br/>'forwarded']
    Approve --> Activity2[DocumentActivity::create<br/>'approved']
    Reject --> Activity3[DocumentActivity::create<br/>'rejected']
    
    Activity1 --> Toast1[Toast Notification]
    Activity2 --> Toast2[Toast Notification]
    Activity3 --> Toast3[Toast Notification]
    
    Toast1 --> Update[تحديث WorkflowOverview]
    Toast2 --> Update
    Toast3 --> Draft[الوثيقة في draft]
    
    Draft --> Creator[المنشئ يستطيع المراجعة]
    Update --> End[نهاية]
```

## 5. Overdue Detection Flow

```mermaid
flowchart TD
    Start[فحص الوثائق] --> Check1{review1 > 7 days?}
    Check1 -->|نعم| Overdue1[⚠️ متأخرة - review1]
    Check1 -->|لا| Check2{proofread > 5 days?}
    
    Check2 -->|نعم| Overdue2[⚠️ متأخرة - proofread]
    Check2 -->|لا| Check3{finalapproval > 3 days?}
    
    Check3 -->|نعم| Overdue3[⚠️ متأخرة - finalapproval]
    Check3 -->|لا| OK[✅ جميع الوثائق في الوقت]
    
    Overdue1 --> Display[عرض في DocumentTable<br/>overdue = true]
    Overdue2 --> Display
    Overdue3 --> Display
    
    Display --> Note[⚠️ لا يوجد إشعار تلقائي<br/>للمكلّف]
    
    style Overdue1 fill:#ffcdd2
    style Overdue2 fill:#ffcdd2
    style Overdue3 fill:#ffcdd2
    style Note fill:#ffd93d
```

## 6. Bulk Actions Flow

```mermaid
flowchart TD
    Start[المستخدم يحدد وثائق] --> Select[تحديد وثائق<br/>checkbox]
    Select --> Action{اختيار إجراء}
    
    Action -->|أرشفة| Archive[bulkAction('archive')]
    Action -->|حذف| Delete[bulkAction('delete')]
    Action -->|تغيير مرحلة| Stage[bulkAction('stage_*')]
    
    Archive --> Check1{visibleTo?}
    Delete --> Check2{visibleTo?}
    Stage --> Check3{visibleTo?}
    
    Check1 -->|نعم| Update1[update is_archived = true]
    Check1 -->|لا| Skip1[تخطي]
    
    Check2 -->|نعم| Update2[soft delete]
    Check2 -->|لا| Skip2[تخطي]
    
    Check3 -->|نعم| Update3[update current_stage]
    Check3 -->|لا| Skip3[تخطي]
    
    Update1 --> Toast1[Toast: 'تم أرشفة X وثيقة']
    Update2 --> Toast2[Toast: 'تم حذف X وثيقة']
    Update3 --> Toast3[Toast: 'تم تغيير مرحلة X وثيقة']
    
    style Check1 fill:#ffd93d
    style Check2 fill:#ffd93d
    style Check3 fill:#ffd93d
    style Update3 fill:#ff6b6b
```

## 7. Archive Workflow

```mermaid
flowchart TD
    Start[وثيقة في أي مرحلة] --> Decision{المستخدم يريد أرشفة?}
    
    Decision -->|نعم| Archive[archiveDocument()<br/>أو bulkAction('archive')]
    Decision -->|لا| Continue[متابعة سير العمل]
    
    Archive --> Check{visibleTo?}
    Check -->|نعم| Update[update is_archived = true]
    Check -->|لا| Deny[❌ رفض]
    
    Update --> Archived[الوثيقة مؤرشفة]
    Archived --> View[عرض في DocumentArchive]
    
    View --> Unarchive{إلغاء أرشفة?}
    Unarchive -->|نعم| UnarchiveAction[unarchive()]
    Unarchive -->|لا| ForceDelete{حذف نهائي?}
    
    UnarchiveAction --> Check2{is_archived?}
    Check2 -->|نعم| Update2[update is_archived = false]
    Check2 -->|لا| Error[⚠️ خطأ: غير مؤرشفة]
    
    ForceDelete -->|نعم| ForceDeleteAction[forceDelete()]
    ForceDeleteAction --> Check3{is_archived?}
    Check3 -->|نعم| Delete[حذف نهائي من DB]
    Check3 -->|لا| Error2[⚠️ خطأ: لا يمكن حذف غير مؤرشفة]
    
    Update2 --> Active[الوثيقة نشطة مرة أخرى]
    Delete --> End[نهاية]
    
    style Check fill:#ffd93d
    style Check2 fill:#ffd93d
    style Check3 fill:#ffd93d
    style Update fill:#ffcdd2
    style UnarchiveAction fill:#ffcdd2
    style ForceDeleteAction fill:#ffcdd2
```

---

**ملاحظة:** جميع المخططات مبنية على تحليل الكود الفعلي في المشروع.

