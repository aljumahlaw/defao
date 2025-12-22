---
**Updated:** 2025-12-22 - Defao v1.0.1  
**Status:** ✅ Production Ready  
**Features:** Workflow, Reports link, Arabic toasts  
---

# مخططات الأدوار والصلاحيات - Mermaid Diagrams

## 1. Class Diagram - هيكل الأدوار والصلاحيات

```mermaid
classDiagram
    class User {
        +int id
        +string name
        +string email
        +string department
        +string position
        +hasMany documents (creator)
        +hasMany assignedDocuments
        +hasMany tasks (creator)
        +hasMany assignedTasks
        +hasMany documentActivities
    }
    
    class Document {
        +int id
        +string title
        +enum type
        +enum current_stage
        +boolean is_archived
        +int user_id FK
        +int assignee_id FK
        +scope visibleTo(user)
        +belongsTo creator
        +belongsTo assignee
        +hasMany tasks
        +hasMany activities
    }
    
    class Task {
        +int id
        +string title
        +enum status
        +enum priority
        +date due_date
        +int user_id FK
        +int assignee_id FK
        +int document_id FK
        +belongsTo creator
        +belongsTo assignee
        +belongsTo document
    }
    
    class DocumentPolicy {
        +view(user, document) bool
        +update(user, document) bool
        +create(user) bool
        +delete(user, document) bool
        +archive(user, document) bool
    }
    
    class TaskPolicy {
        <<missing>> لا يوجد Policy!
        +view(user, task) bool
        +update(user, task) bool
        +delete(user, task) bool
    }
    
    class DocumentActivity {
        +int id
        +int document_id FK
        +int user_id FK
        +string action_type
        +string comment
        +belongsTo document
        +belongsTo user
    }
    
    User "1" --> "*" Document : "creates (user_id)"
    User "1" --> "*" Document : "assigned to (assignee_id)"
    User "1" --> "*" Task : "creates (user_id)"
    User "1" --> "*" Task : "assigned to (assignee_id)"
    User "1" --> "*" DocumentActivity : "performs"
    
    Document "1" --> "*" Task : "has"
    Document "1" --> "*" DocumentActivity : "has"
    
    DocumentPolicy ..> Document : "protects"
    TaskPolicy ..> Task : "missing - unprotected!"
    
    note for DocumentPolicy "✅ موجود:\n- view: creator OR assignee\n- update: assignee ONLY"
    note for TaskPolicy "❌ غير موجود!\nأي مستخدم يمكنه:\n- حذف أي مهمة\n- تعديل أي مهمة\n- رؤية أي مهمة"
```

## 2. Flowchart - سير العمل والصلاحيات

```mermaid
flowchart TD
    Start([مستخدم مسجل<br/>auth()->id()]) --> CreateDoc{إنشاء وثيقة}
    CreateDoc -->|DocumentUpload@save| DocCreated[وثيقة منشأة<br/>user_id = auth()->id()<br/>assignee_id = auth()->id()]
    
    DocCreated --> ViewDoc{عرض وثيقة}
    ViewDoc -->|DocumentPolicy@view| CheckView{منشئ OR مكلّف?}
    CheckView -->|نعم| ShowDoc[✅ عرض الوثيقة]
    CheckView -->|لا| DenyView[❌ رفض الوصول<br/>403]
    
    ShowDoc --> UpdateDoc{تحديث وثيقة}
    UpdateDoc -->|DocumentPolicy@update| CheckAssignee{مكلّف?<br/>assignee_id}
    CheckAssignee -->|نعم| AllowUpdate[✅ السماح بالتحديث]
    CheckAssignee -->|لا| DenyUpdate[❌ رفض التحديث]
    
    AllowUpdate --> Workflow{سير العمل}
    Workflow -->|DocumentDetail@approve| Approve[✅ الموافقة<br/>finalapproval]
    Workflow -->|DocumentDetail@reject| Reject[✅ الرفض<br/>draft]
    Workflow -->|DocumentDetail@forward| Forward[✅ التحويل<br/>next stage]
    
    ShowDoc --> ArchiveDoc{أرشفة}
    ArchiveDoc -->|DocumentTable@archiveDocument| CheckVisible{visibleTo?}
    CheckVisible -->|نعم| ArchiveOK[✅ أرشفة<br/>⚠️ بدون Policy]
    
    ShowDoc --> DeleteDoc{حذف}
    DeleteDoc -->|DocumentTable@bulkAction| CheckVisible2{visibleTo?}
    CheckVisible2 -->|نعم| DeleteOK[✅ حذف<br/>⚠️ بدون Policy]
    
    ShowDoc --> BulkStage{تغيير المرحلة<br/>bulkAction}
    BulkStage -->|DocumentTable@bulkAction| CheckVisible3{visibleTo?}
    CheckVisible3 -->|نعم| BulkStageOK[✅ تغيير المرحلة<br/>⚠️ تناقض مع Policy!]
    
    Start --> CreateTask{إنشاء مهمة}
    CreateTask -->|TaskForm@save| TaskCreated[مهمة منشأة<br/>user_id = auth()->id()<br/>assignee_id = أي مستخدم]
    
    TaskCreated --> UpdateTask{تحديث/حذف مهمة}
    UpdateTask -->|TaskForm@save<br/>TaskList@deleteTask| NoPolicy[⚠️ لا يوجد Policy!]
    NoPolicy --> AllowAny[✅ أي مستخدم يمكنه<br/>تعديل/حذف أي مهمة]
    
    style DenyView fill:#ff6b6b,stroke:#c92a2a,stroke-width:2px
    style DenyUpdate fill:#ff6b6b,stroke:#c92a2a,stroke-width:2px
    style ArchiveOK fill:#ffd93d,stroke:#f59f00,stroke-width:2px
    style DeleteOK fill:#ffd93d,stroke:#f59f00,stroke-width:2px
    style BulkStageOK fill:#ff6b6b,stroke:#c92a2a,stroke-width:2px
    style AllowAny fill:#ff6b6b,stroke:#c92a2a,stroke-width:2px
    style NoPolicy fill:#ff6b6b,stroke:#c92a2a,stroke-width:2px
```

## 3. State Diagram - مراحل سير العمل

```mermaid
stateDiagram-v2
    [*] --> draft: إنشاء وثيقة
    
    draft --> review1: forward (المكلّف فقط)
    review1 --> proofread: forward (المكلّف فقط)
    proofread --> finalapproval: forward (المكلّف فقط)
    
    review1 --> draft: reject (المكلّف فقط)
    proofread --> draft: reject (المكلّف فقط)
    finalapproval --> draft: reject (المكلّف فقط)
    
    draft --> finalapproval: approve (المكلّف فقط)
    review1 --> finalapproval: approve (المكلّف فقط)
    proofread --> finalapproval: approve (المكلّف فقط)
    
    draft --> archived: archive (⚠️ أي مستخدم يرى الوثيقة)
    review1 --> archived: archive (⚠️ أي مستخدم يرى الوثيقة)
    proofread --> archived: archive (⚠️ أي مستخدم يرى الوثيقة)
    finalapproval --> archived: archive (⚠️ أي مستخدم يرى الوثيقة)
    
    archived --> draft: unarchive (⚠️ أي مستخدم يرى الوثيقة)
    
    note right of draft
        المسودة
        المنشئ: عرض فقط
        المكلّف: عرض + تحديث
    end note
    
    note right of review1
        مراجعة أولى
        المكلّف: عرض + تحديث + موافقة/رفض
    end note
    
    note right of proofread
        تدقيق
        المكلّف: عرض + تحديث + موافقة/رفض
    end note
    
    note right of finalapproval
        موافقة نهائية
        المكلّف: عرض + تحديث + موافقة/رفض
    end note
    
    note right of archived
        أرشيف
        ⚠️ لا يوجد Policy
        أي مستخدم يرى الوثيقة يمكنه الأرشفة
    end note
```

## 4. Sequence Diagram - عملية إنشاء وتحديث وثيقة

```mermaid
sequenceDiagram
    participant User as مستخدم
    participant Upload as DocumentUpload
    participant Doc as Document Model
    participant Policy as DocumentPolicy
    participant Activity as DocumentActivity
    
    User->>Upload: رفع ملف + بيانات
    Upload->>Doc: create(user_id=auth()->id())
    Doc-->>Upload: وثيقة منشأة
    Upload->>Activity: create(action_type='created')
    Upload->>Activity: create(action_type='uploaded')
    
    Note over User,Activity: الوثيقة الآن في مرحلة draft
    
    User->>Upload: عرض الوثيقة
    Upload->>Policy: view(user, document)
    Policy->>Policy: check: user_id OR assignee_id?
    alt منشئ أو مكلّف
        Policy-->>Upload: true
        Upload-->>User: عرض الوثيقة
    else غير مصرح
        Policy-->>Upload: false
        Upload-->>User: 403 Forbidden
    end
    
    User->>Upload: تحديث الوثيقة
    Upload->>Policy: update(user, document)
    Policy->>Policy: check: assignee_id?
    alt مكلّف
        Policy-->>Upload: true
        Upload->>Doc: update(...)
        Doc-->>Upload: تم التحديث
    else غير مكلّف
        Policy-->>Upload: false
        Upload-->>User: 403 Forbidden
    end
```

## 5. ER Diagram - العلاقات بين الجداول

```mermaid
erDiagram
    users ||--o{ documents : "creates (user_id)"
    users ||--o{ documents : "assigned to (assignee_id)"
    users ||--o{ tasks : "creates (user_id)"
    users ||--o{ tasks : "assigned to (assignee_id)"
    users ||--o{ document_activities : "performs"
    documents ||--o{ tasks : "has"
    documents ||--o{ document_activities : "has"
    
    users {
        bigint id PK
        string name
        string email
        string password
        string department
        string position
        timestamp created_at
        timestamp updated_at
    }
    
    documents {
        bigint id PK
        string title
        enum type "incoming,outgoing"
        text description
        string file_name
        string file_size
        string mime_type
        string s3_path
        enum current_stage "draft,review1,proofread,finalapproval"
        boolean is_archived
        bigint user_id FK
        bigint assignee_id FK
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at "soft delete"
    }
    
    tasks {
        bigint id PK
        string title
        text description
        enum status "pending,in_progress,completed,overdue"
        enum priority "low,medium,high,urgent"
        date due_date
        bigint document_id FK "nullable"
        bigint user_id FK
        bigint assignee_id FK
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at "soft delete"
    }
    
    document_activities {
        bigint id PK
        bigint document_id FK
        bigint user_id FK
        string action_type
        text comment
        json metadata
        timestamp created_at
        timestamp updated_at
    }
```

## 6. Permission Matrix - مصفوفة الصلاحيات

```mermaid
graph TB
    subgraph "الوثائق (Documents)"
        D1[منشئ الوثيقة<br/>user_id] --> D1A[✅ إنشاء]
        D1 --> D1B[✅ عرض]
        D1 --> D1C[❌ تحديث]
        D1 --> D1D[⚠️ حذف - بدون Policy]
        D1 --> D1E[⚠️ أرشفة - بدون Policy]
        D1 --> D1F[❌ تغيير المرحلة]
        
        D2[مكلّف الوثيقة<br/>assignee_id] --> D2A[❌ إنشاء]
        D2 --> D2B[✅ عرض]
        D2 --> D2C[✅ تحديث]
        D2 --> D2D[⚠️ حذف - بدون Policy]
        D2 --> D2E[⚠️ أرشفة - بدون Policy]
        D2 --> D2F[✅ تغيير المرحلة]
        D2 --> D2G[✅ موافقة/رفض]
    end
    
    subgraph "المهام (Tasks)"
        T1[منشئ المهمة<br/>user_id] --> T1A[✅ إنشاء]
        T1 --> T1B[⚠️ عرض - بدون Policy]
        T1 --> T1C[⚠️ تحديث - بدون Policy]
        T1 --> T1D[⚠️ حذف - بدون Policy]
        T1 --> T1E[✅ إسناد لأي مستخدم]
        
        T2[مكلّف المهمة<br/>assignee_id] --> T2A[✅ إنشاء]
        T2 --> T2B[⚠️ عرض - بدون Policy]
        T2 --> T2C[⚠️ تحديث - بدون Policy]
        T2 --> T2D[⚠️ حذف - بدون Policy]
        
        T3[مستخدم آخر] --> T3A[✅ إنشاء]
        T3 --> T3B[⚠️ عرض - بدون Policy]
        T3 --> T3C[⚠️ تحديث - بدون Policy]
        T3 --> T3D[⚠️ حذف - بدون Policy]
    end
    
    style D1C fill:#ff6b6b
    style D1D fill:#ffd93d
    style D1E fill:#ffd93d
    style D1F fill:#ff6b6b
    style D2A fill:#ff6b6b
    style D2D fill:#ffd93d
    style D2E fill:#ffd93d
    style T1B fill:#ffd93d
    style T1C fill:#ffd93d
    style T1D fill:#ffd93d
    style T2B fill:#ffd93d
    style T2C fill:#ffd93d
    style T2D fill:#ffd93d
    style T3B fill:#ffd93d
    style T3C fill:#ffd93d
    style T3D fill:#ffd93d
```

## 7. Security Issues Diagram - مشاكل الأمان

```mermaid
mindmap
  root((مشاكل الأمان))
    عدم وجود TaskPolicy
      أي مستخدم يمكنه حذف أي مهمة
      أي مستخدم يمكنه تعديل أي مهمة
      لا يوجد فحص صلاحيات
    عدم وجود Policy للحذف
      أي مستخدم يرى وثيقة يمكنه حذفها
      حذف نهائي بدون قيود
      استعادة بدون قيود
    عدم وجود Policy للأرشفة
      أي مستخدم يرى وثيقة يمكنه أرشفتها
      إلغاء أرشفة بدون قيود
    تناقض في bulkAction
      تغيير المرحلة بدون Policy
      يتعارض مع DocumentPolicy@update
    عدم استخدام Spatie Permission
      Package مثبت لكن غير مستخدم
      User model لا يستخدم HasRoles
      لا يوجد نظام أدوار فعلي
    عدم وجود قيود على الإسناد
      أي مستخدم يمكنه إسناد مهام لأي مستخدم
      لا يوجد تسلسل هرمي
```

---

**ملاحظة:** جميع المخططات مبنية على تحليل الكود الفعلي في المشروع.

