---
**Updated:** 2025-12-22 - Defao v1.0.1  
**Status:** ✅ Production Ready  
**Features:** Workflow, Reports link, Arabic toasts  
---

# مخططات دورة حياة الوثيقة - Mermaid Diagrams

## 1. State Diagram - دورة حياة الوثيقة الكاملة

```mermaid
stateDiagram-v2
    [*] --> Draft: DocumentUpload::save<br/>current_stage = 'draft'<br/>is_archived = false
    
    Draft --> Review1: forward() / advanceStage()<br/>✅ المكلّف فقط
    Review1 --> Proofread: forward() / advanceStage()<br/>✅ المكلّف فقط
    Proofread --> FinalApproval: forward() / advanceStage()<br/>✅ المكلّف فقط
    
    Draft --> FinalApproval: approve()<br/>✅ المكلّف فقط
    Review1 --> FinalApproval: approve()<br/>✅ المكلّف فقط
    Proofread --> FinalApproval: approve()<br/>✅ المكلّف فقط
    
    Review1 --> Draft: reject() / rejectStage()<br/>✅ المكلّف فقط
    Proofread --> Draft: reject() / rejectStage()<br/>✅ المكلّف فقط
    FinalApproval --> Draft: reject() / rejectStage()<br/>✅ المكلّف فقط
    
    Draft --> Archived: archiveDocument() / bulkAction('archive')<br/>⚠️ visibleTo فقط
    Review1 --> Archived: archiveDocument() / bulkAction('archive')<br/>⚠️ visibleTo فقط
    Proofread --> Archived: archiveDocument() / bulkAction('archive')<br/>⚠️ visibleTo فقط
    FinalApproval --> Archived: archiveDocument() / bulkAction('archive')<br/>⚠️ visibleTo فقط
    
    Archived --> Draft: unarchive()<br/>⚠️ visibleTo فقط
    Archived --> Review1: unarchive()<br/>⚠️ visibleTo فقط
    Archived --> Proofread: unarchive()<br/>⚠️ visibleTo فقط
    Archived --> FinalApproval: unarchive()<br/>⚠️ visibleTo فقط
    
    Draft --> Deleted: bulkAction('delete')<br/>⚠️ visibleTo فقط
    Review1 --> Deleted: bulkAction('delete')<br/>⚠️ visibleTo فقط
    Proofread --> Deleted: bulkAction('delete')<br/>⚠️ visibleTo فقط
    FinalApproval --> Deleted: bulkAction('delete')<br/>⚠️ visibleTo فقط
    Archived --> Deleted: bulkAction('delete')<br/>⚠️ visibleTo فقط
    
    Deleted --> Draft: restoreDocument()<br/>⚠️ visibleTo فقط
    Deleted --> Review1: restoreDocument()<br/>⚠️ visibleTo فقط
    Deleted --> Proofread: restoreDocument()<br/>⚠️ visibleTo فقط
    Deleted --> FinalApproval: restoreDocument()<br/>⚠️ visibleTo فقط
    
    Archived --> [*]: forceDelete()<br/>⚠️ visibleTo فقط<br/>حذف نهائي
    Deleted --> [*]: forceDeleteDocument()<br/>⚠️ visibleTo فقط<br/>حذف نهائي
    
    note right of Draft
        المسودة
        ✅ DocumentPolicy@update
        ⚠️ bulkAction بدون Policy
    end note
    
    note right of Review1
        مراجعة أولى
        ✅ DocumentPolicy@update
        ⚠️ bulkAction بدون Policy
    end note
    
    note right of Proofread
        تدقيق
        ✅ DocumentPolicy@update
        ⚠️ bulkAction بدون Policy
    end note
    
    note right of FinalApproval
        موافقة نهائية
        ✅ DocumentPolicy@update
        ⚠️ bulkAction بدون Policy
    end note
    
    note right of Archived
        أرشيف
        ⚠️ لا يوجد Policy
        أي مستخدم يرى الوثيقة
    end note
    
    note right of Deleted
        محذوفة (soft delete)
        ⚠️ لا يوجد Policy
        أي مستخدم يرى الوثيقة
    end note
```

## 2. State Diagram - مراحل سير العمل فقط

```mermaid
stateDiagram-v2
    [*] --> Draft: إنشاء وثيقة
    
    Draft --> Review1: forward()<br/>✅ المكلّف فقط
    Review1 --> Proofread: forward()<br/>✅ المكلّف فقط
    Proofread --> FinalApproval: forward()<br/>✅ المكلّف فقط
    
    Draft --> FinalApproval: approve()<br/>✅ المكلّف فقط
    Review1 --> FinalApproval: approve()<br/>✅ المكلّف فقط
    Proofread --> FinalApproval: approve()<br/>✅ المكلّف فقط
    
    Review1 --> Draft: reject()<br/>✅ المكلّف فقط
    Proofread --> Draft: reject()<br/>✅ المكلّف فقط
    FinalApproval --> Draft: reject()<br/>✅ المكلّف فقط
    
    note right of Draft
        المسودة
        - المنشئ: عرض فقط
        - المكلّف: عرض + تحديث
    end note
    
    note right of Review1
        مراجعة أولى
        - المكلّف: عرض + تحديث + موافقة/رفض
    end note
    
    note right of Proofread
        تدقيق
        - المكلّف: عرض + تحديث + موافقة/رفض
    end note
    
    note right of FinalApproval
        موافقة نهائية
        - المكلّف: عرض + تحديث + موافقة/رفض
    end note
```

## 3. Flowchart - مسار الوثيقة النموذجي

```mermaid
flowchart TD
    Start([إنشاء وثيقة]) --> Draft[دraft<br/>المسودة]
    
    Draft --> Decision1{إجراء المكلّف}
    Decision1 -->|forward| Review1[Review1<br/>مراجعة أولى]
    Decision1 -->|approve| FinalApproval[FinalApproval<br/>موافقة نهائية]
    Decision1 -->|archive| Archived[Archived<br/>مؤرشف]
    Decision1 -->|delete| Deleted[Deleted<br/>محذوف]
    
    Review1 --> Decision2{إجراء المكلّف}
    Decision2 -->|forward| Proofread[Proofread<br/>تدقيق]
    Decision2 -->|approve| FinalApproval
    Decision2 -->|reject| Draft
    Decision2 -->|archive| Archived
    Decision2 -->|delete| Deleted
    
    Proofread --> Decision3{إجراء المكلّف}
    Decision3 -->|forward| FinalApproval
    Decision3 -->|approve| FinalApproval
    Decision3 -->|reject| Draft
    Decision3 -->|archive| Archived
    Decision3 -->|delete| Deleted
    
    FinalApproval --> Decision4{إجراء المكلّف}
    Decision4 -->|reject| Draft
    Decision4 -->|archive| Archived
    Decision4 -->|delete| Deleted
    
    Archived --> Decision5{إجراء المستخدم}
    Decision5 -->|unarchive| Draft
    Decision5 -->|unarchive| Review1
    Decision5 -->|unarchive| Proofread
    Decision5 -->|unarchive| FinalApproval
    Decision5 -->|forceDelete| End([حذف نهائي])
    
    Deleted --> Decision6{إجراء المستخدم}
    Decision6 -->|restore| Draft
    Decision6 -->|restore| Review1
    Decision6 -->|restore| Proofread
    Decision6 -->|restore| FinalApproval
    Decision6 -->|forceDelete| End
    
    style Draft fill:#e3f2fd
    style Review1 fill:#bbdefb
    style Proofread fill:#fff9c4
    style FinalApproval fill:#c8e6c9
    style Archived fill:#ffccbc
    style Deleted fill:#ffcdd2
    style End fill:#424242,color:#fff
```

## 4. Sequence Diagram - عملية تغيير المرحلة

```mermaid
sequenceDiagram
    participant U as المستخدم
    participant DD as DocumentDetail
    participant Policy as DocumentPolicy
    participant Doc as Document Model
    participant Activity as DocumentActivity
    participant Toast as Toast Notification
    
    U->>DD: النقر على "تحويل"
    DD->>Policy: authorize('update', document)
    Policy->>Policy: check: assignee_id?
    
    alt المكلّف
        Policy-->>DD: true
        DD->>Doc: update(['current_stage' => 'next'])
        Doc-->>DD: تم التحديث
        DD->>Activity: create(['action_type' => 'forwarded'])
        DD->>Toast: dispatch('show-toast', 'تم تحويل الوثيقة')
        Toast-->>U: ✅ تم تحويل الوثيقة بنجاح
    else غير مكلّف
        Policy-->>DD: false
        DD-->>U: 403 Forbidden
    end
```

## 5. Sequence Diagram - عملية الأرشفة

```mermaid
sequenceDiagram
    participant U as المستخدم
    participant DT as DocumentTable
    participant Doc as Document Model
    participant Toast as Toast Notification
    
    U->>DT: تحديد وثائق + اختيار "أرشفة"
    DT->>DT: bulkAction('archive')
    DT->>Doc: visibleTo(auth()->user())
    Doc-->>DT: وثائق مرئية
    
    Note over DT,Doc: ⚠️ لا يوجد Policy!
    
    DT->>Doc: update(['is_archived' => true])
    Doc-->>DT: تم التحديث
    
    Note over DT,Activity: ⚠️ لا يتم إنشاء DocumentActivity!
    
    DT->>Toast: dispatch('show-toast', 'تم تنفيذ الإجراء')
    Toast-->>U: ✅ تم أرشفة X وثيقة
```

## 6. Permission Matrix - من يستطيع ماذا؟

```mermaid
graph TB
    subgraph "المنشئ (user_id)"
        C1[✅ عرض]
        C2[❌ تحديث]
        C3[❌ تغيير المرحلة]
        C4[⚠️ أرشفة - بدون Policy]
        C5[⚠️ حذف - بدون Policy]
    end
    
    subgraph "المكلّف (assignee_id)"
        A1[✅ عرض]
        A2[✅ تحديث]
        A3[✅ تغيير المرحلة]
        A4[⚠️ أرشفة - بدون Policy]
        A5[⚠️ حذف - بدون Policy]
    end
    
    subgraph "مستخدم آخر"
        O1[❌ عرض]
        O2[❌ تحديث]
        O3[❌ تغيير المرحلة]
        O4[❌ أرشفة]
        O5[❌ حذف]
    end
    
    style C2 fill:#ff6b6b
    style C3 fill:#ff6b6b
    style C4 fill:#ffd93d
    style C5 fill:#ffd93d
    style A4 fill:#ffd93d
    style A5 fill:#ffd93d
```

## 7. Business Rules Diagram

```mermaid
mindmap
  root((قواعد العمل))
    مطبقة ✅
      لا يمكن تغيير مرحلة وثيقة مؤرشفة
        WorkflowStageCard::advanceStage
        WorkflowStageCard::rejectStage
      لا يمكن حذف وثيقة غير مؤرشفة
        DocumentArchive::forceDelete
      لا يمكن إلغاء أرشفة وثيقة غير مؤرشفة
        DocumentArchive::unarchive
    مفقودة ❌
      لا يوجد قيد على bulkAction
        يمكن تغيير المرحلة بدون Policy
        يمكن الأرشفة بدون Policy
        يمكن الحذف بدون Policy
      لا يوجد DocumentActivity للأرشفة
        لا يتم تسجيل عملية الأرشفة
      لا يوجد DocumentActivity للحذف
        لا يتم تسجيل عملية الحذف
```

## 8. Events Timeline - خط زمني للأحداث

```mermaid
gantt
    title خط زمني لأحداث الوثيقة
    dateFormat YYYY-MM-DD
    section إنشاء
    DocumentActivity::create('created') :done, create1, 2025-01-27, 1d
    DocumentActivity::create('uploaded') :done, create2, 2025-01-27, 1d
    section سير العمل
    DocumentActivity::create('forwarded') :active, forward, 2025-01-28, 1d
    DocumentActivity::create('approved') :approved, 2025-01-29, 1d
    DocumentActivity::create('rejected') :crit, reject, 2025-01-30, 1d
    section الأرشفة
    archiveDocument() :milestone, archive, 2025-02-01, 0d
    section الحذف
    bulkAction('delete') :crit, delete, 2025-02-02, 1d
    forceDelete() :crit, force, 2025-02-03, 1d
```

---

**ملاحظة:** جميع المخططات مبنية على تحليل الكود الفعلي في المشروع.

