<?php

namespace App\Livewire\Documents;

use App\Models\Document;
use App\Models\DocumentActivity;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class DocumentUpload extends Component
{
    use WithFileUploads;

    // Upload state
    public $file = null;
    public $uploadProgress = 0;
    public $processingProgress = 0;
    public $isUploading = false;
    public $isProcessing = false;
    public $uploadComplete = false;

    // Form fields
    public $title = '';
    public $type = 'incoming';
    public $description = '';

    // Validation
    protected $rules = [
        'file' => 'required|file|max:25600|mimes:pdf,doc,docx,xls,xlsx',
        'title' => 'required|max:200',
        'type' => 'required|in:incoming,outgoing',
        'description' => 'max:500',
    ];

    protected $messages = [
        'file.required' => 'يرجى اختيار ملف',
        'file.max' => 'حجم الملف يجب أن لا يتجاوز 25 ميجابايت',
        'file.mimes' => 'نوع الملف غير مدعوم. الأنواع المدعومة: PDF, DOCX, XLSX',
        'title.required' => 'عنوان الوثيقة مطلوب',
        'title.max' => 'العنوان لا يمكن أن يتجاوز 200 حرف',
        'type.required' => 'نوع الوثيقة مطلوب',
        'description.max' => 'الوصف لا يمكن أن يتجاوز 500 حرف',
    ];

    public function updatedFile()
    {
        // Validate file
        $this->validate(['file' => 'required|file|max:25600|mimes:pdf,doc,docx,xls,xlsx']);

        if ($this->file) {
            // Auto-fill title from filename
            $this->title = pathinfo($this->file->getClientOriginalName(), PATHINFO_FILENAME);

            // Simulate upload progress (Phase 3 - store to local storage)
            $this->simulateUpload();
        }
    }

    protected function simulateUpload()
    {
        $this->isUploading = true;
        
        // TODO: Phase 6 - Real S3 upload with ProcessDocumentJob
        // For now: Store to local storage
        $this->uploadProgress = 100;
        $this->isUploading = false;
        
        // Start processing
        $this->simulateProcessing();
    }

    protected function simulateProcessing()
    {
        $this->isProcessing = true;
        
        // TODO: Phase 6 - Real document processing:
        // - Virus scan
        // - Extract metadata
        // - Generate thumbnails
        // - OCR (if needed)
        
        // Fake processing progress (instant for demo)
        $this->processingProgress = 100;
        $this->isProcessing = false;
        $this->uploadComplete = true;

        $this->dispatch('show-toast', 
            message: 'تم رفع الملف بنجاح',
            type: 'success'
        );
    }

    public function removeFile()
    {
        if ($this->file) {
            // Delete temporary file
            $this->file->delete();
        }
        $this->reset(['file', 'uploadProgress', 'processingProgress', 'isUploading', 'isProcessing', 'uploadComplete', 'title']);
    }

    public function save()
    {
        $this->validate();

        // Store file to local storage (Phase 3)
        $filePath = $this->file->store('documents', 'local');
        
        // Format file size
        $fileSize = $this->getFileSizeFormatted();
        
        // Create document
        $document = Document::create([
            'title' => $this->title,
            'type' => $this->type,
            'description' => $this->description,
            'file_name' => $this->file->getClientOriginalName(),
            'file_size' => $fileSize,
            'mime_type' => $this->file->getMimeType(),
            's3_path' => $filePath, // ADR-001: For now, local path. Phase 6: S3
            'current_stage' => 'draft',
            'is_archived' => false,
            'user_id' => auth()->id(),
            'assignee_id' => auth()->id(), // Default to creator
        ]);

        // Create initial activity
        DocumentActivity::create([
            'document_id' => $document->id,
            'user_id' => auth()->id(),
            'action_type' => 'created',
        ]);

        DocumentActivity::create([
            'document_id' => $document->id,
            'user_id' => auth()->id(),
            'action_type' => 'uploaded',
        ]);

        $this->dispatch('show-toast', 
            message: 'تم حفظ الوثيقة بنجاح',
            type: 'success'
        );

        // Redirect to documents index
        return redirect()->route('documents.index');
    }

    public function getFileIcon()
    {
        if (!$this->file) return 'heroicon-o-document';

        $extension = $this->file->getClientOriginalExtension();
        return match(strtolower($extension)) {
            'pdf' => 'heroicon-o-document-text',
            'doc', 'docx' => 'heroicon-o-document',
            'xls', 'xlsx' => 'heroicon-o-table-cells',
            default => 'heroicon-o-document',
        };
    }

    public function getFileSizeFormatted()
    {
        if (!$this->file) return '0 KB';

        $bytes = $this->file->getSize();
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        }
        return round($bytes / 1024, 2) . ' KB';
    }

    public function render()
    {
        return view('livewire.documents.document-upload');
    }
}
