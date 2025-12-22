<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير سير العمل</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', 'Tahoma', sans-serif;
            direction: rtl;
            padding: 20px;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        
        .header h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .header p {
            color: #666;
            font-size: 14px;
        }
        
        .summary {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .summary-card {
            background: #f5f5f5;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
        
        .summary-card h3 {
            font-size: 16px;
            margin-bottom: 10px;
            color: #333;
        }
        
        .summary-card .value {
            font-size: 32px;
            font-weight: bold;
            color: #2563eb;
        }
        
        .summary-card.overdue .value {
            color: #dc2626;
        }
        
        .stages {
            margin-top: 30px;
        }
        
        .stages h2 {
            font-size: 20px;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        
        .stages-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }
        
        .stage-card {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }
        
        .stage-card h4 {
            font-size: 14px;
            margin-bottom: 10px;
            color: #666;
        }
        
        .stage-card .count {
            font-size: 28px;
            font-weight: bold;
            color: #2563eb;
        }
        
        .stage-card.draft {
            background: #f3f4f6;
        }
        
        .stage-card.review1 {
            background: #dbeafe;
        }
        
        .stage-card.proofread {
            background: #fef3c7;
        }
        
        .stage-card.finalapproval {
            background: #d1fae5;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        
        @media print {
            body {
                padding: 10px;
            }
            
            .summary {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .stages-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>تقرير سير العمل</h1>
        <p>تاريخ التوليد: {{ $generated_at }}</p>
    </div>
    
    <div class="summary">
        <div class="summary-card">
            <h3>إجمالي المستندات</h3>
            <div class="value">{{ $total }}</div>
            <p style="font-size: 12px; color: #666; margin-top: 5px;">جميع الوثائق النشطة (غير المؤرشفة)</p>
        </div>
        
        <div class="summary-card overdue">
            <h3>المستندات المتأخرة</h3>
            <div class="value">{{ $overdue }}</div>
            <p style="font-size: 12px; color: #666; margin-top: 5px;">وثائق متوقفة في مراحلها لأكثر من المدة المحددة</p>
        </div>
    </div>
    
    <div class="stages">
        <h2>توزيع المستندات حسب المراحل</h2>
        <div class="stages-grid">
            <div class="stage-card draft">
                <h4>مسودة</h4>
                <div class="count">{{ $stages['draft'] }}</div>
            </div>
            
            <div class="stage-card review1">
                <h4>مراجعة أولى</h4>
                <div class="count">{{ $stages['review1'] }}</div>
            </div>
            
            <div class="stage-card proofread">
                <h4>تدقيق لغوي</h4>
                <div class="count">{{ $stages['proofread'] }}</div>
            </div>
            
            <div class="stage-card finalapproval">
                <h4>موافقة نهائية</h4>
                <div class="count">{{ $stages['finalapproval'] }}</div>
            </div>
        </div>
    </div>
    
    <div class="footer">
        <p>تم توليد هذا التقرير تلقائياً من نظام إدارة الوثائق</p>
        <p>Defao Document Management System</p>
    </div>
</body>
</html>
